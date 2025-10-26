<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Partnership Request</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #007bff; text-align: center;">New Partnership Request</h2>
    </div>

    <div class="content">
        <p>Dear Sir,</p>
        <p>We would like to inform you that a new partnership request has been received. Here are the request details:</p>

        <h3>Organization Information:</h3>
        <p><span class="label">Organization Name:</span> {{ $partner->organizationName }}</p>
        <p><span class="label">Contact Person:</span> {{ $partner->contactPersonName }}</p>
        <p><span class="label">Email:</span> {{ $partner->email }}</p>
        <p><span class="label">Phone Number:</span> {{ $partner->phoneNumber }}</p>
        <p><span class="label">Location:</span> {{ $partner->location }}</p>

        <h3>Service Details:</h3>
        <p><span class="label">Service Type:</span> {{ $partner->service->title['en'] }}</p>

        @if($partner->serviceItems->count() > 0)
        <p><span class="label">Selected Items:</span></p>
        <ul>
            @foreach($partner->serviceItems as $item)
                <li>{{ $item->title['en'] }}</li>
            @endforeach
        </ul>
        @endif

        <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            <p>Please review and approve this request through the admin dashboard.</p>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated email - please do not reply</p>
    </div>
</body>
</html>
