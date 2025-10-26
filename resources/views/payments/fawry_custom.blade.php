<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fawry Payment</title>
    <link rel='stylesheet' href="https://atfawry.fawrystaging.com/atfawry/plugin/assets/payments/css/fawrypay-payments.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .payment-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }
        .loading {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid rgba(0, 123, 255, 0.3);
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 1s ease-in-out infinite;
            margin-top: 20px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h3>{{ $message ?? 'Redirecting to payment...' }}</h3>
        <div class="loading"></div>
        <p>Please wait while we redirect you to the payment page.</p>
        <p>If you are not redirected automatically, please click the button below:</p>
        <button id="paymentButton" class="btn">Proceed to Payment</button>
    </div>

    <script type='text/javascript' src="{{ config('nafezly-payments.FAWRY_URL') }}atfawry/plugin/assets/payments/js/fawrypay-payments.js"></script>
    <script>
        // Define the charge request
        const chargeRequest = {
            language: "ar-eg",
            merchantCode: "{{ config('nafezly-payments.FAWRY_MERCHANT') }}",
            merchantRefNum: "{{ $payment_id ?? '' }}",
            customerMobile: "{{ $user_phone ?? '' }}",
            customerEmail: "{{ $user_email ?? '' }}",
            customerName: "{{ $user_name ?? '' }}",
            customerProfileId: "{{ $user_id ?? '' }}",
            paymentExpiry: "{{ strtotime('+24 hours') * 1000 }}", // 24 hours from now in milliseconds
            chargeItems: [{
                itemId: "1",
                description: "{{ $description ?? 'Booking Payment' }}",
                price: {{ $amount ?? 0 }},
                quantity: 1
            }],
            returnUrl: "{{ route('verify-payment', ['payment' => 'fawry']) }}",
            paymentMethod: "{{ config('nafezly-payments.FAWRY_PAY_MODE') }}",
            signature: "{{ $signature ?? '' }}"
        };

        // Function to initiate payment
        function initiatePayment() {
            try {
                // Log the full charge request for debugging
                console.log("Fawry chargeRequest:", JSON.stringify(chargeRequest, null, 2));

                // Add debug info to the page
                const debugDiv = document.createElement('div');
                debugDiv.style.display = 'none';
                debugDiv.id = 'debug-info';
                debugDiv.style.marginTop = '20px';
                debugDiv.style.padding = '10px';
                debugDiv.style.border = '1px solid #ccc';
                debugDiv.style.backgroundColor = '#f9f9f9';
                debugDiv.style.fontSize = '12px';
                debugDiv.style.fontFamily = 'monospace';
                debugDiv.style.whiteSpace = 'pre-wrap';
                debugDiv.style.textAlign = 'left';
                debugDiv.innerHTML = `<strong>Merchant Code:</strong> ${chargeRequest.merchantCode}<br>
                                    <strong>Reference:</strong> ${chargeRequest.merchantRefNum}<br>
                                    <strong>Amount:</strong> ${chargeRequest.chargeItems[0].price}<br>
                                    <strong>Display Mode:</strong> {{ config('nafezly-payments.FAWRY_DISPLAY_MODE') }}<br>
                                    <strong>Return URL:</strong> ${chargeRequest.returnUrl}`;
                document.body.appendChild(debugDiv);

                // Show debug info on click
                const debugLink = document.createElement('a');
                debugLink.href = '#';
                debugLink.textContent = 'Show Debug Info';
                debugLink.style.display = 'block';
                debugLink.style.marginTop = '10px';
                debugLink.style.fontSize = '12px';
                debugLink.onclick = function(e) {
                    e.preventDefault();
                    const debugInfo = document.getElementById('debug-info');
                    debugInfo.style.display = debugInfo.style.display === 'none' ? 'block' : 'none';
                    this.textContent = debugInfo.style.display === 'none' ? 'Show Debug Info' : 'Hide Debug Info';
                };
                document.querySelector('.payment-container').appendChild(debugLink);

                // Initialize Fawry payment
                console.log("Initiating Fawry payment with display mode:", "{{ config('nafezly-payments.FAWRY_DISPLAY_MODE') }}");

                FawryPay.checkout(
                    chargeRequest,
                    {
                        locale: "ar",
                        mode: DISPLAY_MODE.{{ config('nafezly-payments.FAWRY_DISPLAY_MODE') }}
                    }
                );
            } catch (e) {
                console.error("Error initiating payment:", e);
                document.querySelector('.loading').style.display = 'none';

                // Show error details
                const errorDiv = document.getElementById('error-message') || document.createElement('div');
                errorDiv.id = 'error-message';
                errorDiv.style.display = 'block';
                errorDiv.style.color = 'red';
                errorDiv.style.marginTop = '20px';
                errorDiv.innerHTML = `<strong>Error:</strong> ${e.message || 'Unknown error'}`;
                document.querySelector('.payment-container').appendChild(errorDiv);
            }
        }

        // Trigger payment on page load with a delay
        window.onload = function() {
            setTimeout(initiatePayment, 1000);
        };

        // Add click handler for manual button
        document.getElementById('paymentButton').addEventListener('click', initiatePayment);
    </script>

    <div id="error-message" style="display: none; color: red; margin-top: 20px;">
        There was an error initiating the payment. Please try again or contact support.
    </div>

    <!-- Add a direct link to try a different payment method -->
    <div style="margin-top: 20px; text-align: center;">
        <p>If you continue to experience issues with the payment, please try:</p>
        <a href="https://cognfiy.vercel.app/" class="btn" style="margin-right: 10px;">Return to Home</a>
    </div>
</body>
</html>
