<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_TITLE ?> - Admin</title>

    <!-- JS -->
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/jquery-ui.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
    <script src="/assets/js/main.js"></script>
    <!-- CSS -->
    <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/assets/css/fa-all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/dashboard.css" rel="stylesheet">
    <link href="/assets/css/datatables.min.css" rel="stylesheet">
    <link href="/assets/css/animate.min.css" rel="stylesheet">

    <link href="/assets/images/icon.ico" rel="icon" type="image/x-icon">
</head>

<body>
    <main>
        <header>
            <nav class="navbar navbar-expand-lg" id="main-navigation-bar">
                <div class="container-fluid">
                    <a class="navbar-brand mx-5" href="/domu">
                        <img src="/assets/images/logo.png" height="auto" width="200">
                    </a>
                </div>
            </nav>
        </header>
        <div id="wrapper">
            <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-custom p-0" id="admin-sidebar">
                <div class="container-fluid d-flex flex-column p-0">
                    <div class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0">
                        <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-user-shield"></i></div>
                        <div class="sidebar-brand-text mx-3"><span>admin panel</span></div>
                    </div>
                    <ul class="navbar-nav text-light" id="accordionSidebar">
                        <div class="sidebar-heading sidebar-divider text-uppercase text-white text-left my-1">
                            Hlavní    
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="/admin/panel"><i class="fas fa-tachometer-alt mx-2"></i><span>Přehled</span></a></li>
                        <div class="sidebar-heading sidebar-divider text-uppercase text-white text-left my-1">
                            Nabídky    
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-nabidek"><i class="fa fa-dollar mx-2"></i><span>Správa nabídek</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-aukci"><i class="fa fa-gavel mx-2"></i><span>Správa aukcí</span></a></li>
                        <div class="sidebar-heading sidebar-divider text-uppercase text-white text-left my-1">
                            Uživatelé    
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-uzivatelu"><i class="fa fa-user mx-2"></i><span>Správa uživatelů</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-zablokovanych-ip"><i class="fa-solid fa-wifi mx-2"></i><span>Správa zablokovaných IP</span></a></li>
                        <div class="sidebar-heading sidebar-divider text-uppercase text-white text-left my-1">
                            Ostatní    
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-umisteni-trid"><i class="fa-solid fa-location-dot mx-2"></i><span>Správa umístění tříd</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-trid"><i class="fa-solid fa-graduation-cap mx-2"></i><span>Správa tříd</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-knih"><i class="fa fa-book mx-2"></i><span>Správa knih</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-sesitu"><i class="fa fa-book-open mx-2"></i><span>Správa sešitů</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/admin/sprava-api-klicu"><i class="fa-solid fa-key mx-2"></i><span>Správa API klíčů</span></a></li>
                    </ul>
                    <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
                </div>
            </nav>
            <div class="d-flex flex-column" id="content-wrapper">
                <div id="content">