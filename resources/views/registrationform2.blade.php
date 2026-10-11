{{--
registrationform2.blade.php
Step 2 of 2 — Learner's Profile Form (TESDA MIS 03-01), continued

Wiring notes (adjust to your app):
- This view posts to route('registration.submit'). Define that route/controller
to merge session('reg_step1') with this request's input and persist the full record.
- "Back" link points to route('registration.step1') so the applicant can revise page 1.
- Signature is captured via a canvas signature pad (signature_pad.js) and submitted
as a base64 PNG in the hidden field `signature_data`.
- Thumbmark is intentionally left as a static placeholder for now — to be wired up later.
--}}
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner's Profile Form — TESDA (Page 2)</title>
    <style>
      :root {
      --navy: #025628;
      --navy-deep: #013d1c;
      --paper: #f3f6f4;
      --line: #cfd9d3;
      --ink: #1c2420;
      --muted: #5c6b62;
      --field-bg: #ffffff;
      --accent-soft: #eaf2ec;
      --radius: 6px;
      }

      * {
      box-sizing: border-box;
      }

      body {
      margin: 0;
      font-family: "Georgia", "Times New Roman", serif;
      background: var(--paper);
      color: var(--ink);
      padding: 32px 16px 80px;
      }

      .sheet {
      max-width: 880px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid var(--line);
      box-shadow: 0 2px 24px rgba(13, 47, 94, 0.08);
      }

      .letterhead {
      background: linear-gradient(135deg, var(--navy) 0%, var(--navy-deep) 100%);
      color: #fff;
      padding: 22px 28px;
      display: flex;
      align-items: center;
      gap: 18px;
      position: relative;
      overflow: hidden;
      }

      .letterhead::after {
      content: "";
      position: absolute;
      right: -40px;
      top: -40px;
      width: 160px;
      height: 160px;
      border: 2px solid rgba(255, 255, 255, 0.25);
      border-radius: 50%;
      }

      .crest {
      width: 56px;
      height: 56px;
      flex-shrink: 0;
      border-radius: 50%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--navy);
      font-family: "Trebuchet MS", sans-serif;
      font-weight: 700;
      font-size: 11px;
      text-align: center;
      border: 2px solid var(--navy);
      }

      .letterhead .agency {
      font-family: "Trebuchet MS", sans-serif;
      }

      .agency .en {
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .03em;
      }

      .agency .fil {
      font-size: 11px;
      color: #d7e6dc;
      margin-top: 2px;
      }

      .titlebar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 28px;
      border-bottom: 3px solid var(--navy);
      background: #fff;
      }

      .titlebar h1 {
      margin: 0;
      font-size: 26px;
      letter-spacing: .02em;
      color: var(--navy-deep);
      font-family: "Trebuchet MS", sans-serif;
      }

      .step-indicator {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      font-weight: 700;
      color: #fff;
      background: var(--navy);
      padding: 4px 12px;
      border-radius: 20px;
      letter-spacing: .03em;
      }

      .formcode {
      font-family: "Courier New", monospace;
      font-size: 11px;
      text-align: right;
      color: var(--muted);
      line-height: 1.4;
      }

      .section {
      padding: 20px 28px;
      border-bottom: 1px solid var(--line);
      }

      .section:last-of-type {
      border-bottom: none;
      }

      .section-head {
      display: flex;
      align-items: baseline;
      gap: 10px;
      margin-bottom: 14px;
      }

      .section-head .num {
      font-family: "Trebuchet MS", sans-serif;
      font-weight: 700;
      color: #fff;
      background: var(--navy);
      padding: 2px 10px;
      border-radius: 3px;
      font-size: 14px;
      }

      .section-head h2 {
      margin: 0;
      font-size: 16px;
      font-family: "Trebuchet MS", sans-serif;
      color: var(--navy-deep);
      text-transform: uppercase;
      letter-spacing: .04em;
      }

      .section-head .hint {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      font-style: italic;
      color: var(--muted);
      font-weight: 400;
      text-transform: none;
      }

      .req {
      color: #b3261e;
      font-weight: 700;
      }

      .optional {
      color: var(--muted);
      font-weight: 400;
      font-style: italic;
      font-size: 11px;
      text-transform: none;
      letter-spacing: 0;
      }

      .subhead {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--navy);
      margin: 16px 0 8px;
      display: flex;
      align-items: center;
      gap: 6px;
      }

      .subhead .dot {
      width: 6px;
      height: 6px;
      background: var(--navy);
      border-radius: 50%;
      display: inline-block;
      }

      .row {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 12px;
      }

      .field {
      flex: 1 1 160px;
      display: flex;
      flex-direction: column;
      gap: 4px;
      }

      .field.wide {
      flex: 2 1 260px;
      }

      label {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .03em;
      }

      input[type="text"],
      input[type="date"] {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 8px 10px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 14px;
      background: var(--field-bg);
      color: var(--ink);
      width: 100%;
      }

      input:focus {
      outline: 2px solid var(--navy);
      outline-offset: 1px;
      border-color: var(--navy);
      }

      .choice-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 10px 22px;
      }

      .choice {
      display: flex;
      align-items: center;
      gap: 6px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 13.5px;
      }

      .choice input {
      width: 15px;
      height: 15px;
      accent-color: var(--navy);
      cursor: pointer;
      }

      .check-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 8px 24px;
      }

      @media (max-width:640px) {
      .check-grid {
      grid-template-columns: 1fr;
      }
      }

      .inline-input {
      border: none;
      border-bottom: 1px solid var(--line);
      background: transparent;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 13px;
      padding: 2px 4px;
      width: 160px;
      }

      .inline-input:focus {
      outline: none;
      border-bottom-color: var(--navy);
      }

      .disclaimer-box {
      background: var(--accent-soft);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 14px 16px;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 12px;
      }

      .sig-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      }

      @media (max-width:640px) {
      .sig-grid {
      grid-template-columns: 1fr;
      }
      }

      .sig-block {
      display: flex;
      flex-direction: column;
      gap: 6px;
      }

      .sig-pad-wrap {
      display: flex;
      flex-direction: column;
      gap: 6px;
      }

      #signaturePad {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: #fff;
      touch-action: none;
      cursor: crosshair;
      width: 100%;
      max-width: 320px;
      height: 110px;
      }

      .sig-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      }

      .sig-clear-btn {
      background: #fff;
      color: var(--navy);
      border: 1px solid var(--navy);
      padding: 4px 12px;
      border-radius: var(--radius);
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      cursor: pointer;
      }

      .sig-clear-btn:hover {
      background: var(--accent-soft);
      }

      .sig-status {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      }

      .sig-status.filled {
      color: var(--navy);
      font-weight: 600;
      }

      .sig-caption {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .03em;
      text-align: center;
      }

      .photo-thumb-row {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 16px;
      }

      .photo-thumb {
      width: 100px;
      height: 110px;
      border: 2px dashed var(--line);
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      color: var(--muted);
      font-family: "Trebuchet MS", sans-serif;
      font-size: 10.5px;
      text-align: center;
      cursor: pointer;
      background: var(--accent-soft);
      overflow: hidden;
      flex-shrink: 0;
      }

      .photo-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      }

      .photo-thumb input {
      display: none;
      }

      .certify-note {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 12px;
      font-style: italic;
      color: var(--muted);
      text-align: center;
      margin: 14px 0 0;
      }

      .footer-note {
      padding: 16px 28px 24px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      }

      .btn {
      background: var(--navy);
      color: #fff;
      border: none;
      padding: 12px 26px;
      border-radius: var(--radius);
      font-family: "Trebuchet MS", sans-serif;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: .02em;
      cursor: pointer;
      transition: background .15s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      }

      .btn:hover {
      background: var(--navy-deep);
      }

      .btn-outline {
      background: #fff;
      color: var(--navy);
      border: 1px solid var(--navy);
      }

      .btn-outline:hover {
      background: var(--accent-soft);
      }

      .btn-row {
      display: flex;
      gap: 12px;
      }
    </style>

    <link rel="icon" type="image/png" href="{{ asset('images/logo_ledipo.png') }}">

  </head>

  <body>

    <form class="sheet" id="learnerForm2" method="POST"
      action="{{ route('registration.submit') }}" enctype="multipart/form-data" novalidate>
      @csrf

      @if ($errors->any())
        <div style="background:#fdecea;border:1px solid #f5c6cb;color:#b3261e;padding:14px 20px;margin:20px 28px 0;border-radius:6px;font-family:'Trebuchet MS',sans-serif;font-size:13px;">
          <strong>Please fix the following before submitting:</strong>
          <ul style="margin:8px 0 0;padding-left:18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="letterhead">
        <div class="crest">TESDA</div>
        <div class="agency">
          <div class="en">Technical Education and Skills Development Authority
          </div>
          <div class="fil">Pangasiwaan sa Edukasyong Teknikal at Pagpapaunlad
            ng Kaanyuan</div>
          </div>
        </div>

        <div class="titlebar">
          <h1>Registration Form</h1>
          <div style="display:flex; align-items:center; gap:14px;">
            <span class="step-indicator">Step 2 of 2</span>
            <div class="formcode">MIS 03&ndash;01<br>(rev. 2020)</div>
          </div>
        </div>

        <!-- 4. Classification -->
        <div class="section">
          <div class="section-head">
            <span class="num">4</span>
            <h2>Learner / Trainee / Student (Clients) Classification <span class="optional">(optional)</span></h2>
          </div>
          @php
          $previousClassifications = $previousRegistration?->classification ?? [];

          if (is_string($previousClassifications)) {
              $previousClassifications = json_decode($previousClassifications, true) ?? [];
          }

          $classifications = [
          @php
            $classifications = [
            '4Ps Beneficiary',
            'Agrarian Reform Beneficiary',
            'Balik Probinsya',
            'Displaced Workers',
            'Drug Dependents Surrenderees/Surrenderers',
            'Family Members of AFP and PNP Wounded-in-Action',
            'Family Members of AFP and PNP Killed-in-Action',
            'Farmers and Fishermen',
            'Indigenous People & Cultural Communities',
            'Industry Workers',
            'Inmates and Detainees',
            'MILF Beneficiary',
            'Out-of-School Youth',
            'Overseas Filipino Workers (OFW) Dependents',
            'RCEF-RESP',
            'Rebel Returnees/Decommissioned Combatants',
            'Returning/Repatriated OFW',
            'Student',
            'TESDA Alumni',
            'TVET Trainers',
            'Uniformed Personnel',
            'Victim of Natural Disasters and Calamities',
            'Wounded-in-Action AFP & PNP Personnel',
            ];
          @endphp
          <div class="check-grid">
            @foreach ($classifications as $i => $c)
              <label class="choice">
                <input type="checkbox" name="classification[]"
                value="{{ $c }}"
               @if (in_array($c, old('classification', $previousClassifications))) checked @endif>
                  {{ $c }}
                </label>
              @endforeach
              <label class="choice">
                <input type="checkbox" name="classification[]" value="Others"
                    id="classOthersCheck"
                    @if (in_array('Others', old('classification', $previousClassifications))) checked @endif>
                Others (please specify):
                <input type="text" name="classification_other" class="inline-input"
                value="{{ old('classification_other', $previousRegistration?->classification_other ?? '') }}">
              </label>
            </div>
          </div>

          <!-- 5. Type of Disability -->
          <div class="section">
            <div class="section-head">
              <span class="num">5</span>
              <h2>Type of Disability <span class="optional">(optional)</span> <span class="hint">(for Persons with Disability
              only — to be filled up by TESDA personnel)</span></h2>
            </div>
            @php
              $disabilityTypes = [
              'Mental/Intellectual Disability',
              'Hearing Disability',
              'Psychosocial Disability',
              'Visual Disability',
              'Speech Impairment',
              'Disability Due to Chronic Illness',
              'Learning Disability',
              'Orthopedic (Musculoskeletal) Disability',
              ];
            @endphp
            <div class="check-grid">
              @foreach ($disabilityTypes as $d)
                <label class="choice">
                  <input type="checkbox" name="disability_type[]"
                  value="{{ $d }}"
                  @if (is_array(old('disability_type')) && in_array($d, old('disability_type'))) checked @endif>
                    {{ $d }}
                  </label>
                @endforeach
                <label class="choice">
                  Multiple Disabilities, specify:
                  <input type="text" name="disability_multiple_specify"
                  class="inline-input"
                  value="{{ old('disability_multiple_specify') }}">
                </label>
              </div>
            </div>

            <!-- 6. Causes of Disability -->
            <div class="section">
              <div class="section-head">
                <span class="num">6</span>
                <h2>Causes of Disability <span class="optional">(optional)</span> <span class="hint">(for Persons with
                Disability only — to be filled up by TESDA personnel)</span></h2>
              </div>
              <div class="choice-grid">
                <label class="choice"><input type="radio" name="disability_cause"
                  value="Congenital/Inborn"
                  @if (old('disability_cause') == 'Congenital/Inborn') checked @endif>
                    Congenital/Inborn</label>
                    <label class="choice"><input type="radio" name="disability_cause"
                      value="Illness" @if (old('disability_cause') == 'Illness') checked @endif>
                      Illness</label>
                      <label class="choice"><input type="radio" name="disability_cause"
                        value="Injury" @if (old('disability_cause') == 'Injury') checked @endif>
                        Injury</label>
                        <label class="choice">
                          Others, please specify:
                          <input type="text" name="disability_cause_other"
                          class="inline-input" value="{{ old('disability_cause_other') }}">
                        </label>
                      </div>
                    </div>

                    <!-- 7. Course -->
                    <div class="section">
                      <div class="section-head">
                        <span class="num">7</span>
                        <h2>Name of Course / Qualification <span class="req">*</span></h2>
                      </div>
                      <div class="row">
                        <div class="field wide"><input type="text" name="course_name"
                         value="{{ old('course_name', $previousRegistration?->course_name ?? '') }}" required></div>
                        </div>
                      </div>

                      <!-- 8. Scholarship -->
                      <div class="section">
                        <div class="section-head">
                          <span class="num">8</span>
                          <h2>If Scholar, What Type of Scholarship Package? <span class="optional">(optional)</span></h2>
                        </div>
                        <div class="row">
                          <div class="field wide">
                            <label>TWSP, PESFA, STEP, others</label>
                            <input type="text" name="scholarship_package"
                            value="{{ old('scholarship_package', $previousRegistration?->scholarship_package ?? '') }}">
                          </div>
                        </div>
                      </div>

                      <!-- 9. Privacy Disclaimer -->
                      <div class="section">
                        <div class="section-head">
                          <span class="num">9</span>
                          <h2>Privacy Disclaimer <span class="req">*</span></h2>
                        </div>
                        <div class="disclaimer-box">
                          I hereby allow TESDA to use/post my contact details, name, email,
                          cellphone/landline nos.
                          and other information I provided which may be used for processing of my
                          scholarship
                          application, for employment opportunities and for the survey of TESDA
                          programs.
                        </div>
                        <div class="choice-grid">
                          <label class="choice"><input type="radio" name="privacy_consent"
                            value="Agree" required @if (old('privacy_consent') == 'Agree') checked @endif>
                            Agree</label>
                            <label class="choice"><input type="radio" name="privacy_consent"
                              value="Disagree" @if (old('privacy_consent') == 'Disagree') checked @endif>
                              Disagree</label>
                            </div>
                          </div>

                          <!-- 10. Signature -->
                          <div class="section">
                            <div class="section-head">
                              <span class="num">10</span>
                              <h2>Applicant's Signature <span class="req">*</span></h2>
                            </div>
                            <p class="certify-note">This is to certify that the information stated
                              above is true and correct.</p>

                              <div class="sig-grid" style="margin-top:18px;">
                                <div class="sig-block">
                                  <div class="sig-pad-wrap">
                                    <canvas id="signaturePad" width="320" height="110"></canvas>
                                    <div class="sig-actions">
                                      <button type="button" class="sig-clear-btn" id="clearSigBtn">Clear</button>
                                      <span class="sig-status" id="sigStatus">Not signed yet</span>
                                    </div>
                                  </div>
                                  <input type="hidden" name="signature_data" id="signatureData">
                                  <div class="sig-caption">Applicant's Signature Over Printed Name</div>
                                </div>
                                <div class="sig-block">
                                  <label>Date Accomplished <span class="req">*</span></label>
                                  <input type="date" name="date_accomplished"
                                  value="{{ old('date_accomplished') }}" required>
                                </div>
                              </div>

                              <div class="sig-grid" style="margin-top:22px;">
                                <div class="sig-block">
                                  <div class="sig-line" style="border-bottom:1px solid var(--ink); height:36px;"></div>
                                  <div class="sig-caption">Noted by: Registrar / School
                                    Administrator<br>(Signature Over Printed Name)</div>
                                  </div>
                                  <div class="sig-block">
                                    <label>Date Received <span class="optional">(optional)</span></label>
                                    <input type="date" name="date_received"
                                    value="{{ old('date_received') }}">
                                  </div>
                                </div>

                                <div class="photo-thumb-row">
                                  <label class="photo-thumb" for="idPhoto1x1">
                                    <span id="photoText1x1">1x1 picture<br>taken within<br>the last 6
                                      months<br><span class="req" style="font-size:11px;">*</span></span>
                                      <img id="photoPreview1x1" style="display:none;">
                                      <input type="file" id="idPhoto1x1" name="photo_1x1"
                                      accept="image/*" required>
                                    </label>
                                    {{-- Thumbmark capture is not yet implemented — placeholder left as-is. --}}
                                    <div class="photo-thumb" style="border-style:solid; cursor:default;">
                                      Right Thumbmark
                                    </div>
                                  </div>
                                </div>

                                <div class="footer-note">
                                  <span><span class="req">*</span> Required fields. Please review all entries before submitting. This completes the
                                  TESDA Registration Form (MIS 03-01).</span>
                                </div>
                                <div class="footer-note" style="padding-top:0;">
                                  <div class="btn-row">
                                    <a href="{{ route('registration.step1') }}"
                                      class="btn btn-outline">&larr; Back</a>
                                      <button type="submit" class="btn">Submit Registration</button>
                                    </div>
                                  </div>

                                </form>

                                <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.7/signature_pad.umd.min.js"></script>
                                <script>
                                  // ── 1x1 photo preview ─────────────────────────────────────────────────
                                  const photoInput = document.getElementById('idPhoto1x1');
                                  const photoPreview = document.getElementById('photoPreview1x1');
                                  const photoText = document.getElementById('photoText1x1');
                                  photoInput.addEventListener('change', (e) => {
                                  const file = e.target.files[0];
                                  if (!file) return;
                                  const reader = new FileReader();
                                  reader.onload = (ev) => {
                                  photoPreview.src = ev.target.result;
                                  photoPreview.style.display = 'block';
                                  photoText.style.display = 'none';
                                  };
                                  reader.readAsDataURL(file);
                                  });

                                  // ── Signature pad ────────────────────────────────────────────────────
                                  const sigCanvas = document.getElementById('signaturePad');
                                  const signaturePad = new SignaturePad(sigCanvas, {
                                  backgroundColor: 'rgba(255,255,255,1)',
                                  penColor: '#1c2420',
                                  });
                                  const sigStatus = document.getElementById('sigStatus');
                                  const sigDataInput = document.getElementById('signatureData');

                                  // Keep the canvas crisp on high-DPI screens and resize responsively.
                                  function resizeCanvas() {
                                  const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                  const rect = sigCanvas.getBoundingClientRect();
                                  sigCanvas.width = rect.width * ratio;
                                  sigCanvas.height = rect.height * ratio;
                                  sigCanvas.getContext('2d').scale(ratio, ratio);
                                  signaturePad.clear();
                                  }
                                  window.addEventListener('resize', resizeCanvas);
                                  resizeCanvas();

                                  signaturePad.addEventListener('endStroke', () => {
                                  if (!signaturePad.isEmpty()) {
                                  sigStatus.textContent = 'Signature captured';
                                  sigStatus.classList.add('filled');
                                  }
                                  });

                                  document.getElementById('clearSigBtn').addEventListener('click', () => {
                                  signaturePad.clear();
                                  sigStatus.textContent = 'Not signed yet';
                                  sigStatus.classList.remove('filled');
                                  sigDataInput.value = '';
                                  });

                                  // ── Submit: validate required fields + signature, then encode signature ─
                                  const form = document.getElementById('learnerForm2');
                                  form.addEventListener('submit', (e) => {
                                  let blocked = false;

                                  if (!form.checkValidity()) {
                                  blocked = true;
                                  }

                                  // The 1x1 photo input is visually hidden (display:none) behind the styled
                                  // label, so the browser can't show its native validation bubble on it.
                                  // Check it manually so submission doesn't fail silently.
                                  if (!photoInput.files || photoInput.files.length === 0) {
                                  blocked = true;
                                  alert('Please upload a 1x1 picture before submitting.');
                                  document.getElementById('idPhoto1x1').closest('.photo-thumb').scrollIntoView({ behavior: 'smooth', block: 'center' });
                                  }

                                  if (signaturePad.isEmpty()) {
                                  blocked = true;
                                  alert('Please sign in the signature box before submitting.');
                                  } else {
                                  sigDataInput.value = signaturePad.toDataURL('image/png');
                                  }

                                  if (blocked) {
                                  e.preventDefault();
                                  form.reportValidity();
                                  }
                                  });
                                </script>

                              </body>

                            </html>
