<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="id" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error-container {
      min-height: 100vh;
    }
  </style>
</head>
<body class="d-flex flex-column h-100 bg-light">
  <main class="flex-grow-1 d-flex align-items-center justify-content-center error-container">
    <div class="text-center px-4">
      <img src="https://cdn.dyzulk.com/logo/favicon.png" alt="Logo NihonBuzz" class="mb-4" width="100">
      <h1 class="display-4 text-danger fw-bold">404</h1>
      <h2 class="fw-semibold">Halaman tidak ditemukan</h2>
      <p class="text-muted mt-3"><?= isset($message) ? $message : 'Maaf, halaman yang Anda cari tidak ditemukan.' ?></p>
      <div class="mt-4">
        <a href="/" class="btn btn-danger me-2">Kembali ke Beranda</a>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Kembali Sebelumnya</a>
      </div>
    </div>
  </main>
  <footer class="text-center text-muted py-3 small">
    &copy; <?= date('Y') ?> NihonBuzz. Semua Hak Dilindungi.
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
