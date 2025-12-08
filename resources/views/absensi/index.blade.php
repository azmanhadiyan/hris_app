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

    input.table-filter, select.table-filter {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 13px;
    }
    input.table-filter:focus, select.table-filter:focus {
        border-color: #7EA039;
        outline: none;
        box-shadow: 0 0 3px #7EA03933;
    }

    /* Responsive mobile card */
    @media (max-width: 768px) {
        table { display: none; }
        .mobile-card {
            display: block;
            background: white;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .mobile-card .label {
            font-weight: 600;
            color: #6b7280;
        }
        .mobile-card .value {
            margin-bottom: 6px;
        }
    }

    /* Pagination aesthetic */
    .pagination {
        display: flex;
        gap: 6px;
        justify-content: center;
        margin-top: 20px;
    }
    .pagination .page-item .page-link {
        border-radius: 8px;
        padding: 8px 14px;
        border: 1px solid #e0e0e0;
        color: #7EA039;
        font-weight: 600;
        transition: 0.2s;
    }
    .pagination .page-item.active .page-link {
        background-color: #7EA039;
        border-color: #7EA039;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background-color: #7EA039;
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success">📅 Data Absensi</h3>
        <a href="{{ route('absensi.create') }}" class="btn-green shadow-sm">
            + Tambah Absensi
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <!-- Filter -->
            <form id="filterForm" method="GET" action="{{ route('absensi.index') }}">
                <div class="row mb-3">

                    <!-- Tanggal -->
                    <div class="col-md-3 col-12 mb-2">
                        <input type="date" name="tanggal" class="form-control table-filter"
                            value="{{ request('tanggal') }}">
                    </div>

                    <!-- Bulan -->
                    <div class="col-md-3 col-12 mb-2">
                        <select name="bulan" class="form-control table-filter">
                            <option value="">Pilih Bulan</option>
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Karyawan -->
                    <div class="col-md-3 col-12 mb-2">
                        <select name="karyawan_id" class="form-control table-filter">
                            <option value="">Pilih Karyawan</option>
                            @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_lengkap }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol -->
                    <div class="col-md-3 col-12 mb-2 d-flex gap-2">
                        <button class="btn btn-primary flex-fill">Filter</button>
                        @if( collect(request()->only('tanggal','bulan','karyawan_id'))->filter()->isNotEmpty() )
                            <a href="{{ route('absensi.index') }}" class="btn btn-secondary flex-fill">Reset</a>
                        @endif


                    </div>
                </div>
            </form>

            <!-- DESKTOP TABLE -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Karyawan<br>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $absen)
                        <tr>
                            <td>{{ $loop->iteration + ($data->currentPage()-1)*$data->perPage() }}</td>
                            <td>{{ $absen->karyawan->nama_lengkap }}</td>
                            <td>{{ $absen->tanggal }}</td>
                            <td>{{ $absen->jam_masuk }}</td>
                            <td>{{ $absen->jam_pulang ?? '-' }}</td>
                            <td>
                                <span class="badge 
                                    @if($absen->status=='Hadir') bg-success
                                    @elseif($absen->status=='Izin') bg-warning
                                    @else bg-danger @endif">
                                    {{ $absen->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('absensi.edit',$absen->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('absensi.destroy',$absen->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $data->appends(request()->query())->links() }}</div>
            </div>

            <!-- MOBILE CARD VIEW -->
            <div class="d-block d-md-none">
                @forelse($data as $absen)
                <div class="mobile-card">
                    <div class="label">Karyawan:</div>
                    <div class="value">{{ $absen->karyawan->nama_lengkap }}</div>

                    <div class="label">Tanggal:</div>
                    <div class="value">{{ $absen->tanggal }}</div>

                    <div class="label">Jam Masuk:</div>
                    <div class="value">{{ $absen->jam_masuk }}</div>

                    <div class="label">Jam Pulang:</div>
                    <div class="value">{{ $absen->jam_pulang ?? '-' }}</div>

                    <div class="label">Status:</div>
                    <div class="value">
                        <span class="badge 
                            @if($absen->status=='Hadir') bg-success
                            @elseif($absen->status=='Izin') bg-warning
                            @else bg-danger @endif">
                            {{ $absen->status }}
                        </span>
                    </div>

                    <div class="actions text-end mt-2">
                        <a href="{{ route('absensi.edit',$absen->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('absensi.destroy',$absen->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted">Tidak ada data</p>
                @endforelse

                <div class="mt-4">{{ $data->appends(request()->query())->links() }}</div>
            </div>

        </div>
    </div>
</div>
@endsection
