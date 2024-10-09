<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_model extends CI_Model
{

    public $table = 'sys_unit';
    public $id = 'id';
    public $uuid_unit = 'uuid_unit';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,uuid_unit,kode_unit,nama_unit,keterangan');
        $this->datatables->from('sys_unit');
        //add this line for join
        //$this->datatables->join('table2', 'sys_unit.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('sys_unit/read/$1'),'Read')." | ".anchor(site_url('sys_unit/update/$1'),'Update')." | ".anchor(site_url('sys_unit/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
    
    function get_by_uuid_unit($uuid_unit)
    {
        $this->db->where($this->uuid_unit, $uuid_unit);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('uuid_unit', $q);
	$this->db->or_like('kode_unit', $q);
	$this->db->or_like('nama_unit', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('uuid_unit', $q);
	$this->db->or_like('kode_unit', $q);
	$this->db->or_like('nama_unit', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);

        $this->db->set('uuid_unit', "replace(uuid(),'-','')", FALSE);
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

/* End of file Sys_unit_model.php */
/* Location: ./application/models/Sys_unit_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-16 14:10:02 */
/* http://harviacode.com */