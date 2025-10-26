<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Application Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
            color: #333;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 140px;
            margin-bottom: 10px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .content ul {
            margin-left: 20px;
        }
        .credentials {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .credentials p {
            margin: 8px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #777;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }
        a.button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #f59e0b;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background-color: #d97706;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ $message->embed(public_path('storage/images/logo.jpeg')) }}" alt="Cognify Logo">
            </div>
            <h2>Employment Application Approved</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $employee->name }} {{ $employee->middle_name }} {{ $employee->last_name }},</p>

            <p>We are pleased to inform you that your employment application has been <strong>successfully approved</strong>.</p>

            <p>Welcome to the <strong>Cognify</strong> team! We’re excited to have you join us and contribute to our mission and growth.</p>

            <p><strong>What to expect next:</strong></p>
            <ul>
                <li>You will be contacted by our HR team within 48 hours.</li>
                <li>An onboarding session will be scheduled to discuss your role and expectations.</li>
                <li>You will receive more details about your start date and initial responsibilities.</li>
            </ul>

            <div class="credentials">
                <p><strong>Your login credentials:</strong></p>
                <p>Email: {{ $employee->email }}</p>
                <p>Password: {{ $password }}</p>
                <p>
                    You can log in using the link below:
                    <br>
                    <a href="{{ route('filament.employeePanel.auth.login') }}" class="button">Go to Login Page</a>
                </p>
            </div>

            <p>If you have any questions or need further assistance, feel free to reach out to our HR team at any time.</p>
        </div>

        <div class="footer">
            <p>Kind regards,<br>The Cognify Team</p>
            <p style="font-size: 11px; color: #aaa;">This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
