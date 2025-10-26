<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Request Status Update</title>
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
            <h1 style="color: #2c3e50;">Partnership Request Update</h1>
        </div>

        <div class="content">
            <p>Dear {{ $partner->contactPersonName }},</p>

            <p>Thank you for your interest in partnering with Cognify and for taking the time to submit your partnership request for "{{ $partner->organizationName }}".</p>

            <p>After careful consideration of your application, we regret to inform you that we are unable to proceed with the partnership at this time. While we appreciate your interest, we have determined that the current opportunity does not align with our partnership strategy and requirements.</p>

            <p>Please note that this decision does not reflect on the quality of your organization. We encourage you to:</p>
            <ul>
                <li>Review our partnership criteria for future opportunities</li>
                <li>Consider reapplying in the future as your organization evolves</li>
                <li>Stay connected with us for updates on new partnership programs</li>
            </ul>

            <p>We appreciate your understanding and wish you continued success in your business endeavors.</p>
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
