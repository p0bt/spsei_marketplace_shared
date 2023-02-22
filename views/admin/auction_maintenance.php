<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa aukcí</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-check">
                <input type="radio" class="form-check-input" name="auction_status" id="auction_status_all" value="all" checked>
                <label for="auction_status_not_started" class="form-check-label">Všechny aukce</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="auction_status" id="auction_status_not_started" value="not_started">
                <label for="auction_status_not_started" class="form-check-label">Budoucí aukce</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="auction_status" id="auction_status_in_progress" value="in_progress">
                <label for="auction_status_in_progress" class="form-check-label">Běžící aukce</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="auction_status" id="auction_status_ended" value="ended">
                <label for="auction_status_ended" class="form-check-label">Ukončené aukce</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped" id="auctions-table">
                <thead>
                    <tr>
                        <th>ID aukce</th>
                        <th>Nabídka</th>
                        <th>Nejvyšší příhoz</th>
                        <th>Aktuální vyhravající</th>
                        <th>Začátek aukce</th>
                        <th>Konec aukce</th>
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

        let selected_value = $("input[name='auction_status']").val();

        let dataTable = $("#auctions-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/get-auctions',
                type: 'POST',
                dataSrc: '',
                data: function (d) {
                    d.auction_status = selected_value;
                },
            },
            order: [[0, "desc"]],
            columns: [
                {
                    data: "auction_id",
                },
                {
                    data: "offer_id",
                    render: function(data, type, row) {
                        return "<a href='/admin/sprava-nabidek?find=" + data + "'><i class='fa-solid fa-eye text-dark'></i></a>";
                    },
                },
                {
                    data: "top_bid",
                    render: function(data, type, row) {
                        if(data !== null && data != "")
                            return data + " Kč";
                        return "-";
                    },
                },
                {
                    data: "user_id",
                    render: function(data, type, row) {
                        if(data !== null && data != "")
                            return '<a href="/admin/sprava-uzivatelu?find=' + data + '">ID: ' + data + '</a>';
                        return "-";
                    },
                },
                {
                    data: "start_date",
                },
                {
                    data: "end_date",
                },
                {
                    data: "auction_id",
                    render: function(data, type, row) {
                        let render = '<a href="/admin/sprava-aukci?end_auction=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-hand"></i></a>';
                        render += '<a type="button" href="/admin/upravit-aukci?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        render += '<a type="button" href="/admin/upravit-aukci?id=' + data + '&offer_included=ano" class="btn btn-secondary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });

        $("input[name='auction_status']").change(function() {
            selected_value = $(this).val();
            dataTable.ajax.reload(null, false);
        });

        <?php if(isset($_GET['find']) && !empty($_GET['find'])): ?>
            dataTable.column(0).search(<?= $_GET['find'] ?>).draw();
        <?php endif; ?>
    });
</script>