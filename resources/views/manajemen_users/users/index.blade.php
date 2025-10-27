@extends('layouts.app')

@section('content')
<br>
<div class="container">
    <h2>Manajemen Pengguna</h2>
    <a href="{{ route('manajemen_users.users.create') }}" class="btn btn-success mb-3">Tambah Pengguna</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
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
                <td>{{ $user->name }}</td>
                <td>{{ $user->username ?? NULL }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->departement ?? NULL }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
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
@endsection
