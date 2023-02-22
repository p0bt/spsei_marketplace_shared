<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<div>
    <div class="row my-5">
        <div class="col-12">
            <div class="card bg-dark py-2 text-white text-center">
                <h1>Moje nabídky</h1>
            </div>
        </div>
    </div>
    <?php if ($alert = HelperFunctions::getAlert("error-offer")) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <?= $alert ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($alert = HelperFunctions::getAlert("success-offer")) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success">
                    <?= $alert ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form action="" method="GET">
        <div class="card border border-dark pt-3 mb-5">
            <div class="row px-3 g-0">
                <div class="col-md-6 col-12 mb-md-0 mb-5">
                    <?php foreach ($categories as $category) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category[]" id="category-checkbox-<?= $category['value'] ?>" value="<?= $category['value'] ?>" <?= HelperFunctions::setCheckbox("category", $category['value']) ?>>
                            <label class="form-check-label" for="category-checkbox-<?= $category['value'] ?>">
                                <?= $category['name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center">
                    <div class="input-group rounded w-100">
                        <input type="text" name="search" value="<?= HelperFunctions::setInputValue("search") ?>" class="form-control border-right-0 rounded-0" placeholder="Název produktu" aria-label="Search" aria-describedby="search-addon">
                        <button type="submit" class="border-0">
                            <span class="input-group-text border-0 rounded-0" id="search-addon">
                                <i class="fa fa-search"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-5 g-0">
                <a href="/muj-ucet?t=my-offers" class="btn btn-light text-uppercase rounded-0">Obnovit</a>
                <button type="submit" class="btn btn-secondary text-uppercase rounded-0">Filtrovat</button>
            </div>
        </div>
        <?php if (!empty($offers)) : ?>
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
                <div class="row">
                    <div class="col-md-2 col-6 text-center">
                        <img src="<?= $thumbnail ?>" class="img-fluid" alt="<?= $name ?>">
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
                        <?php
                        $price = "<b>Aukce</b>";
                        if (isset($offer['price']) && !empty($offer['price'])) {
                            $price = $offer['price'] . " Kč";
                        }
                        ?>
                        <?= $price ?>
                    </div>
                    <div class="col-md-2 col-6 d-flex justify-content-center align-items-center">
                        <a href="detail-nabidky?id=<?= $offer['offer_id'] ?>" class="btn mt-2 me-1"><i class="fa-solid fa-eye"></i></a>
                        <a href="?delete=<?= $offer['offer_id'] ?>" class="btn mt-2"><i class="fa-solid text-danger fa-trash-can"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr class="w-100 light">
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="w-100 text-center p-5">
                <?= $my_offfers_pagination->render() ?>
            </div>
        <?php else : ?>
            <p>
                Zatím nemáte žádné nabídky.
                <a href="/nova-nabidka">Zveřejnit nabídku</a>
            </p>
        <?php endif; ?>
    </form>
</div>