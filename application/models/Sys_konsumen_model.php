<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_konsumen_model extends CI_Model
{

    public $table = 'sys_konsumen';
    public $id = 'id';
    public $uuid_konsumen = 'uuid_konsumen';
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
    function get_by_uuid_konsumen($uuid_konsumen)
    {
        $this->db->where($this->uuid_konsumen, $uuid_konsumen);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('kode_konsumen', $q);
	$this->db->or_like('nama_konsumen', $q);
	$this->db->or_like('nmr_kontak_konsumen', $q);
	$this->db->or_like('alamat_konsumen', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('kode_konsumen', $q);
	$this->db->or_like('nama_konsumen', $q);
	$this->db->or_like('nmr_kontak_konsumen', $q);
	$this->db->or_like('alamat_konsumen', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_konsumen', "replace(uuid(),'-','')", FALSE);
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

/* End of file Sys_konsumen_model.php */
/* Location: ./application/models/Sys_konsumen_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-05 09:25:47 */
/* http://harviacode.com */