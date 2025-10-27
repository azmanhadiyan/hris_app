{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login HRIS</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        /* ===== HRIS Login Style ===== */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #7EA039, #4A7C0F);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px 45px;
            width: 400px;
            text-align: center;
        }

        .login-header h2 {
            color: #7EA039;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #7EA039;
            box-shadow: 0 0 5px rgba(126, 160, 57, 0.5);
            outline: none;
        }

        .btn-login {
            background-color: #7EA039;
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-login:hover {
            background-color: #6a9033;
        }

        .forgot {
            display: block;
            text-align: right;
            font-size: 13px;
            color: #7EA039;
            text-decoration: none;
            margin-top: 5px;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #7EA039;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>HRIS LOGIN</h2>
            <p>Masuk ke sistem manajemen karyawan</p>
        </div>

        @if (session('error'))
            <div style="background: #ffe6e6; border-left: 4px solid #d9534f; padding: 10px; margin-bottom: 20px; color: #a94442;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: #ffe6e6; border-left: 4px solid #d9534f; padding: 10px; margin-bottom: 20px; color: #a94442;">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>


            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <a href="{{ route('password.request') }}" class="forgot">Lupa kata sandi?</a>

            <button type="submit" class="btn-login">Masuk</button>

            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </form>
    </div>
</body>
</html>
