<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('transaksi_model');
	}

	// Menambahkan metode untuk mengambil produk berdasarkan barcode
	public function index()
	{
		$this->load->view('transaksi');
	}

	public function read()
	{
		// Ambil data transaksi dari model
		$this->load->model('Transaksi_model');
		$result = $this->Transaksi_model->getAll();

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
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 0, // Periksa apakah 'draw' ada
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"data" => $data
		));
	}



	// public function add()
	// {
	// 	$produk = json_decode($this->input->post('produk'));
	// 	$tanggal = new DateTime($this->input->post('tanggal'));
	// 	$barcode = array();
	// foreach ($produk as $produk) {
	// 	$this->transaksi_model->removeStok($produk->id, $produk->stok);
	// 	$this->transaksi_model->addTerjual($produk->id, $produk->terjual);
	// 	array_push($barcode, $produk->id);
	// 	}
	// 	$data = array(
	// 		'tanggal' => $tanggal->format('Y-m-d H:i:s'),
	// 		'barcode' => implode(',', $barcode),
	// 		'qty' => implode(',', $this->input->post('qty')),
	// 		'total_bayar' => $this->input->post('total_bayar'),
	// 		'jumlah_uang' => $this->input->post('jumlah_uang'),
	// 		'diskon' => $this->input->post('diskon'),
	// 		'pelanggan' => $this->input->post('pelanggan'),
	// 		'nota' => $this->input->post('nota'),
	// 		'kasir' => $this->session->userdata('id')
	// 	);
	// 	if ($this->transaksi_model->create($data)) {
	// 		echo json_encode($this->db->insert_id());
	// 	}
	// 	$data = $this->input->post('form');
	// }

	public function add()
	{
		// Mengambil data produk yang dikirimkan dalam format JSON
		$produk = json_decode($this->input->post('produk'));
		$tanggal = new DateTime($this->input->post('tanggal'));
		$barcode = array();  // Menyimpan ID barcode
		$nama_produk = array();  // Menyimpan nama produk
		$qty = array();  // Menyimpan jumlah produk

		// Loop melalui data produk untuk memproses barcode, nama produk, dan qty
		foreach ($produk as $item) {
			// Validasi barcode dan stok
			$validasi = $this->transaksi_model->validateBarcodeAndStock($item->barcode, $item->qty);
			if (!$validasi['success']) {
				echo json_encode($validasi); // Kirim respons jika validasi gagal
				return;
			}

			// Update stok dan jumlah terjual jika validasi berhasil
			$this->transaksi_model->removeStok($item->barcode, $item->qty);
			$this->transaksi_model->addTerjual($item->barcode, $item->qty);

			// Menyimpan data ke dalam array
			array_push($barcode, $item->barcode);
			array_push($nama_produk, $item->nama_produk);
			array_push($qty, $item->qty);
		}


		// Menyusun data transaksi yang akan disimpan
		$data = array(
			'tanggal' => $tanggal->format('d F Y'),  // Format tanggal transaksi
			'barcode' => implode(',', $barcode),  // Menggabungkan barcode menjadi satu string
			'nama_produk' => implode(',', $nama_produk),  // Menggabungkan nama produk menjadi satu string
			'qty' => implode(',', $qty),  // Menggabungkan jumlah produk menjadi satu string
			'total_bayar' => $this->input->post('total_bayar'),  // Total pembayaran
			'jumlah_uang' => $this->input->post('jumlah_uang'),  // Jumlah uang yang dibayar
			'diskon' => $this->input->post('diskon'),  // Diskon
			'pelanggan' => $this->input->post('pelanggan'),  // Nama pelanggan
			'nota' => $this->input->post('nota'),  // Nomor nota
			'kasir' => $this->session->userdata('kasir')
		);

		// Menyimpan data transaksi ke dalam database
		if ($this->transaksi_model->create($data)) {
			echo json_encode($this->db->insert_id());  // Mengembalikan ID transaksi yang baru saja dimasukkan
		} else {
			echo json_encode(['success' => false, 'message' => 'Transaksi gagal']);  // Jika transaksi gagal
		}
	}


	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->transaksi_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function cetak($id)
	{
		// Ambil data transaksi berdasarkan ID
		$transaksi = $this->transaksi_model->getById($id);

		// Pastikan data transaksi ada
		if (!$transaksi) {
			echo "Data transaksi tidak ditemukan!";
			return; // Menghentikan eksekusi jika tidak ada data
		}

		// Format tanggal jika ada
		if ($transaksi->tanggal) {
			// Mengubah tanggal menjadi objek DateTime
			$tanggal = new DateTime($transaksi->tanggal);

			// Format tanggal menjadi "01 Desember 2023"
			$transaksi->tanggal = $tanggal->format('d F Y');  // 'd' untuk hari, 'F' untuk bulan (nama bulan dalam huruf), 'Y' untuk tahun
		} else {
			$transaksi->tanggal = 'Tanggal tidak tersedia';
		}


		// Pisahkan barcode dan qty
		$barcode = explode(',', $transaksi->barcode);
		$qty = explode(',', $transaksi->qty);

		// Pastikan jumlah barcode dan qty sesuai
		if (count($barcode) !== count($qty)) {
			echo "Jumlah barcode dan qty tidak sesuai!";
			return;
		}

		// Ambil data produk berdasarkan barcode
		$dataProduk = $this->transaksi_model->getProdukByBarcodes($barcode);
		if (empty($dataProduk)) {
			echo "Data produk tidak ditemukan!";
			return;
		}

		// Hitung total harga produk per item
		foreach ($dataProduk as $key => $produk) {
			$produk->qty = $qty[$key];  // Tambahkan qty untuk produk
			$produk->total = $produk->harga * $produk->qty;  // Hitung total harga
		}

		// Tentukan variabel total_bayar, jumlah_uang, diskon
		$total_bayar = isset($transaksi->total_bayar) ? floatval($transaksi->total_bayar) : 0;
		$jumlah_uang = isset($transaksi->jumlah_uang) ? floatval($transaksi->jumlah_uang) : 0;
		$diskon = isset($transaksi->diskon) ? floatval($transaksi->diskon) : 0;
		$kembalian = $jumlah_uang - $total_bayar;

		// Kirim data ke view
		$data = array(
			'nota' => $transaksi->nota,
			'tanggal' => $transaksi->tanggal,
			'produk' => $dataProduk,
			'total_bayar' => $total_bayar,   // Pastikan total_bayar ada
			'bayar' => $jumlah_uang,         // Pastikan jumlah_uang ada
			'kembalian' => $kembalian,
			'diskon' => $diskon,
			'kasir' => $transaksi->kasir
		);

		// Tampilkan view
		$this->load->view('cetak', $data);
	}

	public function penjualan_bulan()
	{
		header('Content-type: application/json');
		$day = $this->input->post('day');  // Mendapatkan data hari/tanggal dari POST
		$data = [];  // Inisialisasi array untuk menampung hasil

		foreach ($day as $value) {
			// Mengubah format tanggal
			$now = date('d m Y', strtotime($value));

			// Ambil data penjualan untuk tanggal tertentu
			$qty = $this->transaksi_model->penjualanBulan($now);

			// Periksa jika ada data penjualan
			if ($qty !== []) {
				// Hitung jumlah penjualan dan simpan dalam array
				$data[] = array_sum($qty);
			} else {
				// Jika tidak ada penjualan, simpan 0
				$data[] = 0;
			}
		}

		// Kembalikan hasil dalam format JSON
		echo json_encode($data);
	}
	public function transaksi_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->transaksiHari($now);
		echo json_encode($total);
	}

	public function transaksi_terakhir($value = '')
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		foreach ($this->transaksi_model->transaksiTerakhir($now) as $key) {
			$total = explode(',', $key);
		}
		echo json_encode($total);
	}

}

