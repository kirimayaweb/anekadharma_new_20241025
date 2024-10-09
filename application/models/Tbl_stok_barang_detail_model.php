<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_stok_barang_detail_model extends CI_Model
{

    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'tbl_stok_barang_detail';
    public $id = 'id';
    public $uuid_company_finishing = 'uuid_company_finishing';
    public $date_mulai_finishing = 'date_mulai_finishing';
    public $uuid_stock_detail = 'uuid_stock_detail';
    public $uuid_stock = 'uuid_stock';
    public $uuid_trans_cetak = 'uuid_trans_cetak';
    public $uuid_trans_finishing = 'uuid_trans_finishing';
    public $tingkat_produk = 'tingkat_produk';
    public $tahun_produk = 'tahun_produk';
    public $semester_produk = 'semester_produk';
    public $uuid_cover_produk = 'uuid_cover_produk';
    public $jumlah_halaman = 'jumlah_halaman';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('uuid_stock_detail', $q);
        $this->db->or_like('date_input', $q);
        // $this->db->or_like('id_trans_cetak', $q);
        $this->db->or_like('uuid_trans_cetak', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('id_tbl_produk', $q);
        $this->db->or_like('status_produk', $q);
        $this->db->or_like('id_tbl_company_cetak', $q);
        $this->db->or_like('id_user_processing_cetak', $q);
        $this->db->or_like('id_cover', $q);
        $this->db->or_like('date_mulai_cetak', $q);
        $this->db->or_like('date_selesai_cetak', $q);
        $this->db->or_like('id_tbl_finishing', $q);
        $this->db->or_like('id_user_processing_finishing', $q);
        $this->db->or_like('date_mulai_finishing', $q);
        $this->db->or_like('date_selesai_finishing', $q);
        $this->db->or_like('id_gudang', $q);
        $this->db->or_like('id_user_processing_gudang', $q);
        $this->db->or_like('date_masuk_gudang', $q);
        $this->db->or_like('date_keluar_gudang', $q);
        $this->db->or_like('jumlah_produk', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_stock_detail', $q);
        $this->db->or_like('date_input', $q);
        // $this->db->or_like('id_trans_cetak', $q);
        $this->db->or_like('uuid_trans_cetak', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('id_tbl_produk', $q);
        $this->db->or_like('status_produk', $q);
        $this->db->or_like('id_tbl_company_cetak', $q);
        $this->db->or_like('id_user_processing_cetak', $q);
        $this->db->or_like('id_cover', $q);
        $this->db->or_like('date_mulai_cetak', $q);
        $this->db->or_like('date_selesai_cetak', $q);
        $this->db->or_like('id_tbl_finishing', $q);
        $this->db->or_like('id_user_processing_finishing', $q);
        $this->db->or_like('date_mulai_finishing', $q);
        $this->db->or_like('date_selesai_finishing', $q);
        $this->db->or_like('id_gudang', $q);
        $this->db->or_like('id_user_processing_gudang', $q);
        $this->db->or_like('date_masuk_gudang', $q);
        $this->db->or_like('date_keluar_gudang', $q);
        $this->db->or_like('jumlah_produk', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }



    public function get_join_all_data_stockdetail_tblproduk()
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_seelcted = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,

        sys_cover_a.nama_cover as nama_cover,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_seelcted'
        group by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman,tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";


        return $this->db->query($sql)->result();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_TAHUN_SELECTED($tingkat_selected, $mapel_selected, $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak


        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        ";

        return $this->db->query($sql)->row_array();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_TAHUN_SELECTED_ALL_HALAMAN($tingkat_selected, $mapel_selected,  $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak


        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'
        ";

        // where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'

        return $this->db->query($sql)->row_array();
    }



    public function get_NASKAH_by_tingkat_year_semester_FILTER_TAHUN_SELECTED_ALL_HALAMAN($tingkat_selected, $mapel_selected,  $get_cover_selected, $tahun_selected, $semester_selected)
    {

   

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        -- tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        -- tbl_stok_barang_detail_a.kode_stock as kode_stock,
        -- tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        -- tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        -- tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        -- tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        -- sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        
        -- tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        -- sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        -- tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        -- tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        -- sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        -- left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected'  AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        ";

        // where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'


        return $this->db->query($sql)->row_array();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_TAHUN_SELECTED_ALL_HALAMAN_UUID_MAPEL($tingkat_selected, $uuid_mapel_selected,  $get_cover_selected, $tahun_selected, $semester_selected)
    {


        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.uuid_company_finishing as uuid_company_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak


        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'


        -- group by tbl_stok_barang_detail_a.uuid_company_finishing
        ";

        // where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND 



        return $this->db->query($sql)->row_array();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_TAHUN_SELECTED_AND_BEFORE($tingkat_selected, $uuid_mapel_selected, $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak


        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.uuid_trans_finishing <> '' AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'
        
        ";

        //  where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'


        return $this->db->query($sql)->row_array();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_GUDANG($tingkat_selected, $uuid_mapel_pesanan_selected, $get_jumlah_halaman,  $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];


        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 

        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

       

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$get_jumlah_halaman'  
        
        ";



        return $this->db->query($sql)->row_array();
    }

    public function get_naskah_by_tingkat_year_semester_FILTER_TAHUN_SELECTED($tingkat_selected, $mapel_selected, $jumlah_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $get_cover_selected = "naskah";



        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        -- left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        -- left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        -- group by 
        ";

        return $this->db->query($sql)->row_array();
    }





    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_LAMA($tingkat_selected, $mapel_selected, $jumlah_selected, $get_cover_selected, $tahun_selected, $semester_selected)
    {
        // $tahun_selected = $_SESSION['thn_selected'];
        // $semester_seelcted = $_SESSION['semester_selected'];



        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        ";


        return $this->db->query($sql)->row_array();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_LAMA_UUID_MAPEL($tingkat_selected, $uuid_mapel_selected, $jumlah_selected, $get_cover_selected, $tahun_selected, $semester_selected)
    {
        // $tahun_selected = $_SESSION['thn_selected'];
        // $semester_seelcted = $_SESSION['semester_selected'];



        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        ";


        return $this->db->query($sql)->row_array();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_LAMA_UUID_MAPEL_ALL($tingkat_selected, $uuid_mapel_selected, $tahun_selected, $semester_selected)
    {
        // $tahun_selected = $_SESSION['thn_selected'];
        // $semester_seelcted = $_SESSION['semester_selected'];



        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_mapel_selected' AND tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'
        ";


        return $this->db->query($sql)->row_array();
    }


    public function get_total_per_cover_po($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
AND  tbl_stok_barang_detail_a.uuid_trans_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }



    public function get_total_per_cover_po_32_NASKAH($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 32;
        $get_cover_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";


        return $this->db->query($sql)->row_array();
    }




    public function get_total_per_cover_po_64_NASKAH($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 64;
        $get_cover_selected = "naskah";

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock
        -- tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }

    public function get_total_per_cover_po_64($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 64;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }

    public function get_total_per_cover_po_96($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 96;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";


        return $this->db->query($sql)->row_array();
    }



    public function get_total_per_cover_po_96_naskah($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 96;
        $get_cover_selected = "naskah";

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        -- tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock
        -- tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'  AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";


        return $this->db->query($sql)->row_array();
    }








    public function get_total_per_cover_BDP($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_trans_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }


    public function get_total_per_cover_BDP_64($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 64;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }


    public function get_total_per_cover_BDP_96($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 96;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }

    public function get_total_per_cover_BukuJadi($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        -- sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_trans_gudang IS NOT NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }



    public function get_total_per_cover_BukuJadi_64($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 64;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        -- sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NOT NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }



    public function get_total_per_cover_BukuJadi_96($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $jmlh_halaman = 96;

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        -- sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jmlh_halaman' AND  tbl_stok_barang_detail_a.jmlh_masuk_gudang IS NOT NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }



    public function get_total_per_cover_penjualan_retur_TAHUN_SELECTED($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        AND  tbl_stok_barang_detail_a.uuid_trans_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }


    public function get_total_per_cover_penjualan_retur($tingkat_selected = null, $get_cover_selected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT 
        -- tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        -- tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        -- tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        -- tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        -- tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        -- tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,        
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected'  AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'
        AND  tbl_stok_barang_detail_a.uuid_trans_gudang IS NULL
        
        group by tbl_stok_barang_detail_a.tingkat_produk, tbl_stok_barang_detail_a.tahun_produk,  tbl_stok_barang_detail_a.semester_produk,  tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row_array();
    }




    public function get_data_by_mapel_LAMA_32($tingkat_selected = null, $uuid_produk = null)
    {
        $jumlah_selected = 32;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang


        where tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_produk
        ";

        return $this->db->query($sql)->row();
    }


    public function get_data_by_mapel_LAMA_64($tingkat_selected = null, $uuid_produk = null)
    {
        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang


        where tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_produk
        ";

        return $this->db->query($sql)->row();
    }



    public function get_data_by_mapel_LAMA_96($tingkat_selected = null, $uuid_produk = null)
    {
        $jumlah_selected = 96;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        tbl_stok_barang_detail_a.jmlh_masuk_gudang as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang


        where tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.tahun_produk, tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }




    public function get_data_total_persemester_groupby_cover($tingkat_selected = null, $mapel_selected = null, $jumlah_selected = null, $get_cover_selected = null)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        tbl_stok_barang_detail_a.jmlh_masuk_gudang as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        -- where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.tahun_produk, tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->result_array();
    }


    public function get_data_total_persemester_groupby_cover_32($tingkat_selected = null, $uuid_produk = null,  $get_cover_selected = null)
    {
        $jumlah_selected = 32;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        tbl_stok_barang_detail_a.jmlh_masuk_gudang as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";



        return $this->db->query($sql)->row();
    }

    public function get_data_total_persemester_groupby_cover_64($tingkat_selected = null, $uuid_produk = null,  $get_cover_selected = null)
    {
        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        tbl_stok_barang_detail_a.jmlh_masuk_gudang as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";



        return $this->db->query($sql)->row();
    }

    public function get_data_total_persemester_by_cover_groupby_date_64($tingkat_selected = null, $uuid_produk = null,  $get_cover_selected = null, $date_input_selected = null)
    {
        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.date_input = '$date_input_selected'
        
        GROUP BY tbl_stok_barang_detail_a.tahun_produk, tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.uuid_cover_produk,tbl_stok_barang_detail_a.date_input
        ";

        return $this->db->query($sql)->row();
    }

    public function get_data_total_persemester_by_cover_groupby_date_96($tingkat_selected = null, $uuid_produk = null,  $get_cover_selected = null, $date_input_selected = null)
    {
        $jumlah_selected = 96;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.date_input = '$date_input_selected'
        
        GROUP BY tbl_stok_barang_detail_a.tahun_produk, tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.uuid_cover_produk,tbl_stok_barang_detail_a.date_input
        ";

        return $this->db->query($sql)->row();
    }


    public function get_data_total_per_tingkat_per_cover_32($tingkat_selected = null, $get_cover_selected = null)
    {
        $jumlah_selected = 32;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        sys_cover_a.nama_cover as nama_cover,
        -- tbl_produk_mapel_referensi_a.id as id_produk,
        -- tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        -- left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }




    public function get_data_total_per_tingkat_per_cover_per_dateinput_64($tingkat_selected = null, $get_cover_selected = null, $get_dateinput_selected = null)
    {
        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.date_input = '$get_dateinput_selected'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }


    public function get_data_total_per_tingkat_per_cover_per_dateinput_96($tingkat_selected = null, $get_cover_selected = null, $get_dateinput_selected = null)
    {
        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.date_input = '$get_dateinput_selected'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }

//OK
    public function get_data_total_per_tingkat_per_cover_64($tingkat_selected = null, $get_cover_selected = null)
    {


        $jumlah_selected = 64;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        -- tbl_produk_mapel_referensi_a.id as id_produk,
        -- tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        -- tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        -- left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'


        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";


        return $this->db->query($sql)->row();
    }


    public function get_data_total_per_tingkat_per_cover_96($tingkat_selected = null, $get_cover_selected = null)
    {
        $jumlah_selected = 96;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan) as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        -- tbl_produk_mapel_referensi_a.id as id_produk,
        -- tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        -- tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      

        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        -- left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }

    public function get_data_total_persemester_groupby_cover_96($tingkat_selected = null, $uuid_produk = null,  $get_cover_selected = null)
    {
        $jumlah_selected = 96;
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        -- tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        -- tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        -- tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        -- tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        -- tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        -- tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        -- tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        -- tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        -- tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        -- tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        -- tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        -- tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        -- tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        -- tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        -- tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        -- tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        -- tbl_stok_barang_detail_a.halaman as halaman_stock,
        -- tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak as jumlah_exemplar_cetak_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        tbl_stok_barang_detail_a.jmlh_masuk_gudang as jmlh_masuk_gudang_stock,
        -- tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        -- tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        -- tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        -- tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        -- tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        -- tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        -- tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        -- tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        -- tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        -- tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        -- tbl_produk_mapel_referensi_a.semester as semester_produk,
        -- tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        -- tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk
      
        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 
        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        -- left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        -- left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.uuid_produk = '$uuid_produk' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> 'naskah'
        
        GROUP BY tbl_stok_barang_detail_a.uuid_cover_produk
        ";

        return $this->db->query($sql)->row();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_FILTER_ALL($tingkat_selected, $mapel_selected, $jumlah_selected, $get_cover_selected = null)


    {
        // $tahun_selected = $_SESSION['thn_selected'];
        // $semester_seelcted = $_SESSION['semester_selected'];



        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_jual) as jumlah_exemplar_jual_stock,
        sum(tbl_stok_barang_detail_a.retur) as retur_stock,
        -- tbl_stok_barang_detail_a.jumlah_exemplar_retur as jumlah_exemplar_retur_stock,
        
        tbl_stok_barang_detail_a.uuid_gudang as uuid_gudang_stock,
        sum(tbl_stok_barang_detail_a.jmlh_masuk_gudang) as jmlh_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,

        sys_cover_a.nama_cover as nama_cover,
        
        tbl_gudang_a.nama_gudang as nama_gudang_stock,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,


        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        tbl_produk_mapel_referensi_a.semester as semester_produk,
        tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = tbl_stok_barang_detail_a.uuid_gudang

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_selected' AND tbl_stok_barang_detail_a.mapel_produk = '$mapel_selected' AND  tbl_stok_barang_detail_a.jumlah_halaman = '$jumlah_selected' AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$get_cover_selected'

        -- order by tbl_stok_barang_detail_a.tahun_produk ASC
        GROUP BY tbl_stok_barang_detail_a.tahun_produk, tbl_stok_barang_detail_a.semester_produk
        ";


        return $this->db->query($sql)->row_array();
    }



    public function get_join_all_data_stockdetail_by_tingkat_year_semester($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_seelcted = $_SESSION['semester_selected'];

        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.uuid_company_cetak as uuid_company_cetak_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        tbl_stok_barang_detail_a.tingkat_produk as tingkat_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk_stock,
        tbl_stok_barang_detail_a.tahun_produk as tahun_produk_stock,
        tbl_stok_barang_detail_a.kelas_produk as kelas_produk_stock,
        tbl_stok_barang_detail_a.semester_produk as semester_produk_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.jumlah_halaman as jumlah_halaman_stock,
        tbl_stok_barang_detail_a.harga_jual_pesanan as harga_jual_pesanan_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak) as jumlah_exemplar_cetak_stock,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan as jumlah_exemplar_pemesanan_stock,
        tbl_stok_barang_detail_a.jumlah_exemplar_jual as jumlah_exemplar_jual_stock,
        tbl_stok_barang_detail_a.retur as retur_stock,

        sys_cover_a.nama_cover as nama_cover,
    

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,


        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        tbl_produk_mapel_referensi_a.semester as semester_produk,
        tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_seelcted'
        group by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman,tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";


        return $this->db->query($sql)->result();
    }

    public function get_NASKAH_by_tingkat_year_semester_cover_TAHUN_SELECTED($tingkat_sklh=null,$get_semester=null)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
        sum(tbl_stok_barang_detail_a.jumlah_exemplar_cetak_naskah) as jumlah_exemplar_cetak_naskah,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk = '$naskah_selected' 
        ";

        // group by tbl_stok_barang_detail_a.uuid_cover_produk
        // order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC

    
        return $this->db->query($sql)->result();
    }
    
    public function get_join_all_data_stockdetail_by_tingkat_year_semester_cover_TAHUN_SELECTED($tingkat_sklh=null,$get_semester=null)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";



        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
   

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected' 
        group by tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";

      

        return $this->db->query($sql)->result();
    }

    public function TEST_get_join_all_data_stockdetail_by_tingkat_year_semester_cover_TAHUN_SELECTED($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        -- left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
    --    left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected'  AND  tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected' 
        group by tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";


        return $this->db->query($sql)->result();
    }



    public function get_join_all_data_stockdetail_by_tingkat_year_semester_cover_TAHUN_SELECTED_ONLY($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected' 
        group by tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";


        return $this->db->query($sql)->result();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_cover_LAMA($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk < '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND  tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected' 
        group by tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";


        return $this->db->query($sql)->result();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_BY_COVER_GROUPBY_DATE_Tahun_Selected($tingkat_sklh = null, $uuid_cover_produk = null)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        // $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
    --    left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND tbl_stok_barang_detail_a.uuid_cover_produk = '$uuid_cover_produk'

        group by tbl_stok_barang_detail_a.date_input
        -- order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";

        return $this->db->query($sql)->result();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_GROUPBY_COVER_Tahun_Selected($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
   
        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected'

        group by tbl_stok_barang_detail_a.uuid_cover_produk

        ";



        return $this->db->query($sql)->result();
    }

    public function get_join_all_data_stockdetail_by_tingkat_year_semester_GROUPBY_COVER_Tahun_Selected_pemesanan($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
    --    left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND tbl_stok_barang_detail_a.jumlah_exemplar_pemesanan > 0 AND tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected'

        group by tbl_stok_barang_detail_a.uuid_cover_produk
        -- order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";

        return $this->db->query($sql)->result();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_cover_ALL_Tahun_Selected($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        // $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk = '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh'

        -- group by tbl_stok_barang_detail_a.uuid_cover_produk
        -- order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";

        return $this->db->query($sql)->result();
    }


    public function get_join_all_data_stockdetail_by_tingkat_year_semester_cover_ALL($tingkat_sklh)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
        $naskah_selected = "naskah";

        $sql = "SELECT 
        tbl_stok_barang_detail_a.uuid_cover_produk as uuid_cover_produk_stock,
        tbl_stok_barang_detail_a.mapel_produk as mapel_produk,
 
        sys_cover_a.nama_cover as nama_cover

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  sys_cover sys_cover_a ON sys_cover_a.uuid_cover = tbl_stok_barang_detail_a.uuid_cover_produk
       left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = tbl_stok_barang_detail_a.uuid_company_cetak

        where tbl_stok_barang_detail_a.tahun_produk <= '$tahun_selected' AND  tbl_stok_barang_detail_a.semester_produk = '$semester_selected' AND tbl_stok_barang_detail_a.tingkat_produk = '$tingkat_sklh' AND  tbl_stok_barang_detail_a.uuid_cover_produk <> '$naskah_selected' 

        group by tbl_stok_barang_detail_a.uuid_cover_produk
        order by tbl_stok_barang_detail_a.tingkat_produk,tbl_stok_barang_detail_a.mapel_produk,tbl_stok_barang_detail_a.tahun_produk,tbl_stok_barang_detail_a.kelas_produk,tbl_stok_barang_detail_a.semester_produk,tbl_stok_barang_detail_a.jumlah_halaman ASC
        ";

        return $this->db->query($sql)->result();
    }

    public function get_join_all_data($limit = null, $start = 0, $q = NULL)
    {
        $sql = "SELECT tbl_stok_barang_detail_a.id as id_stock, 
        tbl_stok_barang_detail_a.uuid_stock_detail as uuid_stock,
        tbl_stok_barang_detail_a.kode_stock as kode_stock,
        tbl_stok_barang_detail_a.date_input as date_input_stock,
        -- tbl_stok_barang_detail_a.id_trans_cetak as id_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_trans_cetak as uuid_trans_cetak_stock,
        tbl_stok_barang_detail_a.uuid_produk as uuid_produk_stock,
        tbl_stok_barang_detail_a.id_user_input as id_user_input_stock,
        tbl_stok_barang_detail_a.id_tbl_produk as id_tbl_produk_stock,
        tbl_stok_barang_detail_a.status_produk as status_produk_stock,
        tbl_stok_barang_detail_a.id_tbl_company_cetak as id_tbl_company_cetak_stock,
        tbl_stok_barang_detail_a.id_user_processing_cetak as id_user_processing_cetak_stock,
        tbl_stok_barang_detail_a.id_cover as id_cover_stock,
        tbl_stok_barang_detail_a.date_mulai_cetak as date_mulai_cetak_stock,
        tbl_stok_barang_detail_a.date_selesai_cetak as date_selesai_cetak_stock,
        tbl_stok_barang_detail_a.id_tbl_finishing as id_tbl_finishing_stock,
        tbl_stok_barang_detail_a.uuid_trans_finishing as uuid_trans_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.id_user_processing_finishing as id_user_processing_finishing_stock,
        tbl_stok_barang_detail_a.date_mulai_finishing as date_mulai_finishing_stock,
        tbl_stok_barang_detail_a.date_selesai_finishing as date_selesai_finishing_stock,
        tbl_stok_barang_detail_a.id_gudang as id_gudang_stock,
        tbl_stok_barang_detail_a.uuid_trans_gudang as uuid_trans_gudang_stock,
        tbl_stok_barang_detail_a.id_user_processing_gudang as id_user_processing_gudang_stock,
        tbl_stok_barang_detail_a.date_masuk_gudang as date_masuk_gudang_stock,
        tbl_stok_barang_detail_a.date_keluar_gudang as date_keluar_gudang_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.jumlah_produk as jumlah_produk_stock,
        tbl_stok_barang_detail_a.halaman as halaman_stock,
        tbl_stok_barang_detail_a.keterangan as keterangan_stock,
        
        trans_cetak_a.id as id_cetak,
        trans_cetak_a.uuid_trans_cetak as uuid_trans_cetak,
        trans_cetak_a.kode_barcode_cetak as kode_barcode_cetak,
        trans_cetak_a.date_input as date_input_cetak,
        trans_cetak_a.id_user_input as id_user_input_cetak,
        trans_cetak_a.id_produk as id_produk_cetak,
        trans_cetak_a.uuid_produk as uuid_produk_cetak,
        trans_cetak_a.id_company_cetak as id_company_cetak,
        trans_cetak_a.uuid_company_cetak as uuid_company_cetak,
        trans_cetak_a.jumlah as jumlah_cetak,
        trans_cetak_a.halaman as halaman_cetak,
        trans_cetak_a.harga as harga_cetak,
        trans_cetak_a.date_pesan_cetak as date_pesan_cetak,
        trans_cetak_a.no_nota_cetak as no_nota_cetak,
        trans_cetak_a.date_renc_slese_cetak as date_renc_slese_cetak,
        trans_cetak_a.date_slese_cetak as date_slese_cetak,
        trans_cetak_a.status_cetak as status_cetak,

        tbl_company_cetak_a.nama_perusahaan as nama_perusahaan_cetak,
        tbl_company_cetak_a.alamat_perusahaan as alamat_perusahaan_cetak,
        tbl_company_cetak_a.notelp_perusahaan as notelp_perusahaan_cetak,

        trans_finishing_a.id as id_finishing,
        trans_finishing_a.uuid_trans_finishing as uuid_trans_finishing,
        trans_finishing_a.kode_finishing as kode_finishing,
        trans_finishing_a.date_input as date_input_finishing,
        trans_finishing_a.id_user_input as id_user_input_finishing,
        trans_finishing_a.id_trans_cetak as id_trans_cetak_finishing,
        trans_finishing_a.uuid_trans_cetak as uuid_trans_cetak_finishing,
        trans_finishing_a.id_produk as id_produk_finishing,
        trans_finishing_a.id_company_cetak as id_company_cetak_finishing,
        trans_finishing_a.id_company_finishing as id_company_finishing,
        trans_finishing_a.jumlah as jumlah_finishing,
        trans_finishing_a.halaman as halaman_finishing,
        trans_finishing_a.harga as harga_finishing,
        trans_finishing_a.date_pesan_finishing as date_pesan_finishing,
        trans_finishing_a.date_renc_slese_finishing as date_renc_slese_finishing,
        trans_finishing_a.date_slese_finishing as date_slese_finishing,
        trans_finishing_a.no_nota_finishing as no_nota_finishing,
        trans_finishing_a.status_finishing as status_finishing,

        tbl_company_finishing_a.nama_perusahaan as nama_perusahaan_finishing,
        tbl_company_finishing_a.alamat_perusahaan as alamat_perusahaan_finishing,
        tbl_company_finishing_a.notelp_perusahaan as notelp_perusahaan_finishing,

        trans_gudang_a.id as id_gudang,
        trans_gudang_a.uuid_trans_gudang as uuid_trans_gudang,
        trans_gudang_a.kode_gudang as kode_gudang,
        trans_gudang_a.date_input as date_input_gudang,
        trans_gudang_a.id_user_input as id_user_input_gudang,
        trans_gudang_a.id_produk as id_produk_gudang,
        trans_gudang_a.uuid_produk as uuid_produk_gudang,
        trans_gudang_a.id_trans_cetak as id_trans_cetak_gudang,
        trans_gudang_a.uuid_trans_cetak as uuid_trans_cetak_gudang,
        trans_gudang_a.id_trans_finishing as id_trans_finishing_gudang,
        trans_gudang_a.uuid_trans_finishing as uuid_trans_finishing_gudang,
        trans_gudang_a.id_company_cetak as id_company_cetak_gudang,
        trans_gudang_a.uuid_company_cetak as uuid_company_cetak_gudang,
        trans_gudang_a.id_company_finishing as id_company_finishing_gudang,
        trans_gudang_a.id_gudang as id_gudang,
        trans_gudang_a.uuid_gudang as uuid_gudang,
        trans_gudang_a.jumlah as jumlah_gudang,
        trans_gudang_a.harga as harga_gudang,
        trans_gudang_a.date_pesan_gudang as date_pesan_gudang,
        trans_gudang_a.date_masuk_gudang as date_masuk_gudang,
        trans_gudang_a.date_keluar_gudang as date_keluar_gudang,
        trans_gudang_a.no_nota_penerimaan_gudang as no_nota_penerimaan_gudang,
        trans_gudang_a.status_gudang as status_gudang,

        tbl_gudang_a.id as id_gudang,
        tbl_gudang_a.nama_gudang as nama_gudang,
        tbl_gudang_a.alamat_gudang as alamat_gudang,
        tbl_gudang_a.notelp_gudang as notelp_gudang,
        tbl_gudang_a.keterangan as keterangan_gudang,


        trans_penjualan_a.id as id_trans_penjualan,
        trans_penjualan_a.uuid_trans_penjualan as uuid_trans_penjualan,
        trans_penjualan_a.kode_penjualan as kode_penjualan,
        trans_penjualan_a.uuid_sales as uuid_sales_trans_penjualan,
        trans_penjualan_a.uuid_pengirim as uuid_pengirim_trans_penjualan,
        trans_penjualan_a.date_input as date_input_trans_penjualan,
        trans_penjualan_a.id_user_input as id_user_input_trans_penjualan,
        trans_penjualan_a.spk as spk_trans_penjualan,
        trans_penjualan_a.date_kirim as date_kirim_trans_penjualan,
        trans_penjualan_a.alamat_kirim as alamat_kirim_trans_penjualan,
        trans_penjualan_a.date_sampai_tujuan as date_sampai_tujuan_trans_penjualan,
        trans_penjualan_a.penerima as penerima_trans_penjualan,
        trans_penjualan_a.jumlah as jumlah_trans_penjualan,
        trans_penjualan_a.harga_jual as harga_jual_trans_penjualan,
        trans_penjualan_a.harga_ongkir as harga_ongkir_trans_penjualan,
        trans_penjualan_a.date_proses_jual as date_proses_jual_trans_penjualan,
        trans_penjualan_a.no_nota_penjualan as no_nota_penjualan_trans_penjualan,
        trans_penjualan_a.status_bayar as status_bayar_trans_penjualan,
        trans_penjualan_a.keterangan as keterangan_trans_penjualan,
        

        tbl_produk_mapel_referensi_a.id as id_produk,
        tbl_produk_mapel_referensi_a.uuid_produk as uuid_produk,
        tbl_produk_mapel_referensi_a.date_input as date_input_produk,
        tbl_produk_mapel_referensi_a.id_user_input as id_user_input_produk,
        tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_a.kelas as kelas_produk,
        tbl_produk_mapel_referensi_a.tahun as tahun_produk,
        tbl_produk_mapel_referensi_a.semester as semester_produk,
        tbl_produk_mapel_referensi_a.mapel as mapel_produk,
        tbl_produk_mapel_referensi_a.halaman as halaman_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk

        FROM tbl_stok_barang_detail tbl_stok_barang_detail_a 

        left join  tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a ON tbl_produk_mapel_referensi_a.uuid_produk = tbl_stok_barang_detail_a.uuid_produk
        left join  trans_cetak trans_cetak_a ON trans_cetak_a.uuid_trans_cetak = tbl_stok_barang_detail_a.uuid_trans_cetak
        left join  trans_finishing trans_finishing_a ON trans_finishing_a.uuid_trans_finishing = tbl_stok_barang_detail_a.uuid_trans_finishing
        left join  trans_gudang trans_gudang_a ON trans_gudang_a.uuid_trans_gudang = tbl_stok_barang_detail_a.uuid_trans_gudang

        -- join tbl_stok_barang_detail dengan company_cetak
        left join  tbl_company_cetak tbl_company_cetak_a ON tbl_company_cetak_a.uuid_company_cetak = trans_cetak_a.uuid_company_cetak
        -- join tbl_stok_barang_detail dengan company_finishing
        left join  tbl_company_finishing tbl_company_finishing_a ON tbl_company_finishing_a.uuid_company_finishing = trans_finishing_a.uuid_company_finishing
        -- join rbl_gudang dengan tbl_gudang
        left join  tbl_gudang tbl_gudang_a ON tbl_gudang_a.uuid_gudang = trans_gudang_a.uuid_gudang

       -- DATA PENJUALAN SAMPAI RETUR
        left join  trans_penjualan trans_penjualan_a ON trans_penjualan_a.uuid_stock = tbl_stok_barang_detail_a.uuid_stock_detail


        -- where tbl_diskominfo_emenara_retribusi_a.tglbayar <= '2019-01-01'
        group by tbl_stok_barang_detail_a.uuid_stock_detail
        order by tbl_stok_barang_detail_a.date_input desc
        ";


        return $this->db->query($sql)->result();
    }

    // insert data
    function insert($data)
    {

        $this->db->set('uuid_stock_detail', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);



        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    function update_masuk_gudang()
    {
    }

    // update data


    // update data
    function update_by_uuid_stock($uuid_stock, $data)
    {



        $this->db->where($this->uuid_stock_detail, $uuid_stock);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'uuid' => $uuid_stock
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }


    // update data
    function update_by_uuid_cetak_on_stock($uuid_trans_cetak, $data)
    {
        $this->db->where($this->uuid_trans_cetak, $uuid_trans_cetak);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'uuid' => $uuid_trans_cetak
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    // update data
    function update_by_uuid_trans_finishing_on_stock($uuid_trans_finishing, $data)
    {
        $this->db->where($this->uuid_trans_finishing, $uuid_trans_finishing);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'uuid' => $uuid_trans_finishing
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    // update data
    function update_by_uuid_stock_detail_on_stock($update_by_uuid_stock_detail_on_stock, $data)
    {


        $this->db->where($this->uuid_stock_detail, $update_by_uuid_stock_detail_on_stock);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'uuid' => $update_by_uuid_stock_detail_on_stock
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }
    // update data
    function update_by_tingkat_thn_semester_cover_on_stock($data, $proses)
    {


        $sql = "select count(id) as jmlhstock from tbl_stok_barang_detail";
        $jmlhSTOCKproses = $this->db->query($sql)->row()->jmlhstock;
        $fzeropadded = sprintf("%09d", ($jmlhSTOCKproses + 1));
        $kode_produkproses = "31" . $fzeropadded;

        if ($proses == "PO") {
            // $jumlah_proses = $data['exemplar_pesanan'];

            if (($data['uuid_cover']) == "naskah") {
                $data_insert = array(
                    'tingkat_produk' => $data['tingkat_pesanan'],
                    'kelas_produk' => $data['kelas_pesanan'],
                    'mapel_produk' => $data['mapel_pesanan'],
                    'tahun_produk' => $data['tahun_pesanan'],
                    'semester_produk' => $data['semester_pesanan'],
                    'uuid_cover_produk' => $data['uuid_cover'],
                    'jumlah_halaman' => $data['jumlah_halaman_pesanan'],
                    'jumlah_exemplar_cetak_naskah' => $data['exemplar_pesanan'],
                    // 'harga_jual_pesanan' => $data['harga_jual_pesanan'],
                    'kode_stock' => $kode_produkproses,
                    'uuid_produk' => $data['uuid_produk'],
                );
            } else {
                $data_insert = array(
                    'tingkat_produk' => $data['tingkat_pesanan'],
                    'kelas_produk' => $data['kelas_pesanan'],
                    'mapel_produk' => $data['mapel_pesanan'],
                    'tahun_produk' => $data['tahun_pesanan'],
                    'semester_produk' => $data['semester_pesanan'],
                    'uuid_cover_produk' => $data['uuid_cover'],
                    'jumlah_halaman' => $data['jumlah_halaman_pesanan'],
                    'jumlah_exemplar_cetak' => $data['exemplar_pesanan'],
                    // 'harga_jual_pesanan' => $data['harga_jual_pesanan'],
                    'kode_stock' => $kode_produkproses,
                    'uuid_produk' => $data['uuid_produk'],
                );
            }
        } elseif ($proses == "PEMESANAN") {
            // $jumlah_proses = $data['exemplar_pesanan'];

            $uuid_produk = $data['uuid_produk'];

            $data_insert = array(
                'tingkat_produk' => $data['tingkat_pesanan'],
                'kelas_produk' => $data['kelas_pesanan'],
                'mapel_produk' => $data['mapel_pesanan'],
                'tahun_produk' => $data['tahun_pesanan'],
                'semester_produk' => $data['semester_pesanan'],
                'uuid_cover_produk' => $data['uuid_cover'],
                'jumlah_halaman' => $data['jumlah_halaman_pesanan'],
                'jumlah_exemplar_pemesanan' => $data['exemplar_pesanan'],
                // 'harga_jual_pesanan' => $data['harga_jual_pesanan'],
                'kode_stock' => $kode_produkproses,
                'uuid_produk' => $uuid_produk,
            );
        }

        // CEK DATA PRODUK

        // $sql = "select *  from tbl_produk_mapel_referensi where tingkat='$tingkat_pesanan_cetak_selected' AND mapel='$mapel_pesanan_cetak_selected'";

        // $cek_data = $this->db->get_where('tbl_produk_mapel_referensi', array('tingkat' => $data['tingkat_pesanan'], 'kelas' => $data['kelas_pesanan'], 'mapel' => $data['mapel_pesanan'], 'tahun' => $data['tahun_pesanan'], 'semester' => $data['semester_pesanan'], 'halaman' => $data['jumlah_halaman_pesanan'], 'uuid_cover_produk' => $data['uuid_cover']));

        $cek_data = $this->db->get_where('tbl_produk_mapel_referensi', array('tingkat' => $data['tingkat_pesanan'], 'mapel' => $data['mapel_pesanan']));


        if ($cek_data->num_rows() > 0) {



            $data_tbl_produk_mapel_referensi = $cek_data->row_array();
            // $data_tbl_produk_mapel_referensi = $this->db->query($sql)->row_array();
            $idprodukProses = $data_tbl_produk_mapel_referensi['id'];
            $uuid_produk = $data_tbl_produk_mapel_referensi['uuid_produk'];

            // CEK DATA STOCK , JIKA BELUM ADA STOCK = INSERT STOCK , JIKA SUDAH ADA STOCK = UPDATE JUMLAH STOCK

            // $cek_data_stock = $this->db->get_where('tbl_stok_barang', array('tingkat_produk' => $data['tingkat_pesanan'], 'mapel_produk' => $data['mapel_pesanan'], 'tahun_produk' => $data['tahun_pesanan'], 'semester_produk' => $data['semester_pesanan'], 'uuid_cover_produk' => $data['uuid_cover'], 'jumlah_halaman' => $data['jumlah_halaman_pesanan']));




            $cek_data_stock_detail = $this->db->get_where('tbl_stok_barang_detail', array('tingkat_produk' => $data['tingkat_pesanan'], 'mapel_produk' => $data['mapel_pesanan'], 'tahun_produk' => $data['tahun_pesanan'], 'semester_produk' => $data['semester_pesanan'], 'uuid_cover_produk' => $data['uuid_cover'], 'jumlah_halaman' => $data['jumlah_halaman_pesanan']));


            if ($cek_data_stock_detail->num_rows() > 0) {
                $uuid_produk = $cek_data->row()->uuid_produk;
                if ($proses == "PO") {
                    $data_stock_detail = $cek_data_stock_detail->row();
                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'id_user_input' => $this->session->userdata('sess_iduser'),
                        'id_produk' => $idprodukProses,
                        'uuid_produk' => $uuid_produk,
                        'jumlah' => $data['exemplar_pesanan'],
                        'halaman' => $data_insert['jumlah_halaman'],
                        // //'harga' => $data_insert['harga_jual_pesanan'],
                        'date_pesan_cetak' => date('Y-m-d H:i:s'),
                        'status_cetak' => "DAFTAR_CETAK",
                    );
                    $this->Trans_cetak_model->insert($data);

                    $X_id_stock_detail = $data_stock_detail->id;
                    // update jumlah sctok COVER
                    if (($cek_data_stock_detail->row()->uuid_cover_produk) == "naskah") {
                        $jumlah_STOCk = $data['jumlah'] + ($data_stock_detail->jumlah_exemplar_cetak_naskah);
                        $data_add_jumlah_stock = array(
                            'jumlah_exemplar_cetak_naskah' => $jumlah_STOCk,
                        );
                    } else {
                        $jumlah_STOCk = $data['jumlah'] + ($data_stock_detail->jumlah_exemplar_cetak);
                        $data_add_jumlah_stock = array(
                            'jumlah_exemplar_cetak' => $jumlah_STOCk,
                        );
                    }


                    $this->db->where($this->id, $X_id_stock_detail);
                    $this->db->update($this->table, $data_add_jumlah_stock);
                } elseif ($proses == "PEMESANAN") {
                    $data_stock_detail = $cek_data_stock_detail->row();


                    $uuid_produk = $cek_data_stock_detail->row()->uuid_produk;


                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'id_user_input' => $this->session->userdata('sess_iduser'),
                        'id_produk' => $idprodukProses,
                        'uuid_produk' => $uuid_produk,
                        'jumlah' => $data_insert['jumlah_exemplar_pemesanan'],
                        'halaman' => $data_insert['jumlah_halaman'],
                        //'harga' => $data_insert['harga_jual_pesanan'],
                        'date_pesan_cetak' => date('Y-m-d H:i:s'),
                        'status_cetak' => "DAFTAR_CETAK",
                    );




                    $this->Trans_cetak_model->insert($data);
                    $jumlah_STOCk = $data['jumlah'] + ($data_stock_detail->jumlah_exemplar_pemesanan);
                    $X_id_stock_detail = $data_stock_detail->id;

                    // update jumlah sctok
                    $data_add_jumlah_stock = array(
                        'jumlah_exemplar_pemesanan' => $jumlah_STOCk,
                        'uuid_produk' => $uuid_produk,
                    );
                    $this->db->where($this->id, $X_id_stock_detail);
                    $this->db->update($this->table, $data_add_jumlah_stock);
                }

                $datainsert = array(
                    'PROCESS' => 'UPDATE',
                    'id' => $X_id_stock_detail
                );

                $data_merge = array_merge($datainsert, $data);

                // INSERT DATA TO "report_summary"
                $data_produk_insert = json_encode($data_merge);

                $data_summary = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'process' => 'Stok barang',
                    'detail' => $data_produk_insert
                );

                $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
                $this->db->insert($this->table_report_summary, $data_summary);

                // INSERT DATA TO "report_log"
                $data_log = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'process' => 'Stok barang',
                    'detail' => $data_produk_insert
                );
                $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
                $this->db->insert($this->table_report_log, $data_log);
                // END OF update jumlah sctok
            } else {
                if ($proses == "PO") {
                    // simpan ke tabel trans_cetak dulu
                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'id_user_input' => $this->session->userdata('sess_iduser'),
                        'id_produk' => $idprodukProses,
                        'uuid_produk' => $uuid_produk,
                        'jumlah' => $data['exemplar_pesanan'],
                        'halaman' => $data_insert['jumlah_halaman'],
                        // //'harga' => $data_insert['harga_jual_pesanan'],
                        'date_pesan_cetak' => date('Y-m-d H:i:s'),
                        'status_cetak' => "DAFTAR_CETAK",
                    );
                } elseif ($proses == "PEMESANAN") {
                    $data_stock_detail = $cek_data_stock_detail->row();
                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'id_user_input' => $this->session->userdata('sess_iduser'),
                        'id_produk' => $idprodukProses,
                        'uuid_produk' => $uuid_produk,
                        'jumlah' => $data_insert['jumlah_exemplar_pemesanan'],
                        'halaman' => $data_insert['jumlah_halaman'],
                        // //'harga' => $data_insert['harga_jual_pesanan'],
                        'date_pesan_cetak' => date('Y-m-d H:i:s'),
                        'status_cetak' => "DAFTAR_CETAK",
                    );
                }
                $this->Trans_cetak_model->insert($data);

                // end of simpan ke tabel trans_cetak dulu

                $this->db->set('uuid_stock_detail', "replace(uuid(),'-','')", FALSE);
                $this->db->insert($this->table, $data_insert);
                $datainsert = array(
                    'PROCESS' => 'INSERT',
                    'id' => $this->db->insert_id()
                );


                $data_merge = array_merge($datainsert, $data);

                // INSERT DATA TO "report_summary"
                $data_produk_insert = json_encode($data_merge);

                $data_summary = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'process' => 'Stok barang',
                    'detail' => $data_produk_insert
                );

                $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
                $this->db->insert($this->table_report_summary, $data_summary);

                // INSERT DATA TO "report_log"
                $data_log = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'process' => 'Stok barang',
                    'detail' => $data_produk_insert
                );
                $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
                $this->db->insert($this->table_report_log, $data_log);
            }
        } else {

            // buat produk
            $data_insert_produk = array(
                'tingkat' => $data['tingkat_pesanan'],
                'kelas' => $data['kelas_pesanan'],
                'mapel' => $data['mapel_pesanan'],
                'tahun' => $data['tahun_pesanan'],
                'semester' => $data['semester_pesanan'],
                'halaman' => $data['jumlah_halaman_pesanan'],
                'uuid_cover_produk' => $data['uuid_cover'],
            );
            $cek_data = $this->db->get_where('tbl_produk_mapel_referensi', array('tingkat' => $data['tingkat_pesanan'], 'mapel' => $data['mapel_pesanan']));
            $qad = $cek_data->row();
            $GET_uuid_produk = $qad->uuid_produk;
            $this->Tbl_produk_model->insert($data_insert_produk, $GET_uuid_produk);



            // $cek_data = $this->db->get_where('tbl_produk_mapel_referensi', array('tingkat' => $data['tingkat_pesanan'], 'kelas' => $data['kelas_pesanan'], 'mapel' => $data['mapel_pesanan'], 'tahun' => $data['tahun_pesanan'], 'semester' => $data['semester_pesanan'], 'halaman' => $data['jumlah_halaman_pesanan'], 'uuid_cover_produk' => $data['uuid_cover']));
            // $data_tbl_produk_mapel_referensi = $cek_data->row_array();
            // // $data_tbl_produk_mapel_referensi = $this->db->query($sql)->row_array();
            // $idprodukProses = $data_tbl_produk_mapel_referensi['id'];
            $uuid_produk = $GET_uuid_produk;
            // END OF CEK DATA PRODUK

            if ($proses == "PO") {
                // buat STOCK
                // simpan ke tabel trans_cetak dulu
                $data = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'id_user_input' => $this->session->userdata('sess_iduser'),
                    'id_produk' => $idprodukProses,
                    'uuid_produk' => $uuid_produk,

                    'tahun_pesanan' => $data_insert['tahun_produk'],
                    'semester_pesanan' => $data_insert['semester_produk'],
                    'tingkat_pesanan' => $data_insert['tingkat_produk'],
                    'jumlah' => $data['exemplar_pesanan'],
                    'kelas_pesanan' => $data_insert['kelas_produk'],
                    'uuid_cover' => $data_insert['uuid_cover_produk'],
                    'uuid_produk' => $uuid_produk,
                    'halaman' => $data_insert['jumlah_halaman'],
                    //'harga' => $data_insert['harga_jual_pesanan'],
                    'date_pesan_cetak' => date('Y-m-d H:i:s'),
                    'status_cetak' => "DAFTAR_CETAK",
                );
            } elseif ($proses == "PEMESANAN") {
                $data = array(
                    'date_input' => date('Y-m-d H:i:s'),
                    'id_user_input' => $this->session->userdata('sess_iduser'),
                    'id_produk' => $idprodukProses,
                    'uuid_produk' => $uuid_produk,

                    'tahun_pesanan' => $data_insert['tahun_produk'],
                    'semester_pesanan' => $data_insert['semester_produk'],
                    'tingkat_pesanan' => $data_insert['tingkat_produk'],
                    'jumlah' => $data_insert['jumlah_exemplar_pemesanan'],
                    'kelas_pesanan' => $data_insert['kelas_produk'],
                    'uuid_cover' => $data_insert['uuid_cover_produk'],
                    'uuid_produk' => $uuid_produk,
                    'halaman' => $data_insert['jumlah_halaman'],
                    //'harga' => $data_insert['harga_jual_pesanan'],
                    'date_pesan_cetak' => date('Y-m-d H:i:s'),
                    'status_cetak' => "DAFTAR_CETAK",
                );
            }


            $this->Trans_cetak_model->insert($data);

            // end of simpan ke tabel trans_cetak dulu

            $this->db->set('uuid_stock_detail', "replace(uuid(),'-','')", FALSE);
            $this->db->insert($this->table, $data_insert);
            $datainsert = array(
                'PROCESS' => 'INSERT',
                'id' => $this->db->insert_id()
            );


            $data_merge = array_merge($datainsert, $data);

            // INSERT DATA TO "report_summary"
            $data_produk_insert = json_encode($data_merge);

            $data_summary = array(
                'date_input' => date('Y-m-d H:i:s'),
                'process' => 'Stok barang',
                'detail' => $data_produk_insert
            );

            $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
            $this->db->insert($this->table_report_summary, $data_summary);

            // INSERT DATA TO "report_log"
            $data_log = array(
                'date_input' => date('Y-m-d H:i:s'),
                'process' => 'Stok barang',
                'detail' => $data_produk_insert
            );
            $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
            $this->db->insert($this->table_report_log, $data_log);
            // end of BUAT STOCk




        }
    }

    // delete data
    function delete($id)
    {
        // GET DATA BY ID
        $this->db->where($this->id, $id);
        $data = $this->db->get($this->table)->row_array();

        //PROSES DELETE
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);


        $datainsert = array(
            'PROCESS' => 'DELETE',
            'id' => $this->db->insert_id()
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Stok barang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }
}

/* End of file Tbl_stok_barang_detail_model.php */
/* Location: ./application/models/Tbl_stok_barang_detail_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-27 01:56:44 */
/* http://harviacode.com */