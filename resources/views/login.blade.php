
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
       <h2>HiOrg - aPager Synchronisation - Selfservice Portal</h2>

        <p>
            Dieses Selfservice Portal dient dazu, deine Provisionierung von aPager direkt per E-Mail anzufordern, sowie deine Daten aus HiOrg mit aPager zu synchronisieren.
            Bitte logge dich mit deinem HiOrg Account ein, um den Selfservice zu starten.
        </p>



        <a href="{{ route('redirect') }}" class="btn btn-outline-dark login-hiorg-btn">
            <img src="{{ Vite::asset('resources/images/hiorg_login.png') }}" width="250">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
        </a>
    </div>
</main>



</body>
</html>
