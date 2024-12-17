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

            // Mencari penjualan terendah dan tertinggi
            $lowest_sales = min($sales); // Mencari penjualan terendah
            $highest_sales = max($sales); // Mencari penjualan tertinggi

            // Menentukan tahun yang memiliki penjualan terendah dan tertinggi
            $lowest_year_index = array_search($lowest_sales, $sales);
            $highest_year_index = array_search($highest_sales, $sales);

            $lowest_year_data = $data_tahun[$lowest_year_index]; // Data tahun dengan penjualan terendah
            $highest_year_data = $data_tahun[$highest_year_index]; // Data tahun dengan penjualan tertinggi

            // Kirim data ke view
            $this->load->view('peramalan_tahun', [
                'data_tahun' => $data_tahun, // Kirim data tahun
                'equation' => $equation,
                'slope' => $slope,
                'intercept' => $intercept,
                'years' => $years, // Kirim data tahun ke grafik
                'sales' => $sales, // Kirim data penjualan ke grafik
                'lowest_year_data' => $lowest_year_data, // Data tahun dengan penjualan terendah
                'highest_year_data' => $highest_year_data // Data tahun dengan penjualan tertinggi
            ]);
        } else {
            // Jika tidak ada data, tampilkan pesan
            $this->load->view('peramalan_tahun', [
                'message' => 'Data perhitungan Least Square tidak tersedia.'
            ]);
        }
    }

    // Method untuk menampilkan data penjualan dan total per tahun
    public function show_sales_data()
    {
        // Load model untuk mengakses data
        $data_tahun = $this->LeastSquareModel->get_data_per_tahun();

        // Menghitung total berdasarkan data yang didapatkan
        $totals = $this->LeastSquareModel->calculate_totals($data_tahun);

        // Kirim data penjualan dan total ke view
        $this->load->view('sales_view', [
            'data_tahun' => $data_tahun, // Data penjualan per tahun
            'totals' => $totals // Data total perhitungan
        ]);
    }
}