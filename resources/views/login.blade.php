
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
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">FE2-Hiorg Sync</a>
        <div class="custom-icons-header">
            <img src="{{ Vite::asset('resources/images/custom_icons/apager-pro.svg') }}" class="me-3" width="50">
            <img src="{{ Vite::asset('resources/images/custom_icons/juh.svg') }}" class="me-3" width="50">
            <img src="{{ Vite::asset('resources/images/custom_icons/hiorg-server.svg') }}" width="50">
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
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
        </a>
    </div>
</main>



</body>
</html>
