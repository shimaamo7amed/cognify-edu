<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff5f5;
            text-align: center;
            padding-top: 100px;
            color: #c53030;
        }
        .card {
            background: #fff;
            display: inline-block;
            padding: 30px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 { color: #e53e3e; }
        p { color: #c53030; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Payment Failed ❌</h1>
        <p>{{ $message ?? 'We could not process your payment. Please try again.' }}</p>
    </div>
</body>
</html>
