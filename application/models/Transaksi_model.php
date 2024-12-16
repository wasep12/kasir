<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Transaksi_model', 'transaksi_model');
	}
	public function getById($id)
	{
		// Query untuk mendapatkan data transaksi berdasarkan ID
		return $this->db->get_where('transaksi', ['id' => $id])->row();
	}
	public function getProdukByBarcodes($barcodes)
	{
		if (empty($barcodes)) {
			return [];
		}

		// Menggunakan where_in untuk mencari semua barcode yang cocok
		$this->db->where_in('barcode', $barcodes);
		return $this->db->get('produk')->result();
	}


	private $table = 'transaksi';

	public function removeStok($barcode, $qty)
	{
		$this->db->set('stok', 'stok - ' . (int) $qty, FALSE) // Kurangi stok
			->where('barcode', $barcode)              // Berdasarkan barcode
			->update('produk');                       // Tabel produk
	}
	public function addTerjual($barcode, $qty)
	{
		$this->db->set('terjual', 'terjual + ' . (int) $qty, FALSE) // Tambahkan jumlah terjual
			->where('barcode', $barcode)                     // Berdasarkan barcode
			->update('produk');                              // Tabel produk
	}
	public function validateBarcodeAndStock($barcode, $qty)
	{
		$produk = $this->db->get_where('produk', ['barcode' => $barcode])->row();

		if (!$produk) {
			return ['success' => false, 'message' => "Barcode $barcode tidak ditemukan"];
		}

		if ($produk->stok < $qty) {
			return ['success' => false, 'message' => "Stok tidak mencukupi untuk barcode $barcode"];
		}

		return ['success' => true, 'message' => 'Valid'];
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		// Load model Transaksi_model
		$this->load->model('Transaksi_model');

		// Ambil data transaksi dari model
		$data = $this->Transaksi_model->getAll();

		// Jika data kosong, inisialisasi dengan array kosong
		if (empty($data)) {
			$data = [];
		}

		// Mengirim data ke view dalam format JSON
		echo json_encode(array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 0, // Periksa apakah 'draw' ada
			"recordsTotal" => count($data), // Total jumlah data
			"recordsFiltered" => count($data), // Jumlah data yang difilter (jika ada filter)
			"data" => $data // Data yang dikirimkan
		));
	}
	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getProduk($barcode, $qty)
	{
		$total = explode(',', $qty);
		foreach ($barcode as $key => $value) {
			$this->db->select('nama_produk');
			$this->db->where('id', $value);
			$data[] = '<tr><td>' . $this->db->get('produk')->row()->nama_produk . ' (' . $total[$key] . ')</td></tr>';
		}
		return join($data);
	}


	public function penjualanBulan($date)
	{
		//format tanggal standar (YYYY-MM-DD) dalam query
		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') = '$date'")->result();

		$d = [];
		$data = [];

		// Memproses data qty yang diambil dari database
		foreach ($qty as $key) {
			//qty yang dipisah koma
			$d[] = explode(',', $key->qty);
		}

		// Menghitung total qty per transaksi
		foreach ($d as $key) {
			// Jumlahkan semua qty dalam transaksi
			$data[] = array_sum($key);
		}

		return $data; // Mengembalikan array yang berisi total qty per transaksi
	}


	public function transaksiHari($hari)
	{
		return $this->db->query("SELECT COUNT(*) AS total FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
	}

	public function transaksiTerakhir($hari)
	{
		return $this->db->query("SELECT transaksi.qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari' LIMIT 1")->row();
	}

	public function getAll()
	{
		// Ambil data transaksi tanpa bergabung dengan produk untuk menghindari duplikasi
		$this->db->select('
        transaksi.id, 
        transaksi.tanggal, 
        transaksi.barcode, 
        transaksi.qty, 
        transaksi.total_bayar, 
        transaksi.jumlah_uang, 
        transaksi.diskon, 
        pelanggan.nama as pelanggan
    ');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left');
		$query = $this->db->get();

		// Debugging untuk memastikan data yang diambil
		if ($query->num_rows() > 0) {
			$results = $query->result();
			foreach ($results as &$result) {
				// Pisahkan barcode yang digabung dan cari nama produk untuk setiap barcode
				$barcodes = explode(',', $result->barcode);
				$productNames = [];

				foreach ($barcodes as $barcode) {
					// Cari nama produk berdasarkan barcode
					$this->db->select('nama_produk');
					$this->db->from('produk');
					$this->db->where('barcode', trim($barcode));
					$productQuery = $this->db->get();

					if ($productQuery->num_rows() > 0) {
						$product = $productQuery->row();
						$productNames[] = $product->nama_produk;
					}
				}

				// Gabungkan nama produk yang ditemukan, dipisahkan koma
				$result->nama_produk = implode(', ', $productNames);
			}

			// Menampilkan hasil query ke log
			error_log(print_r($results, true)); // Menampilkan hasil ke log error
		} else {
			error_log("Tidak ada data ditemukan.");
		}

		return $results;
	}

	public function getName($barcode)
	{
		foreach ($barcode as $b) {
			$this->db->select('nama_produk, harga');
			$this->db->where('id', $b);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}

}


/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */