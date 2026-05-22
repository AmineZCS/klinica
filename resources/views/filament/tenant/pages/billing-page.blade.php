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
        .bp-plan { border-radius: 12px; border: 2px solid #e2e8f0; padding: 24px; background: #fff; position: relative; }
        .bp-plan.current-starter { border-color: #2F8082; background: #f0fafb; }
        .bp-plan.current-pro { border-color: #16a34a; background: #f0fdf4; }
        .bp-plan.highlight-pro { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
        .bp-plan-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .bp-plan-title { font-size: 17px; font-weight: 700; color: #1e293b; }
        .bp-plan-price { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
        .bp-plan-price sup { font-size: 14px; font-weight: 600; vertical-align: super; }
        .bp-plan-price span { font-size: 13px; font-weight: 400; color: #94a3b8; }
        .bp-current-pill { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px; background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .bp-popular-badge {
            position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
            background: #16a34a; color: #fff;
            font-size: 11px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase;
            padding: 3px 12px; border-radius: 999px; white-space: nowrap;
        }
        .bp-plan-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
        .bp-plan-list li { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #475569; }
        .bp-plan-list li svg { flex-shrink: 0; margin-top: 1px; }
        .bp-plan-list li.off { color: #cbd5e1; text-decoration: line-through; }
        .bp-plan-list .bp-feature-hint { display: block; font-size: 11px; color: #94a3b8; margin-top: 1px; }
        .bp-plan-cta { margin-top: 16px; display: flex; flex-direction: column; gap: 6px; }
        .bp-btn {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px;
            font-size: 13px; font-weight: 600; text-align: center; text-decoration: none;
            color: #0f172a; background: #fff; cursor: pointer;
        }
        .bp-btn:hover { border-color: #2F8082; color: #0f172a; background: #f8fafc; }
        .bp-btn.primary { background: #16a34a; border-color: #16a34a; color: #fff; }
        .bp-btn.primary:hover { background: #15803d; border-color: #15803d; color: #fff; }
        .bp-btn.disabled { cursor: default; opacity: .7; background: #f8fafc; }
        .bp-cta-hint { font-size: 11px; color: #94a3b8; text-align: center; }

        /* ── Usage ── */
        .bp-usage { border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; background: #fff; }
        .bp-usage-title { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 16px; }
        .bp-usage-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 480px) { .bp-usage-grid { grid-template-columns: 1fr; } }
        .bp-usage-item .label { font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .bp-usage-item .value { font-size: 17px; font-weight: 700; color: #1e293b; }

        /* ── Security note ── */
        .bp-note { text-align: center; font-size: 12px; color: #94a3b8; display: flex; align-items: center; justify-content: center; gap: 6px; }

        /* ── Cancel link ── */
        .bp-cancel-link { display: block; text-align: center; font-size: 12px; color: #94a3b8; margin-top: -8px; text-decoration: underline; cursor: pointer; background: none; border: none; padding: 0; }
        .bp-cancel-link:hover { color: #ef4444; }

        /* ── Cancel modal ── */
        .bpc-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 9998; display: none; align-items: center; justify-content: center; padding: 16px; }
        .bpc-backdrop.open { display: flex; }
        .bpc-modal { background: #fff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 24px 64px rgba(0,0,0,.25); overflow: hidden; }
        .bpc-header { background: #fef2f2; padding: 24px 28px 16px; border-bottom: 1px solid #fee2e2; }
        .bpc-header-icon { width: 40px; height: 40px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .bpc-title { font-size: 18px; font-weight: 700; color: #1e293b; }
        .bpc-subtitle { font-size: 13px; color: #64748b; margin-top: 4px; }
        .bpc-body { padding: 20px 28px; }
        .bpc-loss-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: #ef4444; margin-bottom: 10px; }
        .bpc-loss-list { list-style: none; padding: 0; margin: 0 0 16px; display: flex; flex-direction: column; gap: 7px; }
        .bpc-loss-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: #374151; }
        .bpc-loss-list li svg { flex-shrink: 0; margin-top: 2px; }
        .bpc-data-warning { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 12px 14px; font-size: 12px; color: #9a3412; margin-bottom: 16px; display: flex; gap: 10px; align-items: flex-start; }
        .bpc-data-warning svg { flex-shrink: 0; margin-top: 1px; }
        .bpc-alt { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 14px; font-size: 13px; color: #166534; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .bpc-alt a { color: #15803d; font-weight: 600; text-decoration: underline; }
        .bpc-reason label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .bpc-reason select { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #1e293b; background: #fff; outline: none; }
        .bpc-reason select:focus { border-color: #2F8082; box-shadow: 0 0 0 2px rgba(47,128,130,.15); }
        .bpc-footer { padding: 16px 28px 20px; display: flex; flex-direction: column; gap: 8px; border-top: 1px solid #f1f5f9; }
        .bpc-btn-keep { width: 100%; background: #16a34a; border: none; border-radius: 8px; padding: 11px 12px; font-size: 14px; font-weight: 700; color: #fff; cursor: pointer; }
        .bpc-btn-keep:hover { background: #15803d; }
        .bpc-btn-cancel { width: 100%; background: none; border: 1px solid #fca5a5; border-radius: 8px; padding: 9px 12px; font-size: 13px; font-weight: 500; color: #ef4444; cursor: pointer; }
        .bpc-btn-cancel:hover { background: #fef2f2; }
        .bpc-btn-cancel:disabled { opacity: .5; cursor: not-allowed; }
        .bpc-success { padding: 32px 28px; text-align: center; }
        .bpc-success-icon { width: 52px; height: 52px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .bpc-success-title { font-size: 17px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .bpc-success-sub { font-size: 13px; color: #64748b; }

        /* ── Paddle overlay loading state ── */
        .bp-btn.loading { opacity: .6; cursor: not-allowed; pointer-events: none; }

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
        .dark .bp-plan.current-pro,
        .dark .bp-plan.highlight-pro {
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
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
        }
        .dark .bp-btn.primary:hover {
            background: #15803d;
            border-color: #15803d;
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

        .dark .bpc-modal { background: #1e293b; }
        .dark .bpc-header { background: #2d1515; border-color: #7f1d1d; }
        .dark .bpc-title { color: #f1f5f9; }
        .dark .bpc-subtitle { color: #94a3b8; }
        .dark .bpc-loss-list li { color: #cbd5e1; }
        .dark .bpc-data-warning { background: #2d1a0e; border-color: #92400e; color: #fdba74; }
        .dark .bpc-alt { background: #14291e; border-color: #15803d; color: #bbf7d0; }
        .dark .bpc-alt a { color: #86efac; }
        .dark .bpc-reason label { color: #cbd5e1; }
        .dark .bpc-reason select { background: #0f172a; border-color: #334155; color: #f1f5f9; }
        .dark .bpc-footer { border-color: #334155; }
        .dark .bpc-success { background: #1e293b; }
        .dark .bpc-success-icon { background: #14291e; }
        .dark .bpc-success-title { color: #f1f5f9; }
        .dark .bpc-success-sub { color: #94a3b8; }
        .dark .bp-cancel-link { color: #64748b; }
        .dark .bp-cancel-link:hover { color: #f87171; }
    </style>

    <div class="bp-wrap">

        @if (session('success'))
            <div class="bp-banner active">
                <div>
                    <div class="bp-banner-label">{{ __('klinica.billing.session_success') }}</div>
                    <div class="bp-sub">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="bp-banner trial">
                <div>
                    <div class="bp-banner-label">{{ __('klinica.billing.session_action_required') }}</div>
                    <div class="bp-sub warn">{{ session('warning') }}</div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bp-banner lapsed">
                <div>
                    <div class="bp-banner-label">{{ __('klinica.billing.session_checkout_error') }}</div>
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
                <div class="bp-banner-label">{{ __('klinica.billing.current_plan') }}</div>
                <div class="bp-plan-name">
                    {{ ucfirst($planLabel) }}
                    @if ($isOnTrial)
                        @if ($trialDaysRemaining <= 0)
                            <span class="bp-badge danger">{{ __('klinica.billing.badge_trial_expired') }}</span>
                        @else
                            <span class="bp-badge">{!! __('klinica.billing.badge_trial_days', ['days' => $trialDaysRemaining, 'word' => Str::plural('day', $trialDaysRemaining)]) !!}</span>
                        @endif
                    @endif
                </div>
                @if ($isOnTrial && $trialDaysRemaining <= 0)
                    <div class="bp-sub warn">{{ __('klinica.billing.sub_trial_expired') }}</div>
                @elseif ($isOnTrial)
                    <div class="bp-sub">{{ __('klinica.billing.sub_trial_active', ['days' => $trialDaysRemaining, 'word' => Str::plural('day', $trialDaysRemaining)]) }}</div>
                @elseif ($planCancelsAt)
                    <div class="bp-sub warn">{{ __('klinica.billing.sub_cancels_at', ['date' => $planCancelsAt->format('M j, Y')]) }}</div>
                @elseif ($isActive && $planExpiresAt)
                    <div class="bp-sub">{{ __('klinica.billing.sub_next_billing', ['date' => $planExpiresAt->format('M j, Y')]) }}</div>
                @endif
            </div>
        </div>

        @if ($isActive && $tenant->paddle_subscription_id && ! $planCancelsAt)
            <button type="button" class="bp-cancel-link" onclick="document.getElementById('bpc-backdrop').classList.add('open')">
                {{ __('klinica.billing.cancel_link') }}
            </button>
        @endif

        {{-- Plan comparison --}}
        <div class="bp-plans">

            {{-- Starter --}}
            <div class="bp-plan {{ $tier === 'starter' ? 'current-starter' : '' }}">
                <div class="bp-plan-header">
                    <div>
                        <div class="bp-plan-title">Starter</div>
                        <div class="bp-plan-price"><sup>€</sup>29<span>/mo</span></div>
                    </div>
                    @if ($starterIsPaidCurrent)
                        <span class="bp-current-pill">{{ __('klinica.billing.pill_current') }}</span>
                    @elseif ($starterIsTrialSelection)
                        <span class="bp-current-pill">{{ __('klinica.billing.pill_trial') }}</span>
                    @endif
                </div>
                <ul class="bp-plan-list">
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_starter_f1') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_starter_f2') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_starter_f3') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_starter_f4') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_starter_f5') }}
                    </li>
                    <li class="off">
                        <svg width="14" height="14" fill="none" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('klinica.billing.plan_starter_off1') }}
                    </li>
                    <li class="off">
                        <svg width="14" height="14" fill="none" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('klinica.billing.plan_starter_off2') }}
                    </li>
                </ul>
                <div class="bp-plan-cta">
                    @if ($starterIsPaidCurrent)
                        <span class="bp-btn disabled">{{ __('klinica.billing.cta_current') }}</span>
                    @elseif ($isOnTrial)
                        @php $starterLabel = $starterIsTrialSelection ? __('klinica.billing.cta_subscribe_to', ['plan' => 'Starter']) : __('klinica.billing.cta_choose', ['plan' => 'Starter']); @endphp
                        <button type="button" class="bp-btn {{ $starterIsTrialSelection ? 'primary' : '' }}" onclick="openPaddleCheckout('starter', this)" data-label="{{ $starterLabel }}">
                            {{ $starterLabel }}
                        </button>
                    @else
                        <a href="{{ route('billing.checkout', ['plan' => 'starter']) }}" class="bp-btn">
                            {{ __('klinica.billing.cta_choose', ['plan' => 'Starter']) }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Pro --}}
            <div class="bp-plan {{ $proIsPaidCurrent ? 'current-pro' : (! $proIsPaidCurrent ? 'highlight-pro' : '') }}">
                @if (! $proIsPaidCurrent)
                    <div class="bp-popular-badge">{{ __('klinica.billing.most_popular') }}</div>
                @endif
                <div class="bp-plan-header">
                    <div>
                        <div class="bp-plan-title">Pro</div>
                        <div class="bp-plan-price"><sup>€</sup>49<span>/mo</span></div>
                    </div>
                    @if ($proIsPaidCurrent)
                        <span class="bp-current-pill">{{ __('klinica.billing.pill_current') }}</span>
                    @elseif ($proIsTrialSelection)
                        <span class="bp-current-pill">{{ __('klinica.billing.pill_trial') }}</span>
                    @endif
                </div>
                <ul class="bp-plan-list">
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_pro_f1') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_pro_f2') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_pro_f3') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_pro_f4') }}
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>
                            {{ __('klinica.billing.plan_pro_f5') }}
                            <span class="bp-feature-hint">{{ __('klinica.billing.plan_pro_f5_hint') }}</span>
                        </span>
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('klinica.billing.plan_pro_f6') }}
                    </li>
                </ul>
                <div class="bp-plan-cta">
                    @if ($proIsPaidCurrent)
                        <span class="bp-btn disabled">{{ __('klinica.billing.cta_current') }}</span>
                    @elseif ($isOnTrial)
                        @php $proLabel = $proIsTrialSelection ? __('klinica.billing.cta_subscribe_to', ['plan' => 'Pro']) : __('klinica.billing.cta_upgrade_to', ['plan' => 'Pro']); @endphp
                        <button type="button" class="bp-btn primary" onclick="openPaddleCheckout('pro', this)" data-label="{{ $proLabel }}">
                            {{ $proLabel }}
                        </button>
                        <span class="bp-cta-hint">{{ __('klinica.billing.hint_no_commitment') }}</span>
                    @else
                        <a href="{{ route('billing.checkout', ['plan' => 'pro']) }}" class="bp-btn primary">
                            {{ __('klinica.billing.cta_upgrade_to', ['plan' => 'Pro']) }}
                        </a>
                        <span class="bp-cta-hint">{{ __('klinica.billing.hint_prorated') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Usage limits --}}
        <div class="bp-usage">
            <div class="bp-usage-title">{{ __('klinica.billing.plan_limits_title') }}</div>
            <div class="bp-usage-grid">
                <div class="bp-usage-item">
                    <div class="label">{{ __('klinica.billing.limit_patients') }}</div>
                    <div class="value">{{ $limits['max_patients'] === PHP_INT_MAX ? __('klinica.billing.unlimited') : $limits['max_patients'] }}</div>
                </div>
                <div class="bp-usage-item">
                    <div class="label">{{ __('klinica.billing.limit_employees') }}</div>
                    <div class="value">{{ $limits['max_employees'] === PHP_INT_MAX ? __('klinica.billing.unlimited') : $limits['max_employees'] }}</div>
                </div>
                <div class="bp-usage-item">
                    <div class="label">{{ __('klinica.billing.limit_bookings') }}</div>
                    <div class="value">{{ $limits['max_monthly_bookings'] === PHP_INT_MAX ? __('klinica.billing.unlimited') : $limits['max_monthly_bookings'] }}</div>
                </div>
            </div>
        </div>

        {{-- Security note --}}
        <div class="bp-note">
            <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2l7 4v6c0 5-3.5 9.74-7 11-3.5-1.26-7-6-7-11V6l7-4z"/></svg>
            {{ __('klinica.billing.security_note') }}
        </div>

    </div>

    {{-- Cancel subscription modal --}}
    @if ($isActive && $tenant->paddle_subscription_id && ! $planCancelsAt)
    <div id="bpc-backdrop" class="bpc-backdrop" onclick="if(event.target===this)closeCancelModal()">
        <div class="bpc-modal" role="dialog" aria-modal="true" aria-labelledby="bpc-title">

            {{-- Default view --}}
            <div id="bpc-main">
                <div class="bpc-header">
                    <div class="bpc-header-icon">
                        <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div id="bpc-title" class="bpc-title">{{ __('klinica.billing.cancel_title') }}</div>
                    <div class="bpc-subtitle">{{ __('klinica.billing.cancel_subtitle') }}</div>
                </div>

                <div class="bpc-body">
                    <div class="bpc-loss-title">{{ __('klinica.billing.cancel_loss_title') }}</div>
                    <ul class="bpc-loss-list">
                        @foreach (['cancel_loss_1','cancel_loss_2','cancel_loss_3','cancel_loss_4'] as $key)
                        <li>
                            <svg width="14" height="14" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('klinica.billing.'.$key) }}
                        </li>
                        @endforeach
                        @if ($tier === 'pro')
                        <li>
                            <svg width="14" height="14" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('klinica.billing.cancel_loss_5') }}
                        </li>
                        @endif
                    </ul>

                    <div class="bpc-data-warning">
                        <svg width="16" height="16" fill="none" stroke="#c2410c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2l7 4v6c0 5-3.5 9.74-7 11-3.5-1.26-7-6-7-11V6l7-4z"/></svg>
                        <span>{!! __('klinica.billing.cancel_data_warning') !!}</span>
                    </div>

                    @if ($tier === 'pro')
                    <div class="bpc-alt">
                        <svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{!! __('klinica.billing.cancel_downgrade', ['url' => route('billing.checkout', ['plan' => 'starter'])]) !!}</span>
                    </div>
                    @endif

                    <div class="bpc-reason">
                        <label for="bpc-reason-select">{{ __('klinica.billing.cancel_reason_label') }} <span style="color:#94a3b8;font-weight:400">({{ __('klinica.billing.cancel_reason_hint') }})</span></label>
                        <select id="bpc-reason-select" onchange="document.getElementById('bpc-confirm-btn').disabled = this.value === ''">
                            <option value="">{{ __('klinica.billing.cancel_reason_placeholder') }}</option>
                            <option value="too_expensive">{{ __('klinica.billing.cancel_reason_expensive') }}</option>
                            <option value="not_using">{{ __('klinica.billing.cancel_reason_not_using') }}</option>
                            <option value="missing_feature">{{ __('klinica.billing.cancel_reason_missing') }}</option>
                            <option value="switching">{{ __('klinica.billing.cancel_reason_switching') }}</option>
                            <option value="closing">{{ __('klinica.billing.cancel_reason_closing') }}</option>
                            <option value="temporary">{{ __('klinica.billing.cancel_reason_pause') }}</option>
                            <option value="other">{{ __('klinica.billing.cancel_reason_other') }}</option>
                        </select>
                    </div>
                </div>

                <div class="bpc-footer">
                    <button type="button" class="bpc-btn-keep" onclick="closeCancelModal()">
                        {{ __('klinica.billing.keep_subscription') }}
                    </button>
                    <button type="button" id="bpc-confirm-btn" class="bpc-btn-cancel" disabled onclick="submitCancellation()">
                        {{ __('klinica.billing.cancel_anyway') }}
                    </button>
                </div>
            </div>

            {{-- Success view (shown after API call) --}}
            <div id="bpc-success" style="display:none">
                <div class="bpc-success">
                    <div class="bpc-success-icon">
                        <svg width="24" height="24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="bpc-success-title">{{ __('klinica.billing.cancellation_scheduled') }}</div>
                    <div class="bpc-success-sub" id="bpc-success-text">
                        {{ __('klinica.billing.cancellation_sub', ['date' => '']) }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif

    <x-filament-actions::modals />

    <script>
        var _paddleBtn = null;

        // Returned from central checkout page after payment — start polling immediately.
        if (window.location.search.includes('checkout=complete')) {
            window.addEventListener('DOMContentLoaded', function () { _startActivationPolling(); });
        }

        function openPaddleCheckout(plan, btn) {
            _paddleBtn = btn;
            if (btn) {
                btn.classList.add('loading');
                btn.textContent = 'Opening checkout…';
            }

            fetch('{{ route('tenant.billing.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ plan: plan }),
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.transaction_id) {
                    var returnUrl = encodeURIComponent(window.location.origin + '/clinic/billing-page?checkout=complete');
                    var cancelUrl = encodeURIComponent(window.location.href);
                    window.location.href = '{{ $centralBillingOpenUrl }}?txn=' + data.transaction_id
                        + '&return=' + returnUrl
                        + '&cancel=' + cancelUrl;
                } else {
                    _resetPaddleBtn();
                    alert(data.error || 'Checkout failed. Please try again.');
                }
            })
            .catch(function (e) {
                console.error('Checkout error:', e);
                _resetPaddleBtn();
                alert('Checkout failed. Please try again.');
            });
        }

        function _resetPaddleBtn() {
            if (_paddleBtn) {
                _paddleBtn.classList.remove('loading');
                _paddleBtn.textContent = _paddleBtn.dataset.label || 'Subscribe';
                _paddleBtn = null;
            }
        }

        function closeCancelModal() {
            document.getElementById('bpc-backdrop').classList.remove('open');
        }

        function submitCancellation() {
            var btn = document.getElementById('bpc-confirm-btn');
            var reason = document.getElementById('bpc-reason-select').value;

            btn.disabled = true;
            btn.textContent = 'Cancelling…';

            fetch('{{ route('billing.cancel') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ reason: reason }),
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.ok) {
                        document.getElementById('bpc-main').style.display = 'none';
                        document.getElementById('bpc-success').style.display = 'block';
                        if (data.access_until) {
                            var el = document.getElementById('bpc-success-text');
                            if (el) { el.textContent = el.textContent.replace(/\s*$/, '') + ' ' + data.access_until + '.'; }
                        }
                        setTimeout(function () { window.location.reload(); }, 4000);
                    } else {
                        alert(data.error || 'Something went wrong. Please try again.');
                        btn.disabled = false;
                        btn.textContent = '{{ __('klinica.billing.cancel_anyway') }}';
                    }
                })
                .catch(function () {
                    alert('Network error. Please try again.');
                    btn.disabled = false;
                    btn.textContent = '{{ __('klinica.billing.cancel_anyway') }}';
                });
        }

        function _startActivationPolling() {
            if (_paddleBtn) {
                _paddleBtn.classList.add('loading');
                _paddleBtn.textContent = 'Activating…';
            }

            var attempts = 0;
            var maxAttempts = 30;

            var poll = setInterval(function () {
                attempts++;

                fetch('/billing/plan-status', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.is_active) {
                            clearInterval(poll);
                            window.location.reload();
                        } else if (attempts >= maxAttempts) {
                            clearInterval(poll);
                            window.location.reload();
                        }
                    })
                    .catch(function () {
                        if (attempts >= maxAttempts) {
                            clearInterval(poll);
                            window.location.reload();
                        }
                    });
            }, 2000);
        }
    </script>
</x-filament-panels::page>

