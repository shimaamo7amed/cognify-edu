<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fawry Payment</title>
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
    </style>
</head>
<body>
    <div class="payment-container">
        <h3>{{ $message ?? 'Redirecting to payment...' }}</h3>
        <div class="loading"></div>
        
        <!-- Debug info (remove in production) -->
        <div id="debug" style="display: none; margin-top: 20px; text-align: left; font-family: monospace; font-size: 12px;"></div>
    </div>

    <!-- Render the Fawry HTML that triggers the popup -->
    {!! $html !!}

    <script>
        // Log HTML content for debugging
        const debugElement = document.getElementById('debug');
        debugElement.textContent = "HTML content length: " + 
            (document.querySelector('script:not([src])') ? 
            document.querySelector('script:not([src])').textContent.length : 'No inline script found');
        
        // Force trigger any inline scripts
        setTimeout(() => {
            const scripts = document.querySelectorAll('script:not([src])');
            scripts.forEach(script => {
                try {
                    eval(script.textContent);
                    console.log("Executed inline script");
                } catch (e) {
                    console.error("Error executing script:", e);
                }
            });
        }, 1000);
        
        // Show debug info on error
        setTimeout(() => {
            if (!window.fawryPopup && !window.location.href.includes('verify')) {
                debugElement.style.display = 'block';
                document.querySelector('.loading').style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>
