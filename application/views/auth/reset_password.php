<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.lab/static/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <?= display_flash() ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-4 text-center">Reset Password</h4>
                    <form method="post" action="<?= base_url('auth/do_reset_password') ?>">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password Baru" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Reset Password</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('auth/login') ?>">← Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
