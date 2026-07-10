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
        $this->ensure_schema();
    }

    /**
     * Pastikan kolom alamat & hapus_unit ada di tabel sys_unit.
     */
    function ensure_schema()
    {
        if (!$this->db->table_exists($this->table)) {
            return;
        }

        if (!$this->db->field_exists('alamat', $this->table)) {
            $this->db->query("ALTER TABLE `{$this->table}` ADD `alamat` TEXT NULL AFTER `nama_unit`");
        }

        if (!$this->db->field_exists('hapus_unit', $this->table)) {
            $this->db->query("ALTER TABLE `{$this->table}` ADD `hapus_unit` TINYINT(1) NOT NULL DEFAULT 0 AFTER `keterangan`");
        }
    }

    // datatables
    function json() {
        $this->datatables->select('id,uuid_unit,kode_unit,nama_unit,alamat,keterangan');
        $this->datatables->from('sys_unit');
        if ($this->db->field_exists('hapus_unit', $this->table)) {
            $this->datatables->where('(hapus_unit IS NULL OR hapus_unit = 0)');
        }
        $this->datatables->add_column('action', anchor(site_url('sys_unit/read/$1'),'Read')." | ".anchor(site_url('sys_unit/update/$1'),'Update')." | ".anchor(site_url('sys_unit/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
        return $this->datatables->generate();
    }

    // get all (exclude soft-deleted)
    function get_all()
    {
        if ($this->db->field_exists('hapus_unit', $this->table)) {
            $this->db->where('(hapus_unit IS NULL OR hapus_unit = 0)', null, false);
        }
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

    // soft delete: set hapus_unit = 1
    function soft_delete($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->update($this->table, array('hapus_unit' => 1));
    }

    /**
     * Cek apakah unit dipakai di tbl_pembelian / tbl_pembelian_jasa / tbl_penjualan.
     * @return array{used:bool, tables:string[]}
     */
    function is_unit_in_use($uuid_unit)
    {
        $uuid_unit = trim((string) $uuid_unit);
        $used_in = array();

        if ($uuid_unit === '') {
            return array('used' => false, 'tables' => $used_in);
        }

        if ($this->db->table_exists('tbl_pembelian') && $this->db->field_exists('uuid_konsumen', 'tbl_pembelian')) {
            $this->db->where('uuid_konsumen', $uuid_unit);
            if ($this->db->count_all_results('tbl_pembelian') > 0) {
                $used_in[] = 'tbl_pembelian';
            }
        }

        if ($this->db->table_exists('tbl_pembelian_jasa') && $this->db->field_exists('uuid_konsumen', 'tbl_pembelian_jasa')) {
            $this->db->where('uuid_konsumen', $uuid_unit);
            if ($this->db->count_all_results('tbl_pembelian_jasa') > 0) {
                $used_in[] = 'tbl_pembelian_jasa';
            }
        }

        if ($this->db->table_exists('tbl_penjualan') && $this->db->field_exists('uuid_unit', 'tbl_penjualan')) {
            $this->db->where('uuid_unit', $uuid_unit);
            if ($this->db->count_all_results('tbl_penjualan') > 0) {
                $used_in[] = 'tbl_penjualan';
            }
        }

        return array(
            'used' => count($used_in) > 0,
            'tables' => $used_in,
        );
    }

    // delete data (hard delete — legacy)
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
