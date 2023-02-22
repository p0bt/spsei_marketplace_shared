<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa sešitů</h3>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <table class="table table-striped" id="notebook-table">
                <thead>
                    <tr>
                        <th>ID sešitu</th>
                        <th>Název sešitu</th>
                        <th>Třída</th>
                        <th>Obor</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let data = <?= json_encode($notebooks) ?>;
        let dataTable = $("#notebook-table").DataTable({
            data: data,
            order: [
                [1, "desc"]
            ],
            columns: [
                {
                    data: 'notebook_id',
                },
                {
                    data: 'name',
                },
                {
                    data: 'grade',
                },
                {
                    data: 'm_name',
                },
                {
                    data: 'notebook_id',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-sesitu?delete=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-trash-can"></i></a>';
                        render += '<a type="button" href="/admin/upravit-sesit?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });

        <?php if(isset($_GET['find']) && !empty($_GET['find'])): ?>
            dataTable.column(0).search(<?= $_GET['find'] ?>).draw();
        <?php endif; ?>
    });
</script>