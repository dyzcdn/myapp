<?php $this->load->view('_partials/header', ['title' => 'Tes SMTP']); ?>

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

<div class="centered-page">
    <h1 class="mb-4">Test Koneksi SMTP</h1>

    <?= display_flash() ?>

    <form method="post" action="<?= base_url('welcome/test_smtp') ?>" class="d-flex justify-content-center flex-wrap" style="max-width: 600px;">
        <input type="email" name="email" class="form-control me-2 mb-2" placeholder="Email tujuan" required>
        <button class="btn btn-outline-primary mb-2" type="submit">
            <i class="fas fa-envelope"></i> Kirim Test Email
        </button>
    </form>
</div>

<?php $this->load->view('_partials/footer'); ?>
