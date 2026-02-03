<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify your email</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f6f8; padding:40px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:30px; border-radius:8px;">
                    <tr>
                        <td align="center">
                            <h2 style="color:#1f2937;">Welcome to Cod Intelligence 🚀</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#374151; font-size:16px;">
                            <p>Hi <strong>{{ $name }}</strong>,</p>

                            <p>
                                Thanks for joining <strong>Cod Intelligence</strong>.<br>
                                Please confirm your email address to activate your account.
                            </p>

                            <p style="text-align:center; margin:30px 0;">
                                <a href="{{ $verificationUrl }}"
                                   style="
                                     background:#2563eb;
                                     color:#ffffff;
                                     padding:14px 28px;
                                     text-decoration:none;
                                     border-radius:6px;
                                     font-weight:bold;
                                     display:inline-block;
                                   ">
                                    Verify Email
                                </a>
                            </p>

                            <p>
                                If you didn’t create an account, you can safely ignore this email.
                            </p>

                            <p style="margin-top:30px;">
                                Best regards,<br>
                                <strong>Cod Intelligence Team</strong><br>
                                <small>mimorocco.com</small>
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="color:#9ca3af; font-size:12px; margin-top:15px;">
                    © {{ date('Y') }} Cod Intelligence. All rights reserved.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
