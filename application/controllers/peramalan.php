<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peramalan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('TrendModel');
		$this->load->model('Peramalan_model'); // Tambahkan ini
	}


	public function index()
	{
		// Memeriksa status login session
		if ($this->session->userdata('status') == 'login') {
			// Ambil tahun dari input POST atau gunakan tahun sekarang sebagai default
			$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date('Y');

			// Ambil data tren dari model berdasarkan tahun yang dipilih
			$trend_data = $this->TrendModel->get_trend_data($tahun);

			// Hitung peramalan menggunakan Peramalan_model
			$peramalan_data = $this->Peramalan_model->hitung_least_square($trend_data);

			// Data bulan untuk chart
			$bulan = [
				'January',
				'February',
				'March',
				'April',
				'May',
				'June',
				'July',
				'August',
				'September',
				'October',
				'November',
				'December'
			];

			// Kirim data ke view
			$this->load->view('peramalan', [
				'trend_data' => $trend_data,
				'peramalan_data' => $peramalan_data, // Data peramalan
				'bulan' => $bulan,
				'tahun' => $tahun
			]);
		} else {
			// Jika belum login, arahkan ke halaman login
			$this->load->view('login');
		}
	}
}