// class Peramalan extends CI_Controller
// {

// 	public function __construct()
// 	{
// 		parent::__construct();
// 		$this->load->model('TrendModel');
// 	}

// 	public function index()
// 	{
// 		$tahun = $this->input->get('tahun') ?: date('Y'); // Ambil tahun dari input GET atau default tahun ini
// 		$data_transaksi = $this->TrendModel->get_trend_data($tahun);
// 		$daftar_tahun = $this->TrendModel->get_daftar_tahun(); // Dapatkan daftar tahun dari tabel

// 		// Inisialisasi variabel untuk perhitungan
// 		$n = count($data_transaksi);
// 		$sum_x = 0;
// 		$sum_y = 0;
// 		$sum_x2 = 0;
// 		$sum_xy = 0;

// 		foreach ($data_transaksi as $index => $data) {
// 			$x = $index + 1 - ($n + 1) / 2;
// 			$y = $data['total_qty'];

// 			$sum_x += $x;
// 			$sum_y += $y;
// 			$sum_x2 += pow($x, 2);
// 			$sum_xy += $x * $y;

// 			$data_transaksi[$index]['x'] = $x;
// 			$data_transaksi[$index]['y'] = $y;
// 		}

// 		$b = $sum_xy / $sum_x2;
// 		$a = $sum_y / $n;

// 		foreach ($data_transaksi as $index => $data) {
// 			$data_transaksi[$index]['forecast'] = $a + $b * $data['x'];
// 		}

// 		$this->load->view('peramalan', [
// 			'trend_data' => $data_transaksi,
// 			'tahun' => $tahun,
// 			'daftar_tahun' => $daftar_tahun
// 		]);
// 	}
// }





/* End of file Transaksi.php */
/* Location: ./application/controllers/Transaksi.php */