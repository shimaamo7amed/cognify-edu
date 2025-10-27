{{-- resources/views/payments/verify_error.blade.php --}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فشل الدفع</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .error-icon {
            width: 100px;
            height: 100px;
            background: #fee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .error-icon svg {
            width: 60px;
            height: 60px;
            stroke: #f44;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .error-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: right;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: transform 0.2s;
            margin: 10px;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <h1>فشلت عملية الدفع</h1>
        <p>{{ $message ?? 'حدث خطأ أثناء معالجة عملية الدفع' }}</p>

        @if(isset($description) || isset($errorId) || isset($reason))
            <div class="error-details">
                @if(isset($description))
                    <p><strong>الوصف:</strong> {{ $description }}</p>
                @endif
                @if(isset($reason))
                    <p><strong>السبب:</strong> {{ $reason }}</p>
                @endif
                @if(isset($errorId))
                    <p><strong>رقم الخطأ:</strong> {{ $errorId }}</p>
                @endif
            </div>
        @endif

        @if(isset($payment_id))
            <div class="error-details">
                <p><strong>معرف الدفع:</strong> {{ $payment_id }}</p>
            </div>
        @endif

        <div style="margin-top: 30px;">
            {{-- ✅ التحقق من وجود payment_id قبل استخدامه --}}
            @if(isset($payment_id))
                <a href="{{ route('fawry.payment.page', ['token' => $payment_id]) }}" class="btn">
                    إعادة المحاولة
                </a>
            @endif
            
            <a href="{{ url('/') }}" class="btn btn-secondary">
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>
</body>
</html>