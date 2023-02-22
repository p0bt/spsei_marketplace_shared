<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_TITLE ?></title>

    <!-- JS -->
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/jquery-ui.min.js"></script>
    <script src="/assets/js/dropzone.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/swiper-bundle.min.js"></script>
    <script src="/assets/js/confetti.js"></script>
    <!-- SocketIO -->
    <script src="/assets/js/SocketIO/socket.io.js"></script>
    <!-- CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/fa-all.min.css" rel="stylesheet">
    <link href="/assets/css/dropzone.min.css" rel="stylesheet">
    <link href="/assets/css/animate.min.css" rel="stylesheet">
    <link href="/assets/css/aos.css" rel="stylesheet">
    <link href="/assets/css/swiper-bundle.min.css" rel="stylesheet">

    <link href="/assets/images/icon.ico" rel="icon" type="image/x-icon">
</head>

<body class="bg-light">
    <main class="col-xxl-10 col-12 mx-auto shadow-lg">
        <nav class="navbar navbar-expand small d-md-block d-none p-0" id="secondary-navigation-bar">
            <div class="container-fluid">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="/muj-ucet?t=wishlist"><i class="fa-solid fa-heart me-2"></i>
                            Oblíbené
                            <span class="count-box item-count-box">
                                <?= (isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0) ?>
                            </span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_data'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nova-nabidka"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i></i>Zveřejnit nabídku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="/muj-ucet"><i class="fa-solid fa-user me-2"></i>Můj účet</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="/zpravy"><i class="fa-solid fa-comment me-2"></i>Zprávy</a>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-white bg-transparent border-0" aria-current="page" data-bs-toggle="modal" data-bs-target="#notifications-modal"><i class="fa-solid fa-bell me-2"></i>
                                Oznámení
                                <span class="count-box notifications-count-box bg-secondary">
                                    <?= (isset($notifications) ? count($notifications) : 0) ?>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/odhlaseni"><i class="fa-solid fa-right-from-bracket me-2"></i>Odhlásit se</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nabidky?category%5B%5D=sesity"><i class="fa-solid fa-book-open me-2"></i></i>Sešity</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nabidky?category%5B%5D=povinne_ucebnice"><i class="fa-solid fa-book me-2"></i></i>Povinné učebnice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nabidky?category%5B%5D=doporucene_ucebnice"><i class="fa-solid fa-book me-2"></i></i>Doporučené učebnice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nabidky?category%5B%5D=povinna_cetba"><i class="fa-solid fa-book me-2"></i></i>Povinná četba</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/prihlaseni"><i class="fa-solid fa-lock me-2"></i>Přihlásit se</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <header class="sticky-top shadow-lg">
            <nav class="navbar navbar-expand-lg" id="main-navigation-bar">
                <div class="container-fluid">
                    <a class="navbar-brand mx-5" href="/domu">
                        <img src="/assets/images/logo.png" height="60" width="auto">
                    </a>
                    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Zobrazit menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mx-5 mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link text-white" aria-current="page" href="/domu"><i class="fa-solid fa-house me-2"></i>Domů</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-book me-2"></i>Nabídky
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="/nabidky"><i class="fa-solid fa-border-all me-2"></i>Vše</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/nabidky?category%5B%5D=sesity"><i class="fa-solid fa-book-open me-2"></i>Sešity</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/nabidky?category%5B%5D=povinne_ucebnice"><i class="fa-solid fa-book me-2"></i></i>Povinné učebnice</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/nabidky?category%5B%5D=doporucene_ucebnice"><i class="fa-solid fa-book me-2"></i></i>Doporučené učebnice</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/nabidky?category%5B%5D=povinna_cetba"><i class="fa-solid fa-book me-2"></i></i>Povinná četba</a>
                                    </li>
                                </ul>
                            </li>
                            <?php if (isset($_SESSION['user_data'])) : ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-user me-2"></i>Můj účet
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="/muj-ucet?t=wishlist"><i class="fa-solid fa-heart me-2"></i>
                                                Oblíbené
                                                <span class="count-box item-count-box item-count-box-dropdown">
                                                    <b>(<?= (isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0) ?>)</b>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/muj-ucet"><i class="fa-solid fa-user me-2"></i>Můj účet</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/zpravy"><i class="fa-solid fa-comment me-2"></i></i>Zprávy</a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item bg-transparent border-0" aria-current="page" data-bs-toggle="modal" data-bs-target="#notifications-modal"><i class="fa-solid fa-bell me-2"></i>
                                                Oznámení
                                                <span class="count-box notifications-count-box">
                                                    <b>(<?= (isset($notifications) ? count($notifications) : 0) ?>)</b>
                                                </span>
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/nova-nabidka"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i></i>Zveřejnit nabídku</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/odhlaseni"><i class="fa-solid fa-right-from-bracket me-2"></i>Odhlásit se</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else : ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/prihlaseni"><i class="fa-solid fa-lock me-2"></i>Přihlásit se</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>