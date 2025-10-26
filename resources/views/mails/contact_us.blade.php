<!DOCTYPE html>
<html lang="en">

<head>
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        table {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 16px;
        }
        td {
            background-color: #ecf0f1;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #7f8c8d;
            background: #ecf0f1;
            margin-top: 20px;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th colspan="2" style="text-align:center; font-size: 20px; background-color: #2c3e50; color: #fff;">
                📩 New Contact Request
            </th>
        </tr>

        <tr>
            <td><strong>Full Name:</strong></td>
            <td>{{ $emailData['fullName'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong></td>
            <td>{{ $emailData['phone'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><a href="mailto:{{ $emailData['email'] }}">{{ $emailData['email'] ?? 'N/A' }}</a></td>
        </tr>
        <tr>
            <td><strong>Message:</strong></td>
            <td>{!! nl2br(e($emailData['message'] ?? 'No message provided.')) !!}</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $emailData['date'] ?? 'N/A' }}</td>
        </tr>

    </table>
</body>

</html>
