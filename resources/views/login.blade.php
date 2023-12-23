
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

            </ul>
            <div class="d-flex">
                <a href="{{ route('redirect') }}" class="btn btn-outline-secondary">Login</a>
            </div>
        </div>
    </div>
</nav>

<main class="container">
    <div class="bg-light p-5 rounded">
       <h2>Hiorg - aPager Synchronisation</h2>

        <a href="{{ route('redirect') }}">Bitte melde dich an (Weiterleitung zum Hiorg-Server)</a>
    </div>
</main>



</body>
</html>
