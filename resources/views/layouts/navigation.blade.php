<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="{{ url('/home') }}">HRIS System</a>
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
