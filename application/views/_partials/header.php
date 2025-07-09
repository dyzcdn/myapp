<!-- _partials/header.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Aplikasi' ?> | <?=  $this->config->item('site_name') ?></title>

    <link href="https://cdn.lab/static/fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lab/static/bootstrap-5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.lab/static/icons/fa/pro-5.15.4/css/all.min.css">
    <script src="https://cdn.lab/static/icons/fa/pro-5.15.4/js/pro.min.js"></script>

    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon-16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon-32.png') ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= base_url('favicon-48.png') ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="<?= base_url('favicon-64.png') ?>">
    <link rel="icon" type="image/png" sizes="128x128" href="<?= base_url('favicon-128.png') ?>">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= base_url('favicon-180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('favicon-192.png') ?>">
    <link rel="icon" type="image/png" sizes="256x256" href="<?= base_url('favicon-256.png') ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= base_url('favicon-512.png') ?>">

    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-180.png') ?>">
    <meta name="theme-color" content="#0d6efd">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <?php if ($this->uri->segment(1) === 'certificates'): ?>
        <link rel="stylesheet" href="https://cdn.lab/static/DataTables/datatables.min.min.css">
        <link rel="stylesheet" href="https://cdn.lab/static/dataTables/css/responsive.bootstrap5.min.css">
    <?php endif; ?>

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            font-family: 'Roboto', sans-serif;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Montserrat', sans-serif;
        }
        main {
            flex: 1;
        }
        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
        .action-buttons .btn {
            margin-right: 4px;
            margin-bottom: 4px;
        }
        section {
            margin-bottom: 4rem;
        }
        td.san-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (min-width: 768px) {
            .action-buttons {
                flex-wrap: nowrap !important;
            }
            .dataTables_wrapper .dataTables_filter {
                float: right;
            }
            .dataTables_wrapper .dataTables_length {
                float: left;
            }
        }
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem;
        }
        @media (max-width: 767.98px) {
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length {
                text-align: center;
                float: none !important;
                width: 100%;
            }
            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_length label {
                justify-content: center;
                flex-direction: column;
            }
            .dataTables_filter label {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url() ?>"><i class="fas fa-home"></i> MyApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'welcome' ? 'active' : '' ?>" href="<?= base_url() ?>">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) === 'certificates' ? 'active' : '' ?>" href="<?= base_url('certificates') ?>">
                        <i class="fas fa-lock"></i> SSL Generator
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'welcome/smtp' ? 'active' : '' ?>" href="<?= base_url('welcome/smtp') ?>">
                        <i class="fas fa-envelope"></i> Tes SMTP
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="">Bantuan</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php $user = $this->session->userdata('user'); ?>
                <?php if ($user): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= get_profile_picture($user, "rounded-circle", 32); ?>
                            <span class="ms-2"><?= htmlspecialchars($user->username ?? $user->email) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-4">
