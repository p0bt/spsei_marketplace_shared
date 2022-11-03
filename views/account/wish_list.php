<style>
    .swiper {
        width: 100%;
        max-height: 600px;
    }
</style>

<div class="container min-vh-100">
    <div class="row my-5">
        <div class="col-12">
            <div class="card banner-gradient py-2 text-white text-center">
                <h1>Oblíbené</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mx-auto">
            <?php if(!empty($wishlist_items)): ?>
                <table class="table align-middle text-center small">
                    <thead>
                        <th>Náhled</th>
                        <th>Název</th>
                        <th>Cena / Info</th>
                        <th>Akce</th>
                    </thead>
                    <tbody>
                        <?php foreach($wishlist_items as $offer): ?>
                            <?php
                                $thumbnail = '/assets/images/no_image.png';
                                if(is_dir(SITE_PATH.'/uploads/'.$offer['image_path']))
                                {
                                    $images = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$offer['image_path']), ['.', '..']));
                                    $thumbnail = '/uploads/'.$offer['image_path'].'/'.$images[0];
                                }

                                $name = $offer['name'];
                                if(isset($offer['b_name']))
                                {
                                    $name = $offer['b_name'].' ('.$offer['b_author'].')';
                                }
                            ?>
                            <tr class="text-center">
                                <td>
                                    <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>">
                                        <img src="<?= $thumbnail ?>" class="img-fluid" alt="<?= $name ?>" data-tilt width="100px">
                                    </a>
                                </td>
                                <td>
                                    <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="text-decoration-none text-dark">
                                        <?= $name ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if(isset($offer['price']) && !empty($offer['price'])): ?>
                                        <?php $auction = false; ?>
                                        <?= $offer['price'] ?> Kč
                                    <?php elseif(isset($offer['a_auction_id']) && !empty($offer['a_auction_id'])): ?>
                                        <?php $auction = true; ?>
                                        <div class="auction my-2 d-flex justify-content-md-start justify-content-center align-items-center">
                                            <div class="auction">
                                                <div class="auction-info fw-bold"></div>
                                                <div class="auction-start-date" data-date="<?= $offer['a_start_date'] ?>"></div>
                                                <div class="auction-end-date" data-date="<?= $offer['a_end_date'] ?>"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-delete-offer-from-wishlist" data-id="<?= $offer['offer_id'] ?>"><i class="fa-solid fa-trash-can text-danger"></i></button>
                                    <?php if(!$auction): ?>
                                        <a href="mailto:<?= $offer['email'] ?>" class="btn" data-id="<?= $offer['offer_id'] ?>"><i class="fa-solid fa-envelope text-secondary"></i></a>
                                        <?php
                                            $data = [
                                                "offer_id" => $offer['offer_id'],
                                                "name" => $name,
                                                "email" => $offer['email'],
                                            ];
                                        ?>
                                        <button type="button" class="btn btn-send-mail" data-offer="<?= htmlspecialchars(json_encode($data)) ?>"><i class="fa-solid fa-paper-plane text-primary"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center">
                    Nemáte žádné oblíbené nabídky
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(count($suggestions['offers']) > 0): ?>
        <div class="row my-5 bg-white py-2">
            <div class="col-12">
                <h3>Nabídky pro vás</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="swiper swiper-offers">
                    <div class="swiper-wrapper mb-5">
                        <?php foreach($suggestions['offers'] as $offer): ?>
                        <?php
                            // Get first image from user image directory
                            $thumbnail = '/assets/images/no_image.png';

                            if(is_dir(SITE_PATH.'/uploads/'.$offer['image_path']))
                            {
                                $images = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$offer['image_path']), ['.', '..']));
                                $thumbnail = '/uploads/'.$offer['image_path'].'/'.$images[0];
                            }

                            // Show book name and it's author or just name in case of notebooks
                            $name = $offer['name'];

                            if(isset($offer['b_name']) && !empty($offer['b_name']))
                            {
                                $name = $offer['b_name'].' ('.$offer['b_author'].')';
                            }
                        ?>
                        <div class="swiper-slide small">
                            <a style="position: absolute; right: 10px; top: 5px;" href="/detail-nabidky?id=<?= $offer['offer_id'] ?>" class="big-text-24 text-decoration-none link-dark"><i class="fa-solid fa-eye"></i></a>
                            <img src="<?= $thumbnail ?>" alt="<?= $name ?>" draggable="false" style="object-fit: contain; height: 200px;" class="w-100 bg-secondary">
                            <div class="d-flex justify-content-center align-items-center bg-white small text-center" style="min-height: 150px;">
                                <div class="mt-2">
                                    <p class="big-text-16"><b><?= $name ?></b></p>
                                    <p><?= substr($offer['description'], 0, 30) ?></p>
                                    <p>Cena: <?= $offer['price'] ?> Kč</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev d-md-flex d-none"></div>
                    <div class="swiper-button-next d-md-flex d-none"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(count($suggestions['auctions']) > 0): ?>
        <div class="row my-5 bg-white py-2">
            <div class="col-12">
                <h3>Aukce pro vás</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="swiper swiper-auctions">
                    <div class="swiper-wrapper mb-5">
                        <?php foreach($suggestions['auctions'] as $offer): ?>
                        <?php
                            // Get first image from user image directory
                            $thumbnail = '/assets/images/no_image.png';

                            if(is_dir(SITE_PATH.'/uploads/'.$offer['image_path']))
                            {
                                $images = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$offer['image_path']), ['.', '..']));
                                $thumbnail = '/uploads/'.$offer['image_path'].'/'.$images[0];
                            }

                            // Show book name and it's author or just name in case of notebooks
                            $name = $offer['name'];

                            if(isset($offer['b_name']) && !empty($offer['b_name']))
                            {
                                $name = $offer['b_name'].' ('.$offer['b_author'].')';
                            }
                        ?>
                        <div class="swiper-slide small">
                            <a style="position: absolute; right: 10px; top: 5px;" href="/detail-nabidky?id=<?= $offer['offer_id'] ?>" class="big-text-24 text-decoration-none link-dark"><i class="fa-solid fa-eye"></i></a>
                            <img src="<?= $thumbnail ?>" alt="<?= $name ?>" draggable="false" style="object-fit: contain; height: 200px;" class="w-100 bg-secondary">
                            <div class="d-flex justify-content-center align-items-center bg-white small text-center" style="min-height: 150px;">
                                <div class="mt-2">
                                    <p class="big-text-16 mb-1"><b><?= $name ?></b></p>
                                    <div class="auction">
                                        <div class="auction-info fw-bold"></div>
                                        <div class="auction-start-date" data-date="<?= $offer['a_start_date'] ?>"></div>
                                        <div class="auction-end-date" data-date="<?= $offer['a_end_date'] ?>"></div>
                                    </div>
                                    <p><?= substr($offer['description'], 0, 30) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev d-md-flex d-none"></div>
                    <div class="swiper-button-next d-md-flex d-none"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- EMAIL MODAL -->
