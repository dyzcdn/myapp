<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Aplikasi' ?> | <?=  $this->config->item('site_name') ?></title>
    <link href="https://cdn.lab/static/fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lab/static/bootstrap-5.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.lab/static/icons/fa/pro-5.15.4/css/all.min.css">
    <script src="https://cdn.lab/static/icons/fa/pro-5.15.4/js/pro.min.js"></script>
    <script src="https://cdn.lab/static/bootstrap-5.3.7/js/bootstrap.bundle.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    
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
<?php if ($this->uri->segment(2) === 'login'): ?>
    <style>
        .btn-svg-clean {
            all: unset;
            cursor: pointer;
            display: inline-block;
            padding: 0;
            margin: 0;
            border: none;
            background: none;
            line-height: 0;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 8px;
        }

        .btn-svg-clean:hover {
            transform: scale(1.01);
        }
    </style>
<?php endif; ?>
</head>
<body class="bg-light">
<main class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">