<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HRIS System' }}</title>
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

        footer {
            text-align: center;
            padding: 15px 0;
            color: #777;
            background-color: #f4f6f9;
            margin-top: auto;
        }
    </style>
</head>
<body>

    {{-- ✅ Include Navbar --}}
    @include('layouts.navigation')

    {{-- ✅ Konten halaman --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- ✅ Footer --}}
    <footer>
        <p>© {{ date('Y') }} HRIS System — Dibangun dengan ❤️ oleh Tim HR Development</p>
    </footer>

</body>
</html>
