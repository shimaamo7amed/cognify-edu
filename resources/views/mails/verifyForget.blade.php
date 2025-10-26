<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify</title>
    <style>
        body {
            color: #3CB7A8;
            background-color: rgb(255, 255, 255);
            border-radius: 10px;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .formm {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-12 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            flex: 0 0 100%;
            max-width: 100%;
        }

        h2.text-center {
            text-align: center;
            color: #3CB7A8;

        }

        h2.fw-medium {
            font-weight: 500;
        }

        h2.fw-bold {
            font-weight: bold;
        }

        .hstack {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hstack.gap-3>* {
            margin-right: 1rem;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="formm">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {{-- <h2 class="fw-medium text-center">Use This Code to Verify</h2> --}}
                    <div class="content">
                        <p>Dear {{ $name }},</p>
                        <p>It seems you've requested to reset your password for the account associated with
                            {{ $email }}. To proceed, please use the verification code provided below:</p>
                        <h2 class="fw-bold text-center">Your OTP code is:</h2>
                        <div class="hstack col-md-6 mx-auto gap-3 justify-content-center">
                            @foreach ($code_array as $item)
                                <div class="p-2">
                                    <div class="form-control text-center">{{ $item }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- <p>If you have any questions or need further assistance, please contact our support team.</p>
                    <p>Thank you,<br>The [SHIMA885] Team</p> --}}

                </div>
            </div>
        </div>
    </div>
</body>

</html>
