<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توثيق بريدك الإلكتروني - منصة الإبلاغ عن الاحتيال</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Tahoma', 'Geneva', 'Verdana', sans-serif;
            line-height: 1.8;
            color: #333;
            background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .email-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .header {
            background: linear-gradient(135deg, #4361ee, #7209b7);
            padding: 50px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .header:after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: 0;
            width: 100%;
            height: 40px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,160L48,149.3C96,139,192,117,288,117.3C384,117,480,139,576,165.3C672,192,768,224,864,208C960,192,1056,128,1152,117.3C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
        }
        
        .logo {
            font-size: 60px;
            margin-bottom: 20px;
            display: inline-block;
            background: rgba(255,255,255,0.2);
            width: 100px;
            height: 100px;
            line-height: 100px;
            border-radius: 50%;
        }
        
        .brand-name {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .brand-tagline {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .content {
            padding: 60px 40px 40px;
            text-align: right;
        }
        
        .greeting {
            font-size: 22px;
            color: #2d3748;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f9ff;
        }
        
        .greeting strong {
            color: #4361ee;
            font-weight: 700;
        }
        
        .message-box {
            background: #f8fafc;
            padding: 30px;
            border-radius: 15px;
            border-right: 5px solid #4361ee;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .message-box p {
            margin-bottom: 20px;
            color: #4a5568;
            font-size: 16px;
            line-height: 1.8;
        }
        
        .message-box strong {
            color: #4361ee;
        }
        
        .verification-btn {
            display: inline-block;
            background: linear-gradient(135deg, #f72585, #b5179e); /* Updated to your theme's pink/purple */
            color: white;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            margin: 30px auto;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(247, 37, 133, 0.3); /* Updated shadow color */
            border: none;
            cursor: pointer;
            min-width: 280px;
        }
        
        .verification-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(247, 37, 133, 0.4);
            background: linear-gradient(135deg, #e0217a, #a0158d);
        }
        
        .verification-btn:active {
            transform: translateY(-1px);
        }
        
        .btn-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .instructions {
            background: #f0f9ff;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border-right: 4px solid #4cc9f0;
        }
        
        .instructions h3 {
            color: #0369a1;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        
        .instructions ul {
            list-style: none;
            padding-right: 20px;
        }
        
        .instructions li {
            margin-bottom: 12px;
            color: #475569;
            position: relative;
            padding-right: 25px;
        }
        
        .instructions li:before {
            content: "•";
            position: absolute;
            right: 0;
            color: #4cc9f0;
            font-size: 20px;
        }
        
        .warning-box {
            background: #fef3c7;
            padding: 20px;
            border-radius: 10px;
            border-right: 4px solid #f59e0b;
            margin: 25px 0;
        }
        
        .warning-box p {
            margin: 0;
            color: #92400e;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .warning-box p:before {
            content: "⚠️";
            font-size: 18px;
        }
        
        .footer {
            text-align: center;
            padding: 30px 40px;
            background: #1e293b;
            color: #cbd5e1;
            margin-top: 40px;
        }
        
        .footer p {
            margin: 8px 0;
            font-size: 14px;
        }
        
        .copyright {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 15px;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        
        .social-icons a {
            color: #94a3b8;
            font-size: 20px;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: #f72585; /* Updated hover color to match theme */
        }
        
        .auto-note {
            background: #1e293b;
            color: #94a3b8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 12px;
            border: 1px solid #334155;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .email-container {
                border-radius: 15px;
            }
            
            .header {
                padding: 40px 20px;
            }
            
            .content {
                padding: 40px 25px 25px;
            }
            
            .verification-btn {
                width: 100%;
                min-width: auto;
                padding: 18px 30px;
            }
            
            .brand-name {
                font-size: 24px;
            }
            
            .greeting {
                font-size: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                padding: 30px 15px;
            }
            
            .content {
                padding: 30px 20px 20px;
            }
            
            .message-box {
                padding: 20px;
            }
            
            .verification-btn {
                font-size: 16px;
                padding: 16px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">📱</div>
            <h1 class="brand-name">منصة الإبلاغ عن الاحتيال</h1>
            <p class="brand-tagline">نعمل معاً لحماية المجتمع من عمليات الاحتيال</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                مرحباً <strong>{{ $user->name ?? 'عزيزي المستخدم' }}</strong>،
            </div>
            
            <div class="message-box">
                <p>نشكرك على انضمامك إلى <strong>{{ $appName ?? 'منصة الإبلاغ عن الاحتيال' }}</strong>.</p>
                <p>لبدء استخدام المنصة والاستفادة من جميع الميزات، يرجى تأكيد بريدك الإلكتروني بالنقر على الزر أدناه:</p>
            </div>
            
            <div class="btn-container">
                <a href="{{ $verificationUrl }}" class="verification-btn">
                    🔗 تأكيد البريد الإلكتروني
                </a>
            </div>
            
            <div class="instructions">
                <h3>🔍 لم تصلك الرسالة؟ إليك بعض الحلول:</h3>
                <ul>
                    <li>تفقد مجلد الرسائل غير المرغوب فيها (Spam)</li>
                    <li>تأكد من كتابة عنوان بريدك الإلكتروني بشكل صحيح</li>
                    <li>انتظر بضع دقائق ثم حاول مرة أخرى</li>
                    <li>أضف عنواننا إلى قائمة المرسلين الآمنين</li>
                </ul>
            </div>
            
            <div class="warning-box">
                <p><strong>هام:</strong> رابط التأكيد صالح لمدة 24 ساعة فقط</p>
            </div>
            
            <div style="text-align: center; color: #64748b; font-size: 14px; margin-top: 30px;">
                <p>إذا لم تكن قد أنشأت حساباً على المنصة، يرجى تجاهل هذا البريد.</p>
            </div>
        </div>
        
        <div class="footer">
            <div class="social-icons">
                <a href="#">📧</a>
                <a href="#">ℹ️</a>
                <a href="#">🛡️</a>
            </div>
            
            <p><strong>منصة الإبلاغ عن الاحتيال</strong></p>
            <p>نعمل معاً لحماية المجتمع من عمليات الاحتيال</p>
            <p>جميع الحقوق محفوظة © {{ date('Y') }}</p>
            
            <div class="auto-note">
                ⚠️ هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه
            </div>
            
            <p class="copyright">
                تم إرسال هذا البريد إلى: {{ $user->email ?? 'المستلم' }}
            </p>
        </div>
    </div>
</body>
</html>