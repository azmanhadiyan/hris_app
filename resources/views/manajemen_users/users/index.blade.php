@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Poppins', sans-serif;
    }

    h2 {
        color: #333;
        font-weight: 600;
    }

    .table-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 25px;
        overflow-x: auto;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    thead th {
        background-color: #7EA039;
        color: white;
        text-align: center;
        vertical-align: middle;
    }

    tbody td {
        vertical-align: middle;
    }

    .btn-success {
        background-color: #7EA039;
        border-color: #7EA039;
    }

    .btn-success:hover {
        background-color: #6a8d33;
        border-color: #6a8d33;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .table-container {
            padding: 15px;
        }

        h2 {
            font-size: 22px;
        }

        .btn {
            font-size: 13px;
            padding: 6px 10px;
        }

        table thead {
            display: none; /* Sembunyikan header di HP */
        }

        table tbody td {
            display: block;
            width: 100%;
            text-align: right;
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            text-align: left;
            font-weight: 600;
            color: #555;
        }

        table tbody tr {
            margin-bottom: 15px;
            display: block;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 10px 0;
        }
    }
</style>

<br>
<div class="container">
    <h2 class="mb-3">Manajemen Pengguna</h2>
    <a href="{{ route('manajemen_users.users.create') }}" class="btn btn-success mb-3">Tambah Pengguna</a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-container">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Departement</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td data-label="Nama">{{ $user->name }}</td>
                    <td data-label="Username">{{ $user->username ?? '-' }}</td>
                    <td data-label="Email">{{ $user->email }}</td>
                    <td data-label="Departement">{{ $user->departement ?? '-' }}</td>
                    <td data-label="Role">{{ ucfirst($user->role) }}</td>
                    <td data-label="Aksi">
                        <a href="{{ route('manajemen_users.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('manajemen_users.users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
