<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Request Approved</title>
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
            <h1 style="color: #2c3e50;">Partnership Request Approved</h1>
        </div>

        <div class="content">
            <p>Dear {{ $partner->contactPersonName }},</p>

            <p>We are pleased to inform you that your partnership request for "{{ $partner->organizationName }}" has been approved. Welcome to the Cognify family!</p>

            <p>This partnership marks the beginning of what we believe will be a mutually beneficial relationship. Our team is excited to collaborate with you and help achieve our shared goals.</p>

            <p>What happens next:</p>
            <ul>
                <li>Our partnership team will contact you within the next 48 hours</li>
                <li>We will schedule an onboarding meeting to discuss next steps</li>
                <li>You will receive access to our partner portal and resources</li>
            </ul>

            <p>If you have any immediate questions or concerns, please don't hesitate to reach out to our partnership team.</p>
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
