<?php
class TrendModel extends CI_Model
{
    public function get_trend_data($tahun)
    {
        if (is_string($tahun) || is_numeric($tahun)) {
            $this->db->select('MONTH(tanggal) as bulan, produk.nama_produk, SUM(qty) as qty');
            $this->db->from('transaksi');
            $this->db->join('produk', 'transaksi.nama_produk = produk.nama_produk');
            $this->db->where('YEAR(tanggal)', $tahun);
            $this->db->group_by('MONTH(tanggal), produk.nama_produk');
            $query = $this->db->get();

            // Pastikan hasil query adalah array
            $result = $query->result_array();

            return $result;  // Kembalikan array hasil query
        } else {
            return [];
        }
    }


    public function get_data_per_bulan($tahun)
    {
        if (is_string($tahun) || is_numeric($tahun)) {
            $this->db->select("MONTHNAME(tanggal) AS bulan, SUM(qty) AS total_qty");
            $this->db->from("transaksi");
            $this->db->where("YEAR(tanggal)", $tahun);
            $this->db->group_by("MONTH(tanggal)");
            $this->db->order_by("MONTH(tanggal)", "ASC");
            return $this->db->get()->result();
        } else {
            return [];
        }
    }

    public function get_data_per_tahun()
    {
        $this->db->select("YEAR(tanggal) AS tahun, SUM(qty) AS total_qty");
        $this->db->from("transaksi");
        $this->db->group_by("YEAR(tanggal)");
        $this->db->order_by("YEAR(tanggal)", "ASC");
        return $this->db->get()->result();
    }

}