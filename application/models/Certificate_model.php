<?php
// application/models/Certificate_model.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_model extends CI_Model {

    private function generate_uuid($table, $column = 'uuid', $length = 16) {
        do {
            $uuid = bin2hex(random_bytes($length / 2));
            $exists = $this->db->get_where($table, [$column => $uuid])->num_rows() > 0;
        } while ($exists);

        return $uuid;
    }

    // === FUNGSI BARU UNTUK MENGELOLA CA ===

    /**
     * Mengambil sertifikat CA (root atau intermediate) dari database.
     * @param string $type Tipe CA ('root' atau 'intermediate')
     * @return array|null Data CA atau null jika tidak ditemukan.
     */
    public function get_ca_certificate($type) {
        if (!$this->db->table_exists('ca_certificates')) {
            return null; // Jangan query kalau tabel belum ada
        }

        $query = $this->db->get_where('ca_certificates', ['ca_type' => $type]);
        return $query->row_array();
    }

    public function get_certificate_by_uuid($uuid) {
        return $this->db->get_where('certificates', ['uuid' => $uuid])->row_array();
    }

    /**
     * Menyimpan atau memperbarui sertifikat CA di database.
     * @param string $type Tipe CA ('root' atau 'intermediate')
     * @param string $cert_content Konten sertifikat
     * @param string $key_content Konten kunci privat
     */
    public function save_ca_certificate($type, $cert_content, $key_content) {

        $uuid = $this->generate_uuid('ca_certificates');

        $data = [
            'uuid' => $uuid,
            'ca_type' => $type,
            'cert_content' => $cert_content,
            'key_content' => $key_content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('ca_type', $type);
        $q = $this->db->get('ca_certificates');

        if ($q->num_rows() > 0) {
            $this->db->where('ca_type', $type);
            $this->db->update('ca_certificates', $data);
        } else {
            $this->db->insert('ca_certificates', $data);
        }
    }

    // === FUNGSI UNTUK SERTIFIKAT LEAF (DOMAIN) ===

    public function get_all_certificates() {
        if (!$this->db->table_exists('certificates')) {
            return []; // Kosongkan, tidak error
        }

        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('certificates');
        return $query->result_array();
    }

    public function get_certificate_by_id($id) {
        $query = $this->db->get_where('certificates', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Menyimpan sertifikat domain baru.
     * Tidak lagi menyimpan path file.
     */
    public function save_certificate($cn, $o, $l, $st, $c, $san, $cert_content, $key_content, $csr_content) {

        $uuid = $this->generate_uuid('certificates');

        $data = [
            'uuid'         => $uuid,
            'common_name'  => $cn,
            'organization' => $o,
            'locality'     => $l,
            'state'        => $st,
            'country'      => $c,
            'san'          => $san,
            'cert_content' => $cert_content,
            'key_content'  => $key_content,
            'csr_content'  => $csr_content
        ];
        
        $this->db->insert('certificates', $data);
        // try {
        //     $this->db->insert('certificates', $data);
        // } catch (Exception $e) {
        //     // Jika gagal karena duplikat UUID, bisa retry
        // }
    }
    
    /**
     * Memperbarui sertifikat domain saat regenerate.
     */
    public function update_certificate($uuid, $cert_content, $key_content, $csr_content) {
        $data = [
            'cert_content' => $cert_content,
            'key_content'  => $key_content,
            'csr_content'  => $csr_content,
            'created_at'   => date('Y-m-d H:i:s')
        ];
        $this->db->where('uuid', $uuid);
        $this->db->update('certificates', $data);
    }

    /**
     * Menghapus sertifikat domain.
     * Tidak ada lagi operasi file.
     */
    public function delete_certificate($uuid) {
        $this->db->where('uuid', $uuid);
        $this->db->delete('certificates');
    }
}