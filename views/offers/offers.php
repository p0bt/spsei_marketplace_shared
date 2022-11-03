<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<style>
    .card-img-top {
        width: 100%;
        height: 300px;
        object-fit: contain;
    }

    .card-card {
        min-height: 500px;
    }
</style>

<div class="container-fluid">
    <div class="row min-vh-100">
        <div class="col-lg-3 col-md-4 d-md-block d-none shadow p-4 gx-5" id="filters-col">
            <h3>Filtry</h3>
            <form action="" method="GET">
                <div id="filter-inputs">
                    <div class="row my-5">
                        <div class="col-12">
                            <h5>Vyhledávání</h5>
                            <div class="input-group rounded">
                                <input type="text" name="search" value="<?= HelperFunctions::setInputValue("search") ?>" class="form-control border-right-0 rounded-0" placeholder="Název produktu" aria-label="Search" aria-describedby="search-addon">
                                <button type="submit" class="border-0">
                                    <span class="input-group-text border-0 rounded-0" id="search-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row my-5">
                        <div class="col-12">
                            <h5>Cena / Typ</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-vse" value="vse" <?= HelperFunctions::setRadio("price_type", "vse") ?> required <?= (!isset($_POST['price_type'])) ? "checked" : "" ?>>
                                <label class="form-check-label" for="price-radio-vse">
                                    <div>Vše</div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-pevna" value="pevna" <?= HelperFunctions::setRadio("price_type", "pevna") ?>>
                                <label class="form-check-label" for="price-radio-pevna">
                                    <div>Pevná</div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-aukce" value="aukce" <?= HelperFunctions::setRadio("price_type", "aukce") ?>>
                                <label class="form-check-label" for="price-radio-aukce">
                                    <div>Aukce</div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-zdarma" value="zdarma" <?= HelperFunctions::setRadio("price_type", "zdarma") ?>>
                                <label class="form-check-label" for="price-radio-zdarma">
                                    <div>Zdarma</div>
                                </label>
                            </div>
                            <div id="price-filter">

                            </div>
                        </div>
                    </div>
                    <div class="row my-5">
                        <h5>Kategorie</h5>
                        <div class="col-12">
                            <?php foreach ($categories as $category) : ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category[]" id="category-radio-<?= $category['value'] ?>" value="<?= $category['value'] ?>" <?= HelperFunctions::setCheckbox("category", $category['value']) ?>>
                                    <label class="form-check-label" for="category-radio-<?= $category['value'] ?>">
                                        <?= $category['name'] ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="row my-5">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a type="button" class="btn btn-light text-uppercase me-1" href="/nabidky">Obnovit</a>
                            <button type="submit" class="btn btn-primary text-uppercase">Filtrovat</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-9 col-md-8 col-12 gx-5">
            <div class="row my-md-3 mt-3 mb-5">
                <div class="col-12">
                    <?php
                    $allowed_display = [
                        "list",
                        "grid",
                    ];
                    // Default display method is list
                    if (isset($_GET['d']) && in_array($_GET['d'], $allowed_display))
                        $display = $_GET['d'];
                    else
                        $display = "list";
                    ?>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="button" class="btn btn-primary d-md-none d-block" id="btn-filter" data-bs-toggle="modal" data-bs-target="#filter-modal"><i class="fa-solid fa-filter"></i> Filtry</button>
                        </div>
                        <div>
                            <a href="nabidky?<?= http_build_query(array_merge($_GET, ['d' => 'list'])) ?>" role="button" class="btn btn-outline-dark mx-1 <?= ($display == "list") ? "active" : "" ?>"><i class="fa-solid fa-list"></i></a>
                            <a href="nabidky?<?= http_build_query(array_merge($_GET, ['d' => 'grid'])) ?>" role="button" class="btn btn-outline-dark mx-1 <?= ($display == "grid") ? "active" : "" ?>"><i class="fa-solid fa-border-all"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($offers) && !empty($offers)) : ?>
                <?php if ($display == "grid") : ?>
                    <div class="row">
                    <?php endif; ?>
                    <?php foreach ($offers as $offer) : ?>
                        <?php
                        // Get first image from user image directory
                        $thumbnail = '/assets/images/no_image.png';

                        if (is_dir(SITE_PATH . '/uploads/' . $offer['image_path'])) {
                            $images = array_values(array_diff(scandir(SITE_PATH . '/uploads/' . $offer['image_path']), ['.', '..']));
                            $thumbnail = '/uploads/' . $offer['image_path'] . '/' . $images[0];
                        }

                        // Show book name and it's author or just name in case of notebooks
                        $name = $offer['name'];

                        if (isset($offer['b_name']) && !empty($offer['b_name'])) {
                            $name = $offer['b_name'] . ' (' . $offer['b_author'] . ')';
                        }
                        ?>
                        <?php if ($display == "list") : ?>
                            <div class="row">
                                <div class="col-md-2 col-6 text-center">
                                    <img src="<?= $thumbnail ?>" class="img-fluid" alt="<?= $name ?>" data-tilt>
                                </div>
                                <div class="col-md-6 col-6 d-flex justify-content-center align-items-center text-center">
                                    <div>
                                        <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="text-decoration-none text-dark">
                                            <h5 class="card-title">
                                                <?= $name ?>
                                            </h5>
                                        </a>
                                        <?= substr($offer['description'], 0, 30) ?>...
                                    </div>
                                </div>
                                <div class="col-md-2 col-6 d-flex justify-content-center align-items-center">
                                    <?php if (isset($offer['a_start_date']) && isset($offer['a_end_date']) && !empty($offer['a_start_date']) && !empty($offer['a_end_date'])) : ?>
                                        <div class="auction">
                                            <div class="auction-info fw-bold"></div>
                                            <div class="auction-start-date" data-date="<?= $offer['a_start_date'] ?>"></div>
                                            <div class="auction-end-date" data-date="<?= $offer['a_end_date'] ?>"></div>
                                        </div>
                                    <?php else: ?>
                                        <?= $offer['price'] == 0 ? "Zdarma" : $offer['price']." Kč" ?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 col-6 d-flex justify-content-center align-items-center">
                                    <div class="d-flex flex-wrap text-center justify-content-center align-items-center">
                                        <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="btn btn-primary w-md-100">Podrobnosti</a>
                                        <?php if (isset($_SESSION['user_data']['user_id']) && ($_SESSION['user_data']['user_id'] != $offer['user_id'])) : ?>
                                            <a href="javascript:void(0);" class="w-md-100 btn-add-offer-to-wishlist mx-2" data-id="<?= $offer['offer_id'] ?>"><i class="fa-solid fa-heart" style="color: <?= (isset($_SESSION['wishlist']) && in_array($offer['offer_id'], $_SESSION['wishlist'])) ? "red" : "black" ?>;"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="w-100 light">
                                </div>
                            </div>
                        <?php elseif ($display == "grid") : ?>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="card card-card mb-md-0 mb-5">
                                    <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= $name ?>" data-tilt>
                                    <div class="card-body">
                                        <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="text-decoration-none text-dark">
                                            <h5 class="card-title">
                                                <?= $name ?>
                                            </h5>
                                        </a>
                                        <div class="card-text">
                                            <div class="small">
                                                <b>Kategorie: <?= $offer['cat_name'] ?></b>
                                            </div>
                                            <div>
                                                <?php if (isset($offer['a_start_date']) && isset($offer['a_end_date']) && !empty($offer['a_start_date']) && !empty($offer['a_end_date'])) : ?>
                                                    <div class="auction-info fw-bold"></div>
                                                    <div class="auction-start-date" data-date="<?= $offer['a_start_date'] ?>"></div>
                                                    <div class="auction-end-date" data-date="<?= $offer['a_end_date'] ?>"></div>
                                                <?php elseif (isset($offer['price']) && !empty($offer['price'])) : ?>
                                                    <em>Cena: <?= $offer['price'] ?> Kč</em>
                                                <?php endif; ?>
                                            </div>
                                            <div class="pt-2">
                                                <?= substr($offer['description'], 0, 30) ?>...
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="btn btn-primary mt-2">Podrobnosti</a>
                                            <?php if (isset($_SESSION['user_data']['user_id']) && ($_SESSION['user_data']['user_id'] != $offer['user_id'])) : ?>
                                                <a href="javascript:void(0);" class="w-md-100 btn-add-offer-to-wishlist mx-2" data-id="<?= $offer['offer_id'] ?>"><i class="fa-solid fa-heart" style="color: <?= (isset($_SESSION['wishlist']) && in_array($offer['offer_id'], $_SESSION['wishlist'])) ? "red" : "black" ?>;"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($display == "grid") : ?>
                    </div>
                <?php endif; ?>
                <div class="w-100 text-center p-5">
                    <?= $pagination->render() ?>
                </div>
            <?php else : ?>
                <div class="row">
                    <div class="col-12">
                        Nejsou zde žádné nabídky
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Product filter modal for small devices -->
<form action="" method="GET">
    <div class="modal fade" id="filter-modal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-5">
                </div>
                <div class="modal-footer p-0">
                    <a type="button" class="btn btn-light text-uppercase me-1" href="/nabidky">Obnovit</a>
                    <button type="submit" class="btn btn-primary">Filtrovat</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function init_slider() {
        $('#slider-range').slider({
            range: true,
            min: 0,
            max: <?= $max_price ?>,
            values: [0, 20000],
            slide: function(event, ui) {
                $('#text-price').val(ui.values[0] + ' - ' + ui.values[1] + ' Kč');
                $("input[name='price']").val(ui.values[0] + ' ' + ui.values[1]);
            }
        });
        $('#text-price').val(0 + ' - ' + <?= $max_price ?> + ' Kč');
    }
