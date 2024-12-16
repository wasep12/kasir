<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeastSquareController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LeastSquareModel'); // Memuat model LeastSquareModel
    }

    // Method untuk halaman utama perhitungan Least Square
    public function index()
    {
        // Ambil data penjualan per tahun
        $data_tahun = $this->LeastSquareModel->get_data_per_tahun();

        // Cek apakah ada data
        if (!empty($data_tahun)) {
            // Hitung slope dan intercept
            $slope = $this->LeastSquareModel->calculate_slope($data_tahun);
            $intercept = $this->LeastSquareModel->calculate_intercept($data_tahun);

            // Persamaan Least Square
            $equation = "y = {$slope}x + {$intercept}";

            // Ekstrak data untuk grafik
            $years = array_column($data_tahun, 'tahun'); // Ambil tahun
            $sales = array_column($data_tahun, 'penjualan'); // Ambil penjualan

            // Kirim data ke view
            $this->load->view('peramalan_tahun', [
                'data_tahun' => $data_tahun, // Kirim data tahun
                'equation' => $equation,
                'slope' => $slope,
                'intercept' => $intercept,
                'years' => $years, // Kirim data tahun ke grafik
                'sales' => $sales // Kirim data penjualan ke grafik
            ]);
        } else {
            // Jika tidak ada data, tampilkan pesan
            $this->load->view('peramalan_tahun', [
                'message' => 'Data perhitungan Least Square tidak tersedia.'
            ]);
        }
    }

}