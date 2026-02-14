<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4361ee, #7209b7);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1e293b;
            margin-bottom: 20px;
            font-size: 22px;
        }
        .content p {
            color: #475569;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #4361ee, #7209b7);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            margin: 25px 0;
            font-weight: bold;
            font-size: 18px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #7209b7, #4361ee);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.4);
        }
        .button:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .warning {
            background: #fef9c3;
            border: 1px solid #fde047;
            color: #854d0e;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
            font-size: 14px;
        }
        .warning strong {
            color: #854d0e;
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .link-box {
            direction: ltr;
            text-align: left;
            word-break: break-all;
            background: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 15px;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            font-family: monospace;
        }
        .greeting {
            font-size: 24px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .greeting span {
            color: #4361ee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $appName }}</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                مرحباً <span>{{ $user->name }}</span>،
            </div>
            
            <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك في منصة الإبلاغ عن الاحتيال.</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">إعادة تعيين كلمة المرور</a>
            </div>
            
            <p>إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني.</p>
            
            <div class="warning">
                <strong>⚠️ ملاحظة مهمة:</strong>
                رابط إعادة التعيين صالح لمدة ساعة واحدة فقط. بعد انتهاء الصلاحية، ستحتاج إلى طلب رابط جديد.
            </div>
            
            <p style="margin-top: 20px;">إذا كان لديك أي مشكلة في النقر على الزر أعلاه، يمكنك نسخ الرابط التالي ولصقه في المتصفح:</p>
            <div class="link-box">
                {{ $resetUrl }}
            </div>
            
            <p style="margin-top: 20px; font-size: 14px; color: #64748b;">
                إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني أو الاتصال بالدعم الفني.
            </p>
        </div>
        
        <div class="footer">
            <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ $appName }}</p>
            <p>هذا البريد تم إرساله تلقائياً، يرجى عدم الرد عليه.</p>
            <p style="margin-top: 10px; font-size: 12px; color: #94a3b8;">
                منصة الإبلاغ عن الاحتيال - نعمل معاً لحماية المجتمع
            </p>
        </div>
    </div>
</body>
</html>