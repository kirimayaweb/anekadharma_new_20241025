<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_tingkat_model_1 extends CI_Model
{

    public $table = 'tbl_tingkat';
    public $id = '';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,uuid_tingkat,kode_tingkat,date_input,id_user_input,tingkat,keterangan');
        $this->datatables->from('tbl_tingkat');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_tingkat.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_tingkat_1/read/$1'),'<i class="fa fa-eye" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm'))." 
            ".anchor(site_url('tbl_tingkat_1/update/$1'),'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm'))." 
                ".anchor(site_url('tbl_tingkat_1/delete/$1'),'<i class="fa fa-trash-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), '');
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
    function total_rows($q = NULL) {
        $this->db->like('', $q);
	$this->db->or_like('id', $q);
	$this->db->or_like('uuid_tingkat', $q);
	$this->db->or_like('kode_tingkat', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('id_user_input', $q);
	$this->db->or_like('tingkat', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('', $q);
	$this->db->or_like('id', $q);
	$this->db->or_like('uuid_tingkat', $q);
	$this->db->or_like('kode_tingkat', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('id_user_input', $q);
	$this->db->or_like('tingkat', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Tbl_tingkat_model_1.php */
/* Location: ./application/models/Tbl_tingkat_model_1.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2022-01-14 13:02:32 */
/* http://harviacode.com */