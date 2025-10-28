<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 400px;
            max-width: 90%;
        }
        h1 {
            color: #1a202c;
            margin-bottom: 10px;
        }
        p {
            color: #4a5568;
            font-size: 15px;
        }
        .spinner {
            margin-top: 25px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #3182ce;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #718096;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Processing your payment...</h1>
        <p>Please wait while we redirect you to Fawry to complete your order.</p>

        <div class="spinner"></div>

        <form id="fawryForm" action="{{ $redirect_url }}" method="GET">
            @csrf
            <input type="hidden" name="payment_id" value="{{ $payment_id }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="token" value="{{ $token }}">
        </form>

        <div class="footer">Redirecting to Fawry Secure Payment...</div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('fawryForm').submit();
        }, 1000);
    </script>

</body>
</html>
