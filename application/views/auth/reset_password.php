<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('auth/_partials/header', ['title' => 'Reset Password']); ?>
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
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a class="link-opacity-50-hover link-offset-2 link-underline link-underline-opacity-0" href="<?= base_url('auth/login');?>">← Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('auth/_partials/footer'); ?>