</script>
<script>
    $(document).ready(function() {
        render_price_filter($("input[name='price_type']:checked").val());
        <?php if ($auction_count > 0) : ?>
            print_auction_info();
        <?php endif; ?>

        $("#btn-filter").click(function() {
            $("#filter-modal .modal-body").html($("#filter-inputs").clone());
        });

        $(".btn-add-offer-to-wishlist").click(function() {

            let offer_id = $(this).data('id');
            let _this = $(this);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/add-or-delete-from-wishlist",
                data: {
                    "offer_id": offer_id,
                },
                success: function(data) {
                    Swal.fire({
                        position: 'bottom-end',
                        width: 300,
                        icon: data.type,
                        title: data.content,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        if (data.action === "add") {
                            add_to_wishlist();
                            $(_this).children("svg").css("color", "red");
                        } else {
                            delete_from_wishlist();
                            $(_this).children("svg").css("color", "black");
                        }
                    });
                },
            });
        });

        $("input[name='price_type']").change(function() {
            let val = $(this).val();
            render_price_filter(val);
        });

        function render_price_filter(val) {
            let form = "";

            switch (val) {
                case 'pevna':
                    form = `<input type="hidden" name="price" value="<?= HelperFunctions::setInputValue("price") ?>" hidden>
                            <input type="text" id="text-price" class="border-0 my-1" style="background-color: inherit;" readonly>
                            <div id="slider-range"></div>`;
                    break;
            }

            $("#price-filter").html("");
            $("#price-filter").append(form);

            switch (val) {
                case 'pevna':
                    init_slider();
                    break;
            }
        }

        <?php if ($auction_count > 0) : ?>
            // Each second refresh auction info within offers of auction type
            setInterval(function() {
                print_auction_info();
            }, 1000);
        <?php endif; ?>
    });
</script>