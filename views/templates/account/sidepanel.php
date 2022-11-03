<?php
use SpseiMarketplace\Core\HelperFunctions;
?>

<div class="col-lg-4 col-12 h-100">
    <div class="card rounded-3 p-5 animate__animated animate__pulse">
        <h4><i class="fa-solid fa-user"></i> Základní informace</h4>
        <hr class="w-100 light mt-1 mb-3 p-0">
        <?php if ($alert = HelperFunctions::getAlert("error-profile")) : ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <?= $alert ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($alert = HelperFunctions::getAlert("success-profile")) : ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success">
                        <?= $alert ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <form method="POST" action="" autocomplete="off">
            <div class="mb-3">
                <label for="first_name" class="form-label">Jméno</label>
                <input type="text" class="form-control" name="first_name" id="first_name" value="<?= (isset($account['first_name']) ? $account['first_name'] : "") ?>" minlength="2" maxlength="50">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Příjmení</label>
                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= (isset($account['last_name']) ? $account['last_name'] : "") ?>" minlength="2" maxlength="50">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= (isset($account['email']) ? $account['email'] : "") ?>" maxlength="100" disabled>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Třída</label>
                <select class="form-select" id="class" name="class">
                    <?php foreach ($classes as $class) : ?>
                        <option value="<?= $class['class_id'] ?>" <?= (isset($account['class_id']) && ($account['class_id'] == $class['class_id'])) ? "selected" : "" ?>><?= $class['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary text-uppercase w-100">Aktualizovat profil</button>
        </form>
    </div>
    <div class="card rounded-3 d-lg-block d-none my-4 p-5 animate__animated animate__pulse animate__delay-1s">
        <h4 class="mb-3"><i class="fa-solid fa-bars-staggered"></i> Shrnutí</h4>
        <hr class="w-100 light mt-1 mb-3 p-0">
        <div class="mb-1">
            <i class="fa-solid fa-book"></i> Počet nabídek: <?= isset($data['overview']["offer_count"]) ? $data['overview']["offer_count"] : 0 ?>
        </div>
        <div class="ms-4 mb-3">
            <i class="fa-solid fa-reply" style="transform:rotateZ(180deg)"></i> Z toho aukcí: <?= isset($data['overview']["auction_count"]) ? $data['overview']["auction_count"] : 0 ?>
        </div>
        <div class="mb-3">
            <i class="fa-solid fa-dollar"></i> Počet mých příhozů do běžících aukcí: <?= isset($data['overview']["bid_count"]) ? $data['overview']["bid_count"] : 0 ?>
        </div>
        <div class="mb-3">
            <i class="fa-solid fa-heart"></i> Počet oblíbených položek: <?= isset($data['overview']["fav_count"]) ? $data['overview']["fav_count"] : 0 ?>
        </div>
        <div class="mb-3">
            <i class="fa-solid fa-bell"></i> Počet oznámení: <?= isset($notifications) ? count($notifications) : 0 ?>
        </div>
    </div>
    <div class="card rounded-3 d-lg-block d-none my-4 p-5 animate__animated animate__pulse animate__delay-2s">
        <h4 class="mb-3"><i class="fa-solid fa-heart"></i> Nedávno přidáno do oblíbených</h4>
        <hr class="w-100 light mt-1 mb-3 p-0">
        <?php if (isset($data['wishlist']) && !empty($data['wishlist'])) : ?>
            <?php foreach ($data['wishlist'] as $offer) : ?>
                <div class="mb-2">
                    <a href="/detail-nabidky?id=<?= $offer['id'] ?>" class="text-decoration-none text-dark">
                        <img src="<?= $offer["thumbnail"] ?>" alt="<?= $offer["name"] ?>" width="50">
                        <?= $offer["name"] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>