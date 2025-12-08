@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-4">📊 Dashboard Absensi</h3>

    <div class="row g-3">

        <!-- Summary Cards -->
        <div class="col-md-3 col-6">
            <div class="card shadow-sm p-3 border-0 rounded-4 text-center">
                <h6 class="text-muted">Hadir</h6>
                <h2 class="text-success fw-bold">{{ $summary['hadir'] }}</h2>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm p-3 border-0 rounded-4 text-center">
                <h6 class="text-muted">Izin</h6>
                <h2 class="text-primary fw-bold">{{ $summary['izin'] }}</h2>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm p-3 border-0 rounded-4 text-center">
                <h6 class="text-muted">Sakit</h6>
                <h2 class="text-warning fw-bold">{{ $summary['sakit'] }}</h2>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm p-3 border-0 rounded-4 text-center">
                <h6 class="text-muted">Alpha</h6>
                <h2 class="text-danger fw-bold">{{ $summary['alpha'] }}</h2>
            </div>
        </div>


        <!-- Chart -->
        <div class="col-md-12 mt-4">
            <div class="card shadow-sm p-4 border-0 rounded-4">
                <h5 class="fw-semibold mb-3">Grafik Kehadiran per Bulan</h5>
                <canvas id="attendanceChart" height="120"></canvas>
            </div>
        </div>


        <!-- Absensi Hari Ini -->
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
                                    <td colspan="5" class="text-center text-muted">Tidak ada absensi hari ini</td>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('attendanceChart');

   const labels = @json($chartData->pluck('hari'));
    const values = @json($chartData->pluck('total'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Absensi Harian',
                data: values,
                borderWidth: 3,
                tension: 0.4
            }]
        }
    });

</script>

@endsection
