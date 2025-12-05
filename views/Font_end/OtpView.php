<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực OTP</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 380px;
            background: #fff;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 26px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #888;
        }

        .input-wrap input {
            width: 86%;
            padding: 12px 12px 12px 38px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.25s ease;
        }

        .input-wrap input:focus {
            border-color: #2575fc;
            box-shadow: 0 0 0 3px rgba(37,117,252,0.15);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #6a11cb;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 8px;
            font-weight: bold;
            transition: 0.25s ease;
        }

        .btn-login:hover {
            background: #5a0fbb;
        }

        .link-group {
            text-align: center;
            margin-top: 18px;
        }

        .link-group a {
            text-decoration: none;
            color: #2575fc;
            font-weight: bold;
        }

        .link-group a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Xác thực OTP</h2>

    <form action="<?php echo APP_URL; ?>/AuthController/verifyOtp" method="POST">

        <div class="form-group">
            <label>Mã OTP</label>
            <div class="input-wrap">
                <i class="fa-solid fa-shield-halved"></i>
                <input type="text" name="otp" placeholder="Nhập mã OTP" required>
            </div>
        </div>

        <button class="btn-login">Xác nhận</button>

        <div class="link-group">
            <a href="<?php echo APP_URL; ?>/AuthController/ShowRegister">← Quay lại đăng ký</a>
        </div>

    </form>
</div>

</body>
</html>
