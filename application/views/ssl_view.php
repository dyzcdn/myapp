<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('_partials/header', ['title' => 'SSL 3-Tier Generator']); ?>

    <h1 class="mb-4">🔐 SSL 3-Tier Generator</h1>
    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!$ca_ready): ?>
        <?php
            $step = 1;
            $show_db_button = !$this->db->table_exists('certificates') || !$this->db->table_exists('ca_certificates');
            $show_setup_button = !$ca_ready;
        ?>
        <div class="alert alert-warning">
            <h4><i class="fas fa-exclamation-triangle"></i> Setup Diperlukan</h4>
            <p>Root CA dan Intermediate CA belum ditemukan di database.</p>
            <hr>

            <!-- STEP 1 -->
            <h5><?= $step++ ?>. Konfigurasi Penting</h5>
            <ul class="small">
                <li><b>.htaccess</b>:
                    <div class="bg-light border rounded p-2 mb-2">
                        <code>/public/.htaccess</code>
                        <pre class="bg-dark text-light p-2 rounded"><code>RewriteEngine On<br/>RewriteCond %{REQUEST_FILENAME} !-f<br/>RewriteCond %{REQUEST_FILENAME} !-d<br/>RewriteRule ^(.*)$ index.php/$1 [L]</code></pre>
                    </div>
                </li>

                <li><b>Base URL</b>:
                    <div class="bg-light border rounded p-2 mb-2">
                        <code>/application/config/config.php</code><br>
                        <small>Silakan ubah ke domain/web Anda.</small>
                        <pre class="bg-dark text-light p-2 rounded"><code>$config['base_url'] = '<?= htmlspecialchars(config_item('base_url')) ?>';</code></pre>
                    </div>
                </li>

                <li><b>Konfigurasi CA</b>:
                    <div class="bg-light border rounded p-2 mb-2">
                        <code>/application/config/ca.php</code>
                        <br/>
                        <b>Root DN:</b>
                        <pre class="bg-dark text-light p-2 rounded"><code><?= var_export(config_item('ca_root_dn'), true) ?></code></pre>
                        <b>Intermediate DN:</b>
                        <pre class="bg-dark text-light p-2 rounded"><code><?= var_export(config_item('ca_intermediate_dn'), true) ?></code></pre>
                        <b>Default Leaf DN:</b>
                        <pre class="bg-dark text-light p-2 rounded"><code><?= var_export(config_item('default_leaf_dn'), true) ?></code></pre>
                    </div>
                </li>

                <li><b>Database</b>:
                    <div class="bg-light border rounded p-2 mb-2">
                        <code>/application/config/database.php</code><br>
                        <small>Pastikan koneksi database sudah sesuai:</small>
                        <pre class="bg-dark text-light p-2 rounded"><code>'hostname' => '<?= $this->db->hostname ?>',<br/>'username' => '<?= $this->db->username ?>',<br/>'password' => '<?= $this->db->password ?>',<br/>'database' => '<?= $this->db->database ?>'</code></pre>
                    </div>
                </li>
            </ul>

            <!-- STEP 2 -->
            <h5><?= $step++ ?>. Buat Tabel Database</h5>
            <?php if ($show_db_button): ?>
                <a href="<?= site_url('certificates/init_db') ?>" class="btn btn-success mb-3">Buat Tabel Database</a>
            <?php else: ?>
                <button class="btn btn-outline-secondary mb-3" disabled>✔ Tabel sudah tersedia</button>
            <?php endif; ?>

            <!-- STEP 3 -->
            <h5><?= $step++ ?>. Setup Root dan Intermediate CA</h5>
            <?php if ($show_setup_button): ?>
                <a href="<?= site_url('certificates/setup_ca') ?>" class="btn btn-primary">Jalankan Setup CA Sekarang</a>
            <?php else: ?>
                <button class="btn btn-primary" disabled>✔ CA sudah di-setup</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($ca_ready): ?>
    <section id="sec1">
        <form method="post" action="<?= site_url('certificates/generate') ?>" class="row row-cols-1 row-cols-md-2 g-3 mb-5">
            <div class="col">
                <label class="form-label">Common Name (CN):</label>
                <input name="cn" class="form-control" required>
            </div>
            <div class="col">
                <label class="form-label">Subject Alt Names (SAN):</label>
                <input name="san" class="form-control" placeholder="dns.satu.com,ip.satu.com,127.0.0.1 (Opsional)">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">🚀 Generate SSL</button>
            </div>
        </form>
    </section>

    <section id="sec2">
        <h2>📋 Daftar Sertifikat</h2>
        <div class="table-responsive pt-2">
            <table id="certTable" class="table table-bordered table-striped nowrap mt-3" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th><th>CN</th><th>O</th><th>Waktu</th><th>SAN</th><th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificates as $row): ?>
                    <tr>
                        <td><?= $row['uuid'] ?></td>
                        <td><?= htmlspecialchars($row['common_name']) ?></td>
                        <td><?= htmlspecialchars($row['organization']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td class="san-cell" title="<?= htmlspecialchars($row['san']) ?>"><?= htmlspecialchars($row['san']) ?></td>
                        <td class="text-center">
                            <div class="d-flex flex-wrap justify-content-center action-buttons">
                                <div class="btn-group"><button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-eye"></i> Lihat</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/view/cert/' . $row['uuid']) ?>" target="_blank">📄 Cert</a></li>
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/view/key/' . $row['uuid']) ?>" target="_blank">🔑 Key</a></li>
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/view/csr/' . $row['uuid']) ?>" target="_blank">🧾 CSR</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group"><button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-download"></i> Download</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/download_zip/' . $row['uuid']) ?>">📦 ZIP</a></li>
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/download_p12/' . $row['uuid']) ?>">🗝️ .p12</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group"><button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-cogs"></i> Lainnya</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= site_url('certificates/regenerate/' . $row['uuid']) ?>">🔄 Regen</a></li>
                                        <li><a class="dropdown-item text-danger" href="<?= site_url('certificates/delete/' . $row['uuid']) ?>" onclick="return confirm('Yakin hapus sertifikat ini?')">🗑️ Hapus</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="sec3">
        <h2 class="mt-5">📦 Download Sertifikat Root CA</h2>
        <div class="d-flex flex-wrap gap-3 mb-5">
            <a class="btn btn-outline-primary" href="<?= site_url('certificates/download_ca/root') ?>"><i class="fas fa-download"></i> Root CA (.crt)</a>
            <a class="btn btn-outline-secondary" href="<?= site_url('certificates/download_ca/intermediate') ?>"><i class="fas fa-download"></i> Intermediate CA (.crt)</a>
            <a class="btn btn-outline-success" href="<?= site_url('certificates/download_ca_bundle') ?>"><i class="fas fa-file-certificate"></i> CA Bundle (Windows)</a>
            <a class="btn btn-outline-warning" href="<?= site_url('certificates/download_ca_android') ?>"><i class="fas fa-mobile-alt"></i> CA Android (.der)</a>
        </div>
    </section>

    <?php endif; ?>
    
<?php $this->load->view('_partials/footer'); ?>