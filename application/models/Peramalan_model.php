<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peramalan_model extends CI_Model
{
    public function hitung_least_square($data)
    {
        $x = 1; // Variabel untuk periode waktu
        $total_x = 0;    // Jumlah X
        $total_y = 0;    // Jumlah Y
        $total_x2 = 0;   // Jumlah X kuadrat
        $total_xy = 0;   // Jumlah X * Y

        $result = []; // Array untuk menyimpan hasil perhitungan tiap bulan

        // Proses perhitungan nilai-nilai total
        foreach ($data as $row) {
            $y = $row['qty']; // Ambil nilai Y (jumlah penjualan)

            $x2 = pow($x, 2);   // X kuadrat
            $xy = $x * $y;      // X dikalikan Y

            // Akumulasi nilai
            $total_x += $x;
            $total_y += $y;
            $total_x2 += $x2;
            $total_xy += $xy;

            // Simpan hasil sementara
            $result[] = [
                'bulan' => $row['bulan'],
                'x' => $x,
                'y' => $y,
                'x2' => $x2,
                'xy' => $xy
            ];

            $x++; // Tambahkan periode waktu
        }

        // Hitung jumlah data
        $n = count($data);

        // Cek untuk mencegah pembagian dengan nol
        if ($n == 0 || $total_x2 == 0) {
            return [
                'error' => 'Data tidak valid atau pembagian dengan nol terjadi.'
            ];
        }

        // Hitung konstanta a dan koefisien b
        $a = round($total_y / $n, 2); // Intercept, dibulatkan 2 desimal
        $b = round($total_xy / $total_x2, 2); // Slope, dibulatkan 2 desimal

        // Prediksi ke periode berikutnya
        $next_predictions = [];
        for ($i = 1; $i <= 5; $i++) { // Prediksi 5 bulan ke depan
            $periode = $n + $i;
            $prediksi = round($a + ($b * $periode)); // Hasil dibulatkan ke bilangan bulat

            $next_predictions[] = [
                'bulan_ke' => $periode,
                'prediksi' => $prediksi
            ];
        }

        // Kembalikan data hasil perhitungan
        return [
            'data' => $result,
            'a' => $a,
            'b' => $b,
            'prediksi' => $next_predictions
        ];
    }
}