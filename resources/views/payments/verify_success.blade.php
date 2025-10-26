<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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
        .success-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .success-icon {
            color: #28a745;
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .payment-details {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
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
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>Payment Successful</h1>
        <p>{{ $message ?? 'Your payment has been successfully processed.' }}</p>

        <div class="payment-details">
            <p><strong>Payment ID:</strong> {{ $payment_id ?? 'N/A' }}</p>
            @if(isset($case_id))
                <p><strong>Case ID:</strong> {{ $case_id }}</p>
            @endif
        </div>

        <a href="https://cognfiy.vercel.app/" class="btn">Return to Home</a>
    </div>
</body>
</html>
