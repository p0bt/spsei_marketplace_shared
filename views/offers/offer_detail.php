<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<style>
    #thumbnail {
        height: 100%;
        width: 100%;
    }
</style>

<div class="container">
    <?php if ($is_auction && !isset($_SESSION['user_data'])) : ?>
        <div class="row mt-2">
            <div class="col-10 mx-auto">
                <div class="alert alert-danger text-center">
                    Pro zapojení do aukce je třeba být <a href="/prihlaseni">přihlášen!</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mt-2">
        <div class="col-10 mx-auto">
            <div class="current-auction-info" style="display: none;">
                <div class="alert alert-success text-center">
                    <span class="current-time" style="position: absolute; right: 10px; top: 0;"></span>
                    <div class="auction-owner fw-bold"></div>
                    <div class="auction-price fw-bold"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-5">
        <div class="col-md-6 col-12 order-md-1 order-1">
            <?php
            // Show book name and it's author or just name in case of notebooks (...)
            $name = $offer['name'];

            if (isset($offer['b_name']) && !empty($offer['b_name'])) {
                $name = $offer['b_name'] . ' (' . $offer['b_author'] . ')';
            }
            ?>
            <div id="thumbnail mb-md-0 mb-5">
                <img src="<?= $thumbnail ?>" id="thumbnail-image" class="shadow js-tilt-scale" alt="<?= $name ?>" width="100%" height="auto" data-tilt>
            </div>
        </div>
        <div class="col-12 mb-md-0 mb-5 order-md-3 order-2">
            <?php if (isset($images) && count($images) > 0) : ?>
                <div class="my-2">
                    <?php for ($i = 0; $i < count($images); $i++) : ?>
                        <img src="/uploads/<?= $offer['image_path'] ?>/<?= $images[$i] ?>" class="me-1 border border-primary border-2 small-image" alt="<?= $name ?>" height="125">
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 col-12 d-flex justify-content-center align-items-center text-md-start text-center order-md-2 order-3">
            <div>
                <?php if (isset($_SESSION['user_data']) && $offer['user_id'] == $_SESSION['user_data']['user_id']) : ?>
                    <h5 class="my-0"><em>Vaše nabídka</em></h5>
                <?php endif; ?>

                <h2><?= $name ?></h2>
                <p class="small">Kategorie: <?= $offer['cat_name'] ?></p>
                <p><?= $offer['description'] ?></p>

                <?php if (!$is_auction) : ?>
                    <b>Cena: <?= $offer['price'] == 0 ? "Zdarma" : $offer['price'] . " Kč" ?></b>
                <?php else : ?>
                    <div class="auction my-2 d-flex justify-content-md-start justify-content-center align-items-center">
                        <div class="auction">
                            <div class="auction-info fw-bold"></div>
                            <?php if (isset($_SESSION['user_data']) && ($offer['user_id'] != $_SESSION['user_data']['user_id']) && (strtotime($offer['a_end_date']) >= time())) : ?>
                                <form method="POST" action="" class="auction-form mt-3" id="auction-form">
                                    <div class="d-flex flew-wrap justify-content-md-start justify-content-center align-items-center">
                                        <div>
                                            <input type="number" name="bid" id="bid" class="form-control d-inline-block w-50" min="1" max="10000" required> Kč
                                            <button type="submit" class="btn btn-success ms-2 text-white" id="btn-make-bid"><i class="fa-solid fa-plus"></i></button>
                                            <span id="time-left-to-next-allowed-bid"></span>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>
                            <div class="auction-start-date" data-date="<?= $offer['a_start_date'] ?>"></div>
                            <div class="auction-end-date" data-date="<?= $offer['a_end_date'] ?>"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!isset($_SESSION['user_data']) || $offer['user_id'] != $_SESSION['user_data']['user_id']) : ?>
                    <div class="row mt-3 g-0">
                        <div class="col-md-2 col-12 p-0 mb-md-0 mb-1">
                            <a type="button" class="h-100 me-md-1 me-0 btn btn-primary d-flex justify-content-center align-items-center" href="#" id="btn-send-message" data-id="<?= $offer['user_id'] ?>"><i class="fa-solid fa-comment"></i></a>
                        </div>
                        <div class="col-md-2 col-12 p-0 mb-md-0 mb-1">
                            <a type="button" class="h-100 me-md-1 me-0 btn btn-primary d-flex justify-content-center align-items-center" href="mailto:<?= $offer['email'] ?>"><i class="fa-solid fa-paper-plane"></i></a>
                        </div>
                        <div class="col-md-8 col-12 p-0 mb-md-0 mb-1">
                            <a type="button" class="btn btn-primary d-flex justify-content-center align-items-center" id="btn-contact-form" href="#contact-form">Formulář</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['user_data']) && $offer['user_id'] != $_SESSION['user_data']['user_id']) : ?>
        <div class="row p-5">
            <div class="col-md-6 col-12 mx-auto">
                <h3 class="text-center">Kontaktní formulář</h3>
                <?php if ($alert = HelperFunctions::getAlert("success")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($alert = HelperFunctions::getAlert("error")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <form method="POST" action="" id="contact-form">
                    <div class="mb-3">
                        <label for="message" class="form-label">Zpráva</label>
                        <textarea class="form-control" name="message" id="message" value="<?= HelperFunctions::setInputValue("message") ?>" required maxlength="255" rows="3">Dobrý den,&#10;mám zájem o Váš inzerát č.<?= $offer['offer_id'] ?> [<?= $name ?>]. Je stále platný?&#10;Děkuji za odpověď.</textarea>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="<?= $offer['user_id'] ?>" hidden>
                    <button type="submit" class="btn btn-primary w-100">Odeslat</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {

        // Lines below are called after document is loaded
        show_image($(".small-image")[0]);

        <?php if ($is_auction) : ?>
            // WebSocket connection
            let socket = io('<?= WEBSOCKETS_PROTOCOL ?>://<?= SITE_URL ?>:<?= WEBSOCKETS_PORT ?>', {
                secure: true
            });

            let i = 0;
            let now = new Date();
            let auction_id = <?= $offer['a_auction_id'] ?>;
            let state = get_current_state(auction_id);

            // Initaliaze
            state = get_current_state(auction_id);

            $('.current-auction-info').css("display", (is_auction_in_progress(new Date("<?= $offer['a_start_date'] ?>"), new Date("<?= $offer['a_end_date'] ?>")) ? "block" : "none"));

            print_auction_info();
            print_current_time();
            print_auction_state(state);
            check_current_winner();

            let min_bid_value = (state.top_bid.length > 0) ? parseInt(state.top_bid) + 1 : 1;
            $("#bid").attr("placeholder", min_bid_value);
            $("#bid").attr("min", min_bid_value);

            // On auction change - refresh info of auction
            socket.on('auction_change', data => {
                state = get_current_state(auction_id);

                print_auction_state(state);
                check_current_winner();

                let min_bid_value = (state.top_bid.length > 0) ? parseInt(state.top_bid) + 1 : 1;
                $("#bid").attr("placeholder", min_bid_value);
                $("#bid").attr("min", min_bid_value);
            });

            // Call each second
            setInterval(function() {

                print_auction_info();
                print_current_time();

                // If user can bid button is available -> else we are showing seconds till next possible bid
                if (can_user_bid(auction_id)) {
                    $("#btn-make-bid").html('<i class="fa-solid fa-plus"></i>');
                    $("#btn-make-bid").removeAttr("disabled");
                    i = 0;
                } else {
                    $("#btn-make-bid").attr("disabled", true);
                    if(i < parseInt(<?= AUCTION_BID_DELAY ?>)) {
                        $("#btn-make-bid").text(parseInt(<?= AUCTION_BID_DELAY ?>) - (i + 1));
                        i++;
                    }
                }

                $('.current-auction-info').css("display", (is_auction_in_progress(new Date("<?= $offer['a_start_date'] ?>"), new Date("<?= $offer['a_end_date'] ?>")) ? "block" : "none"));
            }, 1000);

            $("#auction-form").submit(function(e) {
                e.preventDefault();

                let bid = parseInt($("#bid").val());
                rise_price(auction_id, bid);

                Swal.fire({
                    position: 'bottom-end',
                    width: 300,
                    icon: "success",
                    title: "Přihodili jste " + bid + " Kč",
                    showConfirmButton: false,
                    timer: 1500,
                    allowOutsideClick: false,
                });
            });

            function check_current_winner() {
                let user_id = "<?= isset($_SESSION['user_data']['user_id']) ? $_SESSION['user_data']['user_id'] : "" ?>";
                if (state.user_id == user_id) {
                    confetti.start();
                } else {
                    confetti.stop();
                }
            }
        <?php endif; ?>

        $("#btn-send-message").click(function() {
            let user_id = $(this).data('id');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/create-new-chat",
                data: {
                    "user_id": user_id,
                },
                success: function(data) {
                    if (data) {
                        window.location.href = data;
                    }
                },
            });
        });

        $("#contact-form").submit(function(e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/send-message-contact-form",
                data: {
                    "user_id": $("#contact-form #user_id").val(),
                    "message": $("#contact-form #message").val(),
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Zpráva byla doručena',
                            text: data.success,
                            icon: 'success',
                            showCancelButton: true,
                            cancelButtonText: 'Zavřít'
                        });
                    } else if (data.error) {
                        Swal.fire({
                            title: 'Chyba',
                            text: data.error,
                            icon: 'error',
                            showCancelButton: true,
                            cancelButtonText: 'Zavřít'
                        });
                    }
                },
            });

        });

        $(".small-image").click(function() {
            show_image($(this));
        });

        function show_image(image) {
            $(".small-image").removeClass("border-5");
            let this_src = $(image).attr("src");

            $("#thumbnail-image").attr("src", this_src);
            $(image).addClass("border-5");
        }
    });
</script>