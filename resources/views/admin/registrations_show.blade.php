<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TESDA Learner Registration Form</title>
<style>
:root {
    --tesda-green: #075b35;
    --tesda-green-dark: #043f26;
    --line: #333;
    --ink: #111;
    --muted: #333;
    --paper: #fff;
    --soft: #f4f7f4;
}
* { box-sizing: border-box; }
@page { size: A4 portrait; margin: 8mm; }
html, body { margin: 0; padding: 0; }
body {
    background: #eef1ee;
    color: var(--ink);
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8pt;
    line-height: 1.25;
}
.printbar {
    display: none !important;
    width: 190mm;
    margin: 12px auto;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #bbb;
    display: table;
    font-family: Arial, sans-serif;
}
.printbar button {
    background: var(--tesda-green);
    color: #fff;
    border: 0;
    border-radius: 3px;
    padding: 9px 14px;
    font-weight: 700;
    cursor: pointer;
}
.form-page {
    width: 194mm;
    margin: 0 auto 0;
    min-height: 0;
    padding: 0;
    background: #fff;
    border: 1px solid #777;
    position: relative;
    overflow: hidden;
    page-break-after: always;
    break-after: page;
}
.form-page:last-child { page-break-after: auto; break-after: auto; }
/* Do not force a second break before page 2; page 1 already breaks after itself. */
.form-page + .form-page { page-break-before: auto; break-before: auto; }
.letterhead {
    background: var(--tesda-green);
    color: #fff;
    padding: 10px 14px;
    display: table;
    width: 100%;
    border-bottom: 2px solid var(--tesda-green-dark);
}
.crest {
    display: table-cell;
    vertical-align: middle;
    width: 55px;
    height: 43px;
    border-radius: 50%;
    background: #fff;
    text-align: center;
    color: var(--tesda-green-dark);
    font: 700 9px Arial, sans-serif;
    text-align: center;
    border: 1px solid #fff;
}
.agency { display: table-cell; vertical-align: middle; padding-left: 12px; font-family: Arial, sans-serif; }
.agency .en { font-size: 10px; font-weight: 700; }
.agency .fil { font-size: 8px; margin-top: 3px; color: #fff; }
.titlebar {
    display: table;
    width: 100%;
    padding: 8px 14px;
    border-bottom: 2px solid var(--tesda-green);
    background: #fff;
}
.titlebar h1 {
    display: table-cell;
    vertical-align: middle;
    margin: 0;
    font: 700 17px Arial, sans-serif;
    color: #111;
    letter-spacing: 0;
}
.title-meta { display: table-cell; vertical-align: middle; text-align: right; white-space: nowrap; }
.step {
    font: 700 8px Arial, sans-serif;
    color: #fff;
    background: var(--tesda-green);
    padding: 4px 7px;
    border-radius: 2px;
    white-space: nowrap;
}
.formcode {
    font: 7px "Courier New", monospace;
    text-align: right;
    color: #222;
    line-height: 1.3;
    white-space: nowrap;
}
.section { padding: 6px 10px; border-bottom: 1px solid #777; }
.section:last-of-type { border-bottom: 0; }
.section-head { display: table; width: 100%; margin-bottom: 5px; }
.num {
    font: 700 9px Arial, sans-serif;
    color: #fff;
    background: var(--tesda-green);
    padding: 2px 6px;
    border-radius: 1px;
}
.section-head h2 {
    margin: 0;
    font: 700 9px Arial, sans-serif;
    color: #111;
    text-transform: uppercase;
    letter-spacing: .01em;
}
.optional, .hint { font-size: 7px; color: #333; font-weight: 400; font-style: italic; text-transform: none; letter-spacing: 0; }
.subhead {
    font: 700 8px Arial, sans-serif;
    color: #111;
    margin: 7px 0 4px;
    display: block;
}
.dot { width: 4px; height: 4px; background: var(--tesda-green); border-radius: 50%; display: inline-block; }
.row { display: table; width: 100%; table-layout: fixed; margin-bottom: 4px; }
.field { display: table-cell; vertical-align: top; width: 25%; padding-right: 6px; }
.row .field.wide { width: 40%; }
.row.name-row .field { width: 33.333%; }
.row.uli-row .field:first-child { width: 66%; }
.row.uli-row .field:last-child { width: 34%; }
.row.address-row .field:first-child { width: 60%; }
.row.address-row .field:last-child { width: 40%; }
.row.contact-row .field:first-child { width: 45%; }
.row.contact-row .field:nth-child(2) { width: 25%; }
.row.contact-row .field:nth-child(3) { width: 30%; }
.field:last-child { padding-right: 0; }
.field.wide { width: 40%; }
.field.small { width: 16%; }
.label {
    display: block;
    font: 7px Arial, sans-serif;
    color: #222;
    text-transform: uppercase;
    letter-spacing: 0;
    margin-bottom: 2px;
}
.value {
    min-height: 20px;
    padding: 4px 5px;
    border: 1px solid #777;
    border-radius: 0;
    background: #fff;
    font: 8px Arial, sans-serif;
    color: #111;
    overflow-wrap: anywhere;
    white-space: pre-wrap;
}
.value.blank { color: #555; font-weight: 400; }
.choice-grid { display: block; width: 100%; line-height: 1.7; }
.choice { display: inline-block; vertical-align: top; margin: 0 10px 3px 0; font: 7.5px Arial, sans-serif; line-height: 1.25; }
.box {
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    width: 10px;
    height: 10px;
    border: 1px solid #333;
    font: 700 7px Arial, sans-serif;
    margin-top: 0;
}
.box.checked { background: var(--tesda-green); color: #fff; border-color: var(--tesda-green); }
.two-col, .edu-grid { display: block; width: 100%; line-height: 1.6; }
.two-col > *, .edu-grid > * { display: inline-block; vertical-align: top; width: 48%; margin: 0 1% 3px 0; }
.photo-row { display: table; width: 100%; }
.photo-row > div:first-child { display: table-cell; vertical-align: top; width: auto; padding-right: 10px; }
.photo-row > .photo-box { display: table-cell; vertical-align: top; }
.photo-box {
    width: 62px;
    height: 80px;
    border: 1px solid #777;
    background: #fff;
    text-align: center;
    color: #333;
    font: 7px Arial, sans-serif;
    overflow: hidden;
    flex-shrink: 0;
}
.photo-box img { width: 100%; height: 100%; object-fit: cover; }
.disclaimer { border: 1px solid #777; padding: 7px 8px; font-size: 8px; line-height: 1.4; margin-bottom: 6px; }
.signature-area { display: table; width: 100%; margin-top: 9px; }
.signature-area > div { display: table-cell; width: 50%; vertical-align: top; padding-right: 10px; }
.signature-box { width: 100%; height: 44px; border-bottom: 1px solid #333; text-align: center; overflow: hidden; }
.signature-box img { max-height: 42px; max-width: 100%; object-fit: contain; }
.sig-caption { font: 7px Arial, sans-serif; color: #222; text-transform: uppercase; letter-spacing: 0; text-align: center; margin-top: 3px; }
.footer { display: table; width: 100%; border-top: 1px solid #777; padding: 5px 14px; color: #222; font: 6.5px Arial, sans-serif; }
.footer span { display: table-cell; width: 50%; vertical-align: middle; }
.footer span:last-child { text-align: right; }
.record-stamp { font: 7px Arial, sans-serif; color: #222; text-align: right; margin-top: 4px; }
@media screen {
    body { padding: 14px; }
    .form-page { box-shadow: 0 2px 12px #00000014; }
}
@media print {
    body { background: #fff; padding: 0; }
    .printbar { display: none !important; }
    .form-page { width: 100%; min-height: 0; margin: 0; border: 0; box-shadow: none; }
    .section { page-break-inside: avoid; break-inside: avoid; }
    .letterhead, .num, .step, .box.checked {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
</head>
<body>

@php
    $reg = $registration;

    $recordLabel = 'RECORD ' . str_pad(
        (string) ($reg->id ?? 0),
        4,
        '0',
        STR_PAD_LEFT
    );

    $fmt = fn ($value) => ($value !== null && $value !== '') ? $value : '—';

    $decodeList = function ($value) {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return $decoded;
            }

            return trim($value) !== '' ? [$value] : [];
        }

        return [];
    };

    $isChecked = function ($actual, $expected) {
        return mb_strtolower(trim((string) $actual))
            === mb_strtolower(trim((string) $expected));
    };

    $imageUrl = function ($path) {
        if (!$path) {
            return null;
        }

        $path = ltrim((string) $path, '/');

        return str_starts_with($path, 'storage/')
            ? asset($path)
            : asset('storage/' . $path);
    };

    $classifications = $decodeList($reg->classification ?? null);
    $disabilities = $decodeList($reg->disability_type ?? null);

    $fullName = trim(implode(' ', array_filter([
        $reg->first_name ?? null,
        $reg->middle_name ?? null,
        $reg->last_name ?? null,
    ], fn ($part) => $part !== null && trim((string) $part) !== '')));
@endphp

<div class="printbar">
    <span><strong>Learner Registration Form</strong> — use Print / Save as PDF to export.</span>
    <button type="button" onclick="window.print()">Print / Save PDF</button>
</div>


    <!-- FORM PAGE 1 -->
    <section class="form-page">
        <header class="letterhead">
            <div class="crest">TESDA</div>
            <div class="agency"><div class="en">Technical Education and Skills Development Authority</div><div class="fil">Pangasiwaan sa Edukasyong Teknikal at Pagpapaunlad ng Kaanyuan</div></div>
        </header>
        <div class="titlebar"><h1>Registration Form</h1><div class="title-meta"><span class="step">Step 1 of 2</span><div class="formcode">MIS 03–01<br>(rev. 2020)<br>{{ $recordLabel }}</div></div></div>
        <div class="section">
            <div class="section-head"><span class="num">1</span><h2>TE2MIS Auto Generated</h2></div>
            <div class="photo-row">
                <div style="display:table-cell; width:auto; vertical-align:top;">
                    <div class="row uli-row"><div class="field wide"><span class="label">1.1 Unique Learner Identifier (ULI) Number <span class="optional">(optional)</span></span><div class="value">{{ $fmt($reg->uli_number ?? null) }}</div></div><div class="field"><span class="label">1.2 Entry Date <span class="optional">(optional)</span></span><div class="value">{{ $fmt($reg->entry_date ?? null) }}</div></div></div>
                </div>
                <div class="photo-box">@if(!empty($reg->id_picture_path))<img src="{{ $imageUrl($reg->id_picture_path) }}" alt="ID picture">@else ID Picture @endif</div>
            </div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">2</span><h2>Learner / Manpower Profile</h2></div>
            <div class="subhead"><span class="dot"></span>2.1 Name</div>
            <div class="row name-row"><div class="field wide"><span class="label">Last Name, Extension (Jr., Sr.) *</span><div class="value">{{ $fmt($reg->last_name ?? null) }}</div></div><div class="field wide"><span class="label">First Name *</span><div class="value">{{ $fmt($reg->first_name ?? null) }}</div></div><div class="field wide"><span class="label">Middle Name (optional)</span><div class="value">{{ $fmt($reg->middle_name ?? null) }}</div></div></div>
            <div class="subhead"><span class="dot"></span>2.2 Complete Permanent Mailing Address</div>
            <div class="row address-row"><div class="field wide"><span class="label">Number, Street *</span><div class="value">{{ $fmt($reg->address_street ?? null) }}</div></div><div class="field"><span class="label">Barangay *</span><div class="value">{{ $fmt($reg->address_barangay ?? null) }}</div></div></div>
            <div class="row"><div class="field"><span class="label">City / Municipality *</span><div class="value">{{ $fmt($reg->address_city ?? null) }}</div></div><div class="field"><span class="label">Province *</span><div class="value">{{ $fmt($reg->address_province ?? null) }}</div></div><div class="field"><span class="label">District (optional)</span><div class="value">{{ $fmt($reg->address_district ?? null) }}</div></div><div class="field"><span class="label">Region (optional)</span><div class="value">{{ $fmt($reg->address_region ?? null) }}</div></div></div>
            <div class="row contact-row"><div class="field wide"><span class="label">Email Address / Facebook Account *</span><div class="value">{{ $fmt($reg->email ?? null) }}</div></div><div class="field"><span class="label">Contact No. *</span><div class="value">{{ $fmt($reg->contact_no ?? null) }}</div></div><div class="field"><span class="label">Nationality *</span><div class="value">{{ $fmt($reg->nationality ?? null) }}</div></div></div>
            <div class="subhead"><span class="dot"></span>2.3 Training Venue *</div><div class="value">{{ $fmt($reg->training_venue ?? null) }}</div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">3</span><h2>Personal Information</h2></div>
            <div class="two-col">
                <div><div class="subhead"><span class="dot"></span>3.1 Sex *</div><div class="choice-grid">@foreach(['Male','Female'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->sex ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->sex ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div></div>
                <div><div class="subhead"><span class="dot"></span>3.3 Employment Status (before training) *</div><div class="choice-grid">@foreach(['Employed','Unemployed'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->employment_status ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->employment_status ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div></div>
            </div>
            <div class="subhead"><span class="dot"></span>3.2 Civil Status *</div><div class="choice-grid">@foreach(['Single','Married','Widowed','Separated','Solo Parent'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->civil_status ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->civil_status ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
            <div class="subhead"><span class="dot"></span>3.4 Birthdate *</div><div class="row"><div class="field"><span class="label">Month of Birth</span><div class="value">{{ $fmt($reg->birth_month ?? null) }}</div></div><div class="field small"><span class="label">Day of Birth</span><div class="value">{{ $fmt($reg->birth_day ?? null) }}</div></div><div class="field small"><span class="label">Year of Birth</span><div class="value">{{ $fmt($reg->birth_year ?? null) }}</div></div><div class="field small"><span class="label">Age</span><div class="value">{{ $fmt($reg->age ?? null) }}</div></div></div>
            <div class="subhead"><span class="dot"></span>3.5 Birthplace <span class="optional">(optional)</span></div><div class="row"><div class="field"><span class="label">City / Municipality</span><div class="value">{{ $fmt($reg->birthplace_city ?? null) }}</div></div><div class="field"><span class="label">Province</span><div class="value">{{ $fmt($reg->birthplace_province ?? null) }}</div></div><div class="field"><span class="label">Region</span><div class="value">{{ $fmt($reg->birthplace_region ?? null) }}</div></div></div>
            <div class="subhead"><span class="dot"></span>3.6 Educational Attainment Before the Training (Trainee) *</div><div class="edu-grid">@foreach(['No Grade Completed','Pre-School (Nursery/Kinder/Prep)','High School Undergraduate','High School Graduate','Junior High School Graduate','Senior High School Graduate','Elementary Undergraduate','Elementary Graduate','Post Secondary Undergraduate','Post Secondary Graduate','College Undergraduate','College Graduate or Higher'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->education_attainment ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->education_attainment ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
            <div class="subhead"><span class="dot"></span>3.7 Parent / Guardian <span class="optional">(optional)</span></div><div class="row"><div class="field"><span class="label">Name</span><div class="value">{{ $fmt($reg->guardian_name ?? null) }}</div></div><div class="field"><span class="label">Address</span><div class="value">{{ $fmt($reg->guardian_address ?? null) }}</div></div></div>
        </div>
        <footer class="footer"><span>Technical Education and Skills Development Authority · MIS 03-01</span><span>Registration ID: {{ $reg->id }} · Page 1 of 2</span></footer>
    </section>
    <!-- FORM PAGE 2 -->
    <section class="form-page">
        <header class="letterhead">
            <div class="crest">TESDA</div>
            <div class="agency"><div class="en">Technical Education and Skills Development Authority</div><div class="fil">Pangasiwaan sa Edukasyong Teknikal at Pagpapaunlad ng Kaanyuan</div></div>
        </header>
        <div class="titlebar"><h1>Registration Form</h1><div class="title-meta"><span class="step">Step 2 of 2</span><div class="formcode">MIS 03–01<br>(rev. 2020)<br>{{ $recordLabel }}</div></div></div>
        <div class="section">
            <div class="section-head"><span class="num">4</span><h2>Learner / Trainee / Student (Clients) Classification <span class="optional">(optional)</span></h2></div>
            <div class="two-col">@foreach(['4Ps Beneficiary','Agrarian Reform Beneficiary','Balik Probinsya','Displaced Workers','Drug Dependents Surrenderees/Surrenderers','Family Members of AFP and PNP Wounded-in-Action','Family Members of AFP and PNP Killed-in-Action','Farmers and Fishermen','Indigenous People & Cultural Communities','Industry Workers','Inmates and Detainees','MILF Beneficiary','Out-of-School Youth','Overseas Filipino Workers (OFW) Dependents','RCEF-RESP','Rebel Returnees/Decommissioned Combatants','Returning/Repatriated OFW','Student','TESDA Alumni','TVET Trainers','Uniformed Personnel','Victim of Natural Disasters and Calamities','Wounded-in-Action AFP & PNP Personnel'] as $v)<span class="choice"><span class="box {{ in_array($v,$classifications,true)?'checked':'' }}">{{ in_array($v,$classifications,true)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
            <div class="choice" style="margin-top:7px"><span class="box {{ in_array('Others',$classifications,true)?'checked':'' }}">{{ in_array('Others',$classifications,true)?'✓':'' }}</span>Others (please specify): <strong>{{ $fmt($reg->classification_other ?? null) }}</strong></div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">5</span><h2>Type of Disability <span class="optional">(optional)</span></h2></div>
            <div class="hint" style="margin-bottom:8px">For Persons with Disability only — to be filled up by TESDA personnel</div>
            <div class="two-col">@foreach(['Mental/Intellectual Disability','Hearing Disability','Psychosocial Disability','Visual Disability','Speech Impairment','Disability Due to Chronic Illness','Learning Disability','Orthopedic (Musculoskeletal) Disability'] as $v)<span class="choice"><span class="box {{ in_array($v,$disabilities,true)?'checked':'' }}">{{ in_array($v,$disabilities,true)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
            <div class="choice" style="margin-top:7px">Multiple Disabilities, specify: <strong>{{ $fmt($reg->disability_multiple_specify ?? null) }}</strong></div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">6</span><h2>Causes of Disability <span class="optional">(optional)</span></h2></div>
            <div class="hint" style="margin-bottom:8px">For Persons with Disability only — to be filled up by TESDA personnel</div>
            <div class="choice-grid">@foreach(['Congenital/Inborn','Illness','Injury'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->disability_cause ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->disability_cause ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
            <div class="row" style="margin-top:8px"><div class="field"><span class="label">Others, please specify</span><div class="value">{{ $fmt($reg->disability_cause_other ?? null) }}</div></div></div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">7</span><h2>Name of Course / Qualification *</h2></div><div class="value">{{ $fmt($reg->course_name ?? null) }}</div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">8</span><h2>If Scholar, What Type of Scholarship Package? <span class="optional">(optional)</span></h2></div><div class="subhead"><span class="dot"></span>TWSP, PESFA, STEP, others</div><div class="value">{{ $fmt($reg->scholarship_package ?? null) }}</div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">9</span><h2>Privacy Disclaimer *</h2></div>
            <div class="disclaimer">I hereby allow TESDA to use/post my contact details, name, email, cellphone/landline nos. and other information I provided which may be used for processing of my scholarship application, for employment opportunities and for the survey of TESDA programs.</div>
            <div class="choice-grid">@foreach(['Agree','Disagree'] as $v)<span class="choice"><span class="box {{ $isChecked($reg->privacy_consent ?? '',$v)?'checked':'' }}">{{ $isChecked($reg->privacy_consent ?? '',$v)?'✓':'' }}</span>{{ $v }}</span>@endforeach</div>
        </div>
        <div class="section">
            <div class="section-head"><span class="num">10</span><h2>Applicant's Signature *</h2></div>
            <p style="font-size:9px;text-align:center;font-style:italic;margin:4px 0 12px">This is to certify that the information stated above is true and correct.</p>
            <div class="signature-area"><div style="display:table-cell; width:auto; vertical-align:top;"><div class="signature-box">@if(!empty($reg->signature_path))<img src="{{ $imageUrl($reg->signature_path) }}" alt="Applicant signature">@else&nbsp;@endif</div><div class="sig-caption">Applicant's Signature Over Printed Name</div><div class="value" style="margin-top:6px">{{ $fmt($fullName) }}</div></div><div style="display:table-cell; width:auto; vertical-align:top;"><span class="label">Date Accomplished *</span><div class="value">{{ $fmt($reg->date_accomplished ?? null) }}</div><span class="label" style="margin-top:10px">Date Received (TESDA use)</span><div class="value">{{ $fmt($reg->date_received ?? null) }}</div></div></div>
        </div>
        <footer class="footer"><span>Technical Education and Skills Development Authority · MIS 03-01</span><span>Registration ID: {{ $reg->id }} · Page 2 of 2</span></footer>
    </section>


</body>
</html>
