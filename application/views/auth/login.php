<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.lab/static/fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lab/static/bootstrap-5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.lab/static/icons/fa/pro-5.15.4/css/all.min.css">
    <script src="https://cdn.lab/static/icons/fa/pro-5.15.4/js/pro.min.js"></script>
    <script src="https://cdn.lab/static/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Login</h3>

                    <?= display_flash() ?><?= flash_message() ?>

                    <form method="post" action="<?= base_url('auth/do_login') ?>">
                        <div class="mb-3">
                            <label for="identity" class="form-label">Email atau Username</label>
                            <input type="text" class="form-control" name="identity" id="identity" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="cf-turnstile mb-3" data-sitekey="<?= $this->config->item('cf_turnstile_site_key') ?>"></div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('auth/google_login') ?>" class="btn btn-outline-danger w-100">
                            <i class="fab fa-google"></i> Login dengan Google
                        </a>
                    </div>

                    <div class="mt-3 text-center">
                        <p class="mt-2">
                            Belum punya akun? <a href="<?= base_url('auth/register') ?>">Daftar</a><br>
                            <a href="<?= base_url('auth/forgot_password') ?>">Lupa Password?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
