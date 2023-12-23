
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Nicholas Sterk">
    <title>FE2 - Hiorg Synchronisation</title>
    <!-- Application FrontEnd Assets -->
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    <!-- Favicons -->
    <meta name="theme-color" content="#7952b3">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">FE2-Hiorg Sync</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" aria-current="page" href="{{route('home')}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('me') ? 'active' : '' }}" aria-current="page" href="{{route('me')}}">Meine Daten</a>
                </li>
                @can('admin')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('settings') ? 'active' : '' }}" href="{{route('settings')}}">Einstellungen</a>
                </li>
                @endcan
            </ul>
            <div class="d-flex">
                <a href="{{ route('logout') }}" class="btn btn-outline-secondary">Logout</a>
            </div>
        </div>
    </div>
</nav>

<main class="container">
    <div class="bg-light p-5 rounded">
        @yield('content')
    </div>
</main>

</body>
</html>
