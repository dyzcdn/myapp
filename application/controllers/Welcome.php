<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();

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

    public function index() {
        $this->load->view('welcome_message');
    }

    public function smtp() {
        $this->load->view('smtp_test');
    }

    public function test_smtp() {
        $this->load->library('email');

        $email_to = $this->input->post('email', TRUE);

        if (!$email_to || !filter_var($email_to, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Email tujuan tidak valid.');
            redirect('welcome/smtp');
            return;
        }

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
            'priority'    => 1
        ]);

        $this->email->from(
            $this->config->item('smtp_from') ?? $this->config->item('smtp_user'),
            $this->config->item('smtp_from_name') ?? $this->config->item('site_name')
        );
        $this->email->to($email_to);
        $this->email->subject('Test SMTP dari ' . $this->config->item('site_name'));

        $data = [
            'site_name'   => $this->config->item('site_name'),
            'site_slogan' => $this->config->item('site_slogan'),
            'email_to'    => $email_to,
            'tanggal'     => date('d M Y H:i')
        ];

        $message = $this->load->view('emails/smtp_test', $data, TRUE);
        $this->email->message($message);

        if ($this->email->send()) {
            set_flash('success', 'Email berhasil dikirim ke <strong>' . htmlentities($email_to) . '</strong>!');
        } else {
            set_flash('error', 'Email gagal dikirim:<br><pre>' . htmlentities($this->email->print_debugger(['headers'])) . '</pre>');
        }

        redirect('welcome/smtp');
    }
}
