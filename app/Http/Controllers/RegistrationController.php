<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Alias for showStep1 to handle route definitions calling `step1`.
     */
    public function step1()
    {
        return $this->showStep1();
    }

    /**
     * GET /register — show step 1 (Learner's Profile).
     * If the applicant already filled this out and clicked "Back" from step 2,
     * repopulate the form from whatever is still saved in the session.
     */
    public function showStep1()
    {
        if (
            Auth::check() &&
            Registration::where('user_id', Auth::id())->exists() &&
            !request()->boolean('another')
        ) {
            return redirect()
                ->route('homepage')
                ->with('info', 'You have already submitted your registration.');
        }

        if (request()->boolean('another') && Auth::check()) {
        $previousRegistration = Registration::where('user_id', Auth::id())
            ->latest('id')
            ->first();

        $step1 = $previousRegistration
            ? $previousRegistration->toArray()
            : [];
        } else {
            $step1 = Session::get('reg_step1', []);
        }

        return view('registrationform1', ['step1' => $step1]);
    }

    /**
     * POST /register/step-2 — validate step 1, stash in session, move on.
     */
    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            // Optional — often auto-generated / filled by staff later
            'uli_number'          => ['nullable', 'string', 'max:50'],
            'entry_date'          => ['nullable', 'date'],

            // Required
            'id_picture'          => ['required', 'image', 'max:4096'],
            'last_name'           => ['required', 'string', 'max:100'],
            'first_name'          => ['required', 'string', 'max:100'],
            'middle_name'         => ['nullable', 'string', 'max:100'],
            'address_street'      => ['required', 'string', 'max:150'],
            'address_barangay'    => ['required', 'string', 'max:100'],
            'address_city'        => ['required', 'string', 'max:100'],
            'address_province'    => ['required', 'string', 'max:100'],
            'address_district'    => ['nullable', 'string', 'max:100'],
            'address_region'      => ['nullable', 'string', 'max:100'],
            'email'               => ['required', 'email', 'max:150'],
            'contact_no'          => ['required', 'string', 'max:30'],
            'nationality'         => ['required', 'string', 'max:100'],
            'training_venue'      => ['required', 'string', 'max:150'],

            'sex'                 => ['required', 'in:Male,Female'],
            'employment_status'   => ['required', 'in:Employed,Unemployed'],
            'civil_status'        => ['required', 'in:Single,Married,Widowed,Separated,Solo Parent'],
            'birth_month'         => ['required', 'string', 'max:20'],
            'birth_day'           => ['required', 'string', 'max:2'],
            'birth_year'          => ['required', 'string', 'max:4'],
            'age'                 => ['required', 'integer', 'min:0', 'max:120'],
            'birthplace_city'     => ['nullable', 'string', 'max:100'],
            'birthplace_province' => ['nullable', 'string', 'max:100'],
            'birthplace_region'   => ['nullable', 'string', 'max:100'],
            'education_attainment'=> ['required', 'string', 'max:100'],
            'guardian_name'       => ['nullable', 'string', 'max:150'],
            'guardian_address'    => ['nullable', 'string', 'max:255'],
        ]);

        // Upload picture immediately to persist file across redirects
        if ($request->hasFile('id_picture')) {
            $validated['id_picture_path'] = $request->file('id_picture')
                ->store('registrations/id_pictures', 'public');
        }

        // The raw UploadedFile object can't be serialized into the session —
        // only the stored path (already captured above) should be kept.
        unset($validated['id_picture']);

        Session::put('reg_step1', array_merge(
        Session::get('reg_step1', []),
        $validated
    ));

        return redirect()->route('registration.step2.show');
    }

    /**
     * GET /register/step-2 — show step 2, but only if step 1 was completed.
     */

