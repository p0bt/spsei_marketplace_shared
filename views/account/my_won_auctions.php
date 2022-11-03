<h4 class="mb-5">Moje vyhrané aukce</h4>
<?php if (!empty($won_auctions)) : ?>
    <?php foreach ($won_auctions as $won_auction) : ?>
        <?php
        // Get first image from user image directory
        $thumbnail = '/assets/images/no_image.png';
        if (is_dir(SITE_PATH . '/uploads/' . $won_auction['image_path'])) {
            $images = array_values(array_diff(scandir(SITE_PATH . '/uploads/' . $won_auction['image_path']), ['.', '..']));
            $thumbnail = '/uploads/' . $won_auction['image_path'] . '/' . $images[0];
        }

        // Show book name and it's author or just name in case of notebooks
        $name = $won_auction['name'];
        if (isset($won_auction['b_name']) && !empty($won_auction['b_name'])) {
            $name = $won_auction['b_name'] . ' (' . $won_auction['b_author'] . ')';
        }
        ?>
        <div class="row">
            <div class="col-md-2 col-6 text-center">
                <img src="<?= $thumbnail ?>" class="img-fluid" alt="<?= $name ?>">
            </div>
            <div class="col-md-8 col-6 d-flex justify-content-center align-items-center text-center">
                <div>
                    <a href="detail-nabidky?id=<?= $won_auction['offer_id'] ?>" class="text-decoration-none text-dark">
                        <h5 class="card-title">
                            <?= $name ?>
                        </h5>
                    </a>
                    <?= substr($won_auction['description'], 0, 30) ?>...
                </div>
            </div>
            <div class="col-md-2 col-6 d-flex justify-content-center align-items-center">
                <a type="button" class="me-md-1 me-0 d-flex justify-content-center align-items-center text-decoration-none" href="javascript:void(0)" id="btn-send-message" data-id="<?= $won_auction['user_id'] ?>"><i class="fa-solid fa-comment me-1"></i> Chat</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr class="w-100 light">
            </div>
        </div>
    <?php endforeach; ?>
    <div class="w-100 text-center p-5">
        <?= $my_won_auctions_pagination->render() ?>
    </div>
<?php else : ?>
    <p>
        Zatím nemáte žádné nabídky.
        <a href="/nova-nabidka">Zveřejnit nabídku</a>
    </p>
<?php endif; ?>