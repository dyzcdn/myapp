<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // ⬅️ ini yang penting
    }

    public function insert_user($data) {
        $data['id'] = $this->generate_id();
        return $this->db->insert('users', $data);
    }

    public function generate_id($length = 8) {
        do {
            $id = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, $length);
            $exists = $this->db->get_where('users', ['id' => $id])->num_rows() > 0;
        } while ($exists);
        return $id;
    }

    public function get_user_by_email_or_username($identity) {
        return $this->db
            ->where('email', $identity)
            ->or_where('username', $identity)
            ->get('users')
            ->row();
    }

    public function get_user_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }

    public function store_reset_token($email, $token, $expires_at) {
        return $this->db
            ->where('email', $email)
            ->update('users', [
                'reset_token' => $token,
                'token_expiry' => $expires_at
            ]);
    }

    public function get_user_by_token($token) {
        return $this->db
            ->where('reset_token', $token)
            ->get('users')
            ->row();
    }

    public function update_password_by_token($token, $new_password_hash) {
        return $this->db
            ->where('reset_token', $token)
            ->update('users', [
                'password' => $new_password_hash,
                'reset_token' => null,
                'token_expiry' => null
            ]);
    }

    public function verify_email_token($token) {
        $user = $this->db
            ->where('email_verification_token', $token)
            ->where('email_verified', 0)
            ->get('users')->row();

        if ($user) {
            $this->db->where('id', $user->id)->update('users', [
                'email_verified' => 1,
                'email_verification_token' => null
            ]);
            return true;
        }

        return false;
    }

    public function get_or_create_oauth_user($provider, $provider_id, $email, $name, $picture = null) {
        $user = $this->db
            ->where('provider', $provider)
            ->where('provider_id', $provider_id)
            ->get('users')
            ->row();

        if ($user) {
            // Opsional: update foto jika berubah
            if ($picture && $user->profile_picture !== $picture) {
                $this->db->where('id', $user->id)->update('users', ['profile_picture' => $picture]);
                $user->profile_picture = $picture;
            }
            return $user;
        } else {
            $data = [
                'id' => $this->generate_id(),
                'email' => $email,
                'username' => sanitize_username($email),
                'name' => $name,
                'provider' => $provider,
                'provider_id' => $provider_id,
                'email_verified' => 1,
                'profile_picture' => $picture // ✅ Tambah URL foto
            ];
            $this->db->insert('users', $data);
            return $this->db->get_where('users', ['id' => $data['id']])->row();
        }
    }

    public function update_email_token($email, $token) {
        $this->db->where('email', $email);
        return $this->db->update('users', [
            'email_verification_token' => $token
        ]);
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function update_account($id, $data) {
        return $this->db->where('id', $id)->update('users', $data);
    }

    public function update_user($id, $data) {
        return $this->update_account($id, $data);
    }

    public function delete_user($id) {
        return $this->db->where('id', $id)->delete('users');
    }
}
