<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<div class="container d-flex min-vh-100 align-items-center justify-content-center">
    <div class="w-100 px-5">
        <div class="row text-center mb-5 mt-md-0 mt-5">
            <div class="col-12">
                <div class="card banner-gradient py-2 text-white">
                    <h1>Nová nabídka</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="alert" id="new-offer-response-alert"></div>
            </div>
        </div>
        <form action="post-offer" method="POST" enctype="multipart/form-data" id="new-offer-form">
            <div class="row">
                <div class="col-md-6 col-12 mb-5 my-md-0">
                    <div class="row">
                        <div class="col-12">
                            Zvolte kategorii:
                        </div>
                    </div>
                    <div class="row py-1">
                        <?php foreach ($categories as $category) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="category-radio-<?= $category['value'] ?>" value="<?= $category['category_id'] ?>" data-value="<?= $category['value'] ?>" checked required>
                                <label class="form-check-label" for="category-radio-<?= $category['value'] ?>">
                                    <?= $category['name'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row py-1">
                        <div class="col-12">
                            <label for="name" class="form-label">Název:</label>
                            <div id="name-form">
                            </div>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-12">
                            <label for="description" class="form-label">Popište stav:</label>
                            <input class="form-control" type="text" name="description" id="description" value="<?= HelperFunctions::setInputValue("description") ?>" placeholder="Např: Poškozená vazba" minlength="3" maxlength="100" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="price" class="form-label">Cena:</label>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-pevna" value="pevna" checked required>
                                <label class="form-check-label" for="price-radio-pevna">
                                    <div>Pevná</div>
                                    <img src="/assets/images/price_cash.png" alt="Pevná cena" width="100" class="mt-1">
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_type" id="price-radio-aukce" value="aukce">
                                <label class="form-check-label" for="price-radio-aukce">
                                    <div>Aukce</div>
                                    <img src="/assets/images/price_auction.png" alt="Aukce" width="100" class="mt-1">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="price-form">
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <button class="btn btn-primary text-uppercase w-100 mt-2" type="submit" id="submit-btn">Odeslat</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center justify-content-center my-5 my-md-0">
                    <div class="w-100">
                        <div id="dZUpload" class="dropzone">
                            <div class="dz-default dz-message">
                                <h5 class="my-0">Přetáhněte soubory zde pro nahrání</h5>
                                <span>Nebo klikněte zde pro výběr souborů</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let formData_;
    Dropzone.autoDiscover = false;

    $(document).ready(function() {

        render_name_form($("input[name='category']:checked").data('value'));
        render_price_form($("input[name='price_type']:checked").val());

        $("input[name='category']").change(function() {
            let val = $(this).data('value');
            render_name_form(val);
        });

        $("input[name='price_type']").change(function() {
            let val = $(this).val();
            render_price_form(val);
        });

        function render_name_form(val) {
            let form = "",
                options = "",
                select_start = "",
                select_end = "";
            let is_book = ["povinne_ucebnice", "doporucene_ucebnice", "povinna_cetba"].includes(val);

            if (is_book) {
                select_start = `<select name="name" class="form-select" required>
                                    <option value="none" selected>--- Vyberte ---</option>`;
                select_end = `</select>`;
            }

            switch (val) {
                case 'povinne_ucebnice':
                    options += `<?php foreach ($books['mandatory'] as $book) : ?>
                                    <option value="<?= $book['b_book_ISBN'] ?>" <?= (HelperFunctions::setInputValue("name") == $book['b_book_ISBN']) ? "selected" : "" ?>><?= $book['b_name'] ?> (<?= $book['b_author'] ?>)</option>
                                <?php endforeach; ?>`;
                    break;

                case 'doporucene_ucebnice':
                    options += `<?php foreach ($books['recommended'] as $book) : ?>
                                    <option value="<?= $book['b_book_ISBN'] ?>" <?= (HelperFunctions::setInputValue("name") == $book['b_book_ISBN']) ? "selected" : "" ?>><?= $book['b_name'] ?> (<?= $book['b_author'] ?>)</option>
                                <?php endforeach; ?>`;
                    break;

                case 'povinna_cetba':
                    options += `<?php foreach ($books['reading'] as $book) : ?>
                                    <option value="<?= $book['b_book_ISBN'] ?>" <?= (HelperFunctions::setInputValue("name") == $book['b_book_ISBN']) ? "selected" : "" ?>><?= $book['b_name'] ?> (<?= $book['b_author'] ?>)</option>
                                <?php endforeach; ?>`;
                    break;
                case 'sesity':
                    form = `<input class="form-control" type="text" name="name" value="<?= HelperFunctions::setInputValue("name") ?>" placeholder="Např: Kompletní sešit do literetury" autocomplete="off" minlength="3" maxlength="50" required>`;
                    break;
            }

            $("#name-form").html("");

            if (is_book) {
                $("#name-form").append(select_start + options + select_end);
            } else {
                $("#name-form").append(form);
            }
        }

        function render_price_form(val) {
            let form = "";

            switch (val) {
                case 'pevna':
                    form = `<input class="form-control" type="number" name="price" id="price" value="<?= HelperFunctions::setInputValue("price") ?>" placeholder="Např: 89" required>`;
                    form += `<div class="mt-1 form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox-price-free">
                                <label for="checkbox-price-free" class="form-check-label">Zdarma</label>
                            </div>`;
                    break;
                case 'aukce':
                    form = `<label for="start_date" class="form-label">Začátek aukce <span id="start_date_icon" class="mx-1"></span></label>
                            <input class="form-control mb-1" type="datetime-local" name="start_date" id="start_date" value="<?= ($tmp = HelperFunctions::setInputValue("start_date")) ? $tmp : str_replace(" ", "T", date("Y-m-d H:i", time() + (3600 * 2))); ?>" min="<?= str_replace(" ", "T", date("Y-m-d H:i", time() + 3600)); ?>" max="<?= str_replace(" ", "T", date("Y-m-d H:i", time() + ((OFFER_EXPIRATION_DAYS - 1) * 86400))); ?>" required>
                            <label for="end_date" class="form-label">Konec aukce <span id="end_date_icon" class="mx-1"></span></label>
                            <input class="form-control" type="datetime-local" name="end_date" id="end_date" value="<?= ($tmp = HelperFunctions::setInputValue("end_date")) ? $tmp : str_replace(" ", "T", date("Y-m-d H:i", time() + (3600 * 3) + 86400)); ?>" required>
                            `;
                    break;
            }

            $("#price-form").html("");
            $("#price-form").append(form);

            switch (val) {
                case 'aukce':
                    validate_start_date($("#start_date").val());
                    validate_end_date($("#end_date").val());
                    break;
            }
        }

        $("#new-offer-form").on('change', '#start_date', function() {
            let start_date = $(this).val();
            validate_start_date(start_date);
            $("#end_date").attr({
                min: new Date((new Date(start_date).getTime() / 1000) + 3600 + 86400),
                max: new Date((new Date(start_date).getTime() / 1000) + 3600 + <?= OFFER_EXPIRATION_DAYS - 1 ?> * 86400),
            });
        });

        $("#new-offer-form").on('change', '#end_date', function() {
            let start_date = $("#start_date").val();
            let end_date = $(this).val();
            validate_end_date(start_date, end_date);
        });

        $("#price-form").on('change', '#checkbox-price-free', function() {
            let is_checked = $("#checkbox-price-free").is(":checked");
            $("#price").val((is_checked ? 0 : ""));
            $("#price").prop("readonly", is_checked);
        });

        function validate_start_date(start_date) {

            let error = false;
            start_date = new Date(start_date).getTime() / 1000;
            now = new Date().getTime() / 1000;

            if (start_date < (now + 3600)) {
                Swal.fire({
                    title: 'Chyba',
                    text: "Začátek aukce musí být nejdříve za hodinu od teď",
                    icon: 'error',
                    position: 'bottom-end',
                    width: "auto",
                    showConfirmButton: false,
                    timer: 2000
                });
                error = true;
            }
            if (start_date > (now + 3600 + (<?= OFFER_EXPIRATION_DAYS - 1 ?> * 86400))) {
                Swal.fire({
                    title: 'Chyba',
                    text: "Aukci můžete naplánovat maximálně <?= OFFER_EXPIRATION_DAYS ?> dní dopředu",
                    icon: 'error',
                    position: 'bottom-end',
                    width: "auto",
                    showConfirmButton: false,
                    timer: 2000
                });
                error = true;
            }

            $("#start_date_icon").html((error ? '<i class="fa-solid fa-xmark text-danger"></i>' : '<i class="fa-solid fa-check text-success"></i>'));
            return error;
        }

        function validate_end_date(start_date, end_date) {

            let error = false;
            start_date = new Date(start_date).getTime() / 1000;
            end_date = new Date(end_date).getTime() / 1000;

            if (end_date <= (start_date + 86400)) {
                Swal.fire({
                    title: 'Chyba',
                    text: "Aukce musí trvat alespoň den",
                    icon: 'error',
                    position: 'bottom-end',
                    width: "auto",
                    showConfirmButton: false,
                    timer: 2000
                });
                error = true;
            }

            $("#end_date_icon").html((error ? '<i class="fa-solid fa-xmark text-danger"></i>' : '<i class="fa-solid fa-check text-success"></i>'));
            return error;
        }

        let dropzone_config = {
            paramName: "photo",
            url: $("#new-offer-form").attr("action"),
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 4,
            maxFiles: 4,
            maxFilesize: 1,
            acceptedFiles: 'image/*',
            init: function() {
                let dropzone = this;

                dropzone.on("sendingmultiple", function(data, xhr, formData) {
                    for (let data of formData_.entries()) {
                        formData.append(data[0], data[1]);
                    }
                });
            },
            successmultiple: function(file, response) {
                let resp = $.parseJSON(response);
                let alert = $("#new-offer-response-alert");

                $("#new-offer-response-alert").addClass("alert-" + (resp.success ? "success" : "danger"));
                if (resp.success) {
                    alert.text(resp.text);
                    this.removeAllFiles(true);
                    $("#new-offer-form")[0].reset();
                    render_name_form($("input[name='category']:checked").val());
                    render_price_form($("input[name='price_type']:checked").val());
                } else {
                    alert.text("Opravte prosím chyby, a zkuste to znovu");
                    for (let i = 0; i < resp.errors.length; i++) {
                        let error_on_input = resp.errors[i].input_name;
                        let error_message = resp.errors[i].error_message;

                        let builded_alert = `<div class="alert alert-danger new-offer-validation-alert">` + error_message + `</div>`;
                        $("[name='" + error_on_input + "']").before(builded_alert);
                    }
                }
            },
        };

        let dropzone = new Dropzone(document.getElementById("dZUpload"), dropzone_config);

        $("#submit-btn").click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            $("#new-offer-response-alert").text("");
            $("#new-offer-response-alert").removeClass("alert-success");
            $("#new-offer-response-alert").removeClass("alert-danger");
            $(".new-offer-validation-alert").remove();

            let form = document.querySelector("#new-offer-form");
            formData_ = new FormData(form);
            dropzone.processQueue();
        });

    });
</script>