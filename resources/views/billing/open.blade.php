<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Checkout</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #64748b;
        }
        .wrap { text-align: center; }
        .spinner {
            width: 32px; height: 32px;
            border: 3px solid #e2e8f0;
            border-top-color: #2F8082;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .label { font-size: 15px; font-weight: 500; color: #475569; }
        .sub { margin-top: 6px; font-size: 13px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="spinner"></div>
        <div class="label">Opening secure checkout…</div>
        <div class="sub">You will be redirected automatically.</div>
    </div>

    <script>
        var _txn       = '{{ addslashes($txn) }}';
        var _returnUrl = '{{ addslashes($returnUrl) }}';
        var _cancelUrl = '{{ addslashes($cancelUrl) }}';
        var _completed = false;

        function initPaddle() {
            @if ($paddleSandbox)
            Paddle.Environment.set('sandbox');
            @endif

            Paddle.Initialize({
                token: '{{ addslashes($paddleClientToken) }}',
                eventCallback: function (event) {
                    if (event.name === 'checkout.completed') {
                        _completed = true;
                        window.location.href = _returnUrl;
                    }
                    // checkout.closed fires on both cancel AND after successful payment —
                    // only redirect to cancel URL if payment was not completed.
                    if (event.name === 'checkout.closed' && ! _completed) {
                        window.location.href = _cancelUrl;
                    }
                },
            });

            if (_txn) {
                Paddle.Checkout.open({ transactionId: _txn });
            } else {
                window.location.href = _cancelUrl;
            }
        }
    </script>
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js" onload="initPaddle()"></script>
</body>
</html>
