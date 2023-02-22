<div id="banner-carousel" class="carousel slide h-50" data-bs-ride="carousel" data-interval="4000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#banner-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Snímek 1"></button>
        <button type="button" data-bs-target="#banner-carousel" data-bs-slide-to="1" aria-label="Snímek 2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/assets/images/homepage/banner_1.jpg" class="d-block w-100" alt="učebnice">
            <div class="h-100 d-flex flex-wrap justify-content-center align-items-center carousel-caption">
                <div class="text-uppercase">
                    <h3>Učebnice na jednom místě</h3>
                    <p>Ztrácení časů s hledáním potřebných učebnic po celém internetu končí!</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/images/homepage/banner_3.jpg" class="d-block w-100" alt="učebnice">
            <div class="h-100 d-flex flex-wrap justify-content-center align-items-center carousel-caption">
                <div class="text-uppercase">
                    <h3>Nakup a prodej</h3>
                    <p>Kup hotové sešity od ostatních studentů a prodej ty své, které už nepotřebuješ</p>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#banner-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Předchozí</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#banner-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Další</span>
    </button>
</div>

<div class="container min-vh-100">
    <div class="row mt-5 text-center text-white" style="height: 100px; color: white !important;">
        <div class="col-lg-11 col-10 h-100 banner-gradient">
            <h3 class="d-flex h-100 justify-content-center align-items-center">3D nabídky</h3>
        </div>
        <div class="col-lg-1 col-2 h-100 bg-dark border-start border-white border-3">
            <a href="/3d" class="d-flex h-100 justify-content-center align-items-center text-white text-decoration-none"><i class="fa-solid fa-maximize" style="font-size: 24px;"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <img src="/assets/images/homepage/banner_3.jpg" class="img-fluid" alt="mapa">
        </div>
    </div>
    <div class="row my-5 text-white text-center fw-bold display-6">
        <div class="col-4">
            <a href="/nabidky?search=&price_type=vse&category%5B%5D=povinne_ucebnice" class="text-white text-decoration-none grey-filter">
                <div style="background-color: khaki;" data-aos="zoom-in-right">
                    <img src="/assets/images/book_icon_1.png" alt="Učebnice" class="w-100">
                    <p class="text-over-image text-over-image-center">Povinné Učebnice</p>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="/nabidky?search=&price_type=vse&category%5B%5D=doporucene_ucebnice" class="text-white text-decoration-none grey-filter">
                <div style="background-color: khaki;" data-aos="zoom-in-up" data-aos-delay="300">
                    <img src="/assets/images/book_icon_2.png" alt="Sešity" class="w-100">
                    <p class="text-over-image text-over-image-center">Doporučené Učebnice</p>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="/nabidky?search=&price_type=vse&category%5B%5D=povinna_cetba" class="text-white text-decoration-none grey-filter">
                <div style="background-color:khaki;" data-aos="zoom-in-left" data-aos-delay="600">
                    <img src="/assets/images/books_mandatory.png" alt="Vše" class="w-100">
                    <p class="text-over-image text-over-image-center">Povinná Četba</p>
                </div>
            </a>
        </div>
    </div>
    <div class="row my-5 justify-content-center text-white text-center fw-bold display-6">
        <div class="col-4">
            <a href="/nabidky?search=&price_type=vse&category%5B%5D=sesity" class="text-white text-decoration-none grey-filter">
                <div style="background-color: moccasin;" data-aos="zoom-in-left" data-aos-delay="900">
                    <img src="/assets/images/notebook_icon.png" alt="Vše" class="w-100">
                    <p class="text-over-image text-over-image-center">Sešity</p>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="/nabidky" class="text-white text-decoration-none grey-filter">
                <div style="background-color: moccasin;" data-aos="zoom-in-left" data-aos-delay="1200">
                    <img src="/assets/images/notebook_book_icon.png" alt="Vše" class="w-100">
                    <p class="text-over-image text-over-image-center">Vše</p>
                </div>
            </a>
        </div>
    </div>
    <div class="row my-5 p-5 bg-white text-center shadow-sm" class="img-fluid">
        <div class="col-12 mb-2">
            <h3>Přehled</h3>
        </div>
        <div class="col-lg-3 col-6" data-aos="zoom-in">
            <span class="big-text-48"><?= $overview['user_count'] ?></span>
            <div class="light">Uživatelů</div>
        </div>
        <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="300">
            <span class="big-text-48"><?= $overview['offer_count'] ?></span>
            <div class="light">Nabídek</div>
        </div>
        <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="600">
            <span class="big-text-48"><?= $overview['running_auctions_count'] ?></span>
            <div class="light">Běžících aukcí</div>
        </div>
        <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="900">
            <span class="big-text-48"><?= $overview['old_auctions_count'] ?></span>
            <div class="light">Ukončených aukcí</div>
        </div>
    </div>
</div>