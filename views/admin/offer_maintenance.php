<div class="container-fluid">
    <div class="row my-2">
        <div class="col-12">
            <h3>Správa nabídek</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped" id="offers-table">
                <thead>
                    <tr>
                        <th>Přidáno uživatelem</th>
                        <th>Název</th>
                        <th>Popis</th>
                        <th>Kategorie</th>
                        <th>Cena / Typ</th>
                        <th>Adresář s obrázky</th>
                        <th>Datum zveřejnění</th>
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
        let data = <?= json_encode($offers) ?>;
        let dataTable = $("#offers-table").DataTable({
            data: data,
            order: [[6, "desc"]],
            columns: [
                {
                    data: null,
                    render: function(data, type, row) {
                        let render = (row['first_name'] !== null ? row['first_name'] : "?") + " " + (row['last_name'] !== null ? row['last_name'] : "?") + " (" + row['c_name'] + ")";
                        return '<a type="button" class="text-decoration-none btn-user-detail" data-id="' + render + '">' + row['email'] + '</a>';
                    },
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if(row['b_book_ISBN'] !== null && row['b_book_ISBN'].length > 0)
                            return row['b_name'] + " (" + row['b_author'] + ")";
                        return row['name'];
                    },
                },
                {
                    data: 'description',
                },
                {
                    data: 'cat_name',
                    className: "text-center",
                },
                {
                    data: 'price',
                    render: function(data, type, row) {
                        if(row['a_auction_id'] !== null && row['a_auction_id'].length > 0) {
                            return "Aukce"
                        } else {
                            return data + " Kč";
                        }
                    },
                },
                {
                    data: 'image_path',
                    render: function(data, type, row) {
                        return '<a href="/uploads/' + data + '" target="_blank">' + data + '</a>';
                    },
                },
                {
                    data: 'date',
                },
                {
                    data: 'offer_id',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-nabidek?delete=' + data + '" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>';
                        return render;
                    },
                },
            ]
        });
    });

    $('table').on('click', '.btn-user-detail', function() {
        let user_detail = $(this).attr('data-id');
        Swal.fire({
            title: 'Detail uživatele',
            text: user_detail,
        });
    });
</script>