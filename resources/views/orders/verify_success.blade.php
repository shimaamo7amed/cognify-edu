<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0fff4;
            text-align: center;
            padding-top: 100px;
            color: #2f855a;
        }
        .card {
            background: #fff;
            display: inline-block;
            padding: 30px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 { color: #38a169; }
        p { color: #2f855a; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Payment Successful 🎉</h1>
        <p>{{ $message ?? 'Your order has been successfully completed.' }}</p>
    </div>
</body>
</html>
