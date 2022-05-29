<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa uživatelů</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped" id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Jméno</th>
                        <th>Příjmení</th>
                        <th>Třída</th>
                        <th>Datum registrace</th>
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
        let data = <?= json_encode($users) ?>;
        let dataTable = $("#users-table").DataTable({
            data: data,
            order: [[6, "desc"]],
            columns: [
                {
                    data: "user_id",
                    render: function(data, type, row) {
                        let render = '';
                        if(row["bi_id"] !== undefined && row["bi_id"].length > 0) render += '<i class="fa-solid fa-ban mx-2"></i>';
                        return render + data;
                    },
                },
                {
                    data: "email",
                },
                {
                    data: "first_name",
                },
                {
                    data: "last_name",
                },
                {
                    data: "c_name",
                },
                {
                    data: "register_date",
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let render = '<a type="button" href="mailto:' + row['email'] + '" class="btn btn-primary mr-1"><i class="fa-solid fa-envelope"></i></a>';
                        render += '<a type="button" href="?ban=' + row['ip_address'] + '" class="btn btn-danger mr-1"><i class="fa-solid fa-ban"></i></a>';
                        render += '<a type="button" href="?unban=' + row['ip_address'] + '" class="btn btn-success mr-1"><i class="fa-solid fa-circle-check"></i></a>';
                        return render;
                    },
                },
            ],
            createdRow: function( row, data, dataIndex ) {
                if (data["bi_id"] !== undefined && data["bi_id"].length > 0) {
                    $(row).addClass('text-danger');
                }
            }
        });
    });
</script>