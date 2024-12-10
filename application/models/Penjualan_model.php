<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Penjualan_model extends CI_Model {
    public function tambah_penjualan($data) {
        $this->db->insert_batch('penjualan', $data); // Menyimpan banyak data sekaligus
    }
}