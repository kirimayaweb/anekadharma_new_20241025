<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_stok_barang_model extends CI_Model
{
    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'tbl_stok_barang';
    public $id = 'id_stock';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        $this->datatables->select('id_stock,uuid_stock,date_input,id_user_input,id_tbl_produk,id_tbl_company_cetak,id_tbl_finishing,id_cover,jumlah_produk,halaman,keterangan');
        $this->datatables->from('tbl_stok_barang');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_stok_barang.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_stok_barang/read/$1'), '<i class="fa fa-eye" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm')) . " 
            " . anchor(site_url('tbl_stok_barang/update/$1'), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm')) . " 
                " . anchor(site_url('tbl_stok_barang/delete/$1'), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_stock');
        return $this->datatables->generate();
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
        $this->db->like('id_stock', $q);
        $this->db->or_like('uuid_stock', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('id_tbl_produk', $q);
        $this->db->or_like('id_tbl_company_cetak', $q);
        $this->db->or_like('id_tbl_finishing', $q);
        $this->db->or_like('id_cover', $q);
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
        $this->db->like('id_stock', $q);
        $this->db->or_like('uuid_stock', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('id_tbl_produk', $q);
        $this->db->or_like('id_tbl_company_cetak', $q);
        $this->db->or_like('id_tbl_finishing', $q);
        $this->db->or_like('id_cover', $q);
        $this->db->or_like('jumlah_produk', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_stock', "replace(uuid(),'-','')", FALSE);
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

/* End of file Tbl_stok_barang_model.php */
/* Location: ./application/models/Tbl_stok_barang_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-19 12:53:01 */
/* http://harviacode.com */