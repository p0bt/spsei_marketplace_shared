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
                        <th>ID nabídky</th>
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
                    data: 'offer_id',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let full_name = (row['first_name'] !== null ? row['first_name'] : "?") + " " + (row['last_name'] !== null ? row['last_name'] : "?") + " (" + row['c_name'] + ")";
                        return '<a type="button" class="text-decoration-none btn-user-detail" data-id="' + row['user_id'] + '" data-name="' + full_name + '">' + row['email'] + '</a>';
                    },
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if(row['b_book_ISBN'] !== null && row['b_book_ISBN'].length > 0)
                            return row['b_name'] + " (" + row['b_author'] + ")";
                        return "<a href='/admin/sprava-sesitu?find=" + row['nb_id'] + "'>" + row['name'] + "</a>";;
                    },
                },
                {
                    data: 'description',
                },
                {
                    data: 'cat_name',
                    className: "text-center",
                    render: function(data, type, row) {
                        if(data == null || data == "")
                            return "Sešity";
                        return data;
                    },
                },
                {
                    data: 'price',
                    render: function(data, type, row) {
                        if(row['a_auction_id'] !== null && row['a_auction_id'].length > 0) {
                            return "<a href='/admin/sprava-aukci?find=" + row['a_auction_id'] + "'>Aukce</a>";
                        } else {
                            return data + " Kč";
                        }
                    },
                },
                {
                    data: 'image_path',
                    render: function(data, type, row) {
                        return '<a type="button" class="btn-open-image-directory" data-dir="' + data + '"><i class="fa-solid fa-folder text-dark"></i></a>';
                        //return '<a href="/uploads/' + data + '" target="_blank"><i class="fa-solid fa-folder text-dark"></i></a>';
                    },
                },
                {
                    data: 'date',
                },
                {
                    data: 'offer_id',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-nabidek?delete=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-trash-can"></i></a>';
                        render += '<a type="button" href="/admin/upravit-nabidku?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });

        <?php if(isset($_GET['find']) && !empty($_GET['find'])): ?>
            dataTable.column(0).search(<?= $_GET['find'] ?>).draw();
        <?php endif; ?>
    });

    // Show user detail
    $('table').on('click', '.btn-user-detail', function() {
        let user_full_name = $(this).attr('data-name');
        let user_id = $(this).attr('data-id');

        let swal_text = user_full_name + '<br><a href="/admin/sprava-uzivatelu?find=' + user_id + '">Více info</a>';
        Swal.fire({
            title: 'Detail uživatele',
            html: swal_text,
        });
    });

    // Show uploaded images
    $('table').on('click', '.btn-open-image-directory', function() {
        let dir = $(this).attr('data-dir');
        let path = "/uploads/" + dir;

        Swal.fire({
            title: 'Obrázky',
            html: "<div id='image-area'></div>",
        });

        // Code used/inspired from:
        // https://stackoverflow.com/questions/18480550/how-to-load-all-the-images-from-one-of-my-folder-into-my-web-page-using-jquery
        
        $.ajax({
            method: "GET",
            url: path,
            success: function (data) {
                $(data).find("a").attr("href", function (i, val) {
                    if(val.match(/\.(jpe?g|png|gif)$/)) { 
                        $("#image-area").append("<img class='mr-2' src='" + path + "/" + val + "' height='100'>");
                    } 
                });
            }
        });
    });
</script>