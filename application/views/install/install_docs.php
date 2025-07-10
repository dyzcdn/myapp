<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Sistem Autentikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-cogs"></i> Setup Sistem Autentikasi</h2>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!$db_connected): ?>
        <div class="alert alert-danger">
            <h5><i class="fas fa-database"></i> Koneksi database gagal</h5>
            <p>Periksa konfigurasi database Anda di file <code>application/config/database.php</code></p>
        </div>
    <?php else: ?>
        <?php if (!$table_exists): ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-table"></i> Tabel <code>users</code> belum tersedia</h5>
                <p>Klik tombol di bawah untuk membuat tabel <code>users</code> secara otomatis.</p>
                <a href="<?= site_url('install/init_db') ?>" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Buat Tabel
                </a>
            </div>
        <?php elseif (!$user_exists): ?>
            <div class="alert alert-info">
                <h5><i class="fas fa-user-plus"></i> Belum ada pengguna</h5>
                <p>Buat akun pengguna default untuk keperluan uji coba:</p>
                <a href="<?= site_url('install/init_user') ?>" class="btn btn-primary me-2">
                    <i class="fas fa-user-shield"></i> Buat Admin Default
                </a>
                <a href="<?= site_url('auth/login') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-forward"></i> Lewati
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <hr class="my-4">

    <h5>.htaccess (mod_rewrite)</h5>
    <pre class="bg-dark text-light p-3 rounded small"><code>RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]</code></pre>

    <h5 class="mt-4">Base URL</h5>
    <pre class="bg-dark text-light p-3 rounded small"><code>$config['base_url'] = '<?= base_url() ?>';</code></pre>

    <h5 class="mt-4">Konfigurasi SMTP (self_config.php)</h5>
    <pre class="bg-dark text-light p-3 rounded small"><code>$config['smtp_host'] = 'smtp.example.com';
$config['smtp_user'] = 'no-reply@example.com';
$config['smtp_pass'] = 'yourpassword';
$config['smtp_port'] = 587;
$config['smtp_crypto'] = 'tls';</code></pre>

    <h5 class="mt-4">Google OAuth (self_config.php)</h5>
    <pre class="bg-dark text-light p-3 rounded small"><code>$config['google_client_id'] = 'YOUR_GOOGLE_CLIENT_ID';
$config['google_client_secret'] = 'YOUR_GOOGLE_CLIENT_SECRET';</code></pre>

    <h5 class="mt-4">Cloudflare Turnstile (self_config.php)</h5>
    <pre class="bg-dark text-light p-3 rounded small"><code>$config['cf_turnstile_site_key'] = 'YOUR_TURNSTILE_SITE_KEY';
$config['cf_turnstile_secret_key'] = 'YOUR_TURNSTILE_SECRET_KEY';</code></pre>

    <div class="alert alert-secondary mt-5">
        <small><i class="fas fa-info-circle"></i> Fitur ini hanya aktif di <strong>ENVIRONMENT: development</strong></small>
    </div>
</div>

<footer class="text-center py-4 border-top mt-5">
    <small>&copy; <?= date('Y') ?> <?= $this->config->item('site_name') ?> - Setup Sistem Autentikasi</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
