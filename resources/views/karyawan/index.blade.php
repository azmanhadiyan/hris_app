@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa, #f4fdf4);
        font-family: "Segoe UI", sans-serif;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .btn-green {
        background-color: #7EA039;
        color: white;
        border-radius: 6px;
        padding: 6px 14px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-green:hover {
        background-color: #6b8e2f;
    }

    .btn-reset {
        background-color: #9ca3af;
        color: white;
        border-radius: 6px;
        padding: 6px 12px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-reset:hover {
        background-color: #6b7280;
    }

    input.table-filter {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 13px;
    }

    input.table-filter:focus {
        border-color: #7EA039;
        outline: none;
        box-shadow: 0 0 3px #7EA03933;
    }

    /* === Responsive table handling === */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        min-width: 700px; /* agar struktur tabel tetap rapi di mobile scroll */
    }

    @media (max-width: 768px) {
        /* Ganti tabel ke gaya kartu */
        .table-responsive {
            overflow: visible;
        }

        table {
            display: none;
        }

        .mobile-card {
            display: block;
            background: white;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }

        .mobile-card .label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }

        .mobile-card .value {
            font-size: 14px;
            color: #111827;
            margin-bottom: 6px;
        }

        .mobile-card .actions {
            margin-top: 8px;
        }

        .mobile-card .btn {
            font-size: 12px;
            padding: 4px 8px;
        }
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h3 class="text-success fw-semibold mb-2">Data Karyawan</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('karyawan.create') }}" class="btn-green">+ Tambah</a>
            <a href="{{ route('karyawan.index') }}" class="btn-reset">Reset</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <!-- === Desktop Table === -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle" id="karyawanTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIP<br>
                            <input type="text" name="nip" class="table-filter" value="{{ request('nip') }}" placeholder="Cari NIP">
                        </th>
                        <th>Nama<br>
                            <input type="text" name="nama" class="table-filter" value="{{ request('nama') }}" placeholder="Cari Nama">
                        </th>
                        <th>Email<br>
                            <input type="text" name="email" class="table-filter" value="{{ request('email') }}" placeholder="Cari Email">
                        </th>
                        <th>Departemen<br>
                            <input type="text" name="departemen" class="table-filter" value="{{ request('departemen') }}" placeholder="Cari Departemen">
                        </th>
                        <th>Jabatan<br>
                            <input type="text" name="jabatan" class="table-filter" value="{{ request('jabatan') }}" placeholder="Cari Jabatan">
                        </th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $index => $karyawan)
                    <tr>
                        <td>{{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}</td>
                        <td>{{ $karyawan->nip }}</td>
                        <td>{{ $karyawan->nama_lengkap }}</td>
                        <td>{{ $karyawan->email }}</td>
                        <td>{{ $karyawan->departemen }}</td>
                        <td>{{ $karyawan->jabatan }}</td>
                        <td class="text-center">
                            <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $karyawans->links() }}
            </div>
        </div>

        <!-- === Mobile Card View === -->
        <div class="d-block d-md-none">
            @forelse($karyawans as $index => $karyawan)
            <div class="mobile-card">
                <div class="label">No:</div>
                <div class="value">{{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}</div>

                <div class="label">NIP:</div>
                <div class="value">{{ $karyawan->nip }}</div>

                <div class="label">Nama:</div>
                <div class="value">{{ $karyawan->nama_lengkap }}</div>

                <div class="label">Email:</div>
                <div class="value">{{ $karyawan->email }}</div>

                <div class="label">Departemen:</div>
                <div class="value">{{ $karyawan->departemen }}</div>

                <div class="label">Jabatan:</div>
                <div class="value">{{ $karyawan->jabatan }}</div>

                <div class="actions text-end">
                    <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-center text-muted py-3">Tidak ada data ditemukan</p>
            @endforelse

            <div class="mt-3">
                {{ $karyawans->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.table-filter');

    filters.forEach(input => {
        input.addEventListener('keyup', function() {
            clearTimeout(window.searchDelay);
            window.searchDelay = setTimeout(() => {
                const params = new URLSearchParams();
                filters.forEach(f => {
                    if (f.value.trim() !== '') {
                        params.append(f.name, f.value);
                    }
                });
                window.location = `?${params.toString()}`;
            }, 400);
        });
    });
});
</script>
@endsection
