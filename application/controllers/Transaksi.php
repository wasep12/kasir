<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Transaksi_model $transaksi_model
 * @property Pengguna_model $pengguna_model
 */
class Transaksi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Pastikan session dan model sudah dimuat
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}

		// Memuat model
		$this->load->model('Transaksi_model', 'transaksi_model');
		$this->load->model('pengguna_model');

		// Memuat database
		$this->load->database();
	}
	// Main page view for transactions
	public function index()
	{
		$this->load->view('transaksi');
	}
	// Retrieve all transactions for DataTables
	public function read()
	{
		// Ambil data transaksi dari model
		$this->load->model('Transaksi_model');
		$result = $this->transaksi_model->getAll();

		// Buat array data yang akan dikirim ke DataTables
		$data = array();
		foreach ($result as $row) {
			$data[] = array(
				'id' => $row->id,
				'tanggal' => $row->tanggal,
				'barcode' => $row->barcode,
				'nama_produk' => $row->nama_produk,
				'qty' => $row->qty,
				'total_bayar' => $row->total_bayar,
				'jumlah_uang' => $row->jumlah_uang,
				'diskon' => $row->diskon,
				'pelanggan' => $row->pelanggan,
				'action' => '<a class="btn btn-sm btn-success" href="' . site_url('transaksi/cetak/') . $row->id . '">Print</a> <button class="btn btn-sm btn-danger" onclick="remove(' . $row->id . ')">Delete</button>'
			);
		}

		// Kirim data dalam format JSON
		echo json_encode(array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 0, // Menggunakan operator ternary
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"data" => $data
		));
	}

	// Add a new transaction
	public function add()
	{
		// Fetching the product data from the POST request
		$produk = json_decode($this->input->post('produk'));
		$tanggal = new DateTime($this->input->post('tanggal'));
		$barcode = [];
		$nama_produk = [];
		$qty = [];

		// Process each product in the transaction
		foreach ($produk as $item) {
			$validasi = $this->transaksi_model->validateBarcodeAndStock($item->barcode, $item->qty);
			if (!$validasi['success']) {
				echo json_encode($validasi); // Send failure response
				return;
			}

			// Update stock and sales records
			$this->transaksi_model->removeStok($item->barcode, $item->qty);
			$this->transaksi_model->addTerjual($item->barcode, $item->qty);

			$barcode[] = $item->barcode;
			$nama_produk[] = $item->nama_produk;
			$qty[] = $item->qty;
		}

		// Prepare transaction data for saving to the database
		$data = array(
			'tanggal' => $tanggal->format('Y-m-d H:i:s'),
			'barcode' => implode(',', $barcode),
			'nama_produk' => implode(',', $nama_produk),
			'qty' => implode(',', $qty),
			'total_bayar' => $this->input->post('total_bayar'),
			'jumlah_uang' => $this->input->post('jumlah_uang'),
			'diskon' => $this->input->post('diskon'),
			'pelanggan' => $this->input->post('pelanggan'),
			'nota' => $this->input->post('nota'),
			'kasir' => $this->session->userdata('id')
		);

		// Save the transaction
		if ($this->transaksi_model->create($data)) {
			echo json_encode($this->db->insert_id());
		} else {
			echo json_encode(['success' => false, 'message' => 'Transaksi gagal']); // Handle failure
		}
	}

	// Delete a transaction
	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->transaksi_model->delete($id)) {
			echo json_encode('sukses');
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal menghapus transaksi']);
		}
	}

	// Print the transaction receipt
	public function cetak($id)
	{
		$transaksi = $this->transaksi_model->getById($id);
		if (!$transaksi) {
			echo "Data transaksi tidak ditemukan!";
			return;
		}

		// Fetch cashier details
		$kasir = $this->pengguna_model->getPengguna($transaksi->kasir);
		$kasirNama = $kasir ? $kasir->nama : 'Admin';

		// Format the transaction date
		$transaksi->tanggal = $transaksi->tanggal ? (new DateTime($transaksi->tanggal))->format('d F Y') : 'Tanggal tidak tersedia';

		// Process barcode and quantity
		$barcode = explode(',', $transaksi->barcode);
		$qty = explode(',', $transaksi->qty);

		if (count($barcode) !== count($qty)) {
			echo "Jumlah barcode dan qty tidak sesuai!";
			return;
		}

		// Fetch product data by barcode
		$dataProduk = $this->transaksi_model->getProdukByBarcodes($barcode);
		if (empty($dataProduk)) {
			echo "Data produk tidak ditemukan!";
			return;
		}

		// Calculate total for each product
		foreach ($dataProduk as $key => $produk) {
			$produk->qty = $qty[$key];
			$produk->total = $produk->harga * $produk->qty;
		}

		$total_bayar = floatval($transaksi->total_bayar);
		$jumlah_uang = floatval($transaksi->jumlah_uang);
		$diskon = floatval($transaksi->diskon);
		$kembalian = $jumlah_uang - $total_bayar;

		$data = array(
			'nota' => $transaksi->nota,
			'tanggal' => $transaksi->tanggal,
			'produk' => $dataProduk,
			'total_bayar' => $total_bayar,
			'bayar' => $jumlah_uang,
			'kembalian' => $kembalian,
			'diskon' => $diskon,
			'kasir' => $transaksi->kasir,
			'kasirNama' => $kasirNama,
		);

		$this->load->view('cetak', $data);
	}

	// Get sales data for the current month
	public function penjualan_bulan()
	{
		header('Content-type: application/json');
		$day = $this->input->post('day');
		$data = [];

		foreach ($day as $value) {
			$now = date('d m Y', strtotime($value));
			$qty = $this->transaksi_model->penjualanBulan($now);

			$data[] = $qty !== [] ? array_sum($qty) : 0;
		}

		echo json_encode($data);
	}

	// Get transactions for today
	public function transaksi_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->transaksiHari($now);
		echo json_encode($total);
	}

	// Get last transactions for the day
	public function transaksi_terakhir($value = '')
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$result = $this->transaksi_model->transaksiTerakhir($now);
		$total = [];

		foreach ($result as $key) {
			$total = explode(',', $key);
		}

		echo json_encode($total);
	}
}