@extends('layouts.app')

@section('content')
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
                <canvas id="dailyChart" height="100"></canvas>
            </div>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-semibold">Absensi Hari Ini</h5>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
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
                                        <span class="badge bg-{{ 
                                            $abs->status == 'Hadir' ? 'success' :
                                            ($abs->status == 'Izin' ? 'primary' :
                                            ($abs->status == 'Sakit' ? 'warning' : 'danger'))
                                        }}">
                                            {{ $abs->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

    const labels = @json($chartData->pluck('bulan')->map(fn($b)=>$b));
    const values = @json($chartData->pluck('total'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.map(b => monthNames[b - 1]),
            datasets: [{
                label: 'Total Kehadiran',
                data: values,
                borderWidth: 3,
                tension: 0.4,
                borderColor: '#198754',
                pointBackgroundColor: '#198754'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
<script>
    const ctxDaily = document.getElementById('dailyChart');

    const dailyLabels = Object.keys(@json($dailyChart));
    const dailyValues = Object.values(@json($dailyChart));

    new Chart(ctxDaily, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Kehadiran Per Hari',
                data: dailyValues,
                borderWidth: 1,
                backgroundColor: '#198754',
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>



@endsection
