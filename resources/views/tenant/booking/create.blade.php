<!DOCTYPE html>
<html lang="{{ $settings->locale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment — {{ $settings->clinic_name }}</title>
    <meta name="robots" content="noindex">

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
            --taupe:     #515A63;
            --beige:     #E7D7C1;
            --cream:     #F3E9DC;
            --off-white: #FAF8F5;
            --navy:      #36393F;
            --white:     #FFFFFF;
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', system-ui, sans-serif; color: var(--navy); background: var(--off-white); line-height: 1.6; }
        .serif { font-family: 'Manrope', system-ui, sans-serif; font-weight: 700; }

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
        .nav-logo-placeholder {
            width: 36px; height: 36px; border-radius: 8px;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Manrope', system-ui, sans-serif; font-size: 16px; font-weight: 700;
        }
        .nav-logo-img { width: 36px; height: 36px; border-radius: 8px; object-fit: cover; }
        .nav-name { font-family: 'Manrope', system-ui, sans-serif; font-size: 18px; font-weight: 700; color: var(--primary); }
        .nav-back { font-size: 15px; font-weight: 400; color: var(--taupe); text-decoration: none; display: flex; align-items: center; gap: 6px; transition: color 0.2s; }
        .nav-back:hover { color: var(--secondary); }

        /* Page header */
        .page-header { background: var(--primary); padding: 48px 40px 40px; }
        .page-header-inner { max-width: 1152px; margin: 0 auto; }
        @media (max-width: 767px) { .page-header { padding: 40px 20px 32px; } }
        .page-header-label { font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .page-header h1 { font-family: 'Manrope', system-ui, sans-serif; font-size: clamp(32px, 5vw, 52px); font-weight: 700; color: var(--white); line-height: 1.1; letter-spacing: -1px; }

        /* Main layout */
        .main-content { max-width: 1152px; margin: 0 auto; padding: 48px 40px; display: grid; grid-template-columns: 1fr 1.4fr; gap: 48px; align-items: start; }
        @media (max-width: 1023px) { .main-content { grid-template-columns: 1fr; } }
        @media (max-width: 767px) { .main-content { padding: 32px 20px; } }

        /* Selection panel */
        .panel { background: var(--white); border: 1px solid var(--beige); border-radius: 12px; padding: 32px; }
        .panel-title { font-family: 'Manrope', system-ui, sans-serif; font-size: 22px; font-weight: 700; color: var(--navy); margin-bottom: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 400; color: var(--navy); margin-bottom: 8px; }
        .form-input, .form-select {
            width: 100%; background: var(--cream); color: var(--navy);
            font-family: 'Inter', system-ui, sans-serif; font-size: 16px; font-weight: 400;
            padding: 0 16px; border-radius: 10px; border: 1.5px solid var(--beige); height: 46px;
            transition: border-color 0.2s; appearance: none;
        }
        .form-input:focus, .form-select:focus { outline: none; border-color: var(--secondary); }
        .form-input::placeholder { color: var(--taupe); opacity: 0.6; }
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23515A63' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; cursor: pointer;
        }
        .btn-check {
            width: 100%; background: var(--primary); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 16px; font-weight: 600;
            padding: 12px 24px 9px; border-radius: 10px; border: none;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-check:hover { background: var(--navy); }

        /* Selected service card */
        .service-selected { background: var(--off-white); border: 1px solid var(--beige); border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 14px; }
        .service-selected-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; background: var(--secondary); }
        .service-selected-name { font-family: 'Manrope', system-ui, sans-serif; font-size: 18px; font-weight: 600; color: var(--navy); }
        .service-selected-meta { font-size: 14px; color: var(--taupe); font-weight: 400; }

        /* Slots grid */
        .slots-section { margin-top: 32px; }
        .slots-date { font-size: 14px; color: var(--taupe); font-weight: 400; margin-bottom: 16px; }
        .slots-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 24px; }
        @media (max-width: 600px) { .slots-grid { grid-template-columns: repeat(3, 1fr); } }
        .slot-btn {
            background: var(--cream); color: var(--navy);
            font-family: 'Inter', system-ui, sans-serif; font-size: 15px; font-weight: 400;
            padding: 10px 8px 7px; border-radius: 10px; border: 1.5px solid var(--beige);
            cursor: pointer; text-align: center; transition: all 0.15s;
        }
        .slot-btn:hover { border-color: var(--secondary); background: var(--yellow); }
        .slot-btn.selected { background: var(--secondary); color: #fff; border-color: var(--secondary); }
        .slot-btn.booked {
            background: #f3f4f6; color: #9ca3af;
            border-color: #e5e7eb; cursor: not-allowed;
            text-decoration: line-through; opacity: 0.65;
        }
        .slot-btn.booked:hover { background: #f3f4f6; border-color: #e5e7eb; }
        .slots-empty { padding: 24px; text-align: center; background: var(--off-white); border: 1px solid var(--beige); border-radius: 10px; color: var(--taupe); font-size: 16px; font-weight: 400; }

        /* Guest info form */
        .guest-form { background: var(--white); border: 1px solid var(--beige); border-radius: 12px; padding: 32px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
        .form-divider { border: none; border-top: 1px solid var(--beige); margin: 24px 0; }
        .form-submit {
            width: 100%; background: var(--terra-btn); color: #fff;
            font-family: 'Inter', system-ui, sans-serif; font-size: 17px; font-weight: 600;
            padding: 14px 24px 11px; border-radius: 10px; border: none; cursor: pointer;
            transition: background 0.2s; margin-top: 8px;
        }
        .form-submit:hover { background: var(--secondary); }
        .form-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Alerts */
        .alert-success, .alert-error { padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 15px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #F0FAF0; border: 1px solid #B2EAC0; color: #166534; }
        .alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }

        /* Footer */
        footer { background: var(--primary); color: rgba(255,255,255,0.5); padding: 28px 40px; text-align: center; font-size: 14px; font-weight: 400; }
        footer a { color: rgba(255,255,255,0.55); text-decoration: none; }
        footer a:hover { color: var(--yellow); }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav aria-label="Navigation">
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-brand">
                @if ($settings->hasMedia('logo'))
                    <img src="{{ $settings->getFirstMediaUrl('logo') }}" alt="{{ $settings->clinic_name }} logo" class="nav-logo-img">
                @else
                    <div class="nav-logo-placeholder">{{ mb_strtoupper(mb_substr($settings->clinic_name, 0, 1)) }}</div>
                @endif
                <span class="nav-name">{{ $settings->clinic_name }}</span>
            </a>
            <a href="{{ url('/') }}" class="nav-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Back to clinic
            </a>
        </div>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-inner">
            <div class="page-header-label">{{ $settings->clinic_name }}</div>
            <h1 class="serif">Book an appointment</h1>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="main-content">

        {{-- LEFT: service + date picker --}}
        <div>
            <div class="panel">
                <div class="panel-title serif">Choose service & date</div>
                <form method="GET" action="{{ route('book.create') }}" id="select-form">
                    <div class="form-group">
                        <label class="form-label" for="service_id">Service</label>
                        <select class="form-select" id="service_id" name="service_id"
                            onchange="document.getElementById('select-form').submit()">
                            <option value="">— Select a service —</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>
                                    {{ $service->name }} ({{ $service->duration_minutes }} min{{ $service->price > 0 ? ' · €'.number_format((float) $service->price, 0) : '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input class="form-input" id="date" type="date" name="date"
                            value="{{ $date->toDateString() }}"
                            min="{{ now()->toDateString() }}"
                            onchange="document.getElementById('select-form').submit()">
                    </div>
                    <noscript>
                        <button type="submit" class="btn-check">Check availability</button>
                    </noscript>
                </form>
            </div>

            @if (request('service_id') && $selectedService)
                <div style="margin-top:16px">
                    <div class="service-selected">
                        <div class="service-selected-dot" style="background: {{ $selectedService->color ?: '#2F8082' }};"></div>
                        <div>
                            <div class="service-selected-name">{{ $selectedService->name }}</div>
                            <div class="service-selected-meta">{{ $selectedService->duration_minutes }} min @if($selectedService->price > 0)· €{{ number_format((float)$selectedService->price, 0) }}@endif</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT: slots + booking form --}}
        <div>
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

            @if (request('service_id') && $selectedService)
                @if ($slots->isNotEmpty())
                    <div class="guest-form">
                        <div class="panel-title serif">Select a time slot</div>
                        <p class="slots-date">{{ $date->format('l, F j, Y') }}</p>

                        <form method="POST" action="{{ route('book.store') }}" id="booking-form">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ request('service_id') }}">
                            <input type="hidden" name="start_at" id="selected_slot" value="{{ old('start_at') }}">

                            <div class="slots-grid" role="group" aria-label="Available time slots">
                                @foreach ($slots as $slot)
                                    @if ($slot['available'])
                                        <button type="button"
                                            class="slot-btn{{ old('start_at') === $slot['time']->toDateTimeString() ? ' selected' : '' }}"
                                            data-value="{{ $slot['time']->toDateTimeString() }}"
                                            onclick="selectSlot(this)"
                                            aria-pressed="{{ old('start_at') === $slot['time']->toDateTimeString() ? 'true' : 'false' }}">
                                            {{ $slot['time']->format('H:i') }}
                                        </button>
                                    @else
                                        <button type="button" class="slot-btn booked" disabled aria-disabled="true" title="This slot is fully booked">
                                            {{ $slot['time']->format('H:i') }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>

                            <hr class="form-divider">

                            <div class="panel-title serif" style="font-size:20px;margin-bottom:20px">Your details</div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="guest_name">Full name</label>
                                    <input class="form-input" id="guest_name" type="text" name="guest_name" value="{{ old('guest_name') }}" placeholder="Jane Smith">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="guest_phone">Phone</label>
                                    <input class="form-input" id="guest_phone" type="tel" name="guest_phone" value="{{ old('guest_phone') }}" placeholder="+1 555 0100">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="guest_email">Email address</label>
                                <input class="form-input" id="guest_email" type="email" name="guest_email" value="{{ old('guest_email') }}" placeholder="jane@example.com">
                            </div>

                            <button type="submit" class="form-submit" id="submit-btn" disabled>
                                Confirm booking →
                            </button>
                            <p style="font-size:13px;color:var(--taupe);margin-top:12px;font-weight:300;text-align:center">
                                Select a time slot above to continue
                            </p>
                        </form>
                    </div>
                @else
                    <div class="slots-empty">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:0.4"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <strong>No available slots on {{ $date->format('F j, Y') }}</strong><br>
                        <span style="font-size:14px">Try selecting a different date.</span>
                    </div>
                @endif
            @else
                <div class="slots-empty" style="padding:40px">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block;opacity:0.3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    <strong style="font-size:17px;font-weight:400">Select a service to get started</strong><br>
                    <span style="font-size:14px;margin-top:6px;display:block">Choose a service and date on the left to see available slots.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        Powered by <a href="https://klinica.space" target="_blank" rel="noopener">Klinica</a>
    </footer>

    <script>
        function selectSlot(btn) {
            document.querySelectorAll('.slot-btn').forEach(function(b) {
                b.classList.remove('selected');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('selected');
            btn.setAttribute('aria-pressed', 'true');
            document.getElementById('selected_slot').value = btn.dataset.value;
            var submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = false;
            submitBtn.nextElementSibling.style.display = 'none';
        }

        // Re-select if coming back with validation errors
        (function () {
            var slotInput = document.getElementById('selected_slot');
            if (!slotInput) { return; }
            var val = slotInput.value;
            if (val) {
                var btn = document.querySelector('[data-value="' + val + '"]');
                if (btn) {
                    btn.classList.add('selected');
                    document.getElementById('submit-btn').disabled = false;
                }
            }
        })();
    </script>
</body>
</html>
