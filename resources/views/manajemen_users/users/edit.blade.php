@extends('layouts.app')

@section('content')
<style>
    /* ===== Container utama ===== */
    .edit-user-container {
        max-width: 700px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 30px 40px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    /* ===== Judul ===== */
    .edit-user-container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
        font-size: 26px;
    }

    /* ===== Form control ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }

    .form-group input,
    .form-group select {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px 14px;
        width: 100%;
        font-size: 15px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #7EA039;
        outline: none;
        box-shadow: 0 0 6px rgba(126,160,57,0.3);
    }

    /* ===== Tombol ===== */
    .btn-save {
        background-color: #7EA039;
        color: #fff;
        font-weight: 500;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        transition: 0.3s ease;
    }

    .btn-save:hover {
        background-color: #6a8c32;
    }

    .btn-back {
        background-color: #ccc;
        color: #333;
        font-weight: 500;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        margin-left: 8px;
        transition: 0.3s ease;
    }

    .btn-back:hover {
        background-color: #b5b5b5;
    }

    .text-center {
        text-align: center;
    }

    .note {
        font-size: 13px;
        color: #888;
        margin-top: 5px;
    }

    /* ===== Responsif ===== */
    @media (max-width: 768px) {
        .edit-user-container {
            padding: 25px 20px;
            margin: 30px 15px;
        }

        .edit-user-container h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .form-group input,
        .form-group select {
            font-size: 14px;
            padding: 9px 12px;
        }

        .btn-save, .btn-back {
            width: 100%;
            margin: 8px 0;
        }

        .text-center {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .edit-user-container {
            margin: 20px 10px;
            padding: 20px 15px;
        }

        .edit-user-container h2 {
            font-size: 20px;
        }

        .form-group label {
            font-size: 14px;
        }

        .note {
            font-size: 12px;
        }
    }
</style>

<div class="edit-user-container">
    <h2>Edit Pengguna</h2>

    <form action="{{ route('manajemen_users.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>Password (Opsional)</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
            <p class="note">Biarkan kosong jika tidak ingin mengganti password.</p>
        </div>

        <div class="form-group">
            <label>Departement</label>
            <select name="departement" required>
                <option value="" disabled {{ $user->departement == null ? 'selected' : '' }}>Pilih Departemen</option>
                <option value="DIGITAL" {{ $user->departement == 'DIGITAL' ? 'selected' : '' }}>DIGITAL</option>
                <option value="PPIC" {{ $user->departement == 'PPIC' ? 'selected' : '' }}>PPIC</option>
                <option value="HRD" {{ $user->departement == 'HRD' ? 'selected' : '' }}>HRD</option>
                <option value="MARKETING" {{ $user->departement == 'MARKETING' ? 'selected' : '' }}>MARKETING</option>
                <option value="FINANCE" {{ $user->departement == 'FINANCE' ? 'selected' : '' }}>FINANCE</option>
            </select>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn-save">Simpan</button>
            <a href="{{ route('manajemen_users.users.index') }}" class="btn-back">Kembali</a>
        </div>
    </form>
</div>
@endsection
