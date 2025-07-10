<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title : 'Aplikasi' ?> | <?= $this->config->item('site_name') ?: 'MyApp' ?></title>

  <link href="https://cdn.dyzulk.com/static/fonts/fonts.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdn.dyzulk.com/static/icons/fa/pro-5.15.4/css/all.min.css">
  <script src="https://cdn.dyzulk.com/static/icons/fa/pro-5.15.4/js/pro.min.js"></script>

  <?php foreach ([16, 32, 48, 64, 128, 180, 192, 256, 512] as $size): ?>
    <link rel="icon" type="image/png" sizes="<?= $size ?>x<?= $size ?>" href="<?= base_url("favicon-$size.png") ?>">
  <?php endforeach; ?>
  <link rel="apple-touch-icon" href="<?= base_url('favicon-180.png') ?>">
  <link rel="manifest" href="<?= base_url('manifest.json') ?>">

  <!-- PWA -->
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
