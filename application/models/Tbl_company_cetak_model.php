<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_company_cetak_model extends CI_Model
{
    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'tbl_company_cetak';
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
        $this->db->or_like('uuid_company_cetak', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('nama_perusahaan', $q);
        $this->db->or_like('alamat_perusahaan', $q);
        $this->db->or_like('notelp_perusahaan', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_company_cetak', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('nama_perusahaan', $q);
        $this->db->or_like('alamat_perusahaan', $q);
        $this->db->or_like('notelp_perusahaan', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_company_cetak', "replace(uuid(),'-','')", FALSE);
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
            'process' => 'Perusahaan cetak',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Perusahaan cetak',
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
            'process' => 'Perusahaan cetak',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Perusahaan cetak',
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

/* End of file Tbl_company_cetak_model.php */
/* Location: ./application/models/Tbl_company_cetak_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-20 10:28:52 */
/* http://harviacode.com */