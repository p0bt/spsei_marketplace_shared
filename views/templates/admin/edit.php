<div class="h-100 container-fluid">
    <div class="h-100 row justify-content-center align-items-center">
        <div class="col-lg-6 col-12">
            <?= $back_btn ?>
            <?= $edit_form ?>
        </div>
    </div>
</div>

<script>
    // Render errors
    let errors = <?= json_encode((isset($errors) ? $errors : [])) ?>;
    [...errors].map((error) => {
        let error_on_input = error.input_name;
        let error_message = error.error_message;

        let builded_alert = `<div class="alert alert-danger new-offer-validation-alert">` + error_message + `</div>`;
        $("[name='" + error_on_input + "']").before(builded_alert);
    });
</script>