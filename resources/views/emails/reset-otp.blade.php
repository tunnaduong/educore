<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        .greeting {
            color: #374151;
            margin-bottom: 16px;
        }

        .otp {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #111827;
            background: #f3f4f6;
            padding: 12px 16px;
            display: inline-block;
            border-radius: 8px;
        }

        .note {
            color: #6b7280;
            margin-top: 16px;
            font-size: 14px;
        }

        .footer {
            color: #9ca3af;
            margin-top: 24px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">Mã OTP đặt lại mật khẩu</div>
        <div class="greeting">Xin chào {{ $userName }},</div>
        <div>Đây là mã OTP để đặt lại mật khẩu tài khoản của bạn trên EduCore:</div>
        <div class="otp">{{ $otp }}</div>
        <div class="note">Mã có hiệu lực trong 5 phút. Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</div>
        <div class="footer">Email được gửi tự động từ hệ thống EduCore. Vui lòng không trả lời.</div>
    </div>
</body>

</html>
