<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #7EA039;
        }

        .navbar-brand, .nav-link, .dropdown-item {
            color: white !important;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Tengah secara vertikal */
            align-items: center; /* Tengah secara horizontal */
            padding: 40px 20px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
            width: 100%;
            max-width: 900px; /* Batas lebar menu agar tidak terlalu lebar */
            justify-items: center;
        }

        .menu-card {
            background: white;
            border-radius: 15px;
            text-align: center;
            padding: 25px 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            width: 180px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .menu-card img {
            width: 60px;
            margin-bottom: 15px;
        }

        .menu-title {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            color: #777;
            background-color: #f4f6f9;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="#">HRIS System</a>
        <div class="d-flex">
            <a href="{{ route('logout') }}" class="btn btn-light btn-sm text-success fw-semibold"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<div class="content">
    <div class="text-center mb-5">
        <h3 class="fw-semibold mb-2">Selamat Datang di HRIS</h3>
        <p class="text-muted">Sistem Informasi Manajemen Karyawan</p>
    </div>

    {{-- GRID MENU --}}
    <div class="menu-grid">

        {{-- Menu untuk semua user --}}
        <div class="menu-card" onclick="window.location.href='#'">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Karyawan">
            <div class="menu-title">Data Karyawan</div>
        </div>

        <div class="menu-card" onclick="window.location.href='#'">
            <img src="https://cdn-icons-png.flaticon.com/512/2645/2645897.png" alt="Absensi">
            <div class="menu-title">Absensi</div>
        </div>

        <div class="menu-card" onclick="window.location.href='#'">
            <img src="https://cdn-icons-png.flaticon.com/512/1584/1584892.png" alt="Cuti">
            <div class="menu-title">Pengajuan Cuti</div>
        </div>

        {{-- Menu tambahan untuk Admin --}}
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
            <div class="menu-card" onclick="window.location.href='#'">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828640.png" alt="Laporan">
                <div class="menu-title">Laporan Kinerja</div>
            </div>

            <div class="menu-card" onclick="window.location.href='#'">
                <img src="https://cdn-icons-png.flaticon.com/512/2086/2086784.png" alt="Pengaturan">
                <div class="menu-title">Pengaturan HR</div>
            </div>
        @endif

        {{-- Menu eksklusif untuk Super Admin --}}
        @if(Auth::user()->role == 'super_admin')
            <div class="menu-card" onclick="window.location.href='{{ route('manajemen_users.users.index') }}'">
                <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Manajemen User">
                <div class="menu-title">Manajemen Pengguna</div>
            </div>


            <div class="menu-card" onclick="window.location.href='#'">
                <img src="https://cdn-icons-png.flaticon.com/512/2331/2331970.png" alt="Database">
                <div class="menu-title">Manajemen Database</div>
            </div>
        @endif

    </div>
</div>

<footer>
    <p>© {{ date('Y') }} HRIS System — Dibangun dengan ❤️ oleh Tim HR Development</p>
</footer>

</body>
</html>
