<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification Failed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .error-icon {
            color: #dc3545;
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .error-details {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            font-family: monospace;
            word-break: break-all;
        }
        .btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1>Payment Verification Failed</h1>
        <p>{{ $message ?? 'We encountered an error while processing your payment.' }}</p>

        @if(isset($description) || isset($reason) || isset($errorId))
            <div class="error-details">
                @if(isset($description))
                    <p><strong>Description:</strong> {{ $description }}</p>
                @endif

                @if(isset($reason))
                    <p><strong>Reason:</strong> {{ $reason }}</p>
                @endif

                @if(isset($errorId))
                    <p><strong>Error ID:</strong> {{ $errorId }}</p>
                @endif
            </div>
        @endif

        <a href="{{ route('fawry.payment.page', ['token' => $payment_id]) }}" class="btn">Return to Payment</a>
    </div>
</body>
</html>
