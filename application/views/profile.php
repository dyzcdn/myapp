<?php $this->load->view('_partials/header', ['title' => 'Profil Saya']); ?>

<h4>Profil Saya</h4>
<?= display_flash(); ?>

<div class="card mb-3">
    <div class="card-header">Informasi Akun</div>
    <div class="card-body">
        <form method="post" action="<?= base_url('profile/update_account') ?>">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email) ?>" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user->username ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Password Baru <small class="text-muted">(kosongkan jika tidak ingin mengganti)</small></label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Akun</button>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Informasi Profil</div>
    <div class="card-body">
        <form method="post" action="<?= base_url('profile/update_profile') ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user->full_name ?? $user->name ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Foto Profil</label><br>
                <?php if (!empty($user->profile_picture)) : ?>
                    <img src="<?= base_url($user->profile_picture) ?>" class="img-thumbnail mb-2" width="120">
                <?php endif; ?>
                <input type="file" name="profile_picture" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Update Profil</button>
        </form>
    </div>
</div>

<a href="<?= base_url('profile/confirm_delete') ?>" class="btn btn-outline-danger mt-3">Hapus Akun</a>

<?php $this->load->view('_partials/footer'); ?>
