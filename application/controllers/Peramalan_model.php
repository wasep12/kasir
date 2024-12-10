<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peramalan_model extends CI_Model
{
    // Ambil data penjualan per bulan untuk trend
    public function get_trend_data($tahun)
    {
        $this->db->select('nama_produk, MONTH(tanggal) as bulan, SUM(qty) as qty');
        $this->db->from('transaksi');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['nama_produk', 'MONTH(tanggal)']);
        $this->db->order_by('nama_produk, bulan');
        $query = $this->db->get();

        return $query->result_array();
    }

    // Hitung Least Square untuk data tahunan
    public function calculate_least_square($tahun)
    {
        // Ambil data per bulan
        $this->db->select('MONTH(tanggal) as bulan, SUM(qty) as penjualan');
        $this->db->from('transaksi');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('bulan');
        $query = $this->db->get();

        $data = $query->result_array();

        // Inisialisasi variabel untuk perhitungan Least Square
        $n = count($data);
        $total_x = 0; // Jumlah X
        $total_y = 0; // Jumlah Y
        $total_x2 = 0; // Jumlah X^2
        $total_xy = 0; // Jumlah XY

        // Hitung nilai X, Y, X^2, dan XY
        foreach ($data as $index => $row) {
            $x = $index + 1; // Indeks bulan sebagai X (1 untuk Januari, dst.)
            $y = $row['penjualan'];

            $data[$index]['x'] = $x;
            $data[$index]['y'] = $y;
            $data[$index]['x2'] = pow($x, 2);
            $data[$index]['xy'] = $x * $y;

            $total_x += $x;
            $total_y += $y;
            $total_x2 += pow($x, 2);
            $total_xy += $x * $y;
        }

        // Hitung parameter a dan b
        $a = $total_y / $n;
        $b = $total_x2 ? $total_xy / $total_x2 : 0;

        // Tambahkan nilai total ke data
        $data['total'] = [
            'x' => $total_x,
            'y' => $total_y,
            'x2' => $total_x2,
            'xy' => $total_xy,
            'a' => $a,
            'b' => $b
        ];

        return $data;
    }
}