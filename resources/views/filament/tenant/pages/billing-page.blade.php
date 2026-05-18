<x-filament-panels::page>
    <style>
        .bp-wrap { display: flex; flex-direction: column; gap: 24px; max-width: 860px; }

        /* ── Current plan banner ── */
        .bp-banner { border-radius: 12px; border: 1px solid; padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .bp-banner.trial  { background: #fffbeb; border-color: #f59e0b; }
        .bp-banner.active { background: #f0fdf4; border-color: #22c55e; }
        .bp-banner.lapsed { background: #f8fafc; border-color: #cbd5e1; }
        .bp-banner-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: #64748b; margin-bottom: 4px; }
        .bp-plan-name { font-size: 26px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px; }
        .bp-badge { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px; background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .bp-badge.danger { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .bp-sub { margin-top: 4px; font-size: 13px; color: #64748b; }
        .bp-sub.warn { color: #b45309; font-weight: 600; }

        /* ── Plan cards ── */
        .bp-plans { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 640px) { .bp-plans { grid-template-columns: 1fr; } }
        .bp-plan { border-radius: 12px; border: 2px solid #e2e8f0; padding: 24px; background: #fff; }
        .bp-plan.current-starter { border-color: #2F8082; background: #f0fafb; }
        .bp-plan.current-pro { border-color: #22c55e; background: #f0fdf4; }
        .bp-plan-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .bp-plan-title { font-size: 17px; font-weight: 700; color: #1e293b; }
        .bp-plan-price { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
        .bp-plan-price sup { font-size: 14px; font-weight: 600; vertical-align: super; }
        .bp-plan-price span { font-size: 13px; font-weight: 400; color: #94a3b8; }
        .bp-current-pill { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px; background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .bp-plan-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
        .bp-plan-list li { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; }
        .bp-plan-list li svg { flex-shrink: 0; }
        .bp-plan-list li.off { color: #94a3b8; }
        .bp-plan-cta { margin-top: 16px; display: flex; }
        .bp-btn {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px;
            font-size: 13px; font-weight: 600; text-align: center; text-decoration: none;
            color: #0f172a; background: #fff;
        }
        .bp-btn:hover { border-color: #2F8082; color: #0f172a; background: #f8fafc; }
        .bp-btn.primary { background: #2F8082; border-color: #2F8082; color: #fff; }
        .bp-btn.primary:hover { background: #256a6c; border-color: #256a6c; color: #fff; }
        .bp-btn.disabled { cursor: default; opacity: .7; background: #f8fafc; }

        /* ── Usage ── */
        .bp-usage { border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; background: #fff; }
        .bp-usage-title { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 16px; }
        .bp-usage-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 480px) { .bp-usage-grid { grid-template-columns: 1fr; } }
        .bp-usage-item .label { font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .bp-usage-item .value { font-size: 17px; font-weight: 700; color: #1e293b; }

        /* ── Security note ── */
        .bp-note { text-align: center; font-size: 12px; color: #94a3b8; display: flex; align-items: center; justify-content: center; gap: 6px; }

        /* ── Dark mode overrides (Filament) ── */
        .dark .bp-banner {
            background: #0f172a;
            border-color: #334155;
        }
        .dark .bp-banner.trial {
            background: #2b2110;
            border-color: #a16207;
        }
        .dark .bp-banner.active {
            background: #13281a;
            border-color: #15803d;
        }
        .dark .bp-banner.lapsed {
            background: #0f172a;
            border-color: #334155;
        }
        .dark .bp-banner-label { color: #94a3b8; }
        .dark .bp-plan-name { color: #e2e8f0; }
        .dark .bp-sub { color: #94a3b8; }
        .dark .bp-sub.warn { color: #fbbf24; }

        .dark .bp-badge {
            background: #3f2f14;
            color: #fde68a;
            border-color: #a16207;
        }
        .dark .bp-badge.danger {
            background: #3f1f1f;
            color: #fecaca;
            border-color: #991b1b;
        }

        .dark .bp-plan {
            background: #0f172a;
            border-color: #334155;
        }
        .dark .bp-plan.current-starter {
            background: #10292a;
            border-color: #2f8082;
        }
        .dark .bp-plan.current-pro {
            background: #13281a;
            border-color: #15803d;
        }
        .dark .bp-plan-title,
        .dark .bp-plan-price,
        .dark .bp-usage-title,
        .dark .bp-usage-item .value {
            color: #e2e8f0;
        }
        .dark .bp-plan-price span,
        .dark .bp-usage-item .label {
            color: #94a3b8;
        }
        .dark .bp-plan-list li { color: #cbd5e1; }
        .dark .bp-plan-list li.off { color: #64748b; }
        .dark .bp-current-pill {
            background: #1b4332;
            border-color: #15803d;
            color: #bbf7d0;
        }

        .dark .bp-btn {
            background: #0b1220;
            border-color: #334155;
            color: #e2e8f0;
        }
        .dark .bp-btn:hover {
            border-color: #2f8082;
            background: #111827;
            color: #f8fafc;
        }
        .dark .bp-btn.primary {
            background: #2f8082;
            border-color: #2f8082;
            color: #ffffff;
        }
        .dark .bp-btn.primary:hover {
            background: #256a6c;
            border-color: #256a6c;
        }
        .dark .bp-btn.disabled {
            background: #111827;
            color: #94a3b8;
            border-color: #334155;
        }

        .dark .bp-usage {
            background: #0f172a;
            border-color: #334155;
        }

        .dark .bp-note { color: #94a3b8; }
        .dark .bp-note svg { stroke: #94a3b8; }
    </style>

    <div class="bp-wrap">

        @if (session('success'))
            <div class="bp-banner active">
                <div>
                    <div class="bp-banner-label">Success</div>
                    <div class="bp-sub">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bp-banner lapsed">
                <div>
                    <div class="bp-banner-label">Checkout Error</div>
                    <div class="bp-sub warn">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        {{-- Current plan banner --}}
        @php
            $bannerClass = $isOnTrial ? 'trial' : ($isActive ? 'active' : 'lapsed');
            $starterIsPaidCurrent = ! $isOnTrial && $tier === 'starter';
            $proIsPaidCurrent = ! $isOnTrial && $tier === 'pro';
            $starterIsTrialSelection = $isOnTrial && $tier === 'starter';
            $proIsTrialSelection = $isOnTrial && $tier === 'pro';
        @endphp
        <div class="bp-banner {{ $bannerClass }}">
            <div>
                <div class="bp-banner-label">Current Plan</div>
                <div class="bp-plan-name">
                    {{ ucfirst($planLabel) }}
                    @if ($isOnTrial)
                        @if ($trialDaysRemaining <= 0)
                            <span class="bp-badge danger">Trial expired</span>
                        @else
                            <span class="bp-badge">Trial &mdash; {{ $trialDaysRemaining }} {{ Str::plural('day', $trialDaysRemaining) }} left</span>
                        @endif
                    @endif
                </div>
                @if ($isOnTrial && $trialDaysRemaining <= 0)
                    <div class="bp-sub warn">Your trial has expired. Subscribe below to continue.</div>
                @elseif ($isOnTrial)
                    <div class="bp-sub">Your free trial ends in {{ $trialDaysRemaining }} {{ Str::plural('day', $trialDaysRemaining) }}. Subscribe to keep access.</div>
                @elseif ($isActive && $planExpiresAt)
                    <div class="bp-sub">Next billing date: {{ $planExpiresAt->format('M j, Y') }}</div>
                @endif
            </div>
        </div>

        {{-- Plan comparison --}}
        <div class="bp-plans">

            {{-- Starter --}}
            <div class="bp-plan {{ $tier === 'starter' ? 'current-starter' : '' }}">
                <div class="bp-plan-header">
                    <div>
                        <div class="bp-plan-title">Starter</div>
                        <div class="bp-plan-price"><sup>€</sup>19<span>/mo</span></div>
                    </div>
                    @if ($starterIsPaidCurrent)
                        <span class="bp-current-pill">Current plan</span>
                    @elseif ($starterIsTrialSelection)
                        <span class="bp-current-pill">Trial selection</span>
                    @endif
                </div>
                <ul class="bp-plan-list">
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Up to 20 patients
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        1 employee account
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        30 bookings / month
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        AES-256 encryption
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Audit logs
                    </li>
                    <li class="off">
                        <svg width="14" height="14" fill="none" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Email reminders
                    </li>
                </ul>
                <div class="bp-plan-cta">
                    @if ($starterIsPaidCurrent)
                        <span class="bp-btn disabled">Current plan</span>
                    @else
                        <a href="{{ route('billing.checkout', ['plan' => 'starter']) }}" class="bp-btn {{ $starterIsTrialSelection ? 'primary' : '' }}">
                            {{ $starterIsTrialSelection ? 'Subscribe to Starter' : 'Choose Starter' }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Pro --}}
            <div class="bp-plan {{ $tier === 'pro' ? 'current-pro' : '' }}">
                <div class="bp-plan-header">
                    <div>
                        <div class="bp-plan-title">Pro</div>
                        <div class="bp-plan-price"><sup>€</sup>49<span>/mo</span></div>
                    </div>
                    @if ($proIsPaidCurrent)
                        <span class="bp-current-pill">Current plan</span>
                    @elseif ($proIsTrialSelection)
                        <span class="bp-current-pill">Trial selection</span>
                    @endif
                </div>
                <ul class="bp-plan-list">
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Unlimited patients
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Unlimited employees
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Unlimited bookings
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        10 GB storage
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Email reminders
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Priority support
                    </li>
                </ul>
                <div class="bp-plan-cta">
                    @if ($proIsPaidCurrent)
                        <span class="bp-btn disabled">Current plan</span>
                    @else
                        <a href="{{ route('billing.checkout', ['plan' => 'pro']) }}" class="bp-btn primary">
                            {{ $proIsTrialSelection ? 'Subscribe to Pro' : 'Upgrade to Pro' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Usage limits --}}
        <div class="bp-usage">
            <div class="bp-usage-title">Plan limits</div>
            <div class="bp-usage-grid">
                <div class="bp-usage-item">
                    <div class="label">Patients</div>
                    <div class="value">{{ $limits['max_patients'] === PHP_INT_MAX ? 'Unlimited' : $limits['max_patients'] }}</div>
                </div>
                <div class="bp-usage-item">
                    <div class="label">Employees</div>
                    <div class="value">{{ $limits['max_employees'] === PHP_INT_MAX ? 'Unlimited' : $limits['max_employees'] }}</div>
                </div>
                <div class="bp-usage-item">
                    <div class="label">Bookings / month</div>
                    <div class="value">{{ $limits['max_monthly_bookings'] === PHP_INT_MAX ? 'Unlimited' : $limits['max_monthly_bookings'] }}</div>
                </div>
            </div>
        </div>

        {{-- Security note --}}
        <div class="bp-note">
            <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2l7 4v6c0 5-3.5 9.74-7 11-3.5-1.26-7-6-7-11V6l7-4z"/></svg>
            Data encrypted with AES-256 and hosted on ISO 27001 certified servers in Germany.
        </div>

    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>