<div class="modal fade" id="email-modal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="" id="contact-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Kontaktovat prodejce</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Váš email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?= isset($_SESSION['user_data']['email']) ? $_SESSION['user_data']['email'] : '' ?>" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Zpráva</label>
                        <textarea type="text" class="form-control" name="text" id="text" required maxlength="255" rows="4"></textarea>
                    </div>
                </div>
                <input type="hidden" name="receiver" id="receiver" hidden>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Odeslat</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    $(document).ready(function() {

        let data = null;
        print_auction_info();

        $(".btn-delete-offer-from-wishlist").click(function() {
            let offer_id = $(this).data('id');
            add_or_delete_from_wishlist(offer_id);
        });

        $(".btn-send-mail").click(function() {
            data = $(this).data('offer');

            let text = "Dobrý den,\nmám zájem o Váš inzerát č." + data.offer_id + " [" + data.name + "]. Je stále platný?\nDěkuji za odpověď.";

            $("#email-modal").modal('show');

            $("#email-modal #text").val(text);

            $("#email-modal #receiver").val(data.email);
        });

        $("#contact-form").submit(function(e) {

            e.preventDefault();
            $("#email-modal").modal('hide');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/ajax/send-email",
                data: {
                    "email": $("#contact-form #email").val(),
                    "text": $("#contact-form #text").val(),
                    "receiver": $("#contact-form #receiver").val(),
                },
                success: function(data) {
                    if(data.success) {
                        Swal.fire({
                            title: 'Zpráva byla doručena',
                            text: data.success + ". Chcete nyní odstranit nabídku z oblíbených?",
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Ano',
                            cancelButtonText: 'Ne'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                add_or_delete_from_wishlist(data.offer_id);
                            }
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

        <?php if($auction_count > 0): ?>
            // Each second refresh auction info within offers of auction type
            setInterval(function() {
                print_auction_info();
            }, 1000);
        <?php endif; ?>

        function add_or_delete_from_wishlist(offer_id) {
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
                        location.reload();
                    });
                },
            });
        }
    });
</script>

<script>
    const settings = {
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction'
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        spaceBetween: 30,
    };

    // If there's 1 offer, show only 1 item per page, and don't "loop"
    settings.loop = <?= count($suggestions['auctions']) <= 1 ? 0 : 1 ?>;
    settings.slidesPerView = <?= count($suggestions['auctions']) <= 1 ? 1 : 3 ?>;
    const swiper_offers = new Swiper('.swiper-offers', settings);

    settings.loop = <?= count($suggestions['auctions']) <= 1 ? 0 : 1 ?>;
    settings.slidesPerView = <?= count($suggestions['auctions']) <= 1 ? 1 : 2 ?>;
    const swiper_auctions = new Swiper('.swiper-auctions', settings);
</script>