<?php $this->load->view('_partials/header', ['title' => 'Selamat Datang']); ?>

<style>
    .centered-page {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }
</style>

<?= display_flash() ?>

<div class="centered-page">
    <h1 class="display-5 mb-3">Selamat Datang, <?= htmlentities($this->session->userdata('user')->name ?? 'Pengguna') ?></h1>
    <p class="lead">Kamu berhasil login ke aplikasi ini.</p>
</div>

<?php $this->load->view('_partials/footer'); ?>
