<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_kas_kecil_model extends CI_Model
{

    public $table = 'tbl_kas_kecil';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,uuid_kas_kecil,tanggal,unit,keterangan,debet,kredit,saldo,id_usr');
        $this->datatables->from('tbl_kas_kecil');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_kas_kecil.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_kas_kecil/read/$1'),'Read')." | ".anchor(site_url('tbl_kas_kecil/update/$1'),'Update')." | ".anchor(site_url('tbl_kas_kecil/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
        $this->db->like('id', $q);
	$this->db->or_like('uuid_kas_kecil', $q);
	$this->db->or_like('tanggal', $q);
	$this->db->or_like('unit', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->or_like('debet', $q);
	$this->db->or_like('kredit', $q);
	$this->db->or_like('saldo', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('uuid_kas_kecil', $q);
	$this->db->or_like('tanggal', $q);
	$this->db->or_like('unit', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->or_like('debet', $q);
	$this->db->or_like('kredit', $q);
	$this->db->or_like('saldo', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_kas_kecil', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );
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

/* End of file Tbl_kas_kecil_model.php */
/* Location: ./application/models/Tbl_kas_kecil_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-08 13:38:25 */
/* http://harviacode.com */