public function showStep2()
{
    if (! Session::has('reg_step1')) {
        return redirect()
            ->route('registration.step1')
            ->with('error', 'Please complete page 1 first.');
    }

    $previousRegistration = null;

    if (Auth::check()) {
        $previousRegistration = Registration::where('user_id', Auth::id())
            ->latest('id')
            ->first();
    }

    return view('registrationform2', [
        'previousRegistration' => $previousRegistration,
    ]);
}


    /**
     * POST /register/submit — validate step 2, merge with step 1, save to DB.
     */
    public function store(Request $request)
    {
        if (! Session::has('reg_step1')) {
            return redirect()
                ->route('registration.step1')
                ->with('error', 'Your session expired — please start again.');
        }

        $validated = $request->validate([
            // Optional — not every applicant fits a special category
            'classification'                => ['nullable', 'array'],
            'classification.*'              => ['string'],
            'classification_other'          => ['nullable', 'string', 'max:150'],

            // Optional — for PWD applicants only, typically filled by TESDA personnel
            'disability_type'               => ['nullable', 'array'],
            'disability_type.*'             => ['string'],
            'disability_multiple_specify'   => ['nullable', 'string', 'max:150'],
            'disability_cause'              => ['nullable', 'in:Congenital/Inborn,Illness,Injury'],
            'disability_cause_other'        => ['nullable', 'string', 'max:150'],

            // Required
            'course_name'                   => ['required', 'string', 'max:150'],
            'scholarship_package'           => ['nullable', 'string', 'max:150'],

            'privacy_consent'               => ['required', 'in:Agree,Disagree'],

            'date_accomplished'             => ['required', 'date'],
            'date_received'                 => ['nullable', 'date'],
            'photo_1x1'                      => ['required', 'image', 'max:2048'],

            // Signature — captured as a base64 PNG from the signature pad
            'signature_data'                => ['required', 'string'],

            // Thumbmark intentionally left unvalidated for now — not yet implemented on the form.
        ]);

        if ($request->hasFile('photo_1x1')) {
            $validated['photo_1x1_path'] = $request->file('photo_1x1')
                ->store('registrations/photos_1x1', 'public');
        }

        // Drop the raw UploadedFile object — only the stored path (above) should
        // be persisted, and 'photo_1x1' isn't an actual column on the table.
        unset($validated['photo_1x1']);

        // Decode and store the signature image, then drop the raw base64 from $validated
        // so we don't try to persist a huge data URI into a plain column.
        if (! empty($validated['signature_data'])) {
            $signaturePath = $this->storeBase64Image(
                $validated['signature_data'],
                'registrations/signatures'
            );

            if ($signaturePath) {
                $validated['signature_path'] = $signaturePath;
            }
        }
        unset($validated['signature_data']);

        $data = array_merge(Session::get('reg_step1'), $validated);
        $data['user_id'] = Auth::id();

        $registration = Registration::create($data);

        Session::forget('reg_step1');

        return redirect()
            ->route('handle')
            ->with('success', "Registration submitted! Reference #{$registration->id}.");
    }

    /**
     * Decode a base64 data URI (e.g. from a <canvas> signature pad) and store it
     * as a PNG file on the public disk. Returns the stored path, or null on failure.
     */
    private function storeBase64Image(string $dataUri, string $directory): ?string
    {
        if (! preg_match('/^data:image\/(\w+);base64,/', $dataUri, $matches)) {
            return null;
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $encoded   = substr($dataUri, strpos($dataUri, ',') + 1);
        $decoded   = base64_decode(str_replace(' ', '+', $encoded));

        if ($decoded === false) {
            return null;
        }

        $path = $directory . '/' . uniqid('sig_', true) . '.' . $extension;
        Storage::disk('public')->put($path, $decoded);

        return $path;
    }

    /**
     * Display administrative detail view for a specific registration.
     */
    public function adminShow(Registration $registration)
    {
        return view('admin.registrations_show', compact('registration'));
    }

    /**
     * Export registered learners to CSV format.
     */
    public function exportCsv()
    {
        $filename = 'registrations_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'ID', 'ULI Number', 'Entry Date', 'Last Name', 'First Name', 'Middle Name',
            'Address Street', 'Barangay', 'City', 'Province', 'District', 'Region',
            'Email', 'Contact No', 'Nationality', 'Training Venue',
            'Sex', 'Civil Status', 'Employment Status',
            'Birth Month', 'Birth Day', 'Birth Year', 'Age',
            'Birthplace City', 'Birthplace Province', 'Birthplace Region',
            'Educational Attainment', 'Guardian Name', 'Guardian Address',
            'Classification', 'Classification Other',
            'Disability Type', 'Disability Multiple Specify', 'Disability Cause', 'Disability Cause Other',
            'Course Name', 'Scholarship Package', 'Privacy Consent',
            'Date Accomplished', 'Date Received', 'Submitted At',
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Registration::latest()->chunk(200, function ($rows) use ($file) {
                foreach ($rows as $r) {
                    fputcsv($file, [
                        $r->id, $r->uli_number, optional($r->entry_date)->format('Y-m-d'),
                        $r->last_name, $r->first_name, $r->middle_name,
                        $r->address_street, $r->address_barangay, $r->address_city,
                        $r->address_province, $r->address_district, $r->address_region,
                        $r->email, $r->contact_no, $r->nationality, $r->training_venue,
                        $r->sex, $r->civil_status, $r->employment_status,
                        $r->birth_month, $r->birth_day, $r->birth_year, $r->age,
                        $r->birthplace_city, $r->birthplace_province, $r->birthplace_region,
                        $r->education_attainment, $r->guardian_name, $r->guardian_address,
                        implode('; ', $r->classification ?? []), $r->classification_other,
                        implode('; ', $r->disability_type ?? []), $r->disability_multiple_specify,
                        $r->disability_cause, $r->disability_cause_other,
                        $r->course_name, $r->scholarship_package, $r->privacy_consent,
                        optional($r->date_accomplished)->format('Y-m-d'),
                        optional($r->date_received)->format('Y-m-d'),
                        $r->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download registration document in PDF format.
     */
    public function downloadPdf(Registration $registration)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.registrations_pdf', compact('registration'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('registration_' . $registration->id . '.pdf');
    }
}