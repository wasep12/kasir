<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Transaksi_model', 'transaksi_model'); // Pastikan model dimuat
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
		// Gunakan format tanggal standar (YYYY-MM-DD) dalam query
		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') = '$date'")->result();

		$d = [];
		$data = [];

		// Memproses data qty yang diambil dari database
		foreach ($qty as $key) {
			// Pisahkan qty yang dipisah koma
			$d[] = explode(',', $key->qty);
		}

		// Menghitung total qty per transaksi
		foreach ($d as $key) {
			// Jumlahkan semua qty dalam transaksi
			$data[] = array_sum($key);
		}

		return $data; // Mengembalikan array yang berisi total qty per transaksi
	}

	// public function penjualanTahun($year)
	// {
	// 	$data = []; // Inisialisasi array untuk menampung hasil

	// 	// Loop untuk setiap bulan dalam setahun
	// 	for ($month = 1; $month <= 12; $month++) {
	// 		// Format bulan dan tahun menjadi "YYYY-MM"
	// 		$monthYear = sprintf('%04d-%02d', $year, $month);

	// 		// Query untuk mendapatkan data qty dari transaksi
	// 		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$monthYear'")->result();

	// 		$monthlyTotal = 0; // Variabel untuk menampung total penjualan bulan ini

	// 		// Memproses setiap data qty yang diterima
	// 		foreach ($qty as $key) {
	// 			// Pisahkan qty jika ada lebih dari satu angka yang dipisahkan oleh koma
	// 			$qtyArray = explode(',', $key->qty);

	// 			// Jumlahkan semua qty yang dipisahkan koma
	// 			foreach ($qtyArray as $q) {
	// 				$monthlyTotal += (int) $q; // Menjumlahkan nilai qty
	// 			}
	// 		}

	// 		// Tambahkan total bulan ke dalam array data
	// 		$data[] = $monthlyTotal;
	// 	}

	// 	// Kembalikan array dengan total penjualan untuk setiap bulan
	// 	return $data;
	// }


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
		$this->db->select('
        transaksi.id, 
        transaksi.tanggal, 
        transaksi.barcode, 
        transaksi.qty, 
        transaksi.total_bayar, 
        transaksi.jumlah_uang, 
        transaksi.diskon, 
        pelanggan.nama as pelanggan, 
        produk.nama_produk as nama_produk
    ');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left');
		$this->db->join('produk', 'transaksi.barcode = produk.barcode', 'left'); // Update kolom nama_produk
		$query = $this->db->get();
		return $query->result();
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

// class Peramalan extends CI_Controller
// {
// 	public function index()
// 	{
// 		$tahun = $this->input->post('tahun') ?: date('Y');

// 		// Ambil data transaksi berdasarkan tahun
// 		$this->load->model('TransaksiModel');
// 		$transaksi = $this->TransaksiModel->get_data_per_bulan($tahun);

// 		// Variabel untuk perhitungan Least Square
// 		$data = [];
// 		$total_bulan = count($transaksi);
// 		$start_x = -1 * floor($total_bulan / 2);

// 		foreach ($transaksi as $key => $row) {
// 			$x = $start_x + $key;
// 			$y = $row->total_qty;

// 			$data[] = [
// 				'bulan' => $row->bulan,
// 				'x' => $x,
// 				'y' => $y,
// 				'x2' => pow($x, 2),
// 				'xy' => $x * $y,
// 			];
// 		}

// 		$this->load->view('least_square', [
// 			'data' => $data,
// 			'tahun' => $tahun,
// 		]);
// 	}
// }

// class TransaksiModel extends CI_Model
// {
// 	public function get_data_per_bulan($tahun)
// 	{
// 		$this->db->select("MONTHNAME(tanggal) as bulan, SUM(qty) as total_qty");
// 		$this->db->from("transaksi");
// 		$this->db->where("YEAR(tanggal)", $tahun);
// 		$this->db->group_by("MONTH(tanggal)");
// 		$this->db->order_by("MONTH(tanggal)", "ASC");
// 		$query = $this->db->get();

// 		if ($query->num_rows() > 0) {
// 			return $query->result();
// 		}

// 		return []; // Kembalikan array kosong jika tidak ada data
// 	}
// }


/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */