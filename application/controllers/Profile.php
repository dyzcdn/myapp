<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');

        if (!is_logged_in()) {
            set_flash('warning', 'Silakan login terlebih dahulu.');
            redirect('auth/login');
        }
    }

    public function index() {
        $data['user'] = $this->session->userdata('user');
        $this->load->view('profile', $data);
    }

    public function update_account() {
        $this->load->library('form_validation');
        $this->load->helper('auth');

        $user = $this->session->userdata('user');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_dash|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('password', 'Password', 'min_length[6]');

        if (!$this->form_validation->run()) {
            set_flash('error', validation_errors('<div>', '</div>'));
            redirect('profile');
        }

        $email = $this->input->post('email');
        $username = sanitize_username($this->input->post('username')); // ← filter username
        $password = $this->input->post('password');

        $data = [
            'email' => $email,
            'username' => $username,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $updated = $this->User_model->update_user($user->id, $data);

        if ($updated) {
            $user = $this->User_model->get_user_by_id($user->id);
            $this->session->set_userdata('user', $user);
            set_flash('success', 'Akun berhasil diperbarui.');
        } else {
            set_flash('error', 'Gagal memperbarui akun.');
        }

        redirect('profile');
    }

    public function update_profile() {
        $user = $this->session->userdata('user');
        $name = $this->input->post('name');

        $data = ['name' => $name];

        if (!empty($_FILES['profile_picture']['name'])) {
            $config['upload_path'] = './uploads/profile_pics/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = 'profile_' . $user->id . '_' . time();
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('profile_picture')) {
                $data['profile_picture'] = 'uploads/profile_pics/' . $this->upload->data('file_name');
            } else {
                set_flash('error', $this->upload->display_errors());
                redirect('profile');
            }
        }

        $updated = $this->User_model->update_user($user->id, $data);

        if ($updated) {
            $user = $this->User_model->get_user_by_id($user->id);
            $this->session->set_userdata('user', $user);
            set_flash('success', 'Profil berhasil diperbarui.');
        } else {
            set_flash('error', 'Gagal memperbarui profil.');
        }

        redirect('profile');
    }

    public function confirm_delete() {
        $this->load->view('confirm_delete');
    }

    public function delete_account() {
        $user = $this->session->userdata('user');
        $confirmed = $this->input->post('confirm_delete');

        if ($confirmed === 'yes') {
            if ($user->oauth_provider === 'google' && $this->session->userdata('google_token')) {
                $client = new Google_Client();
                $client->setClientId($this->config->item('google_client_id'));
                $client->setClientSecret($this->config->item('google_client_secret'));
                $client->revokeToken($this->session->userdata('google_token'));
            }

            $this->User_model->delete_user($user->id);
            $this->session->sess_destroy();
            set_flash('success', 'Akun Anda berhasil dihapus. Jika Anda login dengan Google, Anda juga dapat menghapus akses aplikasi dari <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener noreferrer">Google Akun Anda</a>.');
            redirect('auth/login');
        } else {
            set_flash('error', 'Konfirmasi penghapusan akun tidak valid.');
            redirect('profile');
        }
    }

    public function info(){
        $is_logged_in = is_logged_in(); // bool
        $user_data = $this->session->userdata('user');
        $google_token_expiry = $this->session->userdata('google_token_expiry');
        $current_time = time();

        $response = [
            'is_logged_in' => $is_logged_in,
            'user' => $user_data,
            'google_token_expiry' => $google_token_expiry,
            'current_time' => $current_time,
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
}
