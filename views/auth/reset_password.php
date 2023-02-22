<?php

use SpseiMarketplace\Core\HelperFunctions;
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="text-left g-0 vh-100 d-flex justify-content-center align-items-center">
            <div class="col-md-6 d-md-block d-none">
                <img src="/assets/images/homepage/banner_1.jpg" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
            </div>
            <div class="col-md-6 col-12 p-5 animate__animated animate__flipInX">
                <h1>Obnovení hesla</h1>
                <?php if ($alert = HelperFunctions::getAlert("error")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($alert = HelperFunctions::getAlert("success")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($show_new_password_form) : ?>
                    <?php if ($alert = HelperFunctions::getAlert("error-password")) : ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <?= $alert ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($alert = HelperFunctions::getAlert("success-password")) : ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <?= $alert ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST" id="reset-password-form">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nové heslo</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" value="" minlength="8" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nové heslo znovu</label>
                            <input type="password" class="form-control" name="new_cpassword" id="new_cpassword" value="" minlength="8" maxlength="255" required>
                        </div>
                        <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                        <div class="mb-3">
                            <div>
                                <a href="/prihlaseni" class="link-primary text-decoration-none">Zpět na přihlášení</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary text-uppercase w-100">Změnit heslo</button>
                    </form>
                <?php else : ?>
                    <form action="" method="POST" id="send-reset-password-email-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <div>
                                <a href="/prihlaseni" class="link-primary text-decoration-none">Zpět na přihlášení</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary text-uppercase w-100">Odeslat email</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>