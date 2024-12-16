<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeastSquareModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Fungsi untuk mengambil data penjualan per tahun
    public function get_data_per_tahun()
    {
        $this->db->select('YEAR(tanggal) as tahun, SUM(qty) as penjualan');
        $this->db->from('transaksi');
        $this->db->group_by('YEAR(tanggal)');
        $query = $this->db->get();

        return $query->result_array();
    }

    // Fungsi untuk menghitung slope (m)
    public function calculate_slope($data_tahun)
    {
        $x_sum = 0;
        $y_sum = 0;
        $xy_sum = 0;
        $x_squared_sum = 0;
        $n = count($data_tahun);

        $x = 1; // Dimulai dari 1 untuk X

        foreach ($data_tahun as $row) {
            $y = $row['penjualan'];
            $x_sum += $x;
            $y_sum += $y;
            $xy_sum += $x * $y;
            $x_squared_sum += $x * $x;
            $x++;
        }

        // Hitung slope (m)
        $m = ($n * $xy_sum - $x_sum * $y_sum) / ($n * $x_squared_sum - $x_sum * $x_sum);
        return $m;
    }

    // Fungsi untuk menghitung intercept (b)
    public function calculate_intercept($data_tahun)
    {
        $x_sum = 0;
        $y_sum = 0;
        $x = 1; // Dimulai dari 1 untuk X

        foreach ($data_tahun as $row) {
            $y = $row['penjualan'];
            $x_sum += $x;
            $y_sum += $y;
            $x++;
        }

        $n = count($data_tahun);
        $slope = $this->calculate_slope($data_tahun);

        // Hitung intercept (b)
        $b = ($y_sum - $slope * $x_sum) / $n;
        return $b;
    }
}