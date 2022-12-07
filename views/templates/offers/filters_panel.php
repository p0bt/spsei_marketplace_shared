<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<div class="col-lg-3 col-md-4 d-md-block d-none shadow p-4 gx-5" id="filters-col">
    <h3>Filtry</h3>
    <form action="" method="GET">
        <div id="filter-inputs">
            <div class="row mt-5 mb-4">
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
            <div class="row my-4">
                <div class="col-12">
                    <h5>Obor</h5>
                    <select name="major" class="form-select" id="major">
                        <?php foreach (array_reverse($majors) as $major) : ?>
                            <option value="<?= $major['major_id'] ?>" <?= HelperFunctions::setSelect('major', $major['major_id']) ?>><?= $major['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-12">
                    <h5>Ročník</h5>
                    <select name="grade" class="form-select" id="grade">
                        <?php for ($grade = 0; $grade <= 4; $grade++) : ?>
                            <?php if($grade == 0): ?>
                                <option value="<?= $grade ?>" <?= HelperFunctions::setSelect('grade', $grade) ?>>Vše</option>
                            <?php else: ?>
                                <option value="<?= $grade ?>" <?= HelperFunctions::setSelect('grade', $grade) ?>><?= $grade ?>. ročník</option>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="row my-4">
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
            <div class="row my-4">
                <h5>Kategorie</h5>
                <div class="col-12">
                    <?php foreach ($categories as $category) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category[]" id="category-checkbox-<?= $category['value'] ?>" value="<?= $category['value'] ?>" <?= HelperFunctions::setCheckbox("category", $category['value']) ?>>
                            <label class="form-check-label" for="category-checkbox-<?= $category['value'] ?>">
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