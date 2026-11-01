<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('registration.provisioning_title') }}</title>
    <style>
        :root { color-scheme: light dark; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #f4f7f7;
            color: #1f2937;
        }
        @media (prefers-color-scheme: dark) {
            body { background: #0f1717; color: #e5e7eb; }
            .card { background: #16201f !important; }
        }
        .card {
            background: #fff;
            max-width: 440px;
            width: 100%;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .08);
        }
        .spinner {
            width: 48px;
            height: 48px;
            margin: 0 auto 24px;
            border: 4px solid rgba(47, 128, 130, .2);
            border-top-color: #2f8082;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { font-size: 1.25rem; margin: 0 0 8px; }
        p { margin: 0; color: #6b7280; line-height: 1.5; }
        @media (prefers-color-scheme: dark) { p { color: #9ca3af; } }
        .error { color: #b91c1c; }
        @media (prefers-color-scheme: dark) { .error { color: #f87171; } }
    </style>
</head>
<body>
    <div class="card">
        <div id="spinner" class="spinner"></div>
        <h1 id="heading">{{ __('registration.provisioning_heading') }}</h1>
        <p id="subtext">{{ __('registration.provisioning_subtext') }}</p>
        <p id="error" class="error" style="display:none"></p>
    </div>

    <script>
        const statusUrl = @json(route('registering.status', ['tenant' => $tenant->id]));
        const failedMsg = @json(__('registration.provisioning_failed'));
        const timeoutMsg = @json(__('registration.provisioning_timeout'));
        const maxMs = 90000;
        let elapsed = 0;

        function showError(message) {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('heading').style.display = 'none';
            document.getElementById('subtext').style.display = 'none';
            const el = document.getElementById('error');
            el.style.display = 'block';
            el.textContent = message;
        }

        async function poll() {
            try {
                const response = await fetch(statusUrl, { headers: { Accept: 'application/json' } });
                const data = await response.json();

                if (data.status === 'ready' && data.login_url) {
                    window.location.href = data.login_url;
                    return;
                }

                if (data.status === 'failed') {
                    showError(failedMsg);
                    return;
                }
            } catch (e) {
                // Transient error — keep polling until the timeout.
            }

            elapsed += 2000;

            if (elapsed >= maxMs) {
                showError(timeoutMsg);
                return;
            }

            setTimeout(poll, 2000);
        }

        setTimeout(poll, 1500);
    </script>
</body>
</html>
