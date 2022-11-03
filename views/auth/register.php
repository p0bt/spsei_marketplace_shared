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
                <?php if ($alert = HelperFunctions::getAlert("success")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($alert = HelperFunctions::getAlert("error")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <h1>Registrace</h1>
                <form action="" method="POST" id="login-form">
                    <?php if ($validator->getError("email")) : ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <?= $validator->getError("email") ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-3 align-items-start">
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= HelperFunctions::setInputValue('email') ?>" maxlength="100" required>
                        </div>
                    </div>
                    <?php if ($validator->getError("password")) : ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <?= $validator->getError("password") ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="password" class="form-label">Heslo</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?= HelperFunctions::setInputValue('password') ?>" minlength="8" maxlength="255" required>
                        </div>
                    </div>
                    <?php if ($validator->getError("cpassword")) : ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <?= $validator->getError("cpassword") ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="password" class="form-label">Potvrďte heslo</label>
                            <input type="password" class="form-control" id="cpassword" name="cpassword" value="<?= HelperFunctions::setInputValue('cpassword') ?>" minlength="8" maxlength="255" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <a href="/prihlaseni" class="link-primary text-decoration-none">Klikněte zde pro přihlášení</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase w-100">Registrovat se</button>
                </form>
            </div>
        </div>
    </div>
</div>