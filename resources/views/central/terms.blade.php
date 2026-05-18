<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service — Klinica</title>
    <meta name="description" content="Klinica Terms of Service — the rules, rights, and responsibilities governing use of the Klinica platform.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/terms') }}">
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

        .warn-box {
            background: rgba(204,78,78,0.06);
            border-left: 3px solid #CC4E4E;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .warn-box p { margin: 0; font-size: 14px; color: var(--navy); }

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
            <a href="{{ url('/') }}" class="nav-back">← Back to home</a>
        </div>
    </nav>

    {{-- HERO --}}
    <div class="legal-hero">
        <div class="legal-hero-badge">Legal Document</div>
        <h1>Terms of Service</h1>
        <p>The rules, rights, and responsibilities governing your use of the Klinica platform.</p>
        <div class="legal-meta">
            <div class="legal-meta-item"><strong>Version:</strong> 1.0</div>
            <div class="legal-meta-item"><strong>Effective:</strong> {{ date('F j, Y') }}</div>
            <div class="legal-meta-item"><strong>Governing law:</strong> European Union</div>
        </div>
    </div>

    <div class="legal-wrap">

        {{-- TABLE OF CONTENTS --}}
        <div class="toc-box">
            <h2>Table of Contents</h2>
            <ol class="toc-list">
                <li><a href="#acceptance">1. Acceptance of Terms</a></li>
                <li><a href="#definitions">2. Definitions</a></li>
                <li><a href="#service">3. Description of Service</a></li>
                <li><a href="#accounts">4. Account Registration and Security</a></li>
                <li><a href="#acceptable-use">5. Acceptable Use Policy</a></li>
                <li><a href="#subscriptions">6. Subscriptions and Billing</a></li>
                <li><a href="#data">7. Data, Privacy, and Security</a></li>
                <li><a href="#ip">8. Intellectual Property</a></li>
                <li><a href="#uptime">9. Availability and Service Levels</a></li>
                <li><a href="#disclaimers">10. Disclaimers</a></li>
                <li><a href="#liability">11. Limitation of Liability</a></li>
                <li><a href="#termination">12. Termination</a></li>
                <li><a href="#governing-law">13. Governing Law and Disputes</a></li>
                <li><a href="#changes">14. Changes to These Terms</a></li>
                <li><a href="#contact">15. Contact</a></li>
            </ol>
        </div>

        {{-- SECTION 1 --}}
        <div class="section" id="acceptance">
            <span class="section-number">Section 1</span>
            <h2>Acceptance of Terms</h2>
            <p>By accessing or using Klinica (<strong>klinica.space</strong>), creating an account, or clicking "Start free trial" or any similar button, you confirm that you have read, understood, and agree to be bound by these Terms of Service ("Terms").</p>
            <p>If you are accepting these Terms on behalf of an organisation (such as a clinic or medical practice), you represent that you have the authority to bind that organisation to these Terms. In that case, "you" and "your" refer to that organisation.</p>
            <div class="warn-box">
                <p><strong>If you do not agree to these Terms, you must not use Klinica.</strong> Continued use of the platform after any update to these Terms constitutes acceptance of the revised version.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 2 --}}
        <div class="section" id="definitions">
            <span class="section-number">Section 2</span>
            <h2>Definitions</h2>
            <ul>
                <li><strong>"Klinica"</strong> — the platform operated at klinica.space, including all software, services, dashboards, and APIs provided thereunder.</li>
                <li><strong>"We", "us", "our"</strong> — the operator of Klinica.</li>
                <li><strong>"You", "Customer"</strong> — the clinic, healthcare professional, or organisation subscribing to Klinica.</li>
                <li><strong>"User"</strong> — any individual (including clinic staff and employees) who accesses Klinica under your account.</li>
                <li><strong>"Tenant"</strong> — an isolated clinic account within the Klinica multi-tenant infrastructure, identified by a unique subdomain.</li>
                <li><strong>"Patient Data"</strong> — personal data relating to patients entered into Klinica by you or your Users.</li>
                <li><strong>"Subscription"</strong> — your paid plan granting access to Klinica features.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 3 --}}
        <div class="section" id="service">
            <span class="section-number">Section 3</span>
            <h2>Description of Service</h2>
            <p>Klinica is a Software-as-a-Service (SaaS) clinic management platform that provides:</p>
            <ul>
                <li>A branded online booking page at a unique subdomain (e.g., <em>yourclinic.klinica.space</em>).</li>
                <li>Patient management tools including records, health notes, and appointment history.</li>
                <li>Scheduling tools including working hours, service configuration, and employee management.</li>
                <li>An administrative dashboard with analytics and reporting.</li>
                <li>Role-based access control for clinic staff.</li>
                <li>Audit logging and GDPR data export tools.</li>
            </ul>
            <p>Klinica is a general-purpose clinic management tool. It is <strong>not</strong> a regulated medical device, electronic health record system (EHR/EMR) under any specific healthcare regulation, or a system designed for emergency medical use. You remain responsible for ensuring Klinica is appropriate for your specific regulatory environment.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 4 --}}
        <div class="section" id="accounts">
            <span class="section-number">Section 4</span>
            <h2>Account Registration and Security</h2>
            <p><strong>4.1 — Registration.</strong> To use Klinica, you must register an account by providing accurate and complete information including your clinic name, a chosen subdomain, and administrator contact details. You agree to keep this information current.</p>
            <p><strong>4.2 — Subdomain.</strong> Your chosen subdomain is unique and cannot be transferred to another account. We reserve the right to reject or reclaim subdomains that are misleading, infringe third-party rights, or violate our Acceptable Use Policy.</p>
            <p><strong>4.3 — Account security.</strong> You are responsible for:</p>
            <ul>
                <li>Maintaining the confidentiality of your login credentials.</li>
                <li>All activity that occurs under your account, including actions taken by your staff and employees.</li>
                <li>Promptly notifying us at <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a> if you suspect unauthorised access.</li>
            </ul>
            <p><strong>4.4 — One account per clinic.</strong> Each registered subdomain constitutes one tenant account. Creating multiple accounts to circumvent plan limits or extend a free trial is prohibited and may result in immediate termination of all associated accounts.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 5 --}}
        <div class="section" id="acceptable-use">
            <span class="section-number">Section 5</span>
            <h2>Acceptable Use Policy</h2>
            <p>You agree to use Klinica only for lawful purposes and in a manner consistent with these Terms. The following are expressly prohibited:</p>
            <ul>
                <li>Uploading, storing, or transmitting content that is unlawful, harmful, defamatory, obscene, or that infringes any third-party rights.</li>
                <li>Using Klinica to process patient data without the appropriate legal basis under applicable data protection law (including GDPR).</li>
                <li>Attempting to gain unauthorised access to any part of the platform, another tenant's data, or Klinica's infrastructure.</li>
                <li>Reverse engineering, decompiling, or disassembling any part of the Klinica software.</li>
                <li>Reselling, sublicensing, or otherwise commercialising access to Klinica without our express written consent.</li>
                <li>Using automated scripts, bots, or scrapers to access the platform or extract data at scale.</li>
                <li>Transmitting malware, viruses, or any code designed to disrupt, damage, or gain unauthorised access to systems.</li>
                <li>Violating any applicable laws or regulations, including healthcare data protection laws applicable to your jurisdiction.</li>
            </ul>
            <p>We reserve the right to suspend or terminate accounts that violate this policy, with or without prior notice, at our sole discretion.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 6 --}}
        <div class="section" id="subscriptions">
            <span class="section-number">Section 6</span>
            <h2>Subscriptions and Billing</h2>
            <p><strong>6.1 — Free trial.</strong> New accounts include a 14-day free trial with no credit card required. At the end of the trial, your account will become inactive unless you upgrade to a paid subscription.</p>
            <p><strong>6.2 — Paid subscriptions.</strong> Paid subscriptions are billed monthly in advance. By subscribing, you authorise us (or our payment processor) to charge your payment method on a recurring basis until you cancel.</p>
            <p><strong>6.3 — Plan limits.</strong> Each plan includes specific limits on the number of patients, employees, and monthly bookings. Exceeding these limits may restrict your ability to add further records until your plan is upgraded.</p>
            <p><strong>6.4 — Pricing changes.</strong> We reserve the right to modify subscription pricing. We will provide at least <strong>30 days' written notice</strong> via email before any price increase takes effect. Continued use of the service after the effective date constitutes acceptance of the new pricing.</p>
            <p><strong>6.5 — Refunds.</strong> All subscription payments are subject to our <a href="{{ route('refund-policy') }}" style="color:var(--green)">Refund Policy</a>, which is incorporated into these Terms by reference.</p>
            <p><strong>6.6 — Taxes.</strong> Subscription fees are exclusive of any applicable taxes (including VAT). You are responsible for paying all taxes applicable to your subscription in your jurisdiction.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 7 --}}
        <div class="section" id="data">
            <span class="section-number">Section 7</span>
            <h2>Data, Privacy, and Security</h2>
            <p><strong>7.1 — Your data.</strong> You retain full ownership of all data you enter into Klinica, including Patient Data. We do not sell, rent, or use your data for any purpose other than providing and improving the Klinica service.</p>
            <p><strong>7.2 — Data processing.</strong> For Patient Data, you act as the Data Controller and we act as the Data Processor under GDPR. Our obligations as Processor are governed by our <a href="{{ route('dpa') }}" style="color:var(--green)">Data Processing Agreement</a> (DPA), which forms part of these Terms.</p>
            <p><strong>7.3 — Privacy Policy.</strong> Our collection and use of personal data relating to your account registration is governed by our <a href="{{ route('privacy') }}" style="color:var(--green)">Privacy Policy</a>.</p>
            <p><strong>7.4 — Your data protection obligations.</strong> You are responsible for ensuring that your use of Klinica to process Patient Data complies with all applicable data protection laws in your jurisdiction, including obtaining appropriate consent or establishing a lawful basis for processing under GDPR.</p>
            <p><strong>7.5 — Data isolation.</strong> Each tenant account operates in an isolated database environment. We implement appropriate technical and organisational measures to prevent cross-tenant data access.</p>
            <p><strong>7.6 — Data export and deletion.</strong> You may export your data at any time from within your dashboard. Upon account termination, you have 30 days to export your data before it is permanently deleted. See our DPA for full retention and deletion terms.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 8 --}}
        <div class="section" id="ip">
            <span class="section-number">Section 8</span>
            <h2>Intellectual Property</h2>
            <p><strong>8.1 — Klinica IP.</strong> All intellectual property rights in the Klinica platform — including the software, design, trademarks, and documentation — are and remain the exclusive property of Klinica and its licensors. Nothing in these Terms transfers any ownership rights to you.</p>
            <p><strong>8.2 — Licence to use.</strong> Subject to your compliance with these Terms and payment of applicable fees, we grant you a limited, non-exclusive, non-transferable, revocable licence to access and use Klinica for your internal business purposes during your subscription period.</p>
            <p><strong>8.3 — Your content.</strong> You grant us a limited licence to host, store, and process your data solely for the purpose of providing the Klinica service to you. We claim no ownership over your data.</p>
            <p><strong>8.4 — Feedback.</strong> If you provide us with feedback or suggestions about Klinica, you grant us the right to use that feedback without restriction or compensation to you.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 9 --}}
        <div class="section" id="uptime">
            <span class="section-number">Section 9</span>
            <h2>Availability and Service Levels</h2>
            <p>We aim to maintain high availability of the Klinica platform but do not guarantee uninterrupted access. Downtime may occur due to:</p>
            <ul>
                <li>Scheduled maintenance (we will provide advance notice where practicable).</li>
                <li>Unplanned outages caused by infrastructure failures, security incidents, or third-party service disruptions.</li>
                <li>Force majeure events outside our reasonable control.</li>
            </ul>
            <p>We will make reasonable efforts to restore service promptly in the event of an outage. We are not liable for any losses resulting from service unavailability except where expressly required by applicable law.</p>
            <div class="highlight-box">
                <p><strong>Status updates:</strong> In the event of a major outage, we will communicate updates via email to registered account holders.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 10 --}}
        <div class="section" id="disclaimers">
            <span class="section-number">Section 10</span>
            <h2>Disclaimers</h2>
            <p>Klinica is provided <strong>"as is"</strong> and <strong>"as available"</strong>, without warranties of any kind, express or implied, to the fullest extent permitted by applicable law. We specifically disclaim:</p>
            <ul>
                <li>Any implied warranties of merchantability, fitness for a particular purpose, or non-infringement.</li>
                <li>Any warranty that the service will be error-free, uninterrupted, or free from vulnerabilities.</li>
                <li>Any warranty regarding the accuracy, completeness, or reliability of any content or data processed through the platform.</li>
            </ul>
            <p><strong>Klinica is not a substitute for professional medical judgement.</strong> Clinical decisions, diagnoses, and treatments remain the exclusive responsibility of qualified healthcare professionals. Klinica is an administrative tool only.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 11 --}}
        <div class="section" id="liability">
            <span class="section-number">Section 11</span>
            <h2>Limitation of Liability</h2>
            <p>To the fullest extent permitted by applicable law:</p>
            <ul>
                <li>We will not be liable for any indirect, incidental, special, consequential, or punitive damages, including loss of profits, loss of data, business interruption, or loss of goodwill.</li>
                <li>Our total aggregate liability to you for all claims arising from or relating to these Terms or your use of Klinica shall not exceed the <strong>total subscription fees paid by you in the 12 months immediately preceding the event giving rise to the claim</strong>.</li>
            </ul>
            <p>These limitations apply regardless of the legal theory on which the claim is based (contract, tort, strict liability, or otherwise), even if we have been advised of the possibility of such damages.</p>
            <p>Nothing in these Terms limits or excludes liability that cannot be excluded under applicable law, including liability for death or personal injury caused by negligence, or for fraud or fraudulent misrepresentation.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 12 --}}
        <div class="section" id="termination">
            <span class="section-number">Section 12</span>
            <h2>Termination</h2>
            <p><strong>12.1 — By you.</strong> You may cancel your subscription at any time from within your dashboard. Cancellation halts future billing but does not entitle you to a refund for the current billing period. See our <a href="{{ route('refund-policy') }}" style="color:var(--green)">Refund Policy</a> for full details.</p>
            <p><strong>12.2 — By us.</strong> We reserve the right to suspend or terminate your account immediately and without notice if:</p>
            <ul>
                <li>You materially breach these Terms and fail to remedy the breach within 7 days of written notice.</li>
                <li>You violate our Acceptable Use Policy (Section 5).</li>
                <li>Your subscription payment fails and is not resolved within a reasonable period.</li>
                <li>We are required to do so by applicable law or a competent authority.</li>
            </ul>
            <p><strong>12.3 — Effect of termination.</strong> Upon termination:</p>
            <ul>
                <li>Your access to the platform ceases immediately (or at the end of the paid billing period, for voluntary cancellations).</li>
                <li>You have 30 days to export your data before permanent deletion.</li>
                <li>Sections 7, 8, 10, 11, and 13 of these Terms survive termination.</li>
            </ul>
        </div>

        <div class="divider"></div>

        {{-- SECTION 13 --}}
        <div class="section" id="governing-law">
            <span class="section-number">Section 13</span>
            <h2>Governing Law and Disputes</h2>
            <p>These Terms are governed by and construed in accordance with the laws of the European Union and applicable national law. Any disputes arising from or relating to these Terms shall be subject to the exclusive jurisdiction of the competent courts.</p>
            <p>Before initiating any legal proceedings, you agree to first attempt to resolve any dispute informally by contacting us at <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a>. We will make reasonable efforts to resolve disputes within 30 days of receiving written notice.</p>
            <p>Nothing in this section prevents either party from seeking urgent injunctive or other equitable relief from a court of competent jurisdiction where necessary to protect their rights.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 14 --}}
        <div class="section" id="changes">
            <span class="section-number">Section 14</span>
            <h2>Changes to These Terms</h2>
            <p>We may update these Terms from time to time to reflect changes in our services, applicable law, or business practices. When we make material changes, we will:</p>
            <ul>
                <li>Send an email notification to your registered address at least <strong>14 days before the changes take effect</strong>.</li>
                <li>Display a prominent notice within your Klinica dashboard.</li>
                <li>Update the "Effective" date at the top of this page.</li>
            </ul>
            <p>Your continued use of Klinica after the effective date of any updated Terms constitutes your acceptance. If you do not agree to the updated Terms, you must stop using the service and cancel your subscription before the changes take effect.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 15 --}}
        <div class="section" id="contact">
            <span class="section-number">Section 15</span>
            <h2>Contact</h2>
            <p>For questions about these Terms, billing disputes, or to report a violation of our Acceptable Use Policy, please contact us:</p>
            <div class="highlight-box">
                <p>
                    <strong>Klinica — Legal & Support</strong><br>
                    Email: <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a><br>
                    Privacy matters: <a href="mailto:privacy@klinica.space" style="color:var(--green)">privacy@klinica.space</a><br>
                    Web: <a href="{{ url('/') }}" style="color:var(--green)">klinica.space</a>
                </p>
            </div>
            <p>We aim to respond to all legal and billing enquiries within <strong>5 business days</strong>.</p>
        </div>

    </div>

    {{-- FOOTER --}}
    <footer>
        <p class="footer-copy">
            &copy; {{ date('Y') }} Klinica. All rights reserved. &mdash;
            <a href="{{ url('/') }}">Home</a> &middot;
            <a href="{{ route('terms') }}">Terms of Service</a> &middot;
            <a href="{{ route('privacy') }}">Privacy Policy</a> &middot;
            <a href="{{ route('dpa') }}">DPA</a> &middot;
            <a href="{{ route('refund-policy') }}">Refund Policy</a> &middot;
            <a href="mailto:support@klinica.space">Contact</a>
        </p>
    </footer>

</body>
</html>
