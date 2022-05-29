<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa knih</h3>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <table class="table table-striped" id="book-table">
                <thead>
                    <tr>
                        <th>Název knihy</th>
                        <th>Autor</th>
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
            <h3>Přidání nové knihy</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Název knihy</label>
                    <input type="text" class="form-control" name="name" id="name" autocomplete="off" required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" class="form-control" name="author" id="author" autocomplete="off" required maxlength="50">
                </div>
                <button type="submit" class="btn btn-success">Odeslat</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let data = <?= json_encode($books) ?>;
        let dataTable = $("#book-table").DataTable({
            data: data,
            order: [[1, "desc"]],
            columns: [
                {
                    data: 'name',
                },
                {
                    data: 'author',
                },
                {
                    data: 'book_id',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-knih?delete=' + data + '" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>';
                        return render;
                    },
                },
            ]
        });
    });
</script>