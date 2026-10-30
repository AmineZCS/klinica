<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('central.footer_privacy') }} — Klinica</title>
    <meta name="description" content="Klinica Privacy Policy — how we collect, use, and protect your data in compliance with GDPR.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/privacy') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green:     #2F8082;
            --navy:      #2C3A47;
            --terra:     #8DAA91;
            --beige:     #E7D7C1;
            --cream:     #F3E9DC;
            --off-white: #FAF8F5;
            --white:     #FFFFFF;
            --slate:     #515A63;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--navy);
            background: var(--off-white);
            line-height: 1.7;
        }

        /* NAV */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--beige);
            padding: 0 40px;
        }
        .nav-inner {
            max-width: 1152px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
        }
        .nav-logo {
            font-family: 'Manrope', sans-serif;
            font-size: 22px; font-weight: 700; color: var(--green);
            text-decoration: none; letter-spacing: -0.5px;
        }
        .nav-logo span { color: var(--terra); }
        .nav-back {
            font-size: 14px; color: var(--slate); text-decoration: none;
            display: flex; align-items: center; gap: 6px; transition: color 0.2s;
        }
        .nav-back:hover { color: var(--green); }

        /* HERO */
        .legal-hero {
            background: linear-gradient(135deg, #1a2e38 0%, #2C3A47 100%);
            padding: 64px 40px 52px;
            text-align: center;
        }
        .legal-hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.85); font-size: 12px; font-weight: 600;
            padding: 5px 14px; border-radius: 50px; margin-bottom: 20px;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .legal-hero h1 {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(28px, 4vw, 44px); font-weight: 700;
            color: #fff; letter-spacing: -1px; margin-bottom: 16px; line-height: 1.1;
        }
        .legal-hero p {
            font-size: 16px; color: rgba(255,255,255,0.65);
            max-width: 560px; margin: 0 auto;
        }
        .legal-meta {
            display: flex; justify-content: center; gap: 32px;
            margin-top: 32px; flex-wrap: wrap;
        }
        .legal-meta-item {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: rgba(255,255,255,0.55);
        }
        .legal-meta-item strong { color: rgba(255,255,255,0.85); }

        /* CONTENT */
        .legal-wrap {
            max-width: 860px; margin: 0 auto;
            padding: 56px 40px 80px;
        }

        .toc-box {
            background: var(--white); border: 1px solid var(--beige);
            border-radius: 12px; padding: 28px 32px; margin-bottom: 48px;
        }
        .toc-box h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 16px;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .toc-list { list-style: none; }
        .toc-list li { margin-bottom: 8px; }
        .toc-list a { font-size: 14px; color: var(--green); text-decoration: none; font-weight: 500; }
        .toc-list a:hover { text-decoration: underline; }

        .section { margin-bottom: 52px; scroll-margin-top: 80px; }
        .section-number {
            display: inline-block; font-size: 11px; font-weight: 700;
            color: var(--green); background: rgba(47,128,130,0.08);
            padding: 3px 10px; border-radius: 4px;
            text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;
        }
        .section h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 22px; font-weight: 700;
            color: var(--navy); margin-bottom: 16px; line-height: 1.3;
        }
        .section p { font-size: 15px; color: var(--slate); margin-bottom: 14px; }
        .section ul, .section ol { margin: 12px 0 14px 20px; color: var(--slate); }
        .section li { font-size: 15px; margin-bottom: 8px; line-height: 1.65; }
        .section strong { color: var(--navy); font-weight: 600; }

        .highlight-box {
            background: rgba(47,128,130,0.07);
            border-left: 3px solid var(--green);
            border-radius: 0 8px 8px 0;
            padding: 16px 20px; margin: 20px 0;
        }
        .highlight-box p { margin: 0; font-size: 14px; color: var(--navy); }

        .rights-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 16px; margin: 20px 0;
        }
        @media (max-width: 640px) { .rights-grid { grid-template-columns: 1fr; } }

        .right-card {
            background: var(--white); border: 1px solid var(--beige);
            border-radius: 10px; padding: 20px;
        }
        .right-card h3 {
            font-family: 'Manrope', sans-serif;
            font-size: 14px; font-weight: 700;
            color: var(--navy); margin-bottom: 8px;
        }
        .right-card p { font-size: 13px; color: var(--slate); margin: 0; line-height: 1.6; }

        .divider { height: 1px; background: var(--beige); margin: 48px 0; }

        /* FOOTER */
        footer { background: var(--navy); padding: 32px 40px; text-align: center; }
        .footer-copy { font-size: 13px; color: rgba(255,255,255,0.35); }
        .footer-copy a { color: rgba(255,255,255,0.5); text-decoration: none; }
        .footer-copy a:hover { color: rgba(255,255,255,0.8); }

        @media (max-width: 767px) {
            .legal-wrap { padding: 36px 20px 60px; }
            .legal-hero { padding: 48px 20px 40px; }
            nav { padding: 0 20px; }
        }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-logo">Klinica<span>.</span></a>
            <a href="{{ url('/') }}" class="nav-back">{{ __('central.back_to_home') }}</a>
        </div>
    </nav>

    {{-- HERO --}}
    <div class="legal-hero">
        <div class="legal-hero-badge">{{ __('central.legal_badge') }}</div>
        <h1>{{ __('central.footer_privacy') }}</h1>
        <p>How Klinica collects, uses, and protects personal data — your rights under the GDPR and beyond.</p>
        <div class="legal-meta">
            <div class="legal-meta-item"><strong>Version:</strong> 1.0</div>
            <div class="legal-meta-item"><strong>{{ __('central.effective_label') }}</strong> {{ date('F j, Y') }}</div>
            <div class="legal-meta-item"><strong>Regulation:</strong> GDPR 2016/679</div>
        </div>
    </div>

    <div class="legal-wrap">

        {{-- TABLE OF CONTENTS --}}
        <div class="toc-box">
            <h2>Table of Contents</h2>
            <ol class="toc-list">
                <li><a href="#who-we-are">1. Who We Are</a></li>
                <li><a href="#what-we-collect">2. What Data We Collect</a></li>
                <li><a href="#how-we-use">3. How We Use Your Data</a></li>
                <li><a href="#lawful-basis">4. Lawful Basis for Processing</a></li>
                <li><a href="#data-sharing">5. Who We Share Data With</a></li>
                <li><a href="#security">6. How We Protect Your Data</a></li>
                <li><a href="#retention">7. Data Retention</a></li>
                <li><a href="#your-rights">8. Your Rights Under GDPR</a></li>
                <li><a href="#cookies">9. Cookies and Tracking</a></li>
                <li><a href="#children">10. Children's Privacy</a></li>
                <li><a href="#changes">11. Changes to This Policy</a></li>
                <li><a href="#contact">12. Contact Us</a></li>
            </ol>
        </div>

        {{-- SECTION 1 --}}
        <div class="section" id="who-we-are">
            <span class="section-number">Section 1</span>
            <h2>Who We Are</h2>
            <p>Klinica (operating at <strong>klinica.space</strong>) is a Software-as-a-Service (SaaS) platform that provides clinic management, online booking, patient management, and scheduling tools for healthcare professionals.</p>
            <p>For the purposes of GDPR, Klinica acts as:</p>
            <ul>
                <li><strong>Data Controller</strong> — for personal data we collect directly (e.g., account registration data for clinic administrators).</li>
                <li><strong>Data Processor</strong> — for patient and clinical data entered by clinics using our platform. The clinic remains the Data Controller for their patients' data.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 2 --}}
        <div class="section" id="what-we-collect">
            <span class="section-number">Section 2</span>
            <h2>What Data We Collect</h2>
            <p>We collect different types of personal data depending on your role:</p>

            <p><strong>For clinic administrators and staff:</strong></p>
            <ul>
                <li>Full name and email address (for account creation and login)</li>
                <li>Clinic name and subdomain preferences</li>
                <li>Subscription and billing contact details (not payment card data — handled by Paddle.com)</li>
                <li>IP address and browser/device information (for security and fraud prevention)</li>
                <li>Usage logs and activity records within the platform</li>
            </ul>

            <p><strong>For patients (processed on behalf of the clinic):</strong></p>
            <ul>
                <li>Name, date of birth, gender, and contact information</li>
                <li>Appointment history and scheduling data</li>
                <li>Clinical notes and medical records entered by the clinic</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 3 --}}
        <div class="section" id="how-we-use">
            <span class="section-number">Section 3</span>
            <h2>How We Use Your Data</h2>
            <p>We use the data we collect for the following purposes:</p>
            <ul>
                <li><strong>Providing the service:</strong> Operating your clinic management platform, enabling online bookings, and managing patient records.</li>
                <li><strong>Account management:</strong> Creating and maintaining your clinic account, including authentication and access control.</li>
                <li><strong>Communications:</strong> Sending essential service notifications, onboarding emails, trial expiry reminders, and security alerts.</li>
                <li><strong>Billing:</strong> Managing your subscription and processing payments through our payment partner.</li>
                <li><strong>Security:</strong> Detecting and preventing fraudulent activity, unauthorized access, and abuse.</li>
                <li><strong>Legal compliance:</strong> Meeting our obligations under applicable laws and regulations.</li>
                <li><strong>Service improvement:</strong> Analyzing anonymized, aggregated usage data to improve platform features — we never use patient data for this.</li>
            </ul>
            <div class="highlight-box">
                <p><strong>We never sell your data.</strong> We do not use your data or your patients' data for advertising, profiling, or any commercial purposes beyond providing the Klinica service.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 4 --}}
        <div class="section" id="lawful-basis">
            <span class="section-number">Section 4</span>
            <h2>Lawful Basis for Processing</h2>
            <p>Under GDPR Article 6, we rely on the following lawful bases:</p>
            <ul>
                <li><strong>Contractual necessity (Art. 6(1)(b)):</strong> Processing required to fulfil our contract with you (providing the platform).</li>
                <li><strong>Legitimate interests (Art. 6(1)(f)):</strong> Security monitoring, fraud prevention, and service improvement.</li>
                <li><strong>Legal obligation (Art. 6(1)(c)):</strong> Compliance with applicable laws (e.g., tax records, data breach reporting).</li>
                <li><strong>Consent (Art. 6(1)(a)):</strong> Where we explicitly request your consent (e.g., marketing communications). You may withdraw consent at any time.</li>
            </ul>
            <p>For patient data processed on behalf of clinics, the clinic bears responsibility for establishing the appropriate lawful basis under healthcare law.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 5 --}}
        <div class="section" id="data-sharing">
            <span class="section-number">Section 5</span>
            <h2>Who We Share Data With</h2>
            <p>We do not sell, rent, or share personal data with third parties for their own purposes. We share data only with:</p>
            <ul>
                <li><strong>Amazon Web Services (AWS), Frankfurt (eu-central-1), Germany:</strong> Cloud infrastructure provider hosting our servers. ISO 27001 certified. All data stays in the EU.</li>
                <li><strong>Paddle.com:</strong> Payment processing for subscriptions (acts as Merchant of Record). We share only billing contact details — no medical or patient data is shared.</li>
                <li><strong>Transactional email service:</strong> For sending system notifications. Only the recipient email address and minimal content are shared.</li>
                <li><strong>Law enforcement or regulators:</strong> Only when legally required and to the minimum extent necessary.</li>
            </ul>
            <p>All third-party processors are bound by data processing agreements and are required to implement adequate security measures.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 6 --}}
        <div class="section" id="security">
            <span class="section-number">Section 6</span>
            <h2>How We Protect Your Data</h2>
            <p>Security is fundamental to our platform design. Key measures include:</p>
            <ul>
                <li><strong>AES-256 encryption at rest</strong> for all stored data, including database backups.</li>
                <li><strong>TLS 1.3 encryption in transit</strong> for all communication between your browser and our servers.</li>
                <li><strong>Strict tenant isolation:</strong> Each clinic has its own dedicated database. There is no shared database between clinics by design.</li>
                <li><strong>ISO 27001 certified infrastructure</strong> hosted entirely in Germany on Amazon Web Services (AWS), Frankfurt region (eu-central-1).</li>
                <li><strong>Bcrypt password hashing</strong> — passwords are never stored in plain text.</li>
                <li><strong>Comprehensive audit logging</strong> for all sensitive operations.</li>
                <li><strong>Regular security reviews</strong> and prompt patching of critical vulnerabilities.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 7 --}}
        <div class="section" id="retention">
            <span class="section-number">Section 7</span>
            <h2>Data Retention</h2>
            <p>We retain personal data for as long as necessary to provide our services and comply with legal obligations:</p>
            <ul>
                <li><strong>Active accounts:</strong> Data is retained for the duration of your subscription.</li>
                <li><strong>After account termination:</strong> You have 30 days to export your data. After that, all personal data is permanently deleted, including from backups.</li>
                <li><strong>Billing records:</strong> Retained for 7 years to comply with tax and accounting obligations.</li>
                <li><strong>Security logs:</strong> Retained for 90 days for security monitoring purposes.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 8 --}}
        <div class="section" id="your-rights">
            <span class="section-number">Section 8</span>
            <h2>Your Rights Under GDPR</h2>
            <p>As an EU resident, you have the following rights over your personal data:</p>

            <div class="rights-grid">
                <div class="right-card">
                    <h3>Right of Access (Art. 15)</h3>
                    <p>Request a copy of the personal data we hold about you.</p>
                </div>
                <div class="right-card">
                    <h3>Right to Rectification (Art. 16)</h3>
                    <p>Request correction of inaccurate or incomplete personal data.</p>
                </div>
                <div class="right-card">
                    <h3>Right to Erasure (Art. 17)</h3>
                    <p>Request deletion of your personal data ("right to be forgotten").</p>
                </div>
                <div class="right-card">
                    <h3>Right to Restrict (Art. 18)</h3>
                    <p>Request that we limit how we use your personal data in certain circumstances.</p>
                </div>
                <div class="right-card">
                    <h3>Right to Portability (Art. 20)</h3>
                    <p>Receive your data in a structured, machine-readable format to transfer to another service.</p>
                </div>
                <div class="right-card">
                    <h3>Right to Object (Art. 21)</h3>
                    <p>Object to processing based on legitimate interests or for direct marketing.</p>
                </div>
            </div>

            <p style="margin-top:20px">To exercise any of these rights, contact us at <a href="mailto:privacy@klinica.space" style="color:var(--green);font-weight:500">privacy@klinica.space</a>. We will respond within <strong>30 days</strong>.</p>
            <p>You also have the right to lodge a complaint with your local data protection supervisory authority at any time.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 9 --}}
        <div class="section" id="cookies">
            <span class="section-number">Section 9</span>
            <h2>Cookies and Tracking</h2>
            <p>Klinica uses essential cookies required for the platform to function (session management, CSRF protection, authentication). We do not use advertising cookies or third-party tracking pixels.</p>
            <ul>
                <li><strong>Session cookies:</strong> Required for authenticated access to the platform. Deleted when you close your browser.</li>
                <li><strong>CSRF tokens:</strong> Security tokens to protect form submissions.</li>
                <li><strong>Preference cookies:</strong> Storing your UI preferences (e.g., language, display settings).</li>
            </ul>
            <p>We do not use Google Analytics, Facebook Pixel, or any advertising tracking technologies on our platform.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 10 --}}
        <div class="section" id="children">
            <span class="section-number">Section 10</span>
            <h2>Children's Privacy</h2>
            <p>The Klinica platform (for clinic administrators) is not intended for use by persons under 18 years of age. We do not knowingly collect personal data from children for account registration purposes.</p>
            <p>Note: Clinics may store records for minor patients (with parental/guardian consent). In such cases, the clinic is the Data Controller and is responsible for ensuring appropriate consent and legal basis under applicable healthcare law.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 11 --}}
        <div class="section" id="changes">
            <span class="section-number">Section 11</span>
            <h2>Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. Material changes will be communicated via:</p>
            <ul>
                <li>Email notification to the address associated with your account.</li>
                <li>A prominent notice within your Klinica dashboard.</li>
            </ul>
            <p>Continued use of Klinica after the effective date of an updated policy constitutes acceptance of the changes. If you do not agree, you may terminate your account before the changes take effect.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 12 --}}
        <div class="section" id="contact">
            <span class="section-number">Section 12</span>
            <h2>Contact Us</h2>
            <p>For any privacy-related questions, data subject requests, or concerns, please contact us:</p>
            <div class="highlight-box">
                <p>
                    <strong>Klinica — Privacy Team</strong><br>
                    Email: <a href="mailto:privacy@klinica.space" style="color:var(--green)">privacy@klinica.space</a><br>
                    Web: <a href="{{ url('/') }}" style="color:var(--green)">klinica.space</a>
                </p>
            </div>
            <p>For urgent data protection matters or to report a security concern, email us directly. We aim to respond to all enquiries within <strong>5 business days</strong>.</p>
            <p>You may also contact the relevant data protection supervisory authority in your country if you believe your rights have been violated.</p>
        </div>

    </div>

    {{-- FOOTER --}}
    <footer>
        <p class="footer-copy">
            &copy; {{ date('Y') }} Klinica. {{ __('central.all_rights_reserved') }} &mdash;
            <a href="{{ url('/') }}">Home</a> &middot;
            <a href="{{ route('terms') }}">{{ __('central.footer_terms') }}</a> &middot;
            <a href="{{ route('privacy') }}">{{ __('central.footer_privacy') }}</a> &middot;
            <a href="{{ route('dpa') }}">{{ __('central.footer_dpa') }}</a> &middot;
            <a href="{{ route('refund-policy') }}">{{ __('central.footer_refund') }}</a> &middot;
            <a href="mailto:privacy@klinica.space">Contact</a>
        </p>
    </footer>

</body>
</html>
