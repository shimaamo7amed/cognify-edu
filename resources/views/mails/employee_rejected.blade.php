<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Application Status Update</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
        }
        .logo {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ $message->embed(public_path('storage/images/logo.jpeg')) }}" alt="Cognify Logo" style="max-width: 150px;">
            </div>
            <h1 style="color: #2c3e50;">Employment Application Update</h1>
        </div>

        <div class="content">
            <p>Dear {{ $employee->first_name }} {{ $employee->last_name }},</p>

            <p>Thank you for your interest in joining Cognify and for taking the time to submit your employment application.</p>

            <p>After careful consideration of your application, we regret to inform you that we are unable to offer you a position at this time. While we appreciate your interest and qualifications, we have determined that other candidates' qualifications more closely match our current needs.</p>

            <p>Please note that this decision does not reflect on your capabilities. We encourage you to:</p>
            <ul>
                <li>Keep your skills and qualifications updated</li>
                <li>Consider applying for future positions that match your expertise</li>
                <li>Stay connected with us for updates on new job opportunities</li>
            </ul>

            <p>We appreciate your understanding and wish you success in your career endeavors.</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>
            The Cognify Team</p>

            <p style="font-size: 12px; color: #999;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>
