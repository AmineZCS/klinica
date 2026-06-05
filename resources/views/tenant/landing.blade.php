<!DOCTYPE html>
<html lang="{{ $settings->locale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon --}}
    @if ($settings->hasMedia('logo'))
        @php $faviconMedia = $settings->getFirstMedia('logo'); @endphp
        <link rel="icon" href="{{ klinica_asset($faviconMedia->id . '/' . $faviconMedia->file_name) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    {{-- SEO --}}
    @php $pageTitle = $settings->clinic_name ?: 'Clinic'; @endphp
    <title>{{ $pageTitle }} — Online Booking</title>
    <meta name="description" content="{{ $settings->tagline ?: 'Book your appointment at ' . $pageTitle . ' — easy, fast, and secure.' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $settings->tagline ?: 'Book your appointment online.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    @if ($settings->hasMedia('logo'))
        @php $ogLogoMedia = $settings->getFirstMedia('logo'); @endphp
        <meta property="og:image" content="{{ klinica_asset($ogLogoMedia->id . '/' . $ogLogoMedia->file_name) }}">
    @endif

    {{-- Schema.org structured data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "MedicalClinic",
        "name": "{{ addslashes($settings->clinic_name) }}",
        "description": "{{ addslashes($settings->tagline ?? '') }}",
        "url": "{{ url('/') }}",
        "telephone": "{{ addslashes($settings->contact_phone ?? collect($settings->contact_phones ?? [])->first()['number'] ?? '') }}",
        "email": "{{ addslashes($settings->contact_email ?? '') }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "{{ addslashes($settings->contact_address ?? '') }}"
        }
    }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   {{ $settings->primary_color   ?: '#2F8082' }};
            --secondary: {{ $settings->secondary_color ?: '#8DAA91' }};
            --yellow:    #F3E9DC;
            --terra-btn: {{ $settings->primary_color   ?: '#2F8082' }};
            --terra-soft:#F3E9DC;
            --taupe:     #515A63;
            --beige:     #E7D7C1;
            --cream:     #F3E9DC;
            --off-white: #FAF8F5;
            --navy:      #36393F;
            --white:     #FFFFFF;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--navy); background: var(--white); line-height: 1.6;
        }

        .serif { font-family: 'Manrope', system-ui, sans-serif; font-weight: 700; }

        .container { max-width: 1152px; margin: 0 auto; padding: 0 40px; }
        @media (max-width: 1023px) { .container { padding: 0 32px; } }
        @media (max-width: 767px)  { .container { padding: 0 20px; } }

        /* NAV */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--beige);
        }
        .nav-inner {
            max-width: 1152px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px; height: 68px;
        }
        @media (max-width: 767px) { .nav-inner { padding: 0 20px; } }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo-img { height: 40px; max-width: 180px; border-radius: 8px; object-fit: contain; }
        .nav-logo-placeholder {
            width: 40px; height: 40px; border-radius: 8px;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 18px; font-weight: 700; flex-shrink: 0;
        }
        .nav-name { font-family: 'Manrope', system-ui, sans-serif; font-size: 20px; font-weight: 700; color: var(--primary); }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { font-size: 16px; font-weight: 400; color: var(--navy); text-decoration: none; transition: color 0.2s; }
        .nav-links a:hover { color: var(--secondary); }
        .nav-book-btn {
            background: var(--terra-btn) !important; color: #fff !important;
            padding: 8px 22px 5px !important; border-radius: 10px !important;
            border: 2px solid var(--terra-btn) !important;
            transition: background 0.2s !important;
        }
        .nav-book-btn:hover { background: var(--secondary) !important; border-color: var(--secondary) !important; }
        @media (max-width: 767px) { .nav-links a:not(.nav-book-btn) { display: none; } }

        /* HERO */
        .hero {
            background: var(--primary); padding: 96px 40px 80px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 85% 30%, rgba(86,178,180,0.12) 0%, transparent 55%);
            pointer-events: none;
        }
        .hero-inner {
            max-width: 1152px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 72px; align-items: center;
        }
        @media (max-width: 1023px) { .hero-inner { grid-template-columns: 1fr; text-align: center; } }
        @media (max-width: 767px)  { .hero { padding: 60px 20px 48px; } }
        @media (max-width: 767px)  { .hero h1 { font-size: clamp(36px, 10vw, 44px); letter-spacing: -1px; } }
        @media (max-width: 767px)  { .hero-tagline { font-size: 16px; margin-bottom: 28px; } }

        .hero-eyebrow { font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px; }
        .hero h1 {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: clamp(44px, 7vw, 88px); font-weight: 700;
            color: var(--white); line-height: 1.05;
            letter-spacing: -2px; margin-bottom: 20px;
        }
        .hero h1 em { color: var(--yellow); font-style: normal; }
        .hero-tagline {
            font-size: clamp(18px, 2.5vw, 22px); font-weight: 400;
            color: rgba(255,255,255,0.8); margin-bottom: 40px; max-width: 480px;
        }
        @media (max-width: 1023px) { .hero-tagline { margin-left: auto; margin-right: auto; } }
        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }
        @media (max-width: 1023px) { .hero-actions { justify-content: center; } }

        .btn-primary {
            background: var(--terra-btn); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 17px; font-weight: 600;
            padding: 14px 32px 11px; border-radius: 10px;
            border: 2px solid var(--terra-btn);
            text-decoration: none; display: inline-block; transition: background 0.2s;
        }
        .btn-primary:hover { background: var(--secondary); border-color: var(--secondary); }
        .btn-ghost {
            color: rgba(255,255,255,0.8); font-size: 17px; font-weight: 400;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            padding: 14px 0 11px; transition: color 0.2s;
        }
        .btn-ghost:hover { color: #fff; }

        /* Hero decorative card */
        .hero-card {
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.13);
            border-radius: 16px; padding: 28px;
        }
        @media (max-width: 1023px) { .hero-card { display: none; } }
        .hero-card-label { font-size: 12px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
        .hc-service {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,0.08); border-radius: 10px;
            padding: 14px; margin-bottom: 12px;
        }
        .hc-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .hc-name { color: #fff; font-size: 15px; font-weight: 400; margin-bottom: 2px; }
        .hc-meta { color: rgba(255,255,255,0.5); font-size: 13px; }
        .hc-price { color: var(--yellow); font-size: 14px; font-weight: 400; margin-left: auto; }

        /* Quick booking bar */
        .quickbook {
            background: var(--cream);
            border-top: 1px solid var(--beige); border-bottom: 1px solid var(--beige);
            padding: 24px 40px;
        }
        .quickbook-inner {
            max-width: 1152px; margin: 0 auto;
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .quickbook-label { font-size: 15px; font-weight: 500; color: var(--navy); white-space: nowrap; margin-right: 4px; }
        /* Wrapper that holds the native input + custom icon overlay */
        .qb-field {
            position: relative; flex: 1; min-width: 160px; display: flex;
        }
        .qb-field--date { flex: 0 0 170px; min-width: 0; }
        /* The actual native input — reset all browser chrome */
        .qb-input {
            width: 100%; min-width: 0;
            background: #fff; color: var(--navy);
            font-family: 'Inter', system-ui, sans-serif; font-size: 15px; font-weight: 400;
            height: 46px; padding: 0 40px 0 14px;
            border: 1.5px solid rgba(44,58,71,0.18); border-radius: 10px;
            box-shadow: 0 1px 3px rgba(44,58,71,0.06);
            -webkit-appearance: none; -moz-appearance: none; appearance: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            cursor: pointer;
            /* Remove native date input extra controls */
            color-scheme: light;
        }
        .qb-input:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(47,128,130,0.14); }
        /* SVG icon overlay — pointer-events:none so the input stays clickable */
        .qb-icon {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            pointer-events: none; display: flex; align-items: center; color: #515A63;
        }
        /* Hide native date picker icon in WebKit */
        .qb-input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0; position: absolute; right: 0; top: 0; width: 40px; height: 100%; cursor: pointer; }
        .qb-input[type="date"]::-webkit-inner-spin-button,
        .qb-input[type="date"]::-webkit-clear-button { -webkit-appearance: none; display: none; }
        .quickbook-btn {
            background: var(--terra-btn); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 15px; font-weight: 600;
            height: 46px; padding: 0 24px; border-radius: 10px; border: none;
            cursor: pointer; white-space: nowrap; transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 6px rgba(44,58,71,0.12);
        }
        .quickbook-btn:hover { background: var(--secondary); box-shadow: 0 4px 12px rgba(47,128,130,0.25); }
        @media (max-width: 767px) { .quickbook { padding: 18px 20px; } .quickbook-inner { flex-direction: column; align-items: stretch; gap: 10px; } .qb-field { flex: 1 1 auto; } .qb-field--date { flex: 1 1 auto; } }
        @media (max-width: 767px) { .quickbook-date { flex: 1 !important; max-width: 100% !important; } }

        /* Section defaults */
        .section { padding: 88px 0; }
        @media (max-width: 767px) { .section { padding: 60px 0; } }
        .section-label { font-size: 13px; font-weight: 700; color: var(--secondary); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
        .section-heading { font-family: 'Manrope', system-ui, sans-serif; font-size: clamp(32px, 4.5vw, 52px); font-weight: 700; line-height: 1.1; color: var(--navy); margin-bottom: 16px; letter-spacing: -1px; }
        .section-sub { font-size: 18px; font-weight: 400; color: var(--taupe); line-height: 1.6; max-width: 580px; }
        @media (max-width: 767px) { .section-sub { max-width: 100%; font-size: 16px; } }

        /* Services */
        .services-bg { background: var(--off-white); }
        .services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 52px; }
        @media (max-width: 1023px) { .services-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px)  { .services-grid { grid-template-columns: 1fr; } }
        .service-card {
            background: var(--white); border: 1px solid var(--beige); border-radius: 12px;
            box-shadow: 0 2px 8px rgba(44,58,71,0.06);
            display: flex; flex-direction: column; overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .service-card:hover { box-shadow: 0 8px 24px rgba(44,58,71,0.1); transform: translateY(-2px); }
        .service-card-img { width: 100%; height: 160px; object-fit: cover; display: block; flex-shrink: 0; }
        .service-card-body { padding: 28px; display: flex; flex-direction: column; flex: 1; }
        .service-color-dot { width: 14px; height: 14px; border-radius: 50%; margin-bottom: 16px; flex-shrink: 0; display: block; outline: 2px solid rgba(0,0,0,0.08); outline-offset: 1px; }
        .service-name { font-family: 'Manrope', system-ui, sans-serif; font-size: 20px; font-weight: 600; color: var(--navy); margin-bottom: 10px; }
        .service-desc { font-size: 15px; font-weight: 400; color: var(--taupe); line-height: 1.65; flex: 1; margin-bottom: 20px; }
        .service-meta { display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid var(--beige); }
        .service-duration { font-size: 14px; color: var(--taupe); display: flex; align-items: center; gap: 6px; }
        .service-price { font-family: 'Manrope', system-ui, sans-serif; font-size: 20px; font-weight: 700; color: var(--navy); }
        .service-book-link {
            display: block; text-align: center; margin-top: 16px;
            color: var(--secondary); font-family: 'Inter', system-ui, sans-serif;
            font-size: 15px; font-weight: 500;
            padding: 9px 20px 6px; border-radius: 10px;
            border: 1.5px solid var(--secondary);
            text-decoration: none; transition: background 0.2s;
        }
        .service-book-link:hover { background: var(--terra-soft); }

        /* Booking section */
        .booking-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: start; }
        @media (max-width: 1023px) { .booking-inner { grid-template-columns: 1fr; gap: 40px; } }
        @media (max-width: 767px) { .booking-form-wrap { padding: 24px 20px; } }
        .booking-form-wrap {
            background: var(--off-white); border: 1px solid var(--beige);
            border-radius: 16px; padding: 36px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 400; color: var(--navy); margin-bottom: 8px; }
        /* Form field wrapper (for select + date inputs with custom icon overlay) */
        .form-field-wrap { position: relative; }
        .form-field-wrap .form-icon {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            pointer-events: none; display: flex; align-items: center; color: #515A63;
        }
        .form-input, .form-select {
            width: 100%; background: var(--cream); color: var(--navy);
            font-family: 'Inter', system-ui, sans-serif; font-size: 15px; font-weight: 400;
            padding: 0 16px; border-radius: 10px; border: 1.5px solid rgba(44,58,71,0.15); height: 48px;
            -webkit-appearance: none; -moz-appearance: none; appearance: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            color-scheme: light;
        }
        .form-input:focus, .form-select:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(47,128,130,0.12); }
        .form-input::placeholder { color: var(--taupe); opacity: 0.6; }
        /* Selects and date inputs get extra right padding for the icon overlay */
        .form-field-wrap .form-select,
        .form-field-wrap input[type="date"].form-input { padding: 0 40px 0 16px; cursor: pointer; }
        /* Hide native date chrome */
        input[type="date"].form-input::-webkit-calendar-picker-indicator { opacity: 0; position: absolute; right: 0; top: 0; width: 40px; height: 100%; cursor: pointer; }
        input[type="date"].form-input::-webkit-inner-spin-button,
        input[type="date"].form-input::-webkit-clear-button { -webkit-appearance: none; display: none; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
        .form-submit {
            width: 100%; background: var(--terra-btn); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 17px; font-weight: 600;
            padding: 14px 24px 11px; border-radius: 10px; border: none; cursor: pointer;
            transition: background 0.2s; margin-top: 8px;
        }
        .form-submit:hover { background: var(--secondary); }
        .booking-info-title { font-family: 'Manrope', system-ui, sans-serif; font-size: 36px; font-weight: 700; color: var(--navy); margin-bottom: 16px; letter-spacing: -1px; }
        .booking-info-text { font-size: 17px; font-weight: 400; color: var(--taupe); line-height: 1.65; margin-bottom: 28px; }
        .booking-bullets { list-style: none; }
        .booking-bullets li { display: flex; align-items: flex-start; gap: 10px; font-size: 16px; font-weight: 400; color: var(--navy); padding: 8px 0; }
        .booking-bullets li::before { content: '✓'; color: var(--secondary); font-weight: 700; flex-shrink: 0; margin-top: 1px; }

        /* Alerts */
        .alert-success, .alert-error { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 15px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #F0FAF0; border: 1px solid #B2EAC0; color: #166534; }
        .alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }

        /* Contact */
        .contact-section { background: var(--off-white); }
        .contact-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; margin-top: 52px; }
        @media (max-width: 1023px) { .contact-grid { grid-template-columns: 1fr; max-width: 400px; } }
        @media (max-width: 767px) { .contact-grid { max-width: 100%; } }
        .contact-item { background: var(--white); border: 1px solid var(--beige); border-radius: 16px; padding: 32px 28px; text-align: center; box-shadow: 0 2px 8px rgba(44,58,71,0.06); transition: box-shadow 0.2s, transform 0.2s; }
        .contact-item:hover { box-shadow: 0 8px 24px rgba(44,58,71,0.1); transform: translateY(-2px); }
        .contact-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
        .contact-icon svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .contact-label { font-size: 12px; font-weight: 700; color: var(--taupe); text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 10px; }
        .contact-value { font-size: 16px; font-weight: 400; color: var(--navy); line-height: 1.5; }
        .contact-value a { color: var(--secondary); text-decoration: none; font-weight: 500; }
        .contact-value a:hover { text-decoration: underline; }

        /* Footer */
        footer { background: var(--primary); color: rgba(255,255,255,0.6); padding: 40px; text-align: center; }
        @media (max-width: 767px) { footer { padding: 32px 20px; } }
        .footer-name { font-family: 'Manrope', system-ui, sans-serif; font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .footer-tagline { font-size: 14px; color: rgba(255,255,255,0.5); margin-bottom: 16px; font-weight: 400; }
        .footer-powered { font-size: 13px; color: rgba(255,255,255,0.35); font-weight: 400; }
        .footer-powered a { color: rgba(255,255,255,0.55); text-decoration: none; }
        .footer-powered a:hover { color: var(--yellow); }
    </style>
</head>
<body>

    {{-- NAVIGATION --}}
    <nav aria-label="Main navigation">
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-brand">
                @if ($settings->hasMedia('logo'))
                    @php $logoMedia = $settings->getFirstMedia('logo'); @endphp
                    <img src="{{ klinica_asset($logoMedia->id . '/' . $logoMedia->file_name) }}" alt="{{ $settings->clinic_name }} logo" class="nav-logo-img">
                @else
                    <div class="nav-logo-placeholder">{{ mb_strtoupper(mb_substr($settings->clinic_name, 0, 1)) }}</div>
                @endif
                <span class="nav-name">{{ $settings->clinic_name }}</span>
            </a>
            <div class="nav-links">
                <a href="#services">{{ __('klinica.landing.nav_services') }}</a>
                @if ($settings->online_booking_enabled)
                    <a href="#booking">{{ __('klinica.landing.nav_booking') }}</a>
                @endif
                <a href="#contact">{{ __('klinica.landing.nav_contact') }}</a>
                @if ($settings->online_booking_enabled)
                    <a href="#booking" class="nav-book-btn">{{ __('klinica.landing.nav_book_now') }}</a>
                @endif
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero" aria-label="Hero">
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow">{{ $settings->clinic_name }}</div>
                <h1 class="serif">
                    <em>{{ $settings->hero_title ?: ($settings->tagline ?: __('klinica.landing.hero_default_title')) }}</em>
                </h1>
                @if ($settings->hero_subtitle || $settings->tagline)
                    <p class="hero-tagline">{{ $settings->hero_subtitle ?: $settings->tagline }}</p>
                @endif
                <div class="hero-actions">
                    @if ($settings->online_booking_enabled)
                        <a href="#booking" class="btn-primary">{{ $settings->hero_cta_text ?: __('klinica.landing.hero_book_appointment') }}</a>
                    @endif
                    <a href="#services" class="btn-ghost">
                        {{ __('klinica.landing.hero_our_services') }}
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <div class="hero-card" aria-hidden="true">
                    <div class="hero-card-label">{{ __('klinica.landing.hero_card_label') }}</div>
                    @foreach ($services->take(3) as $service)
                        <div class="hc-service">
                            <div class="hc-dot" style="background: {{ $service->color ?: '#E1F079' }};"></div>
                            <div style="flex:1">
                                <div class="hc-name">{{ $service->name }}</div>
                                <div class="hc-meta">{{ $service->duration_minutes }} min</div>
                            </div>
                            <span class="hc-price">{{ $settings->currency ?? '€' }}{{ number_format((float) $service->price, 0) }}</span>
                        </div>
                    @endforeach
                    @if ($services->isEmpty())
                        <div class="hc-service">
                            <div class="hc-dot" style="background:#E1F079;"></div>
                            <div style="flex:1">
                                <div class="hc-name">{{ __('klinica.landing.general_consultation') }}</div>
                                <div class="hc-meta">45 min</div>
                            </div>
                            <span class="hc-price">{{ $settings->currency ?? '€' }}50</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- QUICK BOOKING BAR --}}
    @if ($settings->online_booking_enabled)
        <div class="quickbook">
            <form class="quickbook-inner" method="GET" action="{{ route('book.create') }}">
                <span class="quickbook-label">{{ __('klinica.landing.quickbook_label') }}</span>
                <div class="qb-field">
                    <select name="service_id" class="qb-input" aria-label="{{ __('klinica.landing.quickbook_choose_service') }}">
                        <option value="">{{ __('klinica.landing.quickbook_choose_service') }}</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} — {{ $service->duration_minutes }} min</option>
                        @endforeach
                    </select>
                    <span class="qb-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8l5 5 5-5"/></svg>
                    </span>
                </div>
                <div class="qb-field qb-field--date">
                    <input type="date" name="date" value="{{ now()->toDateString() }}"
                        class="qb-input" aria-label="Preferred date">
                    <span class="qb-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                </div>
                <button type="submit" class="quickbook-btn">{{ __('klinica.landing.quickbook_check') }}</button>
            </form>
        </div>
    @endif

    {{-- SERVICES --}}
    <section class="section services-bg" id="services" aria-label="Services">
        <div class="container">
            <div class="section-label">{{ __('klinica.landing.services_label') }}</div>
            <h2 class="section-heading serif">{{ __('klinica.landing.services_heading') }}</h2>
            @if ($services->isEmpty())
                <p class="section-sub">{{ __('klinica.landing.services_empty_desc') }}</p>
            @else
                <p class="section-sub">{{ __('klinica.landing.services_count', ['count' => $services->count(), 'word' => trans_choice('klinica.landing.services_word', $services->count())]) }}</p>
                <div class="services-grid" role="list">
                    @foreach ($services as $service)
                        <article class="service-card" role="listitem">
                            @if ($service->image_path)
                                <img
                                    src="{{ klinica_asset($service->image_path) }}"
                                    alt="{{ $service->name }}"
                                    loading="lazy"
                                    class="service-card-img"
                                >
                            @endif
                            <div class="service-card-body">
                                <div class="service-color-dot" style="background: {{ $service->color ?: '#2F8082' }};"></div>
                                <h3 class="service-name serif">{{ $service->name }}</h3>
                                <p class="service-desc">{{ $service->description ?: __('klinica.landing.service_desc_default') }}</p>
                                <div class="service-meta">
                                    <span class="service-duration">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                        {{ $service->duration_minutes }} min
                                    </span>
                                    @if ($service->price > 0)
                                        <span class="service-price serif">{{ $settings->currency ?? '€' }}{{ number_format((float) $service->price, 0) }}</span>
                                    @endif
                                </div>
                                @if ($settings->online_booking_enabled)
                                    <a href="{{ route('book.create', ['service_id' => $service->id]) }}" class="service-book-link">
                                        {{ __('klinica.landing.service_book_link') }}
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- BOOKING FORM --}}
    @if ($settings->online_booking_enabled)
        <section class="section" id="booking" aria-label="Book an appointment">
            <div class="container">
                <div class="booking-inner">
                    <div>
                        <div class="section-label">{{ __('klinica.landing.booking_label') }}</div>
                        <div class="booking-info-title serif">{{ __('klinica.landing.booking_title') }}</div>
                        <p class="booking-info-text">{{ __('klinica.landing.booking_text') }}</p>
                        <ul class="booking-bullets">
                            <li>{{ __('klinica.landing.booking_bullet_1') }}</li>
                            <li>{{ __('klinica.landing.booking_bullet_2') }}</li>
                            <li>{{ __('klinica.landing.booking_bullet_3') }}</li>
                            <li>{{ __('klinica.landing.booking_bullet_4') }}</li>
                        </ul>
                    </div>
                    <div>
                        <div class="booking-form-wrap">
                            <h3 class="serif" style="font-size:22px;font-weight:300;color:var(--navy);margin-bottom:24px">{{ __('klinica.landing.form_title') }}</h3>

                            @if (session('success'))
                                <div class="alert-success" role="alert"><span>✓</span><span>{{ session('success') }}</span></div>
                            @endif
                            @if ($errors->any())
                                <div class="alert-error" role="alert">
                                    <span>!</span>
                                    <ul style="list-style:none;padding:0">
                                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('book.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="lb_service">{{ __('klinica.landing.form_service') }}</label>
                                    <div class="form-field-wrap">
                                        <select class="form-select" id="lb_service" name="service_id" required>
                                            <option value="">{{ __('klinica.landing.form_service_placeholder') }}</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                                    {{ $service->name }} ({{ $service->duration_minutes }} min{{ $service->price > 0 ? ' · '.($settings->currency ?? '€').number_format((float) $service->price, 0) : '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="form-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8l5 5 5-5"/></svg></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="lb_date">{{ __('klinica.landing.form_date') }}</label>
                                    <div class="form-field-wrap">
                                        <input class="form-input" type="date" id="lb_date" name="date"
                                            value="{{ old('date', now()->toDateString()) }}"
                                            min="{{ now()->toDateString() }}" required>
                                        <span class="form-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="lb_name">{{ __('klinica.landing.form_name') }}</label>
                                        <input class="form-input" type="text" id="lb_name" name="guest_name"
                                            value="{{ old('guest_name') }}" placeholder="Jane Smith">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="lb_phone">{{ __('klinica.landing.form_phone') }}</label>
                                        <input class="form-input" type="tel" id="lb_phone" name="guest_phone"
                                            value="{{ old('guest_phone') }}" placeholder="+1 555 0100">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="lb_email">{{ __('klinica.landing.form_email') }}</label>
                                    <input class="form-input" type="email" id="lb_email" name="guest_email"
                                        value="{{ old('guest_email') }}" placeholder="jane@example.com">
                                </div>
                                <p style="font-size:13px;color:var(--taupe);margin-bottom:16px;font-weight:300">
                                    {!! __('klinica.landing.form_hint', ['link' => '<a href="'.route('book.create').'" style="color:var(--secondary)">'.__('klinica.landing.form_hint_link').'</a>']) !!}
                                </p>
                                <button type="submit" class="form-submit">{{ __('klinica.landing.form_submit') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- CONTACT --}}
    @php $hasPhones = $settings->contact_phone || !empty($settings->contact_phones); @endphp
    @if ($hasPhones || $settings->contact_email || $settings->contact_address)
        <section class="section contact-section" id="contact" aria-label="Contact information">
            <div class="container">
                <div class="section-label">{{ __('klinica.landing.contact_label') }}</div>
                <h2 class="section-heading serif">{{ __('klinica.landing.contact_heading') }}</h2>
                <p class="section-sub">{{ __('klinica.landing.contact_sub') }}</p>
                <div class="contact-grid">
                    @if ($hasPhones)
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.23h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 6.29 6.29l.95-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div class="contact-label">{{ __('klinica.landing.contact_phone') }}</div>
                            <div class="contact-value">
                                @if ($settings->contact_phone)
                                    <a href="tel:{{ preg_replace('/[^+\d]/', '', $settings->contact_phone) }}">{{ $settings->contact_phone }}</a>
                                @endif
                                @foreach ($settings->contact_phones ?? [] as $extraPhone)
                                    @if (!empty($extraPhone['number']))
                                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $extraPhone['number']) }}">{{ $extraPhone['number'] }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($settings->contact_email)
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div class="contact-label">{{ __('klinica.landing.contact_email') }}</div>
                            <div class="contact-value">
                                <a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a>
                            </div>
                        </div>
                    @endif
                    @if ($settings->contact_address)
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="contact-label">{{ __('klinica.landing.contact_address') }}</div>
                            <div class="contact-value">{{ $settings->contact_address }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- FOOTER --}}
    <footer>
        <div class="footer-name">{{ $settings->clinic_name }}</div>
        @if ($settings->tagline)
            <div class="footer-tagline">{{ $settings->tagline }}</div>
        @endif
        <div class="footer-powered">{{ __('klinica.landing.powered_by') }} <a href="https://klinica.space" target="_blank" rel="noopener">Klinica</a></div>
    </footer>

</body>
</html>
