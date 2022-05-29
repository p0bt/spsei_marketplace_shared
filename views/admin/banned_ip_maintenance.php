<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa zablokovaných IP adres</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped" id="banned-ips-table">
                <thead>
                    <tr>
                        <th>IP adresa</th>
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
        let data = <?= json_encode($banned_ips) ?>;
        let dataTable = $("#banned-ips-table").DataTable({
            data: data,
            order: [[0, "desc"]],
            columns: [
                {
                    data: "ip_address",
                },
                {
                    data: "bi_id",
                    render: function(data, type, row) {
                        return '<a type="button" href="?unban=' + data + '" class="btn btn-danger mr-1"><i class="fa-solid fa-trash"></i></a>';
                    },
                },
            ],
        });
    });
</script>