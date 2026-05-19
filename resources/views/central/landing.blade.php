<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>Klinica | Modern Clinic Management & Online Booking Platform</title>
    <meta name="description" content="Klinica helps clinics grow with beautiful online booking, patient management, and smart scheduling. Start your free trial today, no credit card required.">
    <meta name="keywords" content="clinic management, online booking, patient scheduling, healthcare platform, appointment software">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="Klinica | Modern Clinic Management Platform">
    <meta property="og:description" content="Beautiful online booking, patient management, and smart scheduling for modern clinics.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('/og-image.png') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Klinica | Modern Clinic Management Platform">
    <meta name="twitter:description" content="Beautiful online booking, patient management, and smart scheduling for modern clinics.">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:     #2F8082;
            --navy:      #2C3A47;
            --yellow:    #F3E9DC;
            --terra:     #8DAA91;
            --terra-btn: #2F8082;
            --terra-soft:#F3E9DC;
            --taupe:     #515A63;
            --beige:     #E7D7C1;
            --cream:     #F3E9DC;
            --off-white: #FAF8F5;
            --white:     #FFFFFF;
            --gray:      #EFEFEF;
            --error:     #CC4E4E;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--navy);
            background: var(--white);
            line-height: 1.6;
        }

        .serif { font-family: 'Manrope', system-ui, sans-serif; font-weight: 700; }

        /* ── Container ── */
        .container {
            max-width: 1152px;
            margin: 0 auto;
            padding: 0 40px;
        }
        @media (max-width: 1023px) { .container { padding: 0 32px; } }
        @media (max-width: 767px)  { .container { padding: 0 20px; } }

        /* ── NAV ── */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--beige);
            padding: 0 40px;
        }
        .nav-inner {
            max-width: 1152px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 68px;
        }
        .nav-logo {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 22px; font-weight: 700; color: var(--green);
            text-decoration: none; letter-spacing: -0.5px;
        }
        .nav-logo span { color: var(--terra); }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a {
            font-size: 16px; font-weight: 400; color: var(--navy);
            text-decoration: none; transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--terra); }
        .nav-cta {
            background: var(--terra-btn); color: #fff !important;
            padding: 8px 22px 5px; border-radius: 10px;
            border: 2px solid var(--terra-btn);
            font-weight: 600 !important;
            transition: background 0.2s !important;
        }
        .nav-cta:hover { background: var(--terra) !important; border-color: var(--terra) !important; color: #fff !important; }
        .nav-mobile-toggle {
            display: none; background: none; border: none; cursor: pointer;
            padding: 8px; color: var(--navy);
        }
        @media (max-width: 767px) {
            .nav-links { display: none; }
            .nav-links.open { display: flex; flex-direction: column; position: absolute; top: 68px; left: 0; right: 0; background: var(--white); padding: 24px 20px 32px; border-bottom: 1px solid var(--beige); gap: 20px; align-items: flex-start; }
            .nav-mobile-toggle { display: flex; }
        }

        /* ── HERO ── */
        .hero {
            background: var(--green);
            padding: 80px 40px 72px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 80% 40%, rgba(86,178,180,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-inner {
            max-width: 1152px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 60px; align-items: center;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 500;
            padding: 5px 14px; border-radius: 50px; margin-bottom: 28px;
        }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--yellow); }
        .hero h1 {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: clamp(48px, 7vw, 88px); font-weight: 700;
            line-height: 1.05; color: var(--white);
            letter-spacing: -2px; margin-bottom: 24px;
        }
        .hero h1 em { font-style: normal; color: var(--yellow); }
        .hero p {
            font-size: 20px; font-weight: 400; color: rgba(255,255,255,0.8);
            line-height: 1.6; margin-bottom: 40px; max-width: 480px;
        }
        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; align-items: center; }
        .hero-btn-primary {
            background: var(--white); color: var(--green);
            font-family: 'Inter', system-ui, sans-serif; font-size: 16px; font-weight: 600;
            padding: 14px 32px 11px; border-radius: 10px; border: 2px solid var(--white);
            text-decoration: none; transition: background 0.2s, color 0.2s; display: inline-block;
        }
        .hero-btn-primary:hover { background: var(--yellow); border-color: var(--yellow); color: var(--green); }
        .hero-btn-ghost {
            color: rgba(255,255,255,0.7); font-size: 16px; font-weight: 400;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            padding: 14px 0 11px; transition: color 0.2s;
        }
        .hero-btn-ghost:hover { color: #fff; }
        .hero-stats {
            display: flex; gap: 32px; margin-top: 48px;
            padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.12);
        }
        .hero-stat-num {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 36px; font-weight: 700; color: var(--white); display: block;
        }
        .hero-stat-label { font-size: 13px; color: rgba(255,255,255,0.6); font-weight: 400; }
        .hero-visual {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px; padding: 28px; backdrop-filter: blur(8px);
        }
        .hero-visual-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .hero-visual-title { color: rgba(255,255,255,0.6); font-size: 12px; font-weight: 400; text-transform: uppercase; letter-spacing: 1px; }
        .hero-visual-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--terra); }
        .mock-booking-card {
            background: rgba(255,255,255,0.08); border-radius: 10px; padding: 16px;
            margin-bottom: 12px; display: flex; align-items: center; gap: 12px;
        }
        .mock-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--terra); display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #fff; font-weight: 700; flex-shrink: 0;
        }
        .mock-info { flex: 1; }
        .mock-name { color: #fff; font-size: 14px; font-weight: 400; margin-bottom: 2px; }
        .mock-detail { color: rgba(255,255,255,0.5); font-size: 12px; }
        .mock-time { color: var(--yellow); font-size: 13px; font-weight: 400; }
        .mock-progress { margin-top: 20px; }
        .mock-progress-label { color: rgba(255,255,255,0.5); font-size: 11px; margin-bottom: 8px; display: flex; justify-content: space-between; }
        .mock-progress-bar { height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; }
        .mock-progress-fill { height: 100%; border-radius: 2px; background: var(--terra); }

        @media (max-width: 1023px) { .hero-inner { grid-template-columns: 1fr; } .hero-visual { display: none; } }
        @media (max-width: 767px) { .hero { padding: 60px 20px 52px; } .hero h1 { font-size: 48px; } .hero-stats { gap: 24px; } }

        /* ── LOGOS / TRUST ── */
        .trust-bar {
            background: var(--off-white); border-top: 1px solid var(--beige);
            border-bottom: 1px solid var(--beige); padding: 28px 40px;
        }
        .trust-bar-inner {
            max-width: 1152px; margin: 0 auto;
            display: flex; align-items: center; gap: 48px;
            flex-wrap: wrap; justify-content: center;
        }
        .trust-label { font-size: 13px; color: var(--taupe); white-space: nowrap; }
        .trust-logos { display: flex; gap: 40px; align-items: center; flex-wrap: wrap; justify-content: center; }
        .trust-logo-item { font-family: 'Manrope', system-ui, sans-serif; font-size: 14px; color: var(--navy); font-weight: 600; display: flex; align-items: center; gap: 7px; }
        .trust-logo-item svg { color: var(--green); flex-shrink: 0; }

        /* ── SECTION DEFAULTS ── */
        section { padding: 88px 40px; }
        @media (max-width: 767px) { section { padding: 60px 20px; } }
        .section-label {
            font-size: 13px; font-weight: 700; color: var(--terra);
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;
        }
        .section-heading {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: clamp(36px, 5vw, 56px); font-weight: 700;
            line-height: 1.1; color: var(--navy); margin-bottom: 20px; letter-spacing: -1px;
        }
        .section-sub { font-size: 20px; font-weight: 400; color: var(--taupe); line-height: 1.55; max-width: 600px; }
        .section-header { margin-bottom: 56px; }
        .section-header-centered { text-align: center; }
        .section-header-centered .section-sub { margin: 0 auto; }

        /* ── FEATURES ── */
        .features-bg { background: var(--off-white); }
        .features-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;
        }
        @media (max-width: 1023px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px)  { .features-grid { grid-template-columns: 1fr; } }
        .feature-card {
            background: var(--white); border: 1px solid var(--beige);
            border-radius: 12px; padding: 32px;
            box-shadow: 0 2px 8px rgba(44,58,71,0.06);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .feature-card:hover { box-shadow: 0 8px 24px rgba(44,58,71,0.1); transform: translateY(-2px); }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 10px;
            background: var(--green); display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round; }
        .feature-title { font-size: 20px; font-weight: 600; color: var(--navy); margin-bottom: 10px; font-family: 'Manrope', system-ui, sans-serif; }
        .feature-desc { font-size: 16px; font-weight: 400; color: var(--taupe); line-height: 1.6; }

        /* ── HOW IT WORKS ── */
        .steps-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;
            position: relative;
        }
        .steps-grid::before {
            content: '';
            position: absolute; top: 28px; left: calc(16.67% + 28px); right: calc(16.67% + 28px);
            height: 1px; background: var(--beige); pointer-events: none;
        }
        @media (max-width: 767px) { .steps-grid { grid-template-columns: 1fr; } .steps-grid::before { display: none; } }
        .step { text-align: center; }
        .step-num {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--green); color: var(--white);
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 22px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; position: relative; z-index: 1;
        }
        .step-title { font-size: 18px; font-weight: 600; color: var(--navy); margin-bottom: 10px; font-family: 'Manrope', system-ui, sans-serif; }
        .step-desc { font-size: 16px; font-weight: 400; color: var(--taupe); line-height: 1.6; }

        /* ── PRICING ── */
        .pricing-bg { background: var(--off-white); }
        .pricing-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;
            align-items: start;
        }
        @media (max-width: 1023px) { .pricing-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; } }
        .pricing-card {
            background: var(--white); border: 1px solid var(--beige);
            border-radius: 12px; padding: 36px;
            box-shadow: 0 2px 8px rgba(44,58,71,0.06);
        }
        .pricing-card.featured {
            background: var(--green); border-color: var(--green);
            box-shadow: 0 8px 32px rgba(47,128,130,0.25);
            position: relative; transform: scale(1.03);
        }
        @media (max-width: 1023px) { .pricing-card.featured { transform: none; } }
        .pricing-badge {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            background: var(--yellow); color: var(--navy);
            font-size: 11px; font-weight: 700; padding: 4px 16px; border-radius: 50px;
            white-space: nowrap; text-transform: uppercase; letter-spacing: 1px;
        }
        .pricing-plan { font-size: 14px; font-weight: 700; color: var(--taupe); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .pricing-card.featured .pricing-plan { color: var(--yellow); }
        .pricing-price {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 52px; font-weight: 700; color: var(--navy); line-height: 1;
            margin-bottom: 4px; letter-spacing: -2px;
        }
        .pricing-card.featured .pricing-price { color: #fff; }
        .pricing-price sup { font-size: 24px; vertical-align: top; margin-top: 10px; font-family: 'Inter', sans-serif; }
        .pricing-period { font-size: 14px; color: var(--taupe); margin-bottom: 28px; font-weight: 400; }
        .pricing-card.featured .pricing-period { color: rgba(255,255,255,0.6); }
        .pricing-desc { font-size: 15px; color: var(--taupe); margin-bottom: 28px; line-height: 1.55; font-weight: 400; }
        .pricing-card.featured .pricing-desc { color: rgba(255,255,255,0.7); }
        .pricing-divider { height: 1px; background: var(--beige); margin-bottom: 24px; }
        .pricing-card.featured .pricing-divider { background: rgba(255,255,255,0.15); }
        .pricing-features { list-style: none; margin-bottom: 32px; }
        .pricing-features li {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 15px; font-weight: 400; color: var(--navy);
            padding: 6px 0; line-height: 1.5;
        }
        .pricing-card.featured .pricing-features li { color: rgba(255,255,255,0.85); }
        .pricing-check { color: var(--terra); font-size: 16px; flex-shrink: 0; margin-top: 2px; }
        .pricing-card.featured .pricing-check { color: var(--yellow); }
        .pricing-btn {
            display: block; text-align: center;
            background: var(--terra-btn); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 16px; font-weight: 600;
            padding: 13px 24px 10px; border-radius: 10px; border: 2px solid var(--terra-btn);
            text-decoration: none; transition: background 0.2s;
        }
        .pricing-btn:hover { background: var(--terra); border-color: var(--terra); }
        .pricing-btn.outline {
            background: transparent; color: var(--terra); border-color: var(--terra);
        }
        .pricing-btn.outline:hover { background: var(--terra-soft); }
        .pricing-btn.featured-btn {
            background: var(--white); color: var(--green); border-color: var(--white);
        }
        .pricing-btn.featured-btn:hover { background: var(--yellow); border-color: var(--yellow); }

        /* ── TESTIMONIALS ── */
        .testimonials-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;
        }
        @media (max-width: 1023px) { .testimonials-grid { grid-template-columns: 1fr; max-width: 520px; margin: 0 auto; } }
        .testimonial-card {
            background: var(--white); border: 1px solid var(--beige);
            border-radius: 12px; padding: 28px;
            box-shadow: 0 2px 8px rgba(44,58,71,0.06);
        }
        .testimonial-stars { color: var(--green); font-size: 16px; margin-bottom: 16px; }
        .testimonial-quote { font-size: 16px; font-weight: 400; color: var(--navy); line-height: 1.65; margin-bottom: 20px; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; }
        .testimonial-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--green); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; flex-shrink: 0;
        }
        .testimonial-name { font-size: 15px; font-weight: 600; color: var(--navy); font-family: 'Manrope', system-ui, sans-serif; }
        .testimonial-role { font-size: 13px; color: var(--taupe); font-weight: 400; }

        /* ── CTA SECTION ── */
        .cta-section {
            background: var(--green); padding: 88px 40px;
            text-align: center;
        }
        .cta-section h2 {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: clamp(36px, 5vw, 56px); font-weight: 700;
            color: var(--white); margin-bottom: 20px; line-height: 1.1; letter-spacing: -1px;
        }
        .cta-section p {
            font-size: 20px; font-weight: 400; color: rgba(255,255,255,0.8);
            margin-bottom: 40px; max-width: 540px; margin-left: auto; margin-right: auto;
        }
        .cta-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        @media (max-width: 767px) { .cta-section { padding: 60px 20px; } }

        /* ── FOOTER ── */
        footer {
            background: var(--navy); color: rgba(255,255,255,0.6);
            padding: 64px 40px 32px;
        }
        .footer-inner {
            max-width: 1152px; margin: 0 auto;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 52px;
            margin-bottom: 52px;
        }
        @media (max-width: 1023px) { .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; } }
        @media (max-width: 600px)  { .footer-grid { grid-template-columns: 1fr; gap: 28px; } }
        .footer-brand-name {
            font-family: 'Manrope', system-ui, sans-serif;
            font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 12px;
        }
        .footer-brand-name span { color: var(--terra); }
        .footer-brand-desc { font-size: 14px; line-height: 1.65; color: rgba(255,255,255,0.5); max-width: 260px; font-weight: 400; }
        .footer-col-title { font-size: 13px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { font-size: 14px; color: rgba(255,255,255,0.5); text-decoration: none; font-weight: 400; transition: color 0.2s; }
        .footer-links a:hover { color: var(--terra); }
        .footer-divider { height: 1px; background: rgba(255,255,255,0.08); margin-bottom: 28px; }
        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
        }
        .footer-copy { font-size: 13px; color: rgba(255,255,255,0.35); font-weight: 400; }
        .footer-bottom-links { display: flex; gap: 24px; }
        .footer-bottom-links a { font-size: 13px; color: rgba(255,255,255,0.35); text-decoration: none; transition: color 0.2s; }
        .footer-bottom-links a:hover { color: rgba(255,255,255,0.7); }
    </style>
