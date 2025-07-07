<?php
// application/controllers/Certificates.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificates extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('certificate_model');
        $this->load->library('zip');
        // Library session diperlukan untuk flash messages
        // $this->load->library('session'); --> sudah otomatis dimuat di autoload.php

        if (!is_logged_in()) {
            set_flash('warning', 'Silakan login terlebih dahulu.');
            redirect('auth/login');
        }

        if ($this->session->userdata('google_token_expiry') && time() > $this->session->userdata('google_token_expiry')) {
            $this->session->sess_destroy();
            set_flash('warning', 'Sesi Google Anda telah berakhir. Silakan login kembali.');
            redirect('auth/login');
        }
    }

    /**
     * Halaman utama, memeriksa apakah CA sudah di-setup.
     */
    public function index() {
        $data['ca_ready'] = $this->certificate_model->get_ca_certificate('root') && $this->certificate_model->get_ca_certificate('intermediate');
        $data['certificates'] = $this->certificate_model->get_all_certificates();
        $this->load->view('ssl_view', $data);
    }

    /**
     * Metode untuk setup Root dan Intermediate CA untuk pertama kali.
     * Cukup kunjungi URL /certificates/setup_ca sekali.
     */
    public function setup_ca() {
        if ($this->certificate_model->get_ca_certificate('root') && $this->certificate_model->get_ca_certificate('intermediate')) {
            $this->session->set_flashdata('error', 'CA sudah di-setup. Tidak dapat dijalankan ulang.');
            redirect(base_url('certificates'));
        }

        $root_dn = config_item('ca_root_dn');
        $intermediate_dn = config_item('ca_intermediate_dn');

        $root_privkey = openssl_pkey_new(["private_key_bits" => 4096, "private_key_type" => OPENSSL_KEYTYPE_RSA]);
        $root_csr = openssl_csr_new($root_dn, $root_privkey, ['digest_alg' => 'sha256']);
        $root_cert = openssl_csr_sign($root_csr, null, $root_privkey, 10950, ['digest_alg' => 'sha256', 'x509_extensions' => 'v3_ca']);
        openssl_x509_export($root_cert, $root_cert_out);
        openssl_pkey_export($root_privkey, $root_key_out);
        $this->certificate_model->save_ca_certificate('root', $root_cert_out, $root_key_out);

        $intermediate_privkey = openssl_pkey_new(["private_key_bits" => 4096, "private_key_type" => OPENSSL_KEYTYPE_RSA]);
        $intermediate_csr = openssl_csr_new($intermediate_dn, $intermediate_privkey, ['digest_alg' => 'sha256']);
        $intermediate_cert = openssl_csr_sign($intermediate_csr, $root_cert, $root_privkey, 10950, ['digest_alg' => 'sha256', 'x509_extensions' => 'v3_ca']);
        openssl_x509_export($intermediate_cert, $intermediate_cert_out);
        openssl_pkey_export($intermediate_privkey, $intermediate_key_out);
        $this->certificate_model->save_ca_certificate('intermediate', $intermediate_cert_out, $intermediate_key_out);

        $this->session->set_flashdata('message', 'Root dan Intermediate CA berhasil dibuat.');
        redirect(base_url('certificates'));
    }

    /**
     * Fungsi private untuk proses inti pembuatan sertifikat domain.
     */
    private function _generate_domain_certificate($cn, $o, $l, $st, $c, $san_string) {
        $intermediate_ca = $this->certificate_model->get_ca_certificate('intermediate');
        if (!$intermediate_ca) {
            $this->session->set_flashdata('error', 'Intermediate CA tidak ditemukan. Harap jalankan setup terlebih dahulu.');
            return null;
        }

        $domain_dn = [
            "countryName" => $c,
            "stateOrProvinceName" => $st,
            "localityName" => $l,
            "organizationName" => $o,
            "commonName" => $cn
        ];
        $domain_privkey = openssl_pkey_new(["private_key_bits" => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA]);

        // Buat file konfigurasi sementara yang berisi SAN
        $config_file = tempnam(sys_get_temp_dir(), 'openssl_conf_');
        $config_content = <<<EOD
    [req]
    distinguished_name = req
    req_extensions = v3_req
    prompt = no

    [req_distinguished_name]
    CN = $cn

    [v3_req]
    subjectAltName = $san_string
    EOD;

        file_put_contents($config_file, $config_content);

        // Buat CSR dengan ekstensi SAN
        $domain_csr = openssl_csr_new($domain_dn, $domain_privkey, [
            'digest_alg' => 'sha256',
            'req_extensions' => 'v3_req',
            'config' => $config_file
        ]);

        // Tanda tangani CSR dengan SAN disertakan di sertifikat (x509_extensions)
        $domain_cert = openssl_csr_sign($domain_csr, $intermediate_ca['cert_content'], $intermediate_ca['key_content'], 365, [
            'digest_alg' => 'sha256',
            'x509_extensions' => 'v3_req',
            'config' => $config_file
        ]);

        unlink($config_file); // Hapus file sementara

        openssl_x509_export($domain_cert, $domain_cert_out);
        openssl_pkey_export($domain_privkey, $domain_key_out);
        openssl_csr_export($domain_csr, $domain_csr_out);

        return [
            'cert' => $domain_cert_out,
            'key'  => $domain_key_out,
            'csr'  => $domain_csr_out
        ];
    }

    public function generate() {
        if ($this->input->post('cn')) {
            $cn = $this->input->post('cn');
            $user_san = $this->input->post('san') ?? '';
            
            $entries = array_filter(array_map('trim', explode(',', $user_san)));
            array_unshift($entries, $cn);
            $san_array = array_unique(array_map(function($entry) {
                if (strpos($entry, 'IP:') === 0 || strpos($entry, 'DNS:') === 0) return $entry;
                return filter_var($entry, FILTER_VALIDATE_IP) ? "IP:$entry" : "DNS:$entry";
            }, $entries));
            $san_string = implode(',', $san_array);
            
            $defaults = config_item('default_leaf_dn');
            $o  = $defaults['organization'];
            $l  = $defaults['locality'];
            $st = $defaults['state'];
            $c  = $defaults['country'];
            
            $result = $this->_generate_domain_certificate($cn, $o, $l, $st, $c, $san_string);

            if ($result) {
                $this->certificate_model->save_certificate($cn, $o, $l, $st, $c, $san_string, $result['cert'], $result['key'], $result['csr']);
            }
        }
        redirect(base_url());
    }

    public function regenerate($uuid) {
        $cert = $this->certificate_model->get_certificate_by_uuid($uuid);
        if ($cert) {
            $result = $this->_generate_domain_certificate($cert['common_name'], $cert['organization'], $cert['locality'], $cert['state'], $cert['country'], $cert['san']);
            if ($result) {
                $this->certificate_model->update_certificate($uuid, $result['cert'], $result['key'], $result['csr']);
            }
        }
        redirect(base_url());
    }

    public function download_ca($type) {
        $ca = $this->certificate_model->get_ca_certificate($type);
        if ($ca) {
            $name = ($type == 'root' ? 'DyzulkDev' : 'DyLab_Infinity_CA1') . '.crt';
            $this->output->set_content_type('application/x-x509-ca-cert')
                         ->set_header("Content-Disposition: attachment; filename=$name")
                         ->set_output($ca['cert_content']);
        } else {
            show_404();
        }
    }

    public function download_ca_bundle() {
        $root = $this->certificate_model->get_ca_certificate('root');
        $intermediate = $this->certificate_model->get_ca_certificate('intermediate');

        if ($root && $intermediate) {
            $bundle = $intermediate['cert_content'] . "\n" . $root['cert_content'];
            $this->output->set_content_type('application/x-x509-ca-cert')
                        ->set_header("Content-Disposition: attachment; filename=ca_bundle.crt")
                        ->set_output($bundle);
        } else {
            show_404();
        }
    }

    public function download_ca_android() {
        $root = $this->certificate_model->get_ca_certificate('root');
        $intermediate = $this->certificate_model->get_ca_certificate('intermediate');

        if ($root && $intermediate) {
            $bundle = $intermediate['cert_content'] . "\n" . $root['cert_content'];

            // Gabung dan convert ke format DER
            $pem_file = tempnam(sys_get_temp_dir(), 'ca_') . '.pem';
            $der_file = tempnam(sys_get_temp_dir(), 'ca_') . '.der';
            file_put_contents($pem_file, $bundle);

            // Konversi PEM ke DER menggunakan openssl shell (karena PHP tidak mendukung langsung)
            exec("openssl x509 -outform der -in $pem_file -out $der_file");

            if (file_exists($der_file)) {
                $output = file_get_contents($der_file);
                unlink($pem_file);
                unlink($der_file);

                $this->output->set_content_type('application/x-x509-ca-cert')
                            ->set_header("Content-Disposition: attachment; filename=ca_android.der")
                            ->set_output($output);
            } else {
                $this->session->set_flashdata('error', 'Gagal membuat bundle .der');
                redirect(base_url());
            }
        } else {
            show_404();
        }
    }

    public function download_zip($uuid) {
        $cert = $this->certificate_model->get_certificate_by_uuid($uuid);
        if ($cert) {
            $cn = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $cert['common_name']);
            $this->zip->add_data("{$cn}.pem", $cert['cert_content']);
            $this->zip->add_data("{$cn}.key", $cert['key_content']);
            if (!empty($cert['csr_content'])) {
                $this->zip->add_data("{$cn}.csr", $cert['csr_content']);
            }
            $this->zip->download("cert_{$cn}.zip");
        } else {
            show_404();
        }
    }

    public function view($type, $uuid) {
        $cert = $this->certificate_model->get_certificate_by_uuid($uuid);
        $content = null;
        $filename = 'file.txt';

        if ($cert) {
            $cn = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $cert['common_name']);
            switch ($type) {
                case 'cert': $content = $cert['cert_content']; $filename = "{$cn}.pem"; break;
                case 'key':  $content = $cert['key_content']; $filename = "{$cn}.key"; break;
                case 'csr':  $content = $cert['csr_content']; $filename = "{$cn}.csr"; break;
            }
        }

        if ($content) {
            $this->output->set_content_type('text/plain')
                         ->set_header("Content-Disposition: inline; filename=$filename")
                         ->set_output($content);
        } else {
            show_404();
        }
    }
    
    public function download_p12($uuid) {
        $cert = $this->certificate_model->get_certificate_by_uuid($uuid);
        if ($cert && !empty($cert['cert_content']) && !empty($cert['key_content'])) {
            $cn = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $cert['common_name']);
            $password = "112133";
            $p12_content = '';

            if (openssl_pkcs12_export($cert['cert_content'], $p12_content, $cert['key_content'], $password)) {
                $this->output->set_content_type('application/x-pkcs12')
                             ->set_header("Content-Disposition: attachment; filename={$cn}.p12")
                             ->set_output($p12_content);
            } else {
                $this->session->set_flashdata('error', 'Gagal membuat file .p12: ' . openssl_error_string());
                redirect(base_url());
            }
        } else {
            show_404();
        }
    }

    public function delete($uuid) {
        if ($uuid) {
            $this->certificate_model->delete_certificate($uuid);
        }
        redirect(base_url());
    }

    public function init_db() {
        $this->load->dbforge();

        // 1. Jika tabel sudah ada, jangan lanjut
        if ($this->db->table_exists('ca_certificates') && $this->db->table_exists('certificates')) {
            $this->session->set_flashdata('error', 'Tabel sudah ada. Inisialisasi tidak diperlukan.');
            redirect(base_url('certificates'));
        }

        // 2. Cegah jika environment bukan development
        if (ENVIRONMENT !== 'development') {
            $data['title'] = 'Error';
            $data['heading'] = 'Kesalahan Lingkungan';
            $data['message'] = 'Fitur init_db hanya diizinkan pada environment DEVELOPMENT.';
            $data['redirect_url'] = base_url(); // tujuan redirect
            $data['redirect_delay'] = 3; // dalam detik
            $this->load->view('evirontment_error', $data);
            return;
        }

        // === ca_certificates ===
        if (!$this->db->table_exists('ca_certificates')) {
            $fields = [
                'id' => ['type' => 'INT', 'auto_increment' => TRUE],
                'uuid' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => FALSE],
                'ca_type' => ['type' => 'ENUM("root","intermediate")', 'null' => FALSE],
                'cert_content' => ['type' => 'TEXT', 'null' => FALSE],
                'key_content' => ['type' => 'TEXT', 'null' => FALSE],
                'created_at' => ['type' => 'TIMESTAMP', 'null' => FALSE],
                'updated_at' => ['type' => 'TIMESTAMP', 'null' => TRUE]
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('uuid', TRUE);
            $this->dbforge->create_table('ca_certificates', TRUE);

            $this->db->query("ALTER TABLE ca_certificates 
                MODIFY created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                MODIFY updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
        }

        // === certificates ===
        if (!$this->db->table_exists('certificates')) {
            $fields = [
                'id' => ['type' => 'INT', 'auto_increment' => TRUE],
                'uuid' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => FALSE],
                'common_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'organization' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'locality' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'state' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'country' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE],
                'san' => ['type' => 'TEXT', 'null' => TRUE],
                'cert_content' => ['type' => 'TEXT', 'null' => TRUE],
                'key_content' => ['type' => 'TEXT', 'null' => TRUE],
                'csr_content' => ['type' => 'TEXT', 'null' => TRUE],
                'created_at' => ['type' => 'TIMESTAMP', 'null' => TRUE],
                'updated_at' => ['type' => 'TIMESTAMP', 'null' => TRUE]
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('uuid', TRUE);
            $this->dbforge->create_table('certificates', TRUE);

            $this->db->query("ALTER TABLE certificates 
                MODIFY created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                MODIFY updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
        }

        $this->session->set_flashdata('message', 'Tabel berhasil dibuat.');
        redirect(base_url('certificates'));
    }

}
