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
        <?php require_once("views/templates/offers/filters_panel.php") ?>
        <div class="col-lg-9 col-md-8 col-12 gx-5">
            <?php if (!isset($_SESSION['user_data'])) : ?>
                <div class="row my-2">
                    <div class="alert alert-info">
                        Tip: Pro rychlejší hledání učebnic dle tvých požadavků se přihlaš
                    </div>
                </div>
            <?php endif; ?>
            <div class="row my-md-3 mt-3 mb-5">
                <div class="col-12">
                    <?php
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
                            <?php require("views/templates/offers/offer_display_list.php") ?>
                        <?php elseif ($display == "grid") : ?>
                            <?php require("views/templates/offers/offer_display_grid.php") ?>
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
<?php require_once("views/templates/offers/filters_panel_modal.php") ?>

<script>
    function init_slider() {
        $("input[name='price']").val(1 + ' ' + <?= $max_price ?>);

        $('#slider-range').slider({
            range: true,
            min: 1,
            max: <?= $max_price ?>,
            values: [1, 20000],
            slide: function(event, ui) {
                $('#text-price').val(ui.values[0] + ' - ' + ui.values[1] + ' Kč');
                $("input[name='price']").val(ui.values[0] + ' ' + ui.values[1]);
            }
        });
        $('#text-price').val(1 + ' - ' + <?= $max_price ?> + ' Kč');
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