<?php $this->load->view('_partials/header', ['title' => 'Konfirmasi Hapus Akun']); ?>

<h4>Konfirmasi Penghapusan Akun</h4>
<?= display_flash(); ?>

<div class="alert alert-danger">
    <strong>Peringatan!</strong> Tindakan ini akan menghapus akun Anda secara permanen.
</div>

<form method="post" action="<?= base_url('profile/delete_account') ?>">
    <input type="hidden" name="confirm_delete" value="yes">
    <button type="submit" class="btn btn-danger">Ya, hapus akun saya</button>
    <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Batal</a>
</form>

<?php $this->load->view('_partials/footer'); ?>
