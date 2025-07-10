<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('auth/_partials/header', ['title' => 'Register']); ?>
    <section class="py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="mb-5">
                        <h2 class="display-5 fw-bold text-center">Register</h2>
                        <p class="text-center m-0">
                            Sudah punya akun?  
                            <a class="link-opacity-50-hover link-offset-2 link-underline link-underline-opacity-0" href="<?= base_url('auth/login') ?>">Login Disini</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="row gy-5 justify-content-center">
                        <div class="col-12 col-lg-5">

                            <?= display_flash() ?>

                            <form method="post" action="<?= base_url('auth/register') ?>">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control border-0 border-bottom rounded-0 <?= form_error('username') ? 'is-invalid' : '' ?>" name="username" id="username" placeholder="name123" value="<?= set_value('username') ?>" required>
                                            <label for="username" class="form-label">Username</label>
                                            <?= form_error('username', '<div class="invalid-feedback">', '</div>') ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control border-0 border-bottom rounded-0 <?= form_error('email') ? 'is-invalid' : '' ?>" 
                                                name="email" id="email" placeholder="name@example.com" value="<?= set_value('email') ?>" required>
                                            <label for="email" class="form-label">Email</label>
                                            <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control border-0 border-bottom rounded-0 <?= form_error('password') ? 'is-invalid' : '' ?>" 
                                                name="password" id="password" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                            <?= form_error('password', '<div class="invalid-feedback">', '</div>') ?>
                                        </div>
                                    </div>

                                    <!-- Optional: Cloudflare Turnstile -->
                                    <div class="col-12 text-center">
                                        <?= turnstile_widget($this->config->item('cf_turnstile_site_key')) ?>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input <?= form_error('accept_policy') ? 'is-invalid' : '' ?>" type="checkbox" name="accept_policy" id="accept_policy" value="1" <?= set_checkbox('accept_policy', '1') ?>>
                                            <label class="form-check-label text-secondary" for="accept_policy">
                                                Saya menyetujui 
                                                <a href="<?= base_url('legal/privacy') ?>" target="_blank">Kebijakan Privasi</a>
                                                <br/>serta
                                                <a href="<?= base_url('legal/terms') ?>" target="_blank">Syarat & Ketentuan</a>
                                            </label>
                                            <?= form_error('accept_policy', '<div class="invalid-feedback d-block">', '</div>') ?>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Daftar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->load->view('auth/_partials/footer'); ?>
