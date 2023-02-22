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
                        <th>Kategorie</th>
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
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" name="isbn" id="isbn" autocomplete="off" required maxlength="13">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Název knihy</label>
                    <input type="text" class="form-control" name="name" id="name" autocomplete="off" required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" class="form-control" name="author" id="author" autocomplete="off" required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="grade" class="form-label">Pro ročník:</label>
                    <select name="grade" class="form-control" id="grade">
                        <?php for ($grade = 0; $grade <= 4; $grade++) : ?>
                            <?php if($grade == 0): ?>
                                <option value="<?= $grade ?>">Vše</option>
                            <?php else: ?>
                                <option value="<?= $grade ?>"><?= $grade ?>. ročník</option>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="major" class="form-label">Pro obor:</label>
                    <select name="major" class="form-control" id="major">
                        <?php foreach (array_reverse($majors) as $major) : ?>
                            <option value="<?= $major['major_id'] ?>"><?= $major['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategorie</label>
                    <select name="category" class="form-control" id="category">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Odeslat</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        render_errors();

        let data = <?= json_encode($books) ?>;
        let dataTable = $("#book-table").DataTable({
            data: data,
            order: [
                [1, "desc"]
            ],
            columns: [{
                    data: 'b_name',
                },
                {
                    data: 'b_author',
                },
                {
                    data: 'c_name',
                },
                {
                    data: 'b_book_ISBN',
                    render: function(data, type, row) {
                        let render = '<a type="button" href="/admin/sprava-knih?delete=' + data + '" class="btn btn-danger mr-2"><i class="fa-solid fa-trash-can"></i></a>';
                        render += '<a type="button" href="/admin/upravit-knihu?id=' + data + '" class="btn btn-primary mr-2"><i class="fa-solid fa-pencil"></i></a>';
                        return render;
                    },
                },
            ]
        });

        function render_errors() {
            let errors = <?= json_encode($validator->getErrors()) ?>;
            if(errors.length > 0) {
                [...errors].map((error) => {
                    let alert = "<div class='alert alert-danger'>" + error.error_message + "</div>";
                    //$("#"+ error.input_name).before(alert);
                    $("*[name='" + error.input_name + "']").before(alert);
                });
            }
        }
    });
</script>