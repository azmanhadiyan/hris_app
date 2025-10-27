<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register HRIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .register-box {
            max-width: 420px;
            margin: 5% auto;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .btn-success {
            background-color: #7EA039;
            border-color: #7EA039;
        }
        .btn-success:hover {
            background-color: #6c8c31;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h3 class="text-center mb-4">Daftar Akun HRIS</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Peran (Role)</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="departement" class="form-label">Departement</label>
            <select name="departement" id="departement" class="form-select" required>
                <option value="" disabled selected>Pilih Departemen</option>
                <option value="DIGITAL">DIGITAL</option>
                <option value="PPIC">PPIC</option>
                <option value="HRD">HRD</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></small>
        </div>
    </form>
</div>

</body>
</html>
