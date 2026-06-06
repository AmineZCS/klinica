@php
    $i18n = [
        'enterClinicName'      => __('registration.js_enter_clinic_name'),
        'enterSubdomain'       => __('registration.js_enter_subdomain'),
        'subdomainFormat'      => __('registration.js_subdomain_format'),
        'subdomainUnavailable' => __('registration.js_subdomain_unavailable'),
        'enterName'            => __('registration.js_enter_name'),
        'enterEmail'           => __('registration.js_enter_email'),
        'invalidEmail'         => __('registration.js_invalid_email'),
        'passwordLength'       => __('registration.js_password_length'),
        'passwordMismatch'     => __('registration.js_password_mismatch'),
        'subdomainChecking'    => __('registration.js_subdomain_checking'),
        'subdomainAvailable'   => __('registration.js_subdomain_available'),
        'subdomainReserved'    => __('registration.js_subdomain_reserved'),
        'subdomainInvalid'     => __('registration.js_subdomain_invalid'),
        'subdomainTaken'       => __('registration.js_subdomain_taken'),
        'previewEmpty'         => __('registration.booking_url_preview_empty'),
        'previewUrl'           => __('registration.booking_url_preview'),
        'reviewNotSet'         => __('registration.review_not_set'),
        'reviewPlanStarter'    => __('registration.review_plan_starter'),
        'reviewPlanPro'        => __('registration.review_plan_pro'),
        'creatingClinic'       => __('registration.creating_clinic'),
        'consentError'         => __('registration.consent_error'),
        'passwordShow'         => __('registration.password_show'),
        'passwordHide'         => __('registration.password_hide'),
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('registration.title') }}</title>
    <meta name="description" content="{{ __('registration.meta_description') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal:       #2F8082;
            --teal-dark:  #236163;
            --sage:       #8DAA91;
            --ocean:      #56B2B4;
            --cream:      #F3E9DC;
            --sand:       #E7D7C1;
            --off-white:  #FAF8F5;
            --charcoal:   #36393F;
            --slate:      #515A63;
            --dark-navy:  #2C3A47;
            --white:      #FFFFFF;
            --error:      #CC4E4E;
            --error-bg:   #FEF2F2;
            --error-border:#FECACA;
        }

        html, body {
            min-height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--off-white);
            color: var(--charcoal);
            line-height: 1.6;
        }

        /* ── NAV ── */
        .nav {
            background: var(--white);
            border-bottom: 1px solid var(--sand);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-logo {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 22px; font-weight: 700;
            color: var(--teal); text-decoration: none; letter-spacing: -0.5px;
        }
        .nav-logo span { color: var(--charcoal); }
        .nav-login {
            font-size: 14px; color: var(--slate); text-decoration: none;
            display: flex; align-items: center; gap: 6px;
        }
        .nav-login:hover { color: var(--teal); }

        /* ── HERO STRIP ── */
        .hero-strip {
            background: var(--teal);
            padding: 52px 40px 48px;
            text-align: center;
        }
        .hero-strip-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 500;
            padding: 5px 14px; border-radius: 50px; margin-bottom: 20px;
        }
        .hero-strip h1 {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: clamp(30px, 5vw, 46px); font-weight: 700;
            color: var(--white); letter-spacing: -1px; margin-bottom: 12px;
            line-height: 1.1;
        }
        .hero-strip p {
            font-size: 17px; color: rgba(255,255,255,0.8);
            font-weight: 400; max-width: 460px; margin: 0 auto;
        }

        /* ── PROGRESS STEPPER ── */
        .stepper-wrap {
            background: var(--white);
            border-bottom: 1px solid var(--sand);
            padding: 0 40px;
        }
        .stepper {
            max-width: 680px; margin: 0 auto;
            display: flex; align-items: center;
            padding: 20px 0;
        }
        .step-item {
            display: flex; align-items: center; gap: 10px;
            flex: 1;
        }
        .step-item:last-child { flex: 0; }
        .step-circle {
            width: 32px; height: 32px; border-radius: 50%;
            border: 2px solid var(--sand);
            background: var(--white); color: var(--slate);
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: all 0.2s;
        }
        .step-circle.active {
            background: var(--teal); border-color: var(--teal); color: var(--white);
        }
        .step-circle.done {
            background: var(--sage); border-color: var(--sage); color: var(--white);
            font-size: 16px;
        }
        .step-label {
            font-size: 13px; font-weight: 500; color: var(--slate);
            white-space: nowrap;
        }
        .step-label.active { color: var(--teal); font-weight: 600; }
        .step-label.done { color: var(--sage); }
        .step-connector {
            flex: 1; height: 1px; background: var(--sand); margin: 0 12px;
            transition: background 0.3s;
        }
        .step-connector.done { background: var(--sage); }
        @media (max-width: 500px) { .step-label { display: none; } }

        /* ── MAIN CARD ── */
        .main-wrap {
            max-width: 680px; margin: 0 auto;
            padding: 40px 20px 80px;
        }
        .card {
            background: var(--white);
            border: 1px solid var(--sand);
            border-radius: 16px;
            padding: 40px 44px;
            box-shadow: 0 2px 16px rgba(44,58,71,0.07);
        }
        @media (max-width: 600px) { .card { padding: 28px 24px; } }

        /* ── STEP PANELS ── */
        .step-panel { display: none; }
        .step-panel.active { display: block; }

        .panel-heading {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 24px; font-weight: 700;
            color: var(--dark-navy); margin-bottom: 6px; letter-spacing: -0.5px;
        }
        .panel-sub {
            font-size: 15px; color: var(--slate);
            margin-bottom: 32px;
        }

        /* ── FORM ELEMENTS ── */
        .form-group { margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 500px) { .form-row { grid-template-columns: 1fr; } }

        .form-label {
            display: block; font-size: 14px; font-weight: 500;
            color: var(--charcoal); margin-bottom: 7px;
        }
        .form-label .required { color: var(--teal); margin-left: 2px; }

        .input-wrap { position: relative; }
        .form-input {
            width: 100%; height: 46px;
            padding: 0 14px;
            background: var(--off-white);
            border: 1.5px solid var(--sand);
            border-radius: 10px;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 15px; font-weight: 400;
            color: var(--charcoal);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(47,128,130,0.1);
            background: var(--white);
        }
        .form-input.has-suffix { padding-right: 130px; }
        .form-input.is-error { border-color: var(--error); }
        .form-input::placeholder { color: var(--slate); opacity: 0.5; }

        .input-suffix {
            position: absolute; right: 0; top: 0; bottom: 0;
            display: flex; align-items: center;
            padding: 0 14px;
            background: var(--sand);
            border: 1.5px solid var(--sand);
            border-left: none;
            border-radius: 0 10px 10px 0;
            font-size: 13px; font-weight: 500; color: var(--slate);
            pointer-events: none;
            white-space: nowrap;
        }

        .subdomain-preview {
            margin-top: 8px;
            font-size: 13px; color: var(--slate);
            min-height: 18px;
        }
        .subdomain-preview strong {
            color: var(--teal); font-weight: 600;
        }
        .subdomain-status {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 12px; font-weight: 500; margin-top: 5px;
        }
        .subdomain-status.checking { color: var(--slate); }
        .subdomain-status.available { color: #16a34a; }
        .subdomain-status.unavailable { color: var(--error); }

        .field-hint {
            font-size: 12px; color: var(--slate);
            margin-top: 6px; opacity: 0.8;
        }

        .password-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--slate); font-size: 18px; padding: 0;
            line-height: 1;
        }
        .form-input.has-toggle { padding-right: 46px; }

        /* ── ERROR / ALERT ── */
        .alert-errors {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #991B1B;
        }
        .alert-errors ul { padding-left: 18px; }
        .alert-errors li { margin-bottom: 4px; }
        .field-error { font-size: 12px; color: var(--error); margin-top: 5px; }

        /* ── BUTTONS ── */
        .btn-row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-top: 32px; gap: 12px;
        }
        .btn-primary {
            background: var(--teal); color: var(--white);
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 16px; font-weight: 600;
            padding: 13px 32px 10px; border-radius: 10px;
            border: 2px solid var(--teal);
            cursor: pointer; text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { background: var(--teal-dark); border-color: var(--teal-dark); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-ghost {
            background: none; color: var(--slate);
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 15px; font-weight: 500;
            padding: 13px 0 10px; border: none;
            cursor: pointer; transition: color 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { color: var(--charcoal); }

        /* ── REVIEW SUMMARY ── */
        .review-grid {
            border: 1px solid var(--sand);
            border-radius: 12px; overflow: hidden;
            margin-bottom: 24px;
        }
        .review-row {
            display: flex; align-items: center;
            padding: 14px 20px; gap: 16px;
            border-bottom: 1px solid var(--sand);
        }
        .review-row:last-child { border-bottom: none; }
        .review-icon {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(47,128,130,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .review-icon svg {
            width: 18px; height: 18px;
            stroke: var(--teal); fill: none; stroke-width: 1.9;
            stroke-linecap: round; stroke-linejoin: round;
        }
        .review-label {
            font-size: 12px; font-weight: 600; color: var(--slate);
            text-transform: uppercase; letter-spacing: 0.8px;
            margin-bottom: 2px;
        }
        .review-value {
            font-size: 15px; font-weight: 500; color: var(--dark-navy);
        }
        .review-edit {
            margin-left: auto; font-size: 13px; color: var(--teal);
            cursor: pointer; border: none; background: none;
            font-weight: 500; white-space: nowrap;
        }
        .review-edit:hover { text-decoration: underline; }

        .trust-badges {
            display: flex; gap: 20px; flex-wrap: wrap;
            justify-content: center; margin-top: 28px;
        }
        .trust-badge {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: var(--slate);
        }
        .trust-badge-icon {
            width: 28px; height: 28px; border-radius: 50%;
            background: rgba(141,170,145,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .trust-badge-icon svg {
            width: 14px; height: 14px;
            stroke: var(--teal); fill: none; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round;
        }

        /* ── DIVIDER WITH TEXT ── */
        .or-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 28px 0;
        }
        .or-divider::before, .or-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--sand);
        }
        .or-divider span { font-size: 13px; color: var(--slate); white-space: nowrap; }

        /* ── FOOTER ── */
        .page-footer {
            background: var(--dark-navy);
            padding: 24px 40px;
            text-align: center;
            font-size: 13px; color: rgba(255,255,255,0.35);
        }
        .page-footer a { color: rgba(255,255,255,0.5); text-decoration: none; }
        .page-footer a:hover { color: rgba(255,255,255,0.8); }

        /* ── CONSENT CHECKBOX ── */
        .consent-wrap {
            background: rgba(47,128,130,0.06);
            border: 1px solid rgba(47,128,130,0.2);
            border-radius: 10px;
            padding: 16px 18px;
            margin: 20px 0 4px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .consent-wrap input[type="checkbox"] {
            width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px;
            accent-color: var(--teal); cursor: pointer;
        }
        .consent-label {
            font-size: 13px; color: var(--slate); line-height: 1.6;
        }
        .consent-label a {
            color: var(--teal); font-weight: 600; text-decoration: none;
        }
        .consent-label a:hover { text-decoration: underline; }
        .consent-error {
            font-size: 12px; color: var(--error);
            margin-top: 6px; display: none;
        }

        /* ── STEP 3 SUCCESS STATE ── */
        .success-state {
            text-align: center; padding: 20px 0;
        }
        .success-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(141,170,145,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; margin: 0 auto 20px;
        }
    </style>
</head>
<body>

    {{-- ── NAV ── --}}
    <nav class="nav">
        <a href="{{ url('/') }}" class="nav-logo">Klinica<span>.</span></a>
        <a href="#" class="nav-login">{{ __('registration.already_have_account') }} <strong>{{ __('registration.sign_in') }}</strong></a>
    </nav>

    {{-- ── HERO STRIP ── --}}
    <div class="hero-strip">
        <div class="hero-strip-badge">
            {{ __('registration.free_trial_badge') }}
        </div>
        <h1>{{ __('registration.hero_title') }}</h1>
        <p>{{ __('registration.hero_subtitle') }}</p>
    </div>

    {{-- ── STEPPER ── --}}
    <div class="stepper-wrap">
        <div class="stepper" id="stepperBar">
            <div class="step-item">
                <div class="step-circle active" id="sc1">1</div>
                <span class="step-label active" id="sl1">{{ __('registration.step_plan') }}</span>
            </div>
            <div class="step-connector" id="conn1"></div>
            <div class="step-item">
                <div class="step-circle" id="sc2">2</div>
                <span class="step-label" id="sl2">{{ __('registration.step_clinic') }}</span>
            </div>
            <div class="step-connector" id="conn2"></div>
            <div class="step-item">
                <div class="step-circle" id="sc3">3</div>
                <span class="step-label" id="sl3">{{ __('registration.step_account') }}</span>
            </div>
            <div class="step-connector" id="conn3"></div>
            <div class="step-item">
                <div class="step-circle" id="sc4">4</div>
                <span class="step-label" id="sl4">{{ __('registration.step_confirm') }}</span>
            </div>
        </div>
    </div>

    <div class="main-wrap">

        {{-- Server-side validation errors shown on step 1 if redirected back --}}
        @if ($errors->any())
            <div class="alert-errors" style="margin-bottom:24px;">
                <strong>{{ __('registration.errors_heading') }}</strong>
                <ul style="margin-top:8px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('register.store') }}" id="regForm" novalidate>
                @csrf
                <input type="hidden" name="plan" id="selectedPlan" value="starter">

                {{-- ═══════ STEP 1: PLAN ═══════ --}}
                <div class="step-panel active" id="panel1">
                    <div class="panel-heading">{{ __('registration.plan_heading') }}</div>
                    <p class="panel-sub">{{ __('registration.plan_sub') }}</p>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:24px 0" id="planCards">
                        <label class="plan-card selected" id="card-starter" style="cursor:pointer;border:2px solid var(--teal);border-radius:12px;padding:20px;background:#f0fafb;display:block">
                            <input type="radio" name="_plan_radio" value="starter" style="display:none" checked>
                            <div style="font-weight:700;font-size:16px;color:var(--teal)">{{ __('registration.starter_label') }}</div>
                            <div style="font-size:22px;font-weight:800;margin:4px 0"><sup style="font-size:14px">€</sup>29<span style="font-size:12px;font-weight:400;color:#888">{{ __('registration.starter_price_suffix') }}</span></div>
                            <ul style="margin:12px 0 0;padding:0;list-style:none;font-size:13px;color:#555;line-height:1.8">
                                <li>✓ {{ __('registration.starter_feature_1') }}</li>
                                <li>✓ {{ __('registration.starter_feature_2') }}</li>
                                <li>✓ {{ __('registration.starter_feature_3') }}</li>
                                <li>✓ {{ __('registration.starter_feature_4') }}</li>
                                <li>✓ {{ __('registration.starter_feature_5') }}</li>
                            </ul>
                        </label>
                        <label class="plan-card" id="card-pro" style="cursor:pointer;border:2px solid #e2e8f0;border-radius:12px;padding:20px;background:#fff;display:block">
                            <input type="radio" name="_plan_radio" value="pro" style="display:none">
                            <div style="font-weight:700;font-size:16px;color:#2F8082">{{ __('registration.pro_label') }}</div>
                            <div style="font-size:22px;font-weight:800;margin:4px 0"><sup style="font-size:14px">€</sup>49<span style="font-size:12px;font-weight:400;color:#888">{{ __('registration.pro_price_suffix') }}</span></div>
                            <ul style="margin:12px 0 0;padding:0;list-style:none;font-size:13px;color:#555;line-height:1.8">
                                <li>✓ {{ __('registration.pro_feature_1') }}</li>
                                <li>✓ {{ __('registration.pro_feature_2') }}</li>
                                <li>✓ {{ __('registration.pro_feature_3') }}</li>
                                <li>✓ {{ __('registration.pro_feature_4') }}</li>
                                <li>✓ {{ __('registration.pro_feature_5') }}</li>
                            </ul>
                        </label>
                    </div>

                    <div class="btn-row" style="justify-content:flex-end">
                        <button type="button" class="btn-primary" onclick="goToStep(2)">{{ __('registration.continue') }}</button>
                    </div>
                </div>

                {{-- ═══════ STEP 2: CLINIC ═══════ --}}
                <div class="step-panel" id="panel2">
                    <div class="panel-heading">{{ __('registration.clinic_heading') }}</div>
                    <p class="panel-sub">{{ __('registration.clinic_sub') }}</p>

                    <div class="form-group">
                        <label class="form-label" for="clinic_name">{{ __('registration.clinic_name_label') }} <span class="required">*</span></label>
                        <input
                            class="form-input {{ $errors->has('clinic_name') ? 'is-error' : '' }}"
                            id="clinic_name" type="text" name="clinic_name"
                            value="{{ old('clinic_name') }}"
                            placeholder="{{ __('registration.clinic_name_placeholder') }}"
                            autocomplete="organization"
                        >
                        @error('clinic_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subdomain">{{ __('registration.booking_url_label') }} <span class="required">*</span></label>
                        <div class="input-wrap">
                            <input
                                class="form-input has-suffix {{ $errors->has('subdomain') ? 'is-error' : '' }}"
                                id="subdomain" type="text" name="subdomain"
                                value="{{ old('subdomain') }}"
                                placeholder="{{ __('registration.booking_url_placeholder') }}"
                                autocomplete="off"
                                inputmode="url"
                            >
                            <span class="input-suffix">.klinica.space</span>
                        </div>
                        <div class="subdomain-preview" id="subdomainPreview">
                            {{ __('registration.booking_url_preview_empty') }}
                        </div>
                        <div id="subdomainStatus"></div>
                        @error('subdomain')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                        <div class="field-hint">{{ __('registration.subdomain_hint') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="locale">{{ __('registration.locale_label') }} <span class="required">*</span></label>
                        <select
                            class="form-input {{ $errors->has('locale') ? 'is-error' : '' }}"
                            id="locale" name="locale"
                            style="appearance:auto;"
                        >
                            <option value="en" {{ old('locale', app()->getLocale()) === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            <option value="fr" {{ old('locale', app()->getLocale()) === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                            <option value="de" {{ old('locale', app()->getLocale()) === 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                            <option value="es" {{ old('locale', app()->getLocale()) === 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
                        </select>
                        <div class="field-hint">{{ __('registration.locale_hint') }}</div>
                        @error('locale')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-row" style="justify-content:flex-end;">
                        <button type="button" class="btn-ghost" onclick="goToStep(1)">{{ __('registration.back') }}</button>
                        <button type="button" class="btn-primary" onclick="goToStep(3)">
                            {{ __('registration.continue') }}
                        </button>
                    </div>
                </div>

                {{-- ═══════ STEP 3: ACCOUNT ═══════ --}}
                <div class="step-panel" id="panel3">
                    <div class="panel-heading">{{ __('registration.account_heading') }}</div>
                    <p class="panel-sub">{{ __('registration.account_sub') }}</p>

                    <div class="form-group">
                        <label class="form-label" for="name">{{ __('registration.full_name_label') }} <span class="required">*</span></label>
                        <input
                            class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                            id="name" type="text" name="name"
                            value="{{ old('name') }}"
                            placeholder="{{ __('registration.full_name_placeholder') }}"
                            autocomplete="name"
                        >
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('registration.email_label') }} <span class="required">*</span></label>
                        <input
                            class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            id="email" type="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="{{ __('registration.email_placeholder') }}"
                            autocomplete="email"
                        >
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="password">{{ __('registration.password_label') }} <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input
                                    class="form-input has-toggle {{ $errors->has('password') ? 'is-error' : '' }}"
                                    id="password" type="password" name="password"
                                    placeholder="{{ __('registration.password_placeholder') }}"
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePwd('password', this)" aria-label="Toggle password visibility">{{ __('registration.password_show') }}</button>
                            </div>
                            @error('password')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="password_confirmation">{{ __('registration.confirm_password_label') }} <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input
                                    class="form-input has-toggle"
                                    id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="{{ __('registration.confirm_password_placeholder') }}"
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePwd('password_confirmation', this)" aria-label="Toggle password visibility">{{ __('registration.password_show') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="btn-row">
                        <button type="button" class="btn-ghost" onclick="goToStep(2)">{{ __('registration.back') }}</button>
                        <button type="button" class="btn-primary" onclick="goToStep(4)">
                            {{ __('registration.review') }}
                        </button>
                    </div>
                </div>

                {{-- ═══════ STEP 4: CONFIRM ═══════ --}}
                <div class="step-panel" id="panel4">
                    <div class="panel-heading">{{ __('registration.review_heading') }}</div>
                    <p class="panel-sub">{{ __('registration.review_sub') }}</p>

                    <div class="review-grid">
                        <div class="review-row">
                            <div class="review-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="2.5" y="5" width="19" height="14" rx="2"/><path d="M2.5 10h19"/></svg>
                            </div>
                            <div>
                                <div class="review-label">{{ __('registration.review_plan_label') }}</div>
                                <div class="review-value" id="review-plan">{{ __('registration.review_not_set') }}</div>
                            </div>
                            <button type="button" class="review-edit" onclick="goToStep(1)">{{ __('registration.review_edit') }}</button>
                        </div>
                        <div class="review-row">
                            <div class="review-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V8l7-5 7 5v13"/><path d="M9 21v-6h6v6"/></svg>
                            </div>
                            <div>
                                <div class="review-label">{{ __('registration.review_clinic_label') }}</div>
                                <div class="review-value" id="review-clinic-name">{{ __('registration.review_not_set') }}</div>
                            </div>
                            <button type="button" class="review-edit" onclick="goToStep(2)">{{ __('registration.review_edit') }}</button>
                        </div>
                        <div class="review-row">
                            <div class="review-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M10 14a5 5 0 007.07 0l2.83-2.83a5 5 0 00-7.07-7.07L10 6"/><path d="M14 10a5 5 0 00-7.07 0L4.1 12.83a5 5 0 007.07 7.07L14 18"/></svg>
                            </div>
                            <div>
                                <div class="review-label">{{ __('registration.review_url_label') }}</div>
                                <div class="review-value" id="review-url">{{ __('registration.review_not_set') }}</div>
                            </div>
                            <button type="button" class="review-edit" onclick="goToStep(2)">{{ __('registration.review_edit') }}</button>
                        </div>
                        <div class="review-row">
                            <div class="review-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/></svg>
                            </div>
                            <div>
                                <div class="review-label">{{ __('registration.review_name_label') }}</div>
                                <div class="review-value" id="review-name">{{ __('registration.review_not_set') }}</div>
                            </div>
                            <button type="button" class="review-edit" onclick="goToStep(3)">{{ __('registration.review_edit') }}</button>
                        </div>
                        <div class="review-row">
                            <div class="review-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                            </div>
                            <div>
                                <div class="review-label">{{ __('registration.review_email_label') }}</div>
                                <div class="review-value" id="review-email">{{ __('registration.review_not_set') }}</div>
                            </div>
                            <button type="button" class="review-edit" onclick="goToStep(3)">{{ __('registration.review_edit') }}</button>
                        </div>
                    </div>

                    <div class="trust-badges">
                        <div class="trust-badge">
                            <div class="trust-badge-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M12 3l7 3v6c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>
                            </div>
                            {{ __('registration.badge_encrypted') }}
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                            </div>
                            {{ __('registration.badge_live') }}
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M8 4h8"/><path d="M8 4v4l4 3 4-3V4"/><rect x="3" y="8" width="18" height="12" rx="2"/></svg>
                            </div>
                            {{ __('registration.badge_trial') }}
                        </div>
                    </div>

                    <div class="consent-wrap">
                        <input
                            type="checkbox"
                            id="terms_accepted"
                            name="terms_accepted"
                            value="1"
                            {{ old('terms_accepted') ? 'checked' : '' }}
                        >
                        <div>
                            <label class="consent-label" for="terms_accepted">
                                {{ __('registration.consent_part1') }}
                                <a href="{{ route('terms') }}" target="_blank">{{ __('registration.consent_terms') }}</a>{{ __('registration.consent_comma') }}
                                <a href="{{ route('privacy') }}" target="_blank">{{ __('registration.consent_privacy') }}</a>,
                                {{ __('registration.consent_and') }} <a href="{{ route('dpa') }}" target="_blank">{{ __('registration.consent_dpa') }}</a>.
                                {{ __('registration.consent_part2') }}
                            </label>
                            @error('terms_accepted')
                                <p style="font-size:12px;color:var(--error);margin-top:6px">{{ $message }}</p>
                            @enderror
                            <p class="consent-error" id="consentError">{{ __('registration.consent_error') }}</p>
                        </div>
                    </div>

                    <div class="btn-row">
                        <button type="button" class="btn-ghost" onclick="goToStep(3)">{{ __('registration.back') }}</button>
                        <button type="submit" class="btn-primary" id="submitBtn">
                            {{ __('registration.create_clinic') }}
                        </button>
                    </div>
                    <p style="text-align:center;font-size:12px;color:#888;margin-top:16px">{{ __('registration.disclaimer') }}</p>
                </div>

            </form>
        </div>
    </div>

    <footer class="page-footer">
        &copy; {{ date('Y') }} Klinica &mdash;
        <a href="{{ route('terms') }}">{{ __('registration.footer_terms') }}</a> &middot;
        <a href="{{ route('privacy') }}">{{ __('registration.footer_privacy') }}</a> &middot;
        <a href="{{ route('dpa') }}">{{ __('registration.footer_dpa') }}</a> &middot;
        <a href="{{ route('refund-policy') }}">{{ __('registration.footer_refund') }}</a>
    </footer>

    <script>
        const i18n = @json($i18n);

        let currentStep = 1;

        // If server returned validation errors, show the correct step
        @if ($errors->hasAny(['clinic_name', 'subdomain']))
            currentStep = 2;
        @elseif ($errors->hasAny(['name', 'email', 'password']))
            currentStep = 3;
        @endif

        // Plan selection
        document.querySelectorAll('input[name="_plan_radio"]').forEach(radio => {
            radio.closest('label').addEventListener('click', function () {
                const val = this.querySelector('input').value;
                document.getElementById('selectedPlan').value = val;
                document.getElementById('card-starter').style.borderColor = val === 'starter' ? 'var(--teal)' : '#e2e8f0';
                document.getElementById('card-starter').style.background = val === 'starter' ? '#f0fafb' : '#fff';
                document.getElementById('card-pro').style.borderColor = val === 'pro' ? 'var(--teal)' : '#e2e8f0';
                document.getElementById('card-pro').style.background = val === 'pro' ? '#f0fafb' : '#fff';
            });
        });

        // Pre-select from URL param
        const urlPlan = new URLSearchParams(window.location.search).get('plan');
        if (urlPlan === 'pro') {
            document.getElementById('card-pro').click();
        }

        function updateStepper(step) {
            [1, 2, 3, 4].forEach(i => {
                const sc = document.getElementById('sc' + i);
                const sl = document.getElementById('sl' + i);
                sc.className = 'step-circle' + (i < step ? ' done' : i === step ? ' active' : '');
                sc.textContent = i < step ? '✓' : String(i);
                sl.className = 'step-label' + (i < step ? ' done' : i === step ? ' active' : '');
            });
            [1, 2, 3].forEach(i => {
                const conn = document.getElementById('conn' + i);
                conn.className = 'step-connector' + (i < step ? ' done' : '');
            });
        }

        function validateStep(step) {
            if (step === 1) {
                return !!document.getElementById('selectedPlan').value;
            }
            if (step === 2) {
                const clinic = document.getElementById('clinic_name').value.trim();
                const sub = document.getElementById('subdomain').value.trim();
                if (!clinic) { alert(i18n.enterClinicName); return false; }
                if (!sub)    { alert(i18n.enterSubdomain); return false; }
                if (!/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/.test(sub)) {
                    alert(i18n.subdomainFormat);
                    return false;
                }
                if (lastCheckedSubdomain === sub && ! subdomainAvailable) {
                    alert(i18n.subdomainUnavailable);
                    return false;
                }
                return true;
            }
            if (step === 3) {
                const name  = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const pwd   = document.getElementById('password').value;
                const conf  = document.getElementById('password_confirmation').value;
                if (!name)  { alert(i18n.enterName); return false; }
                if (!email) { alert(i18n.enterEmail); return false; }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert(i18n.invalidEmail);
                    return false;
                }
                if (pwd.length < 8) { alert(i18n.passwordLength); return false; }
                if (pwd !== conf)   { alert(i18n.passwordMismatch); return false; }
                return true;
            }
            return true;
        }

        function goToStep(step) {
            if (step > currentStep && !validateStep(currentStep)) return;

            // Update review panel on step 4
            if (step === 4) {
                const sub = document.getElementById('subdomain').value.trim();
                const plan = document.getElementById('selectedPlan').value;
                document.getElementById('review-plan').textContent =
                    plan === 'pro' ? i18n.reviewPlanPro : i18n.reviewPlanStarter;
                document.getElementById('review-clinic-name').textContent =
                    document.getElementById('clinic_name').value.trim() || i18n.reviewNotSet;
                document.getElementById('review-url').textContent =
                    sub ? sub + '.klinica.space' : i18n.reviewNotSet;
                document.getElementById('review-name').textContent =
                    document.getElementById('name').value.trim() || i18n.reviewNotSet;
                document.getElementById('review-email').textContent =
                    document.getElementById('email').value.trim() || i18n.reviewNotSet;
            }

            document.getElementById('panel' + currentStep).classList.remove('active');
            document.getElementById('panel' + step).classList.add('active');
            currentStep = step;
            updateStepper(step);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Live subdomain preview + async availability check
        let subdomainCheckTimer = null;
        let lastCheckedSubdomain = '';
        let subdomainAvailable = false;

        function setSubdomainStatus(state, message) {
            const el = document.getElementById('subdomainStatus');
            el.className = 'subdomain-status ' + state;
            el.textContent = message;
        }

        function checkSubdomainAvailability(val) {
            if (val.length < 2) {
                document.getElementById('subdomainStatus').textContent = '';
                return;
            }
            if (val === lastCheckedSubdomain) { return; }

            setSubdomainStatus('checking', i18n.subdomainChecking);

            fetch('{{ route('register.check-subdomain') }}?subdomain=' + encodeURIComponent(val))
                .then(r => r.json())
                .then(data => {
                    lastCheckedSubdomain = val;
                    subdomainAvailable = data.available;
                    if (data.available) {
                        setSubdomainStatus('available', i18n.subdomainAvailable);
                    } else if (data.reason === 'reserved') {
                        setSubdomainStatus('unavailable', i18n.subdomainReserved);
                    } else if (data.reason === 'invalid') {
                        setSubdomainStatus('unavailable', i18n.subdomainInvalid);
                    } else {
                        setSubdomainStatus('unavailable', i18n.subdomainTaken);
                    }
                })
                .catch(() => {
                    document.getElementById('subdomainStatus').textContent = '';
                });
        }

        document.getElementById('subdomain').addEventListener('input', function () {
            const val = this.value.trim().toLowerCase().replace(/[^a-z0-9-]/g, '');
            this.value = val;
            const preview = document.getElementById('subdomainPreview');
            if (val) {
                preview.innerHTML = i18n.previewUrl.replace(':url', '<strong>' + val + '.klinica.space</strong>');
            } else {
                preview.innerHTML = i18n.previewEmpty;
                document.getElementById('subdomainStatus').textContent = '';
                return;
            }

            subdomainAvailable = false;
            clearTimeout(subdomainCheckTimer);
            subdomainCheckTimer = setTimeout(() => checkSubdomainAvailability(val), 500);
        });

        document.getElementById('subdomain').addEventListener('blur', function () {
            const val = this.value.trim();
            if (val.length >= 2) {
                clearTimeout(subdomainCheckTimer);
                checkSubdomainAvailability(val);
            }
        });

        // Auto-suggest subdomain from clinic name
        document.getElementById('clinic_name').addEventListener('blur', function () {
            const subInput = document.getElementById('subdomain');
            if (!subInput.value && this.value) {
                const suggested = this.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .substring(0, 30);
                subInput.value = suggested;
                subInput.dispatchEvent(new Event('input'));
            }
        });

        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = i18n.passwordHide;
            } else {
                input.type = 'password';
                btn.textContent = i18n.passwordShow;
            }
        }

        // Validate consent checkbox before submit
        document.getElementById('regForm').addEventListener('submit', function (e) {
            const consent = document.getElementById('terms_accepted');
            const consentError = document.getElementById('consentError');
            if (!consent.checked) {
                e.preventDefault();
                consentError.style.display = 'block';
                consent.closest('.consent-wrap').style.borderColor = 'var(--error)';
                consent.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            consentError.style.display = 'none';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').textContent = i18n.creatingClinic;
        });

        // Clear consent error on check
        document.getElementById('terms_accepted').addEventListener('change', function () {
            if (this.checked) {
                document.getElementById('consentError').style.display = 'none';
                this.closest('.consent-wrap').style.borderColor = 'rgba(47,128,130,0.2)';
            }
        });

        // If there are server-side errors, jump to correct step
        @if ($errors->hasAny(['name', 'email', 'password']))
            goToStep(3);
        @elseif ($errors->hasAny(['clinic_name', 'subdomain']))
            goToStep(2);
        @elseif ($errors->hasAny(['plan']))
            goToStep(1);
        @elseif ($errors->hasAny(['terms_accepted']))
            goToStep(4);
        @endif
    </script>
</body>
</html>
