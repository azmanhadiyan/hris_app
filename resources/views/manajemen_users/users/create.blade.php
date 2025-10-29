@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa, #f4fdf4);
        font-family: 'Poppins', sans-serif;
    }

    .form-container {
        max-width: 650px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 35px 40px;
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.3s ease;
    }

    .form-container:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        font-size: 22px;
        font-weight: 600;
        color: #7EA039;
        margin-bottom: 30px;
    }

    label {
        font-weight: 500;
        color: #444;
        margin-bottom: 6px;
        display: block;
        font-size: 14px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    input:focus,
    select:focus {
        border-color: #7EA039;
        box-shadow: 0 0 5px rgba(126, 160, 57, 0.3);
        outline: none;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper button {
        position: absolute;
        top: 8px;
        right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        color: #888;
    }

    .password-wrapper button:hover {
        color: #7EA039;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #eee;
        margin-top: 25px;
        padding-top: 20px;
    }

    .btn {
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        transition: 0.3s;
    }

    .btn-cancel {
        background-color: #ccc;
        color: #333;
    }

    .btn-cancel:hover {
        background-color: #b1b1b1;
    }

    .btn-save {
        background-color: #7EA039;
        color: white;
    }

    .btn-save:hover {
        background-color: #6b8e2f;
    }
</style>

<div class="form-container">
    <h2>Tambah Pengguna</h2>

    <form method="POST" action="{{ route('manajemen_users.users.store') }}">
        @csrf

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" placeholder="Masukkan nama pengguna" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan alamat email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                <button type="button" onclick="togglePassword()">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5
                            12 5c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7
                            -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label>Role Pengguna</label>
            <select name="role" required>
                <option value="" disabled selected>Pilih Role</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="form-group">
            <label>Departemen</label>
            <select name="departement" required>
                <option value="" disabled selected>Pilih Departemen</option>
                <option value="DIGITAL">DIGITAL</option>
                <option value="PPIC">PPIC</option>
                <option value="HRD">HRD</option>
                <option value="MARKETING">MARKETING</option>
                <option value="FINANCE">FINANCE</option>
            </select>
        </div>

        <div class="form-actions">
            <a href="{{ route('manajemen_users.users.index') }}" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-save">Simpan</button>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19
                c-4.478 0-8.268-2.943-9.542-7
                a9.978 9.978 0 013.24-4.356M9.88 9.88
                a3 3 0 104.24 4.24" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />`;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5
                12 5c4.478 0 8.268 2.943 9.542 7
                -1.274 4.057-5.064 7-9.542 7
                -4.477 0-8.268-2.943-9.542-7z" />`;
    }
}
</script>
@endsection
