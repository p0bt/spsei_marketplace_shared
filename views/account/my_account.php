<div class="container">
    <div class="row my-5">
        <div class="col-12 text-white card border border-dark shadow-sm py-5 banner-gradient">
            <h1 class="ms-5">Můj účet</h1>
        </div>
    </div>
    <div class="row mt-5">
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
                <form method="POST" action="">
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
        <div class="col-lg-8 col-12 p-5">
            <h4 class="mb-5">Moje nabídky</h4>
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
                            <div class="form-check align-middle">
                                <input class="form-check-input" type="checkbox" name="category[]" id="category-radio-ucebnice" value="ucebnice" <?= HelperFunctions::setCheckbox("category", "ucebnice") ?>>
                                <label class="form-check-label">
                                    Učebnice / Pracovní sešity
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category[]" id="category-radio-sesit" value="sesit" <?= HelperFunctions::setCheckbox("category", "sesit") ?>>
                                <label class="form-check-label" for="category-radio-sesit">
                                    Sešity (Poznámky)
                                </label>
                            </div>
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
                                <?= $offer['price'] ?> Kč
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
                        <?= $pagination->render() ?>
                    </div>
                <?php else : ?>
                    <p>
                        Zatím nemáte žádné nabídky.
                        <a href="/nova-nabidka">Zveřejnit nabídku</a>
                    </p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>