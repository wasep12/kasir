<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna_model extends CI_Model
{

	private $table = 'pengguna';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->where('role', '2');
		return $this->db->get($this->table);
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getPengguna($id)
	{
		// Menambahkan klausa where untuk mencocokkan ID
		$this->db->select('id, username, nama');
		$this->db->where('id', $id);

		// Mengambil hasil query
		$query = $this->db->get($this->table);

		// Memeriksa apakah ada data yang ditemukan, dan mengembalikan hasilnya
		if ($query->num_rows() > 0) {
			return $query->row(); // Mengembalikan satu baris data sebagai objek
		} else {
			return null; // Jika tidak ada data ditemukan
		}
	}


	public function search($search = "")
	{
		$this->db->like('kategori', $search);
		return $this->db->get($this->table)->result();
	}

}

/* End of file Pengguna_model.php */
/* Location: ./application/models/Pengguna_model.php */