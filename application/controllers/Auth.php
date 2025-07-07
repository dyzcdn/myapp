<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class Auth extends CI_Controller {

    private $google_client_id;
    private $google_client_secret;
    private $cf_turnstile_secret_key;
    private $table_exists;
    private $user_exists;

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->dbforge();

        $this->google_client_id = $this->config->item('google_client_id');
        $this->google_client_secret = $this->config->item('google_client_secret');
        $this->cf_turnstile_secret_key = $this->config->item('cf_turnstile_secret_key');

        $this->table_exists = $this->db->table_exists('users');
        $this->user_exists = $this->table_exists ? $this->db->count_all('users') > 0 : false;

        $method = $this->router->method;

        if (ENVIRONMENT === 'development') {
            if (!$this->table_exists && !in_array($method, ['index', 'init_db', 'init_user'])) {
                redirect('auth/index');
            }
            if ($this->table_exists && !$this->user_exists && !in_array($method, ['index', 'init_user'])) {
                redirect('auth/index');
            }
            if ($this->table_exists && $this->user_exists && $method === 'index') {
                redirect('auth/login');
            }
        }
    }

    public function index() {
        $data['table_exists'] = $this->table_exists;
        $data['user_exists'] = $this->user_exists;
        $this->load->view('auth/documentation', $data);
    }

    public function init_db() {
        if (ENVIRONMENT !== 'development') show_error('Fitur ini hanya tersedia di environment DEVELOPMENT.', 403);

        if ($this->table_exists) {
            $this->session->set_flashdata('error', 'Tabel users sudah tersedia.');
            redirect('auth/index');
        }

        $fields = [
            'id' => ['type' => 'VARCHAR', 'constraint' => 8],
            'oauth_provider' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
            'oauth_uid' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'profile_picture' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'notification_pref' => ['type' => 'TEXT', 'null' => TRUE],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'reset_token_created_at' => ['type' => 'DATETIME', 'null' => TRUE],
            'reset_token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'token_expiry' => ['type' => 'DATETIME', 'null' => TRUE],
            'email_verification_token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'email_verified' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => TRUE],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('email', TRUE);
        $this->dbforge->create_table('users');

        $this->session->set_flashdata('message', 'Tabel users berhasil dibuat.');
        redirect('auth/index');
    }

    public function init_user() {
        if (!$this->table_exists) {
            $this->session->set_flashdata('error', 'Tabel belum tersedia.');
            redirect('auth/index');
        }

        if ($this->user_exists) {
            $this->session->set_flashdata('error', 'Sudah ada user terdaftar.');
            redirect('auth/index');
        }

        $this->User_model->insert_user([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'name' => 'Administrator',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'email_verified' => 1
        ]);

        $this->session->set_flashdata('message', 'User admin berhasil dibuat.');
        redirect('auth/login');
    }

    public function login() {
        if (is_logged_in()) redirect('welcome');
        $data['cf_site_key'] = $this->config->item('cf_turnstile_site_key');
        $this->load->view('auth/login', $data);
    }

    public function register() {
        if (is_logged_in()) redirect('welcome');
        $data['cf_site_key'] = $this->config->item('cf_turnstile_site_key');
        $this->load->view('auth/register', $data);
    }

    public function do_register() {
        if (!$this->_verify_captcha()) {
            set_flash('error', 'Captcha gagal. Silakan coba lagi.');
            redirect('auth/register');
        }

        $email = $this->input->post('email');
        $username = $this->input->post('username');
        $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(32));

        $this->User_model->insert_user([
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'email_verification_token' => $token,
            'email_verified' => 0
        ]);

        $this->_send_verification_email($email, $username, $token);
        set_flash('success', 'Pendaftaran berhasil. Silakan verifikasi email Anda.');
        redirect('auth/login');
    }

    public function do_login() {
        if (!$this->_verify_captcha()) {
            set_flash('error', 'Captcha gagal. Silakan coba lagi.');
            redirect('auth/login');
        }

        $identity = $this->input->post('identity');
        $password = $this->input->post('password');

        $user = $this->User_model->get_user_by_email_or_username($identity);
        if ($user && password_verify($password, $user->password)) {
            if (!$user->email_verified) {
                $resend_url = base_url('auth/resend_verification/' . urlencode($user->email));
                set_flash('error', 'Verifikasi email diperlukan. <a href="' . $resend_url . '" class="btn btn-sm btn-warning mt-2">Kirim Ulang</a>');
                redirect('auth/login');
            }

            $this->session->set_userdata('user', $user);
            set_flash('success', 'Login berhasil.');
            redirect('welcome');
        } else {
            set_flash('error', 'Login gagal.');
            redirect('auth/login');
        }
    }

    public function resend_verification($email_encoded = null) {
        if (!$email_encoded) {
            set_flash('error', 'Permintaan tidak valid.');
            redirect('auth/login');
        }

        $email = urldecode($email_encoded);
        $user = $this->User_model->get_user_by_email($email);

        if ($user && !$user->email_verified) {
            $token = bin2hex(random_bytes(32));
            $this->User_model->update_email_token($email, $token);
            $this->_send_verification_email($email, $user->username ?? $user->name, $token);
            set_flash('success', 'Email verifikasi ulang telah dikirim.');
        } else {
            set_flash('error', 'Email tidak valid atau sudah diverifikasi.');
        }

        redirect('auth/login');
    }

    public function verify_email($token) {
        if ($this->User_model->verify_email_token($token)) {
            set_flash('success', 'Email berhasil diverifikasi.');
        } else {
            set_flash('error', 'Token tidak valid.');
        }
        redirect('auth/login');
    }

    public function forgot_password() {
        $this->load->view('auth/forgot_password');
    }

    public function do_forgot_password() {
        $email = $this->input->post('email');
        $user = $this->User_model->get_user_by_email($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            $this->User_model->store_reset_token($email, $token, $expires_at);

            $reset_link = base_url("auth/reset_password/{$token}");
            $subject = "Reset Password - " . $this->config->item('site_name');
            $message = $this->load->view('emails/reset_password', [
                'reset_link' => $reset_link,
                'user' => $user,
                'site_name' => $this->config->item('site_name')
            ], true);

            $this->_send_email($email, $subject, $message);
            set_flash('success', 'Link reset password telah dikirim.');
        } else {
            set_flash('error', 'Email tidak ditemukan.');
        }

        redirect('auth/forgot_password');
    }

    public function reset_password($token) {
        $user = $this->User_model->get_user_by_token($token);
        if (!$user || strtotime($user->token_expiry) < time()) {
            set_flash('error', 'Token tidak valid atau kadaluarsa.');
            redirect('auth/login');
        }

        $this->load->view('auth/reset_password', ['token' => $token]);
    }

    public function do_reset_password() {
        $token = $this->input->post('token');
        $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $updated = $this->User_model->update_password_by_token($token, $password);

        if ($updated) {
            set_flash('success', 'Password berhasil diubah.');
            redirect('auth/login');
        } else {
            set_flash('error', 'Gagal mengubah password.');
            redirect('auth/reset_password/' . $token);
        }
    }

    public function google_login() {
        $client = new GoogleClient();
        $client->setClientId($this->google_client_id);
        $client->setClientSecret($this->google_client_secret);
        $client->setRedirectUri(base_url('auth/google_callback'));
        $client->addScope('email');
        $client->addScope('profile');
        redirect($client->createAuthUrl());
    }

    public function google_callback() {
        $client = new GoogleClient();
        $client->setClientId($this->google_client_id);
        $client->setClientSecret($this->google_client_secret);
        $client->setRedirectUri(base_url('auth/google_callback'));

        if ($this->input->get('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($this->input->get('code'));
            if (isset($token['access_token'])) {
                if (isset($token['expires_in']) && $token['expires_in'] < 1) {
                    set_flash('error', 'Sesi Google kadaluarsa.');
                    redirect('auth/login');
                }

                $client->setAccessToken($token['access_token']);
                $this->session->set_userdata('google_token_expiry', time() + $token['expires_in']);

                $oauth = new Oauth2($client);
                $google_user = $oauth->userinfo->get();

                $user = $this->User_model->get_or_create_oauth_user(
                    'google',
                    $google_user->id,
                    $google_user->email,
                    $google_user->name
                );

                $this->session->set_userdata('user', $user);
                set_flash('success', 'Login Google berhasil.');
                redirect('welcome');
            } else {
                set_flash('error', 'Gagal mendapatkan token Google.');
                redirect('auth/login');
            }
        } else {
            set_flash('error', 'Login Google dibatalkan.');
            redirect('auth/login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        set_flash('success', 'Logout berhasil.');
        redirect('auth/login');
    }

    private function _verify_captcha() {
        $response = $this->input->post('cf-turnstile-response');
        if (!$response) return false;

        $verify = curl_init();
        curl_setopt_array($verify, [
            CURLOPT_URL => "https://challenges.cloudflare.com/turnstile/v0/siteverify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret' => $this->cf_turnstile_secret_key,
                'response' => $response,
                'remoteip' => $this->input->ip_address()
            ])
        ]);
        $result = curl_exec($verify);
        curl_close($verify);

        $result = json_decode($result, true);
        return isset($result['success']) && $result['success'];
    }

    private function _send_verification_email($email, $username, $token) {
        $verify_link = base_url("auth/verify_email/{$token}");
        $subject = "Verifikasi Email - " . $this->config->item('site_name');
        $message = $this->load->view('emails/verify_email', [
            'verify_link' => $verify_link,
            'username' => $username,
            'site_name' => $this->config->item('site_name')
        ], true);

        $this->_send_email($email, $subject, $message);
    }

    private function _send_email($to, $subject, $message) {
        $this->load->library('email');

        $this->email->initialize([
            'protocol'    => 'smtp',
            'smtp_host'   => $this->config->item('smtp_host'),
            'smtp_port'   => $this->config->item('smtp_port'),
            'smtp_user'   => $this->config->item('smtp_user'),
            'smtp_pass'   => $this->config->item('smtp_pass'),
            'smtp_crypto' => $this->config->item('smtp_crypto') ?? 'tls',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'priority'    => 1,
        ]);

        $this->email->from(
            $this->config->item('smtp_from') ?? $this->config->item('smtp_user'),
            $this->config->item('smtp_from_name') ?? $this->config->item('site_name')
        );

        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if (!$this->email->send()) {
            log_message('error', 'Email gagal dikirim: ' . $this->email->print_debugger(['headers']));
        }
    }
}
