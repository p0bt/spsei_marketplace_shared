<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="text-left g-0 vh-100 d-flex justify-content-center align-items-center">
            <div class="col-md-6 d-md-block d-none">
                <img src="/assets/images/homepage/banner_1.jpg" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
            </div>
            <div class="col-md-6 col-12 p-5 animate__animated animate__flipInX">
                <h1>Přihlášení</h1>
                <?php if ($alert = HelperFunctions::getAlert("error")) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <?= $alert ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" id="login-form">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Heslo</label>
                        <input type="password" class="form-control" id="password" name="password" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <div>
                            <a href="/zapomenute-heslo" class="link-primary text-decoration-none">Zapomenuté heslo</a>
                        </div>
                        <a href="/registrace" class="link-primary text-decoration-none">Klikněte zde pro registraci</a>
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase w-100">Přihlásit se</button>
                </form>
            </div>
        </div>
    </div>
</div>