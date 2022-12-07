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
        <?php else : ?>
            <?= $offer['price'] == 0 ? "Zdarma" : $offer['price'] . " Kč" ?>
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