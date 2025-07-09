<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

    private $db_connected;
    private $table_exists;
    private $user_exists;

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
        $this->load->model('User_model');

        $this->db_connected = $this->is_database_connected();
        $this->table_exists = $this->db_connected ? $this->db->table_exists('users') : false;
        $this->user_exists = $this->table_exists ? $this->db->count_all('users') > 0 : false;

        if ($this->db_connected && $this->table_exists && $this->user_exists) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['db_connected'] = $this->db_connected;
        $data['table_exists'] = $this->table_exists;
        $data['user_exists'] = $this->user_exists;
        $this->load->view('install/install_docs', $data);
    }

    public function init_db() {
        if (!$this->table_exists && $this->db_connected) {
            $fields = [
                'id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '8',
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => TRUE
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'unique' => TRUE
                ],
                'username' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'unique' => TRUE
                ],
                'password' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255'
                ],
                'profile_picture' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ],
                'email_verified' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'email_verification_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ],
                'reset_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE
                ],
                'token_expiry' => [
                    'type' => 'DATETIME',
                    'null' => TRUE
                ],
                'provider' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE
                ],
                'provider_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => TRUE
                ]
            ];

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            if ($this->dbforge->create_table('users')) {
                set_flash('success', 'Tabel users berhasil dibuat.');
            } else {
                set_flash('error', 'Gagal membuat tabel users.');
            }
        }
        redirect('install');
    }

    public function init_user() {
        if ($this->table_exists && !$this->user_exists && $this->db_connected) {
            $data = [
                'id' => $this->User_model->generate_id(),
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'email_verified' => 1
            ];
            $this->db->insert('users', $data);
            set_flash('success', 'User default berhasil dibuat.');
        }
        redirect('install');
    }

    private function is_database_connected() {
        $CI =& get_instance();
        @mysqli_report(MYSQLI_REPORT_OFF);
        $conn = @mysqli_connect(
            $CI->db->hostname,
            $CI->db->username,
            $CI->db->password,
            $CI->db->database
        );
        return $conn ? true : false;
    }
}