</head>
<body>

    {{-- ── NAVIGATION ── --}}
    <nav>
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-logo">Klinica<span>.</span></a>
            <div class="nav-links" id="navLinks">
                <a href="#features">{{ __('klinica.central.nav_features') }}</a>
                <a href="#how-it-works">{{ __('klinica.central.nav_how') }}</a>
                <a href="#pricing">{{ __('klinica.central.nav_pricing') }}</a>
                <a href="#testimonials">{{ __('klinica.central.nav_reviews') }}</a>
                <a href="{{ route('register') }}" class="nav-cta">{{ __('klinica.central.nav_trial') }}</a>
            </div>
            <button class="nav-mobile-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')" aria-label="Menu">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- ── HERO ── --}}
    <section class="hero" aria-label="Hero">
        <div class="hero-inner">
            <div>
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    {{ __('klinica.central.hero_badge') }}
                </div>
                <h1 class="serif">
                    {{ __('klinica.central.hero_h1_1') }}<br><em>{{ __('klinica.central.hero_h1_em') }}</em>
                </h1>
                <p>{{ __('klinica.central.hero_p') }}</p>
                <div class="hero-actions">
                    <div>
                        <a href="{{ route('register') }}" class="hero-btn-primary">{{ __('klinica.central.hero_cta') }}</a>
                        <div style="font-size:12px;color:rgba(255,255,255,0.55);margin-top:8px;text-align:center;letter-spacing:0.2px;">{{ __('klinica.central.hero_no_cc') }}</div>
                    </div>
                    <a href="#features" class="hero-btn-ghost" style="display:inline-flex;align-items:center;gap:6px">
                        {{ __('klinica.central.hero_explore') }}
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <span class="hero-stat-num serif">2k+</span>
                        <span class="hero-stat-label">{{ __('klinica.central.hero_stat_appts') }}</span>
                    </div>
                    <div>
                        <span class="hero-stat-num serif">200+</span>
                        <span class="hero-stat-label">{{ __('klinica.central.hero_stat_clinics') }}</span>
                    </div>
                    <div>
                        <span class="hero-stat-num serif">4.9★</span>
                        <span class="hero-stat-label">{{ __('klinica.central.hero_stat_rating') }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="hero-visual" aria-hidden="true">
                    <div class="hero-visual-header">
                        <span class="hero-visual-title">{{ __('klinica.central.hero_mock_schedule') }}</span>
                        <span class="hero-visual-dot"></span>
                    </div>
                    <div class="mock-booking-card">
                        <div class="mock-avatar">SL</div>
                        <div class="mock-info">
                            <div class="mock-name">Sarah Lambert</div>
                            <div class="mock-detail">General consultation · 45 min</div>
                        </div>
                        <span class="mock-time">09:00</span>
                    </div>
                    <div class="mock-booking-card" style="opacity:0.75">
                        <div class="mock-avatar" style="background:#7A6C5F">MJ</div>
                        <div class="mock-info">
                            <div class="mock-name">Marc Joubert</div>
                            <div class="mock-detail">Follow-up · 30 min</div>
                        </div>
                        <span class="mock-time">10:15</span>
                    </div>
                    <div class="mock-booking-card" style="opacity:0.55">
                        <div class="mock-avatar" style="background:#2F8082">AN</div>
                        <div class="mock-info">
                            <div class="mock-name">Aïcha Nouri</div>
                            <div class="mock-detail">Physiotherapy · 60 min</div>
                        </div>
                        <span class="mock-time">11:30</span>
                    </div>
                    <div class="mock-progress">
                        <div class="mock-progress-label">
                            <span>{{ __('klinica.central.hero_mock_capacity') }}</span>
                            <span style="color:#E1F079">78%</span>
                        </div>
                        <div class="mock-progress-bar">
                            <div class="mock-progress-fill" style="width:78%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── TRUST BAR ── --}}
    <div class="trust-bar">
        <div class="trust-bar-inner">
            <span class="trust-label">{{ __('klinica.central.trust_label') }}</span>
            <div class="trust-logos">
                <span class="trust-logo-item">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.74 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7"/></svg>
                    {{ __('klinica.central.trust_eu_cloud') }}
                </span>
                <span class="trust-logo-item">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    {{ __('klinica.central.trust_gdpr') }}
                </span>
                <span class="trust-logo-item">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    {{ __('klinica.central.trust_encryption') }}
                </span>
                <span class="trust-logo-item">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.657-4.03 3-9 3S3 13.657 3 12"/><path d="M3 5v14c0 1.657 4.03 3 9 3s9-1.343 9-3V5"/></svg>
                    {{ __('klinica.central.trust_backups') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── FEATURES ── --}}
    <section class="features-bg" id="features">
        <div class="container">
            <div class="section-header section-header-centered">
                <div class="section-label">{{ __('klinica.central.feat_label') }}</div>
                <h2 class="section-heading serif" style="margin:0 auto 20px">{{ __('klinica.central.feat_heading') }}</h2>
                <p class="section-sub">{{ __('klinica.central.feat_sub') }}</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_1_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_1_desc') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_2_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_2_desc') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_3_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_3_desc') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_4_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_4_desc') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_5_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_5_desc') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.feat_6_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.feat_6_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── HOW IT WORKS ── --}}
    <section id="how-it-works">
        <div class="container">
            <div class="section-header section-header-centered">
                <div class="section-label">{{ __('klinica.central.how_label') }}</div>
                <h2 class="section-heading serif" style="margin:0 auto 20px">{{ __('klinica.central.how_heading') }}</h2>
                <p class="section-sub">{{ __('klinica.central.how_sub') }}</p>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="step-num serif">1</div>
                    <h3 class="step-title">{{ __('klinica.central.how_1_title') }}</h3>
                    <p class="step-desc">{{ __('klinica.central.how_1_desc') }}</p>
                </div>
                <div class="step">
                    <div class="step-num serif">2</div>
                    <h3 class="step-title">{{ __('klinica.central.how_2_title') }}</h3>
                    <p class="step-desc">{{ __('klinica.central.how_2_desc') }}</p>
                </div>
                <div class="step">
                    <div class="step-num serif">3</div>
                    <h3 class="step-title">{{ __('klinica.central.how_3_title') }}</h3>
                    <p class="step-desc">{{ __('klinica.central.how_3_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECURITY & COMPLIANCE ── --}}
    <section id="security" style="background:var(--off-white)">
        <div class="container">
            <div class="section-header section-header-centered">
                <div class="section-label">{{ __('klinica.central.sec_label') }}</div>
                <h2 class="section-heading serif" style="margin:0 auto 20px">{{ __('klinica.central.sec_heading') }}</h2>
                <p class="section-sub">{{ __('klinica.central.sec_sub') }}</p>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;margin-top:48px">
                <div class="feature-card" style="text-align:left">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.74 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.sec_1_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.sec_1_desc') }}</p>
                </div>
                <div class="feature-card" style="text-align:left">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.sec_2_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.sec_2_desc') }}</p>
                </div>
                <div class="feature-card" style="text-align:left">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.sec_3_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.sec_3_desc') }}</p>
                </div>
                <div class="feature-card" style="text-align:left">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg></div>
                    <h3 class="feature-title">{{ __('klinica.central.sec_4_title') }}</h3>
                    <p class="feature-desc">{{ __('klinica.central.sec_4_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── PRICING ── --}}
    <section class="pricing-bg" id="pricing">
        <div class="container">
            <div class="section-header section-header-centered">
                <div class="section-label">{{ __('klinica.central.pricing_label') }}</div>
                <h2 class="section-heading serif" style="margin:0 auto 20px">{{ __('klinica.central.pricing_heading') }}</h2>
                <p class="section-sub">{{ __('klinica.central.pricing_sub') }}</p>
            </div>
            <div class="pricing-grid">
                {{-- Starter --}}
                <div class="pricing-card">
                    <div class="pricing-plan">{{ __('klinica.central.starter_plan') }}</div>
                    <div class="pricing-price serif"><sup>€</sup>29</div>
                    <div class="pricing-period">{{ __('klinica.central.starter_period') }}</div>
                    <div class="pricing-desc">{{ __('klinica.central.starter_desc') }}</div>
                    <div class="pricing-divider"></div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f1') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f2') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f3') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f4') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f5') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f6') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.starter_f7') }}</li>
                        <li style="color:#94a3b8;opacity:.7"><span style="color:#cbd5e1;font-size:16px;flex-shrink:0;margin-top:2px">✕</span> <span style="text-decoration:line-through">{{ __('klinica.central.starter_off1') }}</span></li>
                        <li style="color:#94a3b8;opacity:.7"><span style="color:#cbd5e1;font-size:16px;flex-shrink:0;margin-top:2px">✕</span> <span style="text-decoration:line-through">{{ __('klinica.central.starter_off2') }}</span></li>
                    </ul>
                    <a href="{{ route('register') }}?plan=starter" class="pricing-btn outline">{{ __('klinica.central.starter_cta') }}</a>
                </div>

                {{-- Pro (featured) --}}
                <div class="pricing-card featured" style="position:relative">
                    <div class="pricing-badge">{{ __('klinica.central.pro_badge') }}</div>
                    <div class="pricing-plan">{{ __('klinica.central.pro_plan') }}</div>
                    <div class="pricing-price serif"><sup>€</sup>49</div>
                    <div class="pricing-period">{{ __('klinica.central.pro_period') }}</div>
                    <div class="pricing-desc">{{ __('klinica.central.pro_desc') }}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:-18px;margin-bottom:24px">{{ __('klinica.central.pro_noshow') }}</div>
                    <div class="pricing-divider"></div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f1') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f2') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f3') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f4') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f5') }}</li>
                        <li>
                            <span class="pricing-check">✓</span>
                            {{ __('klinica.central.pro_f6') }}
                            <span style="display:block;font-size:11px;color:rgba(255,255,255,0.45);margin-left:22px;margin-top:1px">{{ __('klinica.central.pro_f6_hint') }}</span>
                        </li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.pro_f7') }}</li>
                    </ul>
                    <a href="{{ route('register') }}?plan=pro" class="pricing-btn featured-btn">{{ __('klinica.central.pro_cta') }}</a>
                </div>

                {{-- Enterprise --}}
                <div class="pricing-card">
                    <div class="pricing-plan">{{ __('klinica.central.enterprise_plan') }}</div>
                    <div class="pricing-price serif" style="font-size:40px;margin-top:6px">Custom</div>
                    <div class="pricing-period">{{ __('klinica.central.enterprise_period') }}</div>
                    <div class="pricing-desc">{{ __('klinica.central.enterprise_desc') }}</div>
                    <div class="pricing-divider"></div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f1') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f2') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f3') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f4') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f5') }}</li>
                        <li><span class="pricing-check">✓</span> {{ __('klinica.central.enterprise_f6') }}</li>
                    </ul>
                    <a href="mailto:hello@klinica.space" class="pricing-btn outline">{{ __('klinica.central.enterprise_cta') }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── TESTIMONIALS ── --}}
    <section id="testimonials">
        <div class="container">
            <div class="section-header section-header-centered">
                <div class="section-label">{{ __('klinica.central.test_label') }}</div>
                <h2 class="section-heading serif" style="margin:0 auto 20px">{{ __('klinica.central.test_heading') }}</h2>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-quote">{{ __('klinica.central.test_1_quote') }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">DR</div>
                        <div>
                            <div class="testimonial-name">{{ __('klinica.central.test_1_name') }}</div>
                            <div class="testimonial-role">{{ __('klinica.central.test_1_role') }}</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-quote">{{ __('klinica.central.test_2_quote') }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:#8DAA91">MB</div>
                        <div>
                            <div class="testimonial-name">{{ __('klinica.central.test_2_name') }}</div>
                            <div class="testimonial-role">{{ __('klinica.central.test_2_role') }}</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-quote">{{ __('klinica.central.test_3_quote') }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:#7A6C5F">KA</div>
                        <div>
                            <div class="testimonial-name">{{ __('klinica.central.test_3_name') }}</div>
                            <div class="testimonial-role">{{ __('klinica.central.test_3_role') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FINAL CTA ── --}}
    <div class="cta-section">
        <div class="container">
            <h2 class="serif">{{ __('klinica.central.cta_heading') }}</h2>
            <p>{{ __('klinica.central.cta_p') }}</p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="hero-btn-primary">{{ __('klinica.central.cta_btn') }}</a>
                <a href="mailto:hello@klinica.space" class="hero-btn-ghost" style="color:rgba(255,255,255,0.65)">
                    {{ __('klinica.central.cta_sales') }}
                </a>
            </div>
            <p style="margin-top:20px;font-size:13px;opacity:0.7">{{ __('klinica.central.cta_security') }}</p>
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand-name">Klinica<span>.</span></div>
                    <p class="footer-brand-desc">{{ __('klinica.central.footer_brand_desc') }}</p>
                </div>
                <div>
                    <div class="footer-col-title">{{ __('klinica.central.footer_product') }}</div>
                    <ul class="footer-links">
                        <li><a href="#features">{{ __('klinica.central.footer_link_features') }}</a></li>
                        <li><a href="#pricing">{{ __('klinica.central.footer_link_pricing') }}</a></li>
                        <li><a href="#how-it-works">{{ __('klinica.central.footer_link_how') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('klinica.central.footer_link_trial') }}</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">{{ __('klinica.central.footer_company') }}</div>
                    <ul class="footer-links">
                        <li><a href="#">{{ __('klinica.central.footer_link_about') }}</a></li>
                        <li><a href="#">{{ __('klinica.central.footer_link_blog') }}</a></li>
                        <li><a href="#">{{ __('klinica.central.footer_link_careers') }}</a></li>
                        <li><a href="mailto:hello@klinica.space">{{ __('klinica.central.footer_link_contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">{{ __('klinica.central.footer_legal') }}</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('terms') }}">{{ __('klinica.central.footer_link_terms') }}</a></li>
                        <li><a href="{{ route('privacy') }}">{{ __('klinica.central.footer_link_privacy') }}</a></li>
                        <li><a href="{{ route('dpa') }}">{{ __('klinica.central.footer_link_dpa') }}</a></li>
                        <li><a href="{{ route('refund-policy') }}">{{ __('klinica.central.footer_link_refund') }}</a></li>
                        <li><a href="mailto:privacy@klinica.space">{{ __('klinica.central.footer_link_privacy_contact') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-bottom">
                <span class="footer-copy">{{ __('klinica.central.footer_copy', ['year' => date('Y')]) }}</span>
                <div class="footer-bottom-links">
                    <a href="{{ route('terms') }}">{{ __('klinica.central.footer_terms') }}</a>
                    <a href="{{ route('privacy') }}">{{ __('klinica.central.footer_privacy') }}</a>
                    <a href="{{ route('dpa') }}">{{ __('klinica.central.footer_dpa') }}</a>
                    <a href="{{ route('refund-policy') }}">{{ __('klinica.central.footer_refunds') }}</a>
                    <a href="mailto:privacy@klinica.space">{{ __('klinica.central.footer_contact') }}</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
