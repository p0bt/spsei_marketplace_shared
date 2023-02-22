<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa API klíčů</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped" id="api-keys-table">
                <thead>
                    <tr>
                        <th>Klíč</th>
                        <th>Popis</th>
                        <th>Datum expirace</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row my-5">
        <div class="col-12">
            <h3>Přidání nového API klíče</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="api_key" class="form-label">Klíč</label>
                    <input type="text" class="form-control" name="api_key" id="api_key" autocomplete="off" required maxlength="255">
                    <button type="button" class="btn btn-primary my-3" id="btn-generate-api-key">GENEROVAT</button>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Popis</label>
                    <textarea class="form-control" name="description" id="description" autocomplete="off" required rows="8"></textarea>
                </div>
                <div class="mb-3">
                    <label for="expiration_date" class="form-label">Datum expirace</label>
                    <input type="datetime-local" class="form-control" name="expiration_date" id="expiration_date" autocomplete="off" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="without_expiration" id="without_expiration" autocomplete="off" checked>
                    <label for="expiration_date" class="form-check-label">Bez expirace</label>
                </div>
                <button type="submit" class="btn btn-success">Odeslat</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#expiration_date").attr("disabled", $("#without_expiration").is(":checked"));

        let data = <?= json_encode($api_keys) ?>;
        let dataTable = $("#api-keys-table").DataTable({
            data: data,
            order: [[0, "desc"]],
            columns: [
                {
                    data: "api_key",
                    render: function(data, type, row) {
                        return '<input type="password" value="' + data + '" class="api-key"><button type="button" class="btn btn-show-api-key"><i class="fa-solid fa-eye"></i></button><button type="button" class="btn btn-copy-api-key"><i class="fa-solid fa-copy"></i></button>';
                    },
                },
                {
                    data: "description",
                },
                {
                    data: "expiration_date",
                },
                {
                    data: "api_key_id",
                    render: function(data, type, row) {
                        let render = '<a href="/admin/sprava-api-klicu?delete=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-trash-can"></i></a>';
                        render += '<a type="button" href="/admin/upravit-api-klic?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });

        $('#api-keys-table').on('click', '.btn-show-api-key', function() {
            let _this = this;

            $(_this).closest("td").find(".api-key").attr("type", "text");

            setTimeout(function() {
                $(_this).closest("td").find(".api-key").attr("type", "password");
            }, 1000);
        });

        $('#api-keys-table').on('click', '.btn-copy-api-key', function() {
            copy_to_clipboard($(this).closest("td").find(".api-key").val())
        });

        $("#btn-generate-api-key").click(function() {
            let generated_key = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            $("#api_key").val(generated_key);
        });

        $("#without_expiration").click(function() {
            let is_checked = $(this).is(":checked");
            $("#expiration_date").attr("disabled", is_checked);
        });
    });
</script>