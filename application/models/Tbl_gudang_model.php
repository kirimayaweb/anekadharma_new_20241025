<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_gudang_model extends CI_Model
{
    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'tbl_gudang';
    public $id = 'id';
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
        $this->db->or_like('uuid_gudang', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('nama_gudang', $q);
        $this->db->or_like('alamat_gudang', $q);
        $this->db->or_like('notelp_gudang', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_gudang', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('nama_gudang', $q);
        $this->db->or_like('alamat_gudang', $q);
        $this->db->or_like('notelp_gudang', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_gudang', "replace(uuid(),'-','')", FALSE);
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
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'id' => $id
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
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
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Gudang',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }


    public function get_data_gudang_by_tingkat_mapel_halaman_cover_year_semester_GUDANG($tingkat_selected, $uuid_mapel_pesanan_selected, $get_jumlah_halaman,  $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT trans_gudang_a.id as id_stock, 
        sum(trans_gudang_a.jumlah) as jmlh_masuk_gudang_stock
        FROM trans_gudang trans_gudang_a 
        where trans_gudang_a.tingkat_produk = '$tingkat_selected'  AND trans_gudang_a.uuid_produk = '$uuid_mapel_pesanan_selected' AND  trans_gudang_a.jumlah_halaman = '$get_jumlah_halaman' AND  trans_gudang_a.uuid_cover_produk = '$get_cover_selected' AND trans_gudang_a.tahun_produk = '$tahun_selected' AND trans_gudang_a.semester_produk = '$semester_selected'
        ";

        return $this->db->query($sql)->row_array();
    }


    public function get_data_gudang_by_tingkat_halaman_cover_year_semester_GUDANG_groupby_tingkat($tingkat_selected,  $get_jumlah_halaman,  $get_cover_selected, $tahun_selected, $semester_selected)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        $sql = "SELECT trans_gudang_a.id as id_stock, 
        sum(trans_gudang_a.jumlah) as jmlh_masuk_gudang_stock
        FROM trans_gudang trans_gudang_a 
        where trans_gudang_a.tingkat_produk = '$tingkat_selected'  AND  trans_gudang_a.jumlah_halaman = '$get_jumlah_halaman' AND  trans_gudang_a.uuid_cover_produk = '$get_cover_selected' AND trans_gudang_a.tahun_produk = '$tahun_selected' AND trans_gudang_a.semester_produk = '$semester_selected'
        ";




        return $this->db->query($sql)->row_array();
    }




}

/* End of file Tbl_gudang_model.php */
/* Location: ./application/models/Tbl_gudang_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-27 09:42:35 */
/* http://harviacode.com */