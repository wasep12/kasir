<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peramalan_model extends CI_Model
{
    public function hitung_least_square($data)
    {
        $x = 1;
        $total_x = 0;
        $total_y = 0;
        $total_x2 = 0;
        $total_xy = 0;

        $result = [];
        foreach ($data as $row) {
            $y = $row['qty']; // Ambil nilai Y (jumlah penjualan)

            // Hitung nilai peramalan
            $x2 = pow($x, 2);
            $xy = $x * $y;

            $total_x += $x;
            $total_y += $y;
            $total_x2 += $x2;
            $total_xy += $xy;

            $result[] = [
                'bulan' => $row['bulan'],
                'x' => $x,
                'y' => $y,
                'x2' => $x2,
                'xy' => $xy
            ];

            $x++;
        }

        // Hitung konstanta a dan b
        $n = count($data);

        // Cek untuk mencegah pembagian dengan 0 pada perhitungan konstanta a dan b
        if ($n == 0 || $total_x2 == 0) {
            return [
                'error' => 'Data tidak valid atau pembagian dengan nol terjadi.'
            ];
        }

        $a = $total_y / $n;
        $b = $total_xy / $total_x2;

        // Kembalikan data peramalan beserta hasil konstanta
        return [
            'data' => $result,
            'a' => $a,
            'b' => $b
        ];
    }
}