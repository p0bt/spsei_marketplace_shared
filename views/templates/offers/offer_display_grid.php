<div class="col-xl-4 col-md-6 col-12 mb-5">
    <div class="card card-card mb-md-0">
        <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= $name ?>" data-tilt>
        <div class="card-body">
            <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="text-decoration-none text-dark">
                <h5 class="card-title">
                    <?= $name ?>
                </h5>
            </a>
            <div class="card-text">
                <div class="small">
                    <b>Kategorie: <?= (!empty($offer['cat_name'])) ? $offer['cat_name'] : "Sešity" ?></b>
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