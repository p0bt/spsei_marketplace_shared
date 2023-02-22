<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa tříd</h3>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <table class="table table-striped" id="class-table">
                <thead>
                    <tr>
                        <th>Název třídy</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <h3>Přidání nové třídy</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Název třídy</label>
                    <input type="text" class="form-control" name="name" id="name" autocomplete="off" required maxlength="3">
                </div>
                <button type="submit" class="btn btn-success">Odeslat</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let data = <?= json_encode($classes) ?>;
        let dataTable = $("#class-table").DataTable({
            data: data,
            order: [[1, "desc"]],
            columns: [
                {
                    data: 'name',
                },
                {
                    data: 'class_id',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-trid?delete=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-trash-can"></i></a>';
                        render += '<a type="button" href="/admin/upravit-tridu?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });
    });
</script>