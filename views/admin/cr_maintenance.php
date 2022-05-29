<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa umístění tříd</h3>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <table class="table table-striped text-center" id="cr-table">
                <thead>
                    <tr>
                        <th>Název třídy</th>
                        <th>Kód místnosti</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-12">
            <h3>Přidání nového umístění</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="class_id" class="form-label">Název třídy</label>
                    <select name="class_id" class="form-control">
                        <?php foreach($classes as $class): ?>
                            <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="room_code" class="form-label">Kód místnosti</label>
                    <input type="text" class="form-control" name="room_code" id="room_code">
                </div>
                <button type="submit" class="btn btn-success">Odeslat</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let data = <?= json_encode($crs) ?>;
        let dataTable = $("#cr-table").DataTable({
            data: data,
            order: [[1, "asc"]],
            columnDefs: [
                { 
                    type: 'natural', 
                    targets: 1,
                },
            ],
            columns: [
                {
                    data: 'name',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<input type="text" class="form-control text-center w-auto mx-auto font-weight-bold input-room-code" name="room_code" value="' + row['room_code'] + '" data-id="' + row['class_id'] + '" minlength="4" maxlength="4">';
                    },
                },
            ]
        });

        $('#cr-table').on('change', '.input-room-code', function() {
            let class_id = $(this).data('id');
            let value = $(this).val();
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    class_id: class_id,
                    room_code: value,
                },
            });
        });
    });
</script>