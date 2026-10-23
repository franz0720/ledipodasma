<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsImportTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return [
            'uli_number','entry_date','last_name','first_name','middle_name',
            'address_street','address_barangay','address_city','address_province','address_district','address_region',
            'email','contact_no','nationality','training_venue','sex','employment_status','civil_status',
            'birth_month','birth_day','birth_year','age','birthplace_city','birthplace_province','birthplace_region',
            'education_attainment','guardian_name','guardian_address','classification','classification_other',
            'disability_type','disability_multiple_specify','disability_cause','disability_cause_other',
            'course_name','scholarship_package','privacy_consent','date_accomplished','date_received'
        ];
    }

    public function array(): array
    {
        return [[
            'ULI-0001','2026-10-19','Dela Cruz','Juan','Santos',
            '123 Sample Street','Sample Barangay','Dasmariñas','Cavite','','CALABARZON',
            'juan@example.com','09171234567','Filipino','Sample Training Venue','Male','Unemployed','Single',
            'January',15,2000,26,'Dasmariñas','Cavite','CALABARZON','College Level','','',
            '["Displaced Workers"]','','[]','','','','Computer Literacy','','Agree','2026-10-19',''
        ]];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');
        $sheet->getStyle('1:1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('1:1')->getFill()->setFillType('solid')->getStartColor()->setRGB('075B35');
        $sheet->getRowDimension(1)->setRowHeight(28);
        foreach (range('A', 'Z') as $col) $sheet->getColumnDimension($col)->setWidth(20);
        foreach (['AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM'] as $col) $sheet->getColumnDimension($col)->setWidth(24);
        $sheet->setCellValue('A4', 'Import notes: Keep the first row headers unchanged. Required: last_name, first_name, course_name, privacy_consent. Use Agree/Disagree or Yes/No for privacy_consent. classification and disability_type can be JSON arrays such as ["Displaced Workers"]. Dates should use YYYY-MM-DD. Duplicate ULI, or same email+course, or same first name+last name+course are skipped. This import does not create user accounts.');
        $sheet->mergeCells('A4:AM4');
        $sheet->getStyle('A4')->getAlignment()->setWrapText(true)->setVertical('top');
        $sheet->getRowDimension(4)->setRowHeight(55);
        return [];
    }
}
