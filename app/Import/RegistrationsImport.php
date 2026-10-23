<?php

namespace App\Imports;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class RegistrationsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public int $duplicates = 0;
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $rawRow) {
            // WithHeadingRow uses the first spreadsheet row as headings.
            $rowNumber = $index + 2;

            $row = $this->normalizeRow($rawRow->toArray());

            if ($this->isBlank($row)) {
                continue;
            }

            // Normalize consent BEFORE validation so Agree/YES/true
            // are handled consistently.
            $consent = mb_strtolower(trim(
                (string) ($row['privacy_consent'] ?? '')
            ));

            $consentMap = [
                '1' => '1',
                'yes' => '1',
                'agree' => '1',
                'agreed' => '1',
                'true' => '1',
                '0' => '0',
                'no' => '0',
                'disagree' => '0',
                'disagreed' => '0',
                'false' => '0',
            ];

            if ($consent !== '') {
                $row['privacy_consent'] = $consentMap[$consent]
                    ?? $consent;
            }

            $validator = Validator::make($row, [
                'last_name' => ['required', 'string', 'max:255'],
                'first_name' => ['required', 'string', 'max:255'],
                'course_name' => ['required', 'string', 'max:255'],

                'privacy_consent' => [
                    'required',
                    Rule::in(['1', '0']),
                ],

                'uli_number' => ['nullable', 'string', 'max:100'],
                'email' => ['nullable', 'email', 'max:255'],

                'sex' => [
                    'nullable',
                    Rule::in(['Male', 'Female']),
                ],

                'employment_status' => [
                    'nullable',
                    Rule::in(['Employed', 'Unemployed']),
                ],

                'civil_status' => [
                    'nullable',
                    Rule::in([
                        'Single',
                        'Married',
                        'Widowed',
                        'Separated',
                        'Solo Parent',
                    ]),
                ],

                'age' => ['nullable', 'integer', 'min:0', 'max:120'],
                'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
                'birth_year' => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:' . (date('Y') + 1),
                ],
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'message' => implode(
                        '; ',
                        $validator->errors()->all()
                    ),
                ];
                continue;
            }

            $uli = trim((string) ($row['uli_number'] ?? ''));
            $email = mb_strtolower(trim((string) ($row['email'] ?? '')));
            $first = mb_strtolower(trim((string) $row['first_name']));
            $last = mb_strtolower(trim((string) $row['last_name']));
            $course = mb_strtolower(trim((string) $row['course_name']));

            // Check duplicates without creating accounts or enrollments.
            $duplicateQuery = Registration::query();
            $hasDuplicateRule = false;

            if ($uli !== '') {
                $duplicateQuery->whereRaw(
                    'LOWER(uli_number) = ?',
                    [mb_strtolower($uli)]
                );
                $hasDuplicateRule = true;
            }

            if ($email !== '') {
                $emailQuery = Registration::query()
                    ->whereRaw('LOWER(email) = ?', [$email])
                    ->whereRaw('LOWER(course_name) = ?', [$course]);

                if ($hasDuplicateRule) {
                    $duplicateQuery->orWhereIn(
                        'id',
                        $emailQuery->select('id')
                    );
                } else {
                    $duplicateQuery = $emailQuery;
                }

                $hasDuplicateRule = true;
            }

            $nameQuery = Registration::query()
                ->whereRaw('LOWER(first_name) = ?', [$first])
                ->whereRaw('LOWER(last_name) = ?', [$last])
                ->whereRaw('LOWER(course_name) = ?', [$course]);

            if ($hasDuplicateRule) {
                $duplicateQuery->orWhereIn(
                    'id',
                    $nameQuery->select('id')
                );
            } else {
                $duplicateQuery = $nameQuery;
            }

            if ($duplicateQuery->exists()) {
                $this->duplicates++;
                continue;
            }

            try {
                $columns = [
                    'uli_number',
                    'entry_date',
                    'last_name',
                    'first_name',
                    'middle_name',
                    'address_street',
                    'address_barangay',
                    'address_city',
                    'address_province',
                    'address_district',
                    'address_region',
                    'email',
                    'contact_no',
                    'nationality',
                    'training_venue',
                    'sex',
                    'employment_status',
                    'civil_status',
                    'birth_month',
                    'birth_day',
                    'birth_year',
                    'age',
                    'birthplace_city',
                    'birthplace_province',
                    'birthplace_region',
                    'education_attainment',
                    'guardian_name',
                    'guardian_address',
                    'classification',
                    'classification_other',
                    'disability_type',
                    'disability_multiple_specify',
                    'disability_cause',
                    'disability_cause_other',
                    'course_name',
                    'scholarship_package',
                    'date_accomplished',
                    'date_received',
                ];

                $payload = [];

                foreach ($columns as $column) {
                    if (array_key_exists($column, $row)) {
                        $payload[$column] = $this->normalizeValue(
                            $column,
                            $row[$column]
                        );
                    }
                }

                $payload['privacy_consent'] =
                    $row['privacy_consent'] === '1' ? 1 : 0;

                // Do not set user_id.
                // Do not create users or course enrollments.
                Registration::create($payload);

                $this->imported++;
            } catch (Throwable $e) {
                report($e);

                $this->errors[] = [
                    'row' => $rowNumber,
                    'message' =>
                        'Could not save row. Check the database field types, required columns, and date values.',
                ];
            }
        }
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $key = mb_strtolower(trim((string) $key));
            $key = preg_replace('/[^a-z0-9]+/', '_', $key);
            $key = trim($key, '_');

            $normalized[$key] = is_string($value)
                ? trim($value)
                : $value;
        }

        // Map common spreadsheet heading variations to expected names.
        $aliases = [
            'last_name' => [
                'lastname',
                'surname',
                'family_name',
            ],
            'first_name' => [
                'firstname',
                'given_name',
            ],
            'middle_name' => [
                'middlename',
                'middle_initial',
            ],
            'course_name' => [
                'course',
                'training_course',
                'name_of_course',
            ],
            'privacy_consent' => [
                'privacy_consent',
                'privacy_consent_agreement',
                'data_privacy_consent',
                'consent',
            ],
            'uli_number' => [
                'uli',
                'uli_no',
                'uli_number',
                'unique_learner_identifier',
            ],
            'contact_no' => [
                'contact_number',
                'phone',
                'phone_number',
                'mobile_number',
            ],
            'address_barangay' => [
                'barangay',
            ],
            'address_city' => [
                'city',
                'municipality',
            ],
            'entry_date' => [
                'registration_date',
            ],
        ];

        foreach ($aliases as $target => $sources) {
            if (!array_key_exists($target, $normalized)) {
                foreach ($sources as $source) {
                    if (array_key_exists($source, $normalized)) {
                        $normalized[$target] = $normalized[$source];
                        break;
                    }
                }
            }
        }

        foreach (['classification', 'disability_type'] as $field) {
            if (
                isset($normalized[$field])
                && is_string($normalized[$field])
                && trim($normalized[$field]) !== ''
            ) {
                $value = $normalized[$field];
                $decoded = json_decode($value, true);

                if (is_array($decoded)) {
                    $normalized[$field] = $decoded;
                } elseif (str_contains($value, '|')) {
                    $normalized[$field] = array_values(array_filter(
                        array_map('trim', explode('|', $value))
                    ));
                } elseif (str_contains($value, ',')) {
                    $normalized[$field] = array_values(array_filter(
                        array_map('trim', explode(',', $value))
                    ));
                }
            }
        }

        return $normalized;
    }

    private function isBlank(array $row): bool
    {
        foreach ($row as $value) {
            if (is_array($value) && count($value) > 0) {
                return false;
            }

            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function normalizeValue(string $column, mixed $value): mixed
    {
        if ($value === null || (is_string($value) && trim($value) === '')) {
            return null;
        }

        if (in_array($column, ['classification', 'disability_type'], true)) {
            if (is_array($value)) {
                return array_values($value);
            }

            return [$value];
        }

        if (in_array(
            $column,
            ['entry_date', 'date_accomplished', 'date_received'],
            true
        )) {
            if (is_numeric($value)) {
                try {
                    return ExcelDate::excelToDateTimeObject(
                        (float) $value
                    )->format('Y-m-d');
                } catch (Throwable $e) {
                    // Try parsing it as a regular date below.
                }
            }

            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (Throwable $e) {
                throw new \InvalidArgumentException(
                    'Invalid date in ' . $column
                );
            }
        }

        if (is_string($value)) {
            return trim($value);
        }

        return $value;
    }
}
