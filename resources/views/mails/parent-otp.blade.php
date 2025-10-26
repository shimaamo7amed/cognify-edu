<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cognify - OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }
        .content {
            padding: 30px 0;
        }
        .otp-code {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            letter-spacing: 2px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666666;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #2c3e50; margin: 0;">Cognify Education</h1>
    </div>

    <div class="content">
        <h2 style="color: #2c3e50;">Verification Required</h2>

        <p>Dear Parent,</p>

        <p>Thank you for registering with Cognify Education. To ensure the security of your account, please use the following One-Time Password (OTP) to complete your verification:</p>

        <div class="otp-code">
            <strong>{{ $otp }}</strong>
        </div>

        <p><strong>Important:</strong> This verification code will expire in 10 minutes for security purposes.</p>

        <p>If you did not request this verification code, please ignore this email or contact our support team immediately.</p>
    </div>

    <div class="footer">
        <p>This is an automated message, please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Cognify Education. All rights reserved.</p>
    </div>
</body>
</html>
