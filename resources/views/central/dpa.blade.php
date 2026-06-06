<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Processing Agreement — Klinica</title>
    <meta name="description" content="Klinica Data Processing Agreement (DPA) in accordance with Article 28 of the GDPR.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/dpa') }}">
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
            display: flex; align-items: center; gap: 6px;
            transition: color 0.2s;
        }
        .nav-back:hover { color: var(--green); }

        /* HERO */
        .legal-hero {
            background: var(--navy);
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
            max-width: 540px; margin: 0 auto;
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
            max-width: 860px;
            margin: 0 auto;
            padding: 56px 40px 80px;
        }

        .toc-box {
            background: var(--white);
            border: 1px solid var(--beige);
            border-radius: 12px;
            padding: 28px 32px;
            margin-bottom: 48px;
        }
        .toc-box h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 15px; font-weight: 700;
            color: var(--navy); margin-bottom: 16px;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .toc-list { list-style: none; }
        .toc-list li { margin-bottom: 8px; }
        .toc-list a {
            font-size: 14px; color: var(--green); text-decoration: none;
            font-weight: 500;
        }
        .toc-list a:hover { text-decoration: underline; }

        .section {
            margin-bottom: 52px;
            scroll-margin-top: 80px;
        }
        .section-number {
            display: inline-block;
            font-size: 11px; font-weight: 700;
            color: var(--green); background: rgba(47,128,130,0.08);
            padding: 3px 10px; border-radius: 4px;
            text-transform: uppercase; letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
        .section h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 22px; font-weight: 700;
            color: var(--navy); margin-bottom: 16px; line-height: 1.3;
        }
        .section p {
            font-size: 15px; color: var(--slate);
            margin-bottom: 14px;
        }
        .section ul, .section ol {
            margin: 12px 0 14px 20px; color: var(--slate);
        }
        .section li {
            font-size: 15px; margin-bottom: 8px; line-height: 1.65;
        }
        .section strong { color: var(--navy); font-weight: 600; }

        .highlight-box {
            background: rgba(47,128,130,0.07);
            border-left: 3px solid var(--green);
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .highlight-box p { margin: 0; font-size: 14px; color: var(--navy); }

        .tom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 20px 0;
        }
        @media (max-width: 640px) { .tom-grid { grid-template-columns: 1fr; } }

        .tom-card {
            background: var(--white);
            border: 1px solid var(--beige);
            border-radius: 10px;
            padding: 20px;
        }
        .tom-card-icon { font-size: 22px; margin-bottom: 8px; }
        .tom-card h3 {
            font-family: 'Manrope', sans-serif;
            font-size: 14px; font-weight: 700;
            color: var(--navy); margin-bottom: 8px;
        }
        .tom-card p {
            font-size: 13px; color: var(--slate);
            margin: 0; line-height: 1.6;
        }

        .divider { height: 1px; background: var(--beige); margin: 48px 0; }

        /* FOOTER */
        footer {
            background: var(--navy);
            padding: 32px 40px;
            text-align: center;
        }
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
        <h1>Data Processing Agreement (DPA)</h1>
        <p>In accordance with Article 28 of the EU General Data Protection Regulation (GDPR 2016/679)</p>
        <div class="legal-meta">
            <div class="legal-meta-item"><strong>Version:</strong> 1.0</div>
            <div class="legal-meta-item"><strong>{{ __('central.effective_label') }}</strong> {{ date('F j, Y') }}</div>
            <div class="legal-meta-item"><strong>Jurisdiction:</strong> European Union</div>
            <div class="legal-meta-item"><strong>Regulation:</strong> GDPR Article 28</div>
        </div>
    </div>

    <div class="legal-wrap">

        {{-- TABLE OF CONTENTS --}}
        <div class="toc-box">
            <h2>Table of Contents</h2>
            <ol class="toc-list">
                <li><a href="#parties">1. Parties and Definitions</a></li>
                <li><a href="#subject">2. Subject Matter and Duration</a></li>
                <li><a href="#obligations">3. Obligations of Klinica (Processor)</a></li>
                <li><a href="#controller">4. Obligations of the Data Controller</a></li>
                <li><a href="#subprocessors">5. Sub-Processors</a></li>
                <li><a href="#tom">6. Technical & Organizational Security Measures</a></li>
                <li><a href="#rights">7. Data Subject Rights</a></li>
                <li><a href="#breach">8. Data Breach Notification</a></li>
                <li><a href="#transfers">9. International Data Transfers</a></li>
                <li><a href="#deletion">10. Data Deletion and Return</a></li>
                <li><a href="#liability">11. Liability and Indemnification</a></li>
                <li><a href="#contact">12. Contact and DPO</a></li>
            </ol>
        </div>

        {{-- SECTION 1 --}}
        <div class="section" id="parties">
            <span class="section-number">Article 1</span>
            <h2>Parties and Definitions</h2>
            <p>This Data Processing Agreement ("DPA") is entered into between:</p>
            <ul>
                <li><strong>Data Controller:</strong> The clinic or healthcare professional ("you", "Controller") who has subscribed to Klinica and processes personal data of their patients.</li>
                <li><strong>Data Processor:</strong> Klinica SAS / Klinica (operator of the <em>klinica.space</em> platform), acting as the processor of personal data on behalf of the Controller.</li>
            </ul>
            <p>For the purpose of this DPA, the following definitions apply:</p>
            <ul>
                <li><strong>"Personal Data"</strong> — Any information relating to an identified or identifiable natural person, as defined in Article 4(1) GDPR.</li>
                <li><strong>"Processing"</strong> — Any operation performed on personal data, including storage, retrieval, use, disclosure, or erasure.</li>
                <li><strong>"Data Subject"</strong> — Patients, employees, or any natural person whose personal data is processed through the Klinica platform.</li>
                <li><strong>"Services"</strong> — The clinic management platform, online booking system, patient management, and scheduling tools provided by Klinica.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 2 --}}
        <div class="section" id="subject">
            <span class="section-number">Article 2</span>
            <h2>Subject Matter and Duration</h2>
            <p>Klinica processes personal data solely to provide the contracted Services to the Controller. The categories of data processed include:</p>
            <ul>
                <li>Patient identifiers (name, date of birth, contact details)</li>
                <li>Appointment and scheduling data</li>
                <li>Medical notes and clinical records entered by the Controller</li>
                <li>Authentication credentials (hashed passwords) of clinic staff</li>
                <li>Billing contact information (name, email) — payment details are not stored and are handled exclusively by Paddle.com (our payment processor)</li>
            </ul>
            <p>The DPA is effective for the duration of your active subscription to Klinica. Upon termination, data retention and deletion obligations as described in Article 10 apply.</p>
            <div class="highlight-box">
                <p><strong>Principle of data minimization:</strong> Klinica only processes personal data that is strictly necessary to deliver the Services. We never sell, rent, or use your patient data for advertising or profiling purposes.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 3 --}}
        <div class="section" id="obligations">
            <span class="section-number">Article 3</span>
            <h2>Obligations of Klinica (Processor)</h2>
            <p>As your data processor, Klinica commits to:</p>
            <ol>
                <li>Process personal data only on documented instructions from the Controller (i.e., using the platform as intended).</li>
                <li>Ensure that all personnel with access to personal data are bound by confidentiality obligations.</li>
                <li>Implement and maintain appropriate technical and organizational security measures (see Article 6).</li>
                <li>Assist the Controller in fulfilling obligations regarding data subject rights (Article 7).</li>
                <li>Delete or return all personal data upon termination of services (Article 10).</li>
                <li>Provide all information necessary to demonstrate compliance with Article 28 GDPR obligations.</li>
                <li>Notify the Controller without undue delay upon becoming aware of a personal data breach (Article 8).</li>
                <li>Not transfer personal data to third parties except authorized sub-processors listed in Article 5.</li>
            </ol>
        </div>

        <div class="divider"></div>

        {{-- SECTION 4 --}}
        <div class="section" id="controller">
            <span class="section-number">Article 4</span>
            <h2>Obligations of the Data Controller</h2>
            <p>By using Klinica, you as the Data Controller confirm that:</p>
            <ol>
                <li>You have a lawful basis for processing your patients' personal data (e.g., consent, legitimate interest, or legal obligation under applicable healthcare law).</li>
                <li>You will provide a privacy notice to data subjects (your patients) that discloses the use of Klinica as a data processor.</li>
                <li>You are responsible for the accuracy and lawfulness of the personal data you enter into the platform.</li>
                <li>You will promptly inform Klinica of any changes to processing instructions that may affect our compliance obligations.</li>
                <li>You are responsible for configuring appropriate access controls for your clinic staff.</li>
            </ol>
        </div>

        <div class="divider"></div>

        {{-- SECTION 5 --}}
        <div class="section" id="subprocessors">
            <span class="section-number">Article 5</span>
            <h2>Sub-Processors</h2>
            <p>Klinica uses the following authorized sub-processors. All sub-processors are required to meet equivalent data protection standards:</p>

            <table style="width:100%;border-collapse:collapse;font-size:14px;margin:20px 0">
                <thead>
                    <tr style="background:var(--cream)">
                        <th style="padding:12px 16px;text-align:left;font-weight:700;color:var(--navy);border-bottom:2px solid var(--beige)">Sub-Processor</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:700;color:var(--navy);border-bottom:2px solid var(--beige)">Purpose</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:700;color:var(--navy);border-bottom:2px solid var(--beige)">Location</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:700;color:var(--navy);border-bottom:2px solid var(--beige)">Certification</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid var(--beige)">
                        <td style="padding:12px 16px;color:var(--navy);font-weight:600">Hetzner Online GmbH</td>
                        <td style="padding:12px 16px;color:var(--slate)">Cloud infrastructure & data hosting</td>
                        <td style="padding:12px 16px;color:var(--slate)">Germany / EU</td>
                        <td style="padding:12px 16px;color:var(--slate)">ISO 27001, ISO 9001, GDPR compliant</td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--beige)">
                        <td style="padding:12px 16px;color:var(--navy);font-weight:600">Paddle.com</td>
                        <td style="padding:12px 16px;color:var(--slate)">Subscription billing & payments (Merchant of Record)</td>
                        <td style="padding:12px 16px;color:var(--slate)">UK / EU</td>
                        <td style="padding:12px 16px;color:var(--slate)">PCI-DSS Level 1, GDPR compliant, UK GDPR</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;color:var(--navy);font-weight:600">Transactional Email Provider</td>
                        <td style="padding:12px 16px;color:var(--slate)">System notifications & reminders</td>
                        <td style="padding:12px 16px;color:var(--slate)">EU</td>
                        <td style="padding:12px 16px;color:var(--slate)">GDPR compliant</td>
                    </tr>
                </tbody>
            </table>

            <p>You will be notified at least 14 days in advance of any changes to the sub-processor list. You have the right to object to the addition of new sub-processors.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 6 --}}
        <div class="section" id="tom">
            <span class="section-number">Article 6</span>
            <h2>Technical &amp; Organizational Security Measures</h2>
            <p>Klinica implements the following technical and organizational measures (TOMs) in accordance with Article 32 GDPR to ensure an appropriate level of security:</p>

            <div class="tom-grid">
                <div class="tom-card">
                    <div class="tom-card-icon">01</div>
                    <h3>Data Encryption</h3>
                    <p>All data is encrypted at rest using <strong>AES-256</strong> encryption. All data in transit is protected using <strong>TLS 1.3</strong>. Database backups are also encrypted.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">02</div>
                    <h3>Tenant Isolation</h3>
                    <p>Each clinic operates in a <strong>completely separate, isolated database</strong>. There is zero possibility of cross-tenant data leakage by design.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">03</div>
                    <h3>ISO 27001 Certified Infrastructure</h3>
                    <p>All data is hosted on <strong>Hetzner Online GmbH</strong> servers located in Germany. Hetzner is certified under ISO 27001 and ISO 9001 and is fully GDPR compliant.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">04</div>
                    <h3>Access Control</h3>
                    <p>Role-based access control (RBAC) is enforced. Staff accounts are scoped to their clinic only. Passwords are hashed using <strong>bcrypt</strong>.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">05</div>
                    <h3>Audit Logging</h3>
                    <p>All sensitive operations (patient record access, modifications, deletions) are logged with timestamps and user identifiers for accountability.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">06</div>
                    <h3>Automated Backups</h3>
                    <p>Databases are backed up automatically. Backups are retained for 30 days and are stored in an encrypted format in a separate geographic location.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">07</div>
                    <h3>Vulnerability Management</h3>
                    <p>Dependencies are regularly updated. Security patches are applied within 48 hours of disclosure for critical vulnerabilities. We follow responsible disclosure.</p>
                </div>
                <div class="tom-card">
                    <div class="tom-card-icon">08</div>
                    <h3>Penetration Testing</h3>
                    <p>The platform undergoes periodic security assessments. All identified vulnerabilities are tracked and remediated according to severity.</p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 7 --}}
        <div class="section" id="rights">
            <span class="section-number">Article 7</span>
            <h2>Data Subject Rights</h2>
            <p>Klinica will assist the Controller in responding to data subject requests under Articles 15–22 GDPR, including:</p>
            <ul>
                <li><strong>Right of Access (Art. 15):</strong> Patients may request access to their personal data held in Klinica.</li>
                <li><strong>Right to Rectification (Art. 16):</strong> Inaccurate data may be corrected.</li>
                <li><strong>Right to Erasure (Art. 17):</strong> Data may be deleted upon request, subject to legal retention obligations applicable to healthcare records.</li>
                <li><strong>Right to Portability (Art. 20):</strong> Patient data can be exported in structured, machine-readable formats.</li>
                <li><strong>Right to Object (Art. 21):</strong> Data subjects may object to certain processing activities.</li>
            </ul>
            <p>As the Controller, you remain the primary point of contact for data subject requests. Klinica will support you technically in fulfilling these obligations.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 8 --}}
        <div class="section" id="breach">
            <span class="section-number">Article 8</span>
            <h2>Data Breach Notification</h2>
            <p>In the event of a personal data breach, Klinica will:</p>
            <ol>
                <li>Notify the affected Controller <strong>within 72 hours</strong> of becoming aware of the breach, without undue delay.</li>
                <li>Provide details including: the nature of the breach, categories and approximate number of data subjects and records affected, likely consequences, and measures taken or proposed to address the breach.</li>
                <li>Cooperate fully with the Controller's obligations to notify the competent supervisory authority under Article 33 GDPR.</li>
            </ol>
            <p>Notifications will be sent to the email address associated with your Klinica account.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 9 --}}
        <div class="section" id="transfers">
            <span class="section-number">Article 9</span>
            <h2>International Data Transfers</h2>
            <p>Personal data is primarily stored and processed within the European Union (Germany). In cases where sub-processors are based outside the EEA (such as certain payment processors), appropriate safeguards are in place:</p>
            <ul>
                <li>Standard Contractual Clauses (SCCs) as adopted by the European Commission.</li>
                <li>Adequacy decisions where applicable.</li>
                <li>Binding Corporate Rules where relevant.</li>
            </ul>
            <div class="highlight-box">
                <p><strong>Primary data residency: Germany (EU).</strong> Your patient data never leaves the European Union unless explicitly required by a sub-processor with appropriate SCCs in place.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 10 --}}
        <div class="section" id="deletion">
            <span class="section-number">Article 10</span>
            <h2>Data Deletion and Return</h2>
            <p>Upon termination of your Klinica subscription:</p>
            <ol>
                <li>You have <strong>30 days</strong> from the termination date to export your data in full.</li>
                <li>After this 30-day window, all personal data associated with your clinic will be permanently deleted from our systems, including backups.</li>
                <li>Klinica will provide a written confirmation of deletion upon request.</li>
                <li>Certain anonymized or aggregated statistical data (which cannot be used to identify any individual) may be retained for platform improvement purposes.</li>
            </ol>
        </div>

        <div class="divider"></div>

        {{-- SECTION 11 --}}
        <div class="section" id="liability">
            <span class="section-number">Article 11</span>
            <h2>Liability and Indemnification</h2>
            <p>Each party is responsible for ensuring their own compliance with applicable data protection laws. In the event of a data protection violation:</p>
            <ul>
                <li>If the violation is attributable to the Controller's instructions or failure to comply with their obligations, the Controller bears the associated liability.</li>
                <li>If the violation is attributable to Klinica's failure to fulfil its obligations as Processor, Klinica bears the associated liability.</li>
                <li>Klinica's maximum liability is limited to the total fees paid by the Controller in the 12 months preceding the event giving rise to the claim.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 12 --}}
        <div class="section" id="contact">
            <span class="section-number">Article 12</span>
            <h2>Contact and Data Protection Officer</h2>
            <p>For any questions related to this DPA, data protection, or to exercise your rights, please contact:</p>
            <div class="highlight-box">
                <p>
                    <strong>Klinica — Data Protection Enquiries</strong><br>
                    Email: <a href="mailto:privacy@klinica.space" style="color:var(--green)">privacy@klinica.space</a><br>
                    Web: <a href="{{ url('/') }}" style="color:var(--green)">klinica.space</a>
                </p>
            </div>
            <p>We aim to respond to all data protection enquiries within <strong>5 business days</strong>.</p>
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
