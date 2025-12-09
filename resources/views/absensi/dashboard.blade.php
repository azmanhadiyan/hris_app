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
        .pagination {
            flex-wrap: wrap;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            padding: 6px 10px;
            font-size: 12px;
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

    <h3 class="fw-bold mb-4">📊 Dashboard Absensi</h3>

    {{-- FILTER SECTION --}}
    <div class="card shadow-sm border-0 rounded-4 p-3 mb-4">
        <form method="GET" action="{{ route('absensi.dashboard') }}">
            <div class="row g-2">

                {{-- Filter Tanggal Dari --}}
                <div class="col-md-3">
                    <label class="small text-muted">Tanggal Dari</label>
                    <input type="date" name="tanggal_from" class="form-control"
                        value="{{ request('tanggal_from') }}">
                </div>

                {{-- Filter Tanggal Sampai --}}
                <div class="col-md-3">
                    <label class="small text-muted">Tanggal Sampai</label>
                    <input type="date" name="tanggal_to" class="form-control"
                        value="{{ request('tanggal_to') }}">
                </div>

                {{-- Filter Bulan --}}
                <div class="col-md-3">
                    <label class="small text-muted">Filter Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ([
                            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                        ] as $num => $bulan)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Karyawan --}}
                <div class="col-md-3">
                    <label class="small text-muted">Filter Karyawan</label>
                    <select name="karyawan_id" class="form-control">
                        <option value="">-- Semua Karyawan --</option>
                        @foreach ($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Button --}}
                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-success">Filter</button>

                        @if( collect(request()->only('tanggal_from','tanggal_to','bulan','karyawan_id'))->filter()->isNotEmpty() )
                            <a href="{{ route('absensi.dashboard') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </form>
    </div>


    <div class="row g-3">

       <div class="d-flex gap-3 flex-wrap">

            <div class="card shadow-sm p-3 border-0 rounded-4 text-center flex-fill">
                <h6 class="text-muted">Hadir</h6>
                <h2 class="text-success fw-bold">{{ $summary['hadir'] }}</h2>
            </div>

            <div class="card shadow-sm p-3 border-0 rounded-4 text-center flex-fill">
                <h6 class="text-muted">Izin</h6>
                <h2 class="text-primary fw-bold">{{ $summary['izin'] }}</h2>
            </div>

            <div class="card shadow-sm p-3 border-0 rounded-4 text-center flex-fill">
                <h6 class="text-muted">Sakit</h6>
                <h2 class="text-warning fw-bold">{{ $summary['sakit'] }}</h2>
            </div>

            <div class="card shadow-sm p-3 border-0 rounded-4 text-center flex-fill">
                <h6 class="text-muted">Alpha</h6>
                <h2 class="text-danger fw-bold">{{ $summary['alpha'] }}</h2>
            </div>

            <div class="card shadow-sm p-3 border-0 rounded-4 text-center flex-fill">
                <h6 class="text-muted">Cuti</h6>
                <h2 class="text-danger fw-bold">{{ $summary['cuti'] }}</h2>
            </div>

        </div>

        {{-- Chart --}}
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm p-4 border-0 rounded-4">
                <h5 class="fw-semibold mb-3">Grafik Kehadiran</h5>
                <canvas id="attendanceChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm p-4 border-0 rounded-4">
                <h5 class="mt-5">Grafik Kehadiran Per Hari {{$bulan_ini ?? NULL}}</h5>
                <div style="height: 300px;">
                    <canvas id="dailyChart"></canvas>
                </div>

            </div>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm border-0 rounded-4">
                
                <div class="card-header bg-white border-0">
                    <h5 class="fw-semibold">📅 Absensi Hari Ini</h5>
                </div>

                <div class="card-body">

                    <!-- DESKTOP TABLE -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($todayAbsensi as $abs)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $abs->karyawan->nama_lengkap }}</td>
                                    <td>{{ $abs->tanggal }}</td>
                                    <td>{{ $abs->jam_masuk }}</td>
                                    <td>{{ $abs->jam_pulang ?? '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($abs->status=='Hadir') bg-success
                                            @elseif($abs->status=='Izin') bg-primary
                                            @elseif($abs->status=='Sakit') bg-warning
                                            @else bg-danger @endif">
                                            {{ $abs->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada absensi hari ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $todayAbsensi->links() }}
                        </div>
                    </div>

                    <!-- MOBILE CARD VIEW -->
                    <div class="d-block d-md-none">
                        @forelse ($todayAbsensi as $abs)
                        <div class="mobile-card">

                            <div class="label">Karyawan:</div>
                            <div class="value">{{ $abs->karyawan->nama_lengkap }}</div>

                            <div class="label">Tanggal:</div>
                            <div class="value">{{ $abs->tanggal }}</div>

                            <div class="label">Jam Masuk:</div>
                            <div class="value">{{ $abs->jam_masuk }}</div>

                            <div class="label">Jam Pulang:</div>
                            <div class="value">{{ $abs->jam_pulang ?? '-' }}</div>

                            <div class="label">Status:</div>
                            <div class="value">
                                <span class="badge 
                                    @if($abs->status=='Hadir') bg-success
                                    @elseif($abs->status=='Izin') bg-primary
                                    @elseif($abs->status=='Sakit') bg-warning
                                    @else bg-danger @endif">
                                    {{ $abs->status }}
                                </span>
                            </div>

                        </div>
                        @empty
                        <p class="text-center text-muted">Belum ada absensi hari ini</p>
                        @endforelse
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $todayAbsensi->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

{{-- Chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('attendanceChart');

    const monthNames = [
        "Januari","Februari","Maret","April","Mei","Juni",
        "Juli","Agustus","September","Oktober","November","Desember"
    ];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($chartData['Hadir']),
                    borderColor: '#198754',
                    tension: 0.4,
                    borderWidth: 3
                },
                {
                    label: 'Izin',
                    data: @json($chartData['Izin']),
                    borderColor: '#0d6efd',
                    tension: 0.4,
                    borderWidth: 3
                },
                {
                    label: 'Sakit',
                    data: @json($chartData['Sakit']),
                    borderColor: '#ffc107',
                    tension: 0.4,
                    borderWidth: 3
                },
                {
                    label: 'Alpha',
                    data: @json($chartData['Alpha']),
                    borderColor: '#dc3545',
                    tension: 0.4,
                    borderWidth: 3
                },
                {
                    label: 'Cuti',
                    data: @json($chartData['Cuti']),
                    borderColor: '#6f42c1',
                    tension: 0.4,
                    borderWidth: 3
                }
            ]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });

