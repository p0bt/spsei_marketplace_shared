<?php include_once("views/templates/header.php"); ?>
<div class="container">
    <div class="row vh-100">
        <div class="col-12 d-flex justify-content-center align-items-center text-center">
            <div>
                <h1>404 Chyba</h1>
                Požadovaná položka pro úpravu neexistuje <?= (isset($_GET['id'])) ? $_GET['id'] : "" ?>
            </div>
        </div>
    </div>
</div>
<?php include_once("views/templates/footer.php"); ?>