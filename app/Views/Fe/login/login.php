<?= $this->extend('Fe/login/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="login">
    <!-- BEGIN login-content -->
    <div class="login-content">
        <form action="<?= base_url(route_to('login')); ?>" method="POST" name="login_form">
            <?= csrf_field() ?>
            <h1 class="text-center">Web ISO</h1>
            <div class="text-muted text-center mb-4">
                Administrator login
            </div>

            <!-- Validation Errors and Message -->
            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= esc($error) ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
            <?php endif ?>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control form-control-lg fs-15px" name="username" placeholder="Masukan username">
            </div>
            <div class="mb-3">
                <div class="d-flex">
                    <label class="form-label">Password</label>
                    <!-- <a href="#" class="ms-auto text-muted">Forgot password?</a> -->
                </div>
                <input type="password" class="form-control form-control-lg fs-15px" name="password" placeholder="Masukan password">
            </div>
            <button type="submit" class="btn btn-theme btn-lg d-block w-100 fw-500 mb-3">Login</button>
            <!-- <div class="text-center text-muted">
                Don't have an account yet? <a href="page_register.html">Sign up</a>.
            </div> -->
        </form>
    </div>
    <!-- END login-content -->
</div>


<?= $this->endSection(); ?>