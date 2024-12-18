<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_kode_akun_model extends CI_Model
{

    public $table = 'sys_kode_akun';
    public $id = 'id';
    public $uuid_kode_akun = 'uuid_kode_akun';
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
    

    // get data by id
    function get_by_uuid_kode_akun($uuid_kode_akun)
    {
        $this->db->where($this->uuid_kode_akun, $uuid_kode_akun);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('uuid_kode_akun', $q);
	$this->db->or_like('kode_akun', $q);
	$this->db->or_like('nama_akun', $q);
	$this->db->or_like('group', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('uuid_kode_akun', $q);
	$this->db->or_like('kode_akun', $q);
	$this->db->or_like('nama_akun', $q);
	$this->db->or_like('group', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);
        $this->db->set('uuid_kode_akun', "replace(uuid(),'-','')", FALSE);
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

/* End of file Sys_kode_akun_model.php */
/* Location: ./application/models/Sys_kode_akun_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-21 14:26:01 */
/* http://harviacode.com */