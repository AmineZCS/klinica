<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy — Klinica</title>
    <meta name="description" content="Klinica Refund Policy — subscription billing terms, refund eligibility, and cancellation rules.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/refund-policy') }}">
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

        .warn-box {
            background: rgba(204,78,78,0.06);
            border-left: 3px solid #CC4E4E;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .warn-box p { margin: 0; font-size: 14px; color: var(--navy); }

        .eligibility-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 24px 0;
        }
        @media (max-width: 640px) { .eligibility-grid { grid-template-columns: 1fr; } }

        .elig-card {
            border-radius: 10px;
            padding: 20px 22px;
            border: 1px solid var(--beige);
        }
        .elig-card.yes { background: rgba(141,170,145,0.08); border-color: rgba(141,170,145,0.4); }
        .elig-card.no  { background: rgba(204,78,78,0.05);   border-color: rgba(204,78,78,0.25); }
        .elig-card h3 {
            font-family: 'Manrope', sans-serif;
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.7px;
            margin-bottom: 12px;
        }
        .elig-card.yes h3 { color: #2F7040; }
        .elig-card.no  h3 { color: #CC4E4E; }
        .elig-card ul { margin: 0; padding-left: 16px; }
        .elig-card li { font-size: 13px; color: var(--slate); margin-bottom: 6px; line-height: 1.55; }

        .step-list { list-style: none; padding: 0; margin: 16px 0; }
        .step-list li {
            display: flex; gap: 16px;
            font-size: 15px; color: var(--slate);
            margin-bottom: 16px; line-height: 1.6;
        }
        .step-num {
            flex-shrink: 0;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--green); color: #fff;
            font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin-top: 2px;
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
        <h1>{{ __('central.footer_refund') }}</h1>
        <p>Our billing terms, refund eligibility conditions, and cancellation rules for Klinica subscriptions.</p>
        <div class="legal-meta">
            <div class="legal-meta-item"><strong>Version:</strong> 1.0</div>
            <div class="legal-meta-item"><strong>{{ __('central.effective_label') }}</strong> {{ date('F j, Y') }}</div>
            <div class="legal-meta-item"><strong>Applies to:</strong> All paid subscriptions</div>
        </div>
    </div>

    <div class="legal-wrap">

        {{-- TABLE OF CONTENTS --}}
        <div class="toc-box">
            <h2>Table of Contents</h2>
            <ol class="toc-list">
                <li><a href="#overview">1. Overview</a></li>
                <li><a href="#digital-delivery">2. Immediate Digital Provisioning</a></li>
                <li><a href="#no-refund">3. General No-Refund Policy</a></li>
                <li><a href="#eligible-refunds">4. Eligible Refund Circumstances</a></li>
                <li><a href="#how-to-request">5. How to Request a Refund</a></li>
                <li><a href="#cancellation">6. Cancellations and Auto-Renewal</a></li>
                <li><a href="#plan-changes">7. Plan Upgrades and Downgrades</a></li>
                <li><a href="#trial">8. Free Trial</a></li>
                <li><a href="#chargebacks">9. Chargebacks and Payment Disputes</a></li>
                <li><a href="#contact">10. Contact</a></li>
            </ol>
        </div>

        {{-- SECTION 1 --}}
        <div class="section" id="overview">
            <span class="section-number">Section 1</span>
            <h2>Overview</h2>
            <p>This Refund Policy governs all subscription purchases made on the Klinica platform (<strong>klinica.space</strong>). By subscribing to any paid plan, you confirm that you have read and agreed to these terms.</p>
            <p>Klinica offers subscription-based access to a digital SaaS platform. Because the service is provisioned instantly upon payment and access is granted immediately, our refund policy reflects the digital and time-sensitive nature of the product.</p>
            <div class="highlight-box">
                <p><strong>Free 14-day trial available.</strong> All new accounts begin with a 14-day free trial with no credit card required. We encourage you to evaluate the platform fully before upgrading to a paid subscription.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 2 --}}
        <div class="section" id="digital-delivery">
            <span class="section-number">Section 2</span>
            <h2>Immediate Digital Provisioning</h2>
            <p>When you upgrade to a paid subscription, the following occurs <strong>immediately and automatically</strong> upon successful payment:</p>
            <ul>
                <li>Your account plan is upgraded and plan limits are raised instantly.</li>
                <li>Full access to all features included in your chosen plan is granted.</li>
                <li>Your subscription period begins immediately from the date of payment.</li>
                <li>A subscription confirmation is sent to your registered email address.</li>
            </ul>
            <p>Because digital access is delivered immediately and cannot be "returned" in the way a physical product can, subscription fees are non-refundable except as explicitly described in Section 4.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 3 --}}
        <div class="section" id="no-refund">
            <span class="section-number">Section 3</span>
            <h2>General No-Refund Policy</h2>
            <p>The following circumstances do <strong>not</strong> qualify for a refund under any conditions:</p>

            <div class="warn-box">
                <p><strong>All subscription sales are final.</strong> Refunds are not issued for change of mind, accidental upgrades, partial use of the subscription period, or dissatisfaction with features that were accurately described at the time of purchase.</p>
            </div>

            <div class="eligibility-grid">
                <div class="elig-card no">
                    <h3>✕ Not Eligible for Refund</h3>
                    <ul>
                        <li>Change of mind after upgrading</li>
                        <li>Deciding not to use the platform</li>
                        <li>Partial usage of the subscription period</li>
                        <li>Forgetting to cancel before renewal</li>
                        <li>Switching to a competitor service</li>
                        <li>Features not meeting personal expectations (where features were accurately described)</li>
                        <li>Failure to export data before account expiry</li>
                        <li>Violations of our Terms of Service leading to account suspension</li>
                        <li>Subscriptions cancelled after the current billing period has started</li>
                    </ul>
                </div>
                <div class="elig-card yes">
                    <h3>✓ May Be Eligible for Refund</h3>
                    <ul>
                        <li>Verified major technical defects preventing all system access (see Section 4)</li>
                        <li>Duplicate charges caused by a billing system error</li>
                        <li>Charge made after a confirmed cancellation was already processed</li>
                        <li>Unauthorized charge confirmed by our billing team</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 4 --}}
        <div class="section" id="eligible-refunds">
            <span class="section-number">Section 4</span>
            <h2>Eligible Refund Circumstances</h2>

            <p><strong>4.1 — Major Technical Defect (7-Day Window)</strong></p>
            <p>A refund may be granted if <strong>all three</strong> of the following conditions are met:</p>
            <ol>
                <li>A major technical defect — meaning a defect that completely prevents you from accessing or using the core functionality of your Klinica dashboard — exists.</li>
                <li>The defect was reported to our support team at <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a> within <strong>7 calendar days</strong> of your subscription payment date.</li>
                <li>Our support team was given a reasonable opportunity to investigate and resolve the issue, and was unable to do so within a reasonable timeframe (typically 5 business days).</li>
            </ol>
            <p>Refunds under this provision are not granted for:</p>
            <ul>
                <li>Minor bugs, cosmetic issues, or degraded performance that does not completely prevent use.</li>
                <li>Issues caused by the customer's own equipment, internet connection, or browser configuration.</li>
                <li>Issues reported after the 7-day window has elapsed.</li>
                <li>Defects in third-party integrations outside of Klinica's direct control.</li>
            </ul>

            <p style="margin-top:24px"><strong>4.2 — Billing Errors</strong></p>
            <p>If you were charged in error — for example due to a duplicate charge or a charge occurring after a properly submitted cancellation — please contact us within <strong>30 days</strong> of the erroneous charge. We will investigate promptly and issue a correction if a billing error is confirmed.</p>

            <div class="highlight-box">
                <p><strong>Approved refunds</strong> are processed back to the original payment method and may take 5–10 business days to appear depending on your bank or card issuer. Klinica does not issue refunds by bank transfer or store credit.</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 5 --}}
        <div class="section" id="how-to-request">
            <span class="section-number">Section 5</span>
            <h2>How to Request a Refund</h2>
            <p>To submit a refund request, please follow these steps:</p>
            <ol class="step-list">
                <li>
                    <span class="step-num">1</span>
                    <span>Email <a href="mailto:support@klinica.space" style="color:var(--green);font-weight:500">support@klinica.space</a> with the subject line <strong>"Refund Request — [your clinic subdomain]"</strong>.</span>
                </li>
                <li>
                    <span class="step-num">2</span>
                    <span>Include your <strong>registered email address</strong>, the <strong>date of the charge</strong>, the <strong>amount charged</strong>, and a clear description of the reason for your request.</span>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <span>For technical defect claims, attach <strong>screenshots or a screen recording</strong> demonstrating the issue preventing system access.</span>
                </li>
                <li>
                    <span class="step-num">4</span>
                    <span>Our team will acknowledge your request within <strong>2 business days</strong> and issue a decision within <strong>5 business days</strong>.</span>
                </li>
            </ol>
            <p>Refund requests that do not include the required information may be delayed or rejected. We process requests in good faith and expect the same from our customers.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 6 --}}
        <div class="section" id="cancellation">
            <span class="section-number">Section 6</span>
            <h2>Cancellations and Auto-Renewal</h2>
            <p>Klinica subscriptions are billed on a recurring monthly basis. Your subscription will <strong>automatically renew</strong> at the end of each billing period unless you cancel it beforehand.</p>

            <p><strong>How cancellation works:</strong></p>
            <ul>
                <li>You may cancel your subscription at any time from the <strong>Billing</strong> section of your dashboard.</li>
                <li>Upon cancellation, your subscription will <strong>not renew</strong> at the next billing date.</li>
                <li>Your account will remain <strong>fully active</strong> until the end of the current billing period you have already paid for.</li>
                <li><strong>No prorated refund</strong> is issued for the unused portion of the current billing period.</li>
                <li>After the billing period ends, your account will revert to an inactive state and data will be retained for 30 days per our <a href="{{ route('dpa') }}" style="color:var(--green)">Data Processing Agreement</a>.</li>
            </ul>

            <div class="highlight-box">
                <p><strong>Example:</strong> If your billing date is the 1st of each month and you cancel on the 15th, your account remains active and fully functional through the end of that month. You will not be charged on the 1st of the following month, and no refund is issued for the remaining days of the cancelled month.</p>
            </div>

            <p><strong>Renewal reminders:</strong> We send an email notification to your registered address at least 7 days before each renewal. It is your responsibility to ensure your contact email is current and that our emails are not filtered as spam.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 7 --}}
        <div class="section" id="plan-changes">
            <span class="section-number">Section 7</span>
            <h2>Plan Upgrades and Downgrades</h2>
            <p><strong>Upgrading:</strong> When you upgrade from Starter to Pro, the change takes effect immediately. You are charged the Pro plan rate at the next billing cycle (or immediately, depending on your billing provider's proration rules). No partial refund is issued for the unused Starter period.</p>
            <p><strong>Downgrading:</strong> When you downgrade from Pro to Starter, the change takes effect at the start of your next billing period. You retain Pro access through the end of the current period. No refund is issued for the difference in plan pricing.</p>
            <p>Both upgrades and downgrades are handled automatically. Confirmation is sent to your registered email address when a plan change is processed.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 8 --}}
        <div class="section" id="trial">
            <span class="section-number">Section 8</span>
            <h2>Free Trial</h2>
            <p>All new Klinica accounts include a <strong>14-day free trial</strong> at no cost. No credit card is required to start a trial.</p>
            <ul>
                <li>The trial period begins on the date of account registration and expires automatically after 14 days.</li>
                <li>No charges are made during the trial period.</li>
                <li>If you upgrade to a paid plan during your trial, your trial ends immediately and your paid subscription begins. The first charge is applied at the time of upgrade.</li>
                <li>If you do not upgrade before your trial expires, your account will become inactive. No charges are applied.</li>
            </ul>
            <p>The trial is intended for genuine evaluation purposes. Klinica reserves the right to terminate trial accounts that show signs of abuse or repeated registration to extend the trial period.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 9 --}}
        <div class="section" id="chargebacks">
            <span class="section-number">Section 9</span>
            <h2>Chargebacks and Payment Disputes</h2>
            <p>We strongly encourage you to <a href="mailto:support@klinica.space" style="color:var(--green)">contact our support team</a> before initiating a chargeback with your bank or payment provider. Most billing issues can be resolved promptly without the need for a formal dispute.</p>
            <p>Filing a chargeback for a charge that is valid under this Refund Policy constitutes a breach of our Terms of Service and may result in:</p>
            <ul>
                <li>Immediate suspension of your Klinica account pending resolution of the dispute.</li>
                <li>Permanent termination of your account if the chargeback is found to be unwarranted.</li>
                <li>Being barred from creating new Klinica accounts in the future.</li>
            </ul>
            <p>If a chargeback is filed in error and subsequently reversed, your account may be reinstated at our discretion. Please contact <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a> if this applies to you.</p>
        </div>

        <div class="divider"></div>

        {{-- SECTION 10 --}}
        <div class="section" id="contact">
            <span class="section-number">Section 10</span>
            <h2>Contact</h2>
            <p>For billing enquiries, refund requests, or cancellation assistance, please contact us:</p>
            <div class="highlight-box">
                <p>
                    <strong>Klinica — Billing Support</strong><br>
                    Email: <a href="mailto:support@klinica.space" style="color:var(--green)">support@klinica.space</a><br>
                    For privacy matters: <a href="mailto:privacy@klinica.space" style="color:var(--green)">privacy@klinica.space</a><br>
                    Web: <a href="{{ url('/') }}" style="color:var(--green)">klinica.space</a>
                </p>
            </div>
            <p>We aim to respond to all billing enquiries within <strong>2 business days</strong>. For urgent matters related to an active charge or account access, please include "URGENT" in the subject line of your email.</p>
            <p>Klinica reserves the right to update this Refund Policy at any time. Material changes will be communicated via email to your registered address at least 14 days before taking effect. Continued use of the platform after the effective date of any updated policy constitutes acceptance of the revised terms.</p>
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
            <a href="mailto:support@klinica.space">Contact</a>
        </p>
    </footer>

</body>
</html>