const dailyChartData = @json($dailyChart);

// Labels = tanggal 'YYYY-MM-DD' terurut
const labels = Object.keys(dailyChartData);

// Format label untuk tampilan (mis. "01 Nov" / "09 Des")
const formattedLabels = labels.map(d => {
    if (d.length === 10) {
        const dt = new Date(d);
        return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    // fallback jika hanya angka
    return d;
});
console.log("LABELS:", labels);



// Ambil dataset per status
const hadirData = labels.map(day => (dailyChartData[day]?.Hadir ?? 0));
const izinData  = labels.map(day => (dailyChartData[day]?.Izin ?? 0));
const sakitData = labels.map(day => (dailyChartData[day]?.Sakit ?? 0));
const alphaData = labels.map(day => (dailyChartData[day]?.Alpha ?? 0));
const cutiData  = labels.map(day => (dailyChartData[day]?.Cuti ?? 0));

const ctx1 = document.getElementById('dailyChart');

new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: formattedLabels,
        datasets: [
            { label: 'Hadir', data: hadirData, backgroundColor: '#198754' },
            { label: 'Izin', data: izinData, backgroundColor: '#0d6efd' },
            { label: 'Sakit', data: sakitData, backgroundColor: '#ffc107' },
            { label: 'Alpha', data: alphaData, backgroundColor: '#dc3545' },
            { label: 'Cuti', data: cutiData, backgroundColor: '#6f42c1' }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { stacked: false },
            y: { beginAtZero: true }
        },
        plugins: {
            tooltip: { mode: 'index', intersect: false },
            legend: { position: 'top' }
        }
    }
});
</script>


@endsection
