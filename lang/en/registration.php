<?php

declare(strict_types=1);

return [

    // ── Page meta ────────────────────────────────────────────────────────────
    'title' => 'Start Your Free Trial | Klinica',
    'meta_description' => 'Create your free Klinica clinic account in minutes. Get a branded subdomain, online booking page, and patient management dashboard — no credit card required.',
    'already_have_account' => 'Already have an account?',
    'sign_in' => 'Sign in →',
    'free_trial_badge' => 'Free for 14 days, no credit card required',
    'hero_title' => 'Set up your clinic in minutes',
    'hero_subtitle' => 'Your booking page, patient management, and scheduling, all in one place.',

    // ── Stepper ───────────────────────────────────────────────────────────────
    'step_plan' => 'Choose Plan',
    'step_clinic' => 'Your Clinic',
    'step_account' => 'Your Account',
    'step_confirm' => 'Confirm',

    // ── Error banner ─────────────────────────────────────────────────────────
    'errors_heading' => 'Please fix the following:',

    // ── Navigation ───────────────────────────────────────────────────────────
    'back' => '← Back',
    'continue' => 'Continue →',
    'review' => 'Review →',

    // ── Step 1: Plan ─────────────────────────────────────────────────────────
    'plan_heading' => 'Choose your plan',
    'plan_sub' => 'Start a 14-day free trial. No credit card required.',
    'starter_label' => 'Starter',
    'starter_price_suffix' => '/mo',
    'starter_feature_1' => 'Up to 20 patients',
    'starter_feature_2' => '1 employee',
    'starter_feature_3' => '30 bookings/month',
    'starter_feature_4' => 'AES-256 encryption',
    'starter_feature_5' => 'Audit logs',
    'pro_label' => 'Pro',
    'pro_price_suffix' => '/mo',
    'pro_feature_1' => 'Unlimited patients',
    'pro_feature_2' => 'Unlimited employees',
    'pro_feature_3' => 'Unlimited bookings',
    'pro_feature_4' => '10 GB storage',
    'pro_feature_5' => 'Email reminders',

    // ── Step 2: Clinic ───────────────────────────────────────────────────────
    'clinic_heading' => 'Tell us about your clinic',
    'clinic_sub' => "This will be your clinic's public identity on Klinica.",
    'clinic_name_label' => 'Clinic name',
    'clinic_name_placeholder' => "e.g. Dr. Martin's Physiotherapy",
    'booking_url_label' => 'Your booking URL',
    'booking_url_placeholder' => 'yourclinic',
    'booking_url_preview_empty' => 'Enter a subdomain to preview your URL',
    'booking_url_preview' => 'Your URL will be: :url',
    'subdomain_hint' => 'Lowercase letters, numbers and hyphens only.',
    'locale_label' => 'Dashboard language',
    'locale_hint' => 'Sets the language for your dashboard and public booking page.',

    // ── Step 3: Account ──────────────────────────────────────────────────────
    'account_heading' => 'Create your account',
    'account_sub' => 'This will be your admin login for the Klinica dashboard.',
    'full_name_label' => 'Your full name',
    'full_name_placeholder' => 'Dr. Anna Martin',
    'email_label' => 'Work email',
    'email_placeholder' => 'anna@yourclinic.com',
    'password_label' => 'Password',
    'password_placeholder' => 'Min. 8 characters',
    'password_show' => 'Show',
    'password_hide' => 'Hide',
    'confirm_password_label' => 'Confirm password',
    'confirm_password_placeholder' => 'Repeat password',

    // ── Step 4: Review ───────────────────────────────────────────────────────
    'review_heading' => "Looks good, let's launch!",
    'review_sub' => 'Review your details before we create your clinic.',
    'review_plan_label' => 'Plan',
    'review_clinic_label' => 'Clinic Name',
    'review_url_label' => 'Booking URL',
    'review_name_label' => 'Name',
    'review_email_label' => 'Email',
    'review_plan_starter' => 'Starter - €29/mo',
    'review_plan_pro' => 'Pro - €49/mo',
    'review_edit' => 'Edit',
    'review_not_set' => 'Not set',

    // ── Trust badges ─────────────────────────────────────────────────────────
    'badge_encrypted' => 'Encrypted & GDPR compliant',
    'badge_live' => 'Live in under 2 minutes',
    'badge_trial' => '14-day free trial',

    // ── Consent ──────────────────────────────────────────────────────────────
    'consent_part1' => "I have read and agree to Klinica's",
    'consent_terms' => 'Terms of Service',
    'consent_comma' => ',',
    'consent_privacy' => 'Privacy Policy',
    'consent_and' => 'and',
    'consent_dpa' => 'Data Processing Agreement (DPA)',
    'consent_part2' => "I understand that my clinic's data will be processed in accordance with GDPR and hosted on ISO 27001 certified servers in Germany.",
    'consent_error' => 'You must accept the terms to continue.',

    // ── Submit ───────────────────────────────────────────────────────────────
    'create_clinic' => 'Create my clinic',
    'creating_clinic' => 'Creating your clinic...',
    'disclaimer' => 'Your data is encrypted and hosted on ISO 27001 certified servers in Germany. We never share or sell your data.',

    // ── Footer ───────────────────────────────────────────────────────────────
    'footer_terms' => 'Terms of Service',
    'footer_privacy' => 'Privacy Policy',
    'footer_dpa' => 'DPA',
    'footer_refund' => 'Refund Policy',

    // ── JS validation (injected into JS via @json) ────────────────────────────
    'js_enter_clinic_name' => 'Please enter your clinic name.',
    'js_enter_subdomain' => 'Please enter a subdomain.',
    'js_subdomain_format' => 'Subdomain may only contain lowercase letters, numbers and hyphens, and cannot start or end with a hyphen.',
    'js_subdomain_unavailable' => 'That subdomain is not available. Please choose a different one.',
    'js_enter_name' => 'Please enter your full name.',
    'js_enter_email' => 'Please enter your email address.',
    'js_invalid_email' => 'Please enter a valid email address.',
    'js_password_length' => 'Password must be at least 8 characters.',
    'js_password_mismatch' => 'Passwords do not match.',

    // ── JS subdomain status ───────────────────────────────────────────────────
    'js_subdomain_checking' => '⏳ Checking availability…',
    'js_subdomain_available' => '✓ Available',
    'js_subdomain_reserved' => '✗ Reserved — choose a different name',
    'js_subdomain_invalid' => '✗ Invalid format',
    'js_subdomain_taken' => '✗ Already taken — try another',

    'provisioning_title' => 'Setting up your clinic',
    'provisioning_heading' => 'Setting up your clinic…',
    'provisioning_subtext' => 'This usually takes a few seconds. You’ll be redirected to your clinic automatically.',
    'provisioning_failed' => 'Something went wrong while creating your clinic. Please contact support at admin@klinica.space.',
    'provisioning_timeout' => 'This is taking longer than expected. If you’re not redirected shortly, please contact support at admin@klinica.space.',
];
