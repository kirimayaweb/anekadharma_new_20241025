<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_cover_model extends CI_Model
{

    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'sys_cover';
    public $id = 'id';
    public $nama_cover = 'nama_cover';
    public $uuid_cover = 'uuid_cover';
    public $tingkat = 'tingkat';
    public $order = 'DESC';
    public $orderASC = 'ASC';
    function __construct()
    {
        parent::__construct();
    }

    function get_tingkat(){
        $hasil=$this->db->query("SELECT id,uuid_cover,tingkat FROM sys_cover GROUP BY tingkat");
        return $hasil;
    }

 
    function get_cover($tingkat){
        // $hasil_row_tingkat=$this->db->query("SELECT * FROM sys_cover WHERE tingkat='$tingkat'")->row()->tingkat;

   
        $hasil=$this->db->query("SELECT * FROM sys_cover WHERE tingkat='$tingkat' Group By sys_cover.nama_cover");
        return $hasil->result();
    }



    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_all_tingkat_ASC()
    {
        $this->db->order_by($this->tingkat, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    // get data by id
    function get_by_uuid($uuid_cover)
    {
        $this->db->where($this->uuid_cover, $uuid_cover);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('uuid_cover', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('nama_cover', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_cover', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('nama_cover', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function get_limit_data_ALL()
    {
        $this->db->order_by($this->tingkat, Asc);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_cover', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('nama_cover', $q);
        $this->db->or_like('keterangan', $q);
        // $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    // insert data
    function insert($data)
    {
        $this->db->set('uuid_cover', "replace(uuid(),'-','')", FALSE);
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
            'process' => 'Cover',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Cover',
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
            'process' => 'Cover',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Cover',
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
            'process' => 'Cover',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Cover',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }
}

/* End of file Sys_cover_model.php */
/* Location: ./application/models/Sys_cover_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-08-11 02:32:32 */
/* http://harviacode.com */