<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_keluar extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('stok_keluar_model');
	}

	public function index()
	{
		$this->load->view('stok_keluar');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->stok_keluar_model->read()->num_rows() > 0) {
			foreach ($this->stok_keluar_model->read()->result() as $stok_keluar) {
				$tanggal = new DateTime($stok_keluar->tanggal);
				$data[] = array(
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'barcode' => $stok_keluar->barcode,
					'nama_produk' => $stok_keluar->nama_produk,
					'jumlah' => $stok_keluar->jumlah,
					'keterangan' => $stok_keluar->keterangan,
				);
			}
		} else {
			$data = array();
		}
		$stok_keluar = array(
			'data' => $data
		);
		echo json_encode($stok_keluar);
	}

	public function add()
	{
		$id = $this->input->post('barcode');
		$jumlah = $this->input->post('jumlah');
		$stok = $this->stok_keluar_model->getStok($id)->stok;
		$rumus = max($stok - $jumlah, 0);
		$addStok = $this->stok_keluar_model->addStok($id, $rumus);
		if ($addStok) {
			$tanggal = new DateTime($this->input->post('tanggal'));
			$data = array(
				'tanggal' => $tanggal->format('Y-m-d H:i:s'),
				'barcode' => $id,
				'jumlah' => $jumlah,
				'keterangan' => $this->input->post('keterangan')
			);
			if ($this->stok_keluar_model->create($data)) {
				echo json_encode('sukses');
			}
		}
	}

	public function get_barcode()
	{
		$barcode = $this->input->post('barcode');

		if ($barcode) {
			// Assuming the model method expects barcode
			$kategori = $this->stok_masuk_model->getKategori($barcode);

			if ($kategori->row()) {
				echo json_encode($kategori->row());
			} else {
				echo json_encode(['error' => 'Kategori not found']);
			}
		} else {
			echo json_encode(['error' => 'No barcode provided']);
		}
	}


}

/* End of file Stok_keluar.php */
/* Location: ./application/controllers/Stok_keluar.php */