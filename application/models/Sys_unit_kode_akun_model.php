<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_kode_akun_model extends CI_Model
{

    public $table = 'sys_unit_kode_akun';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function get_duplicate_row($uuid_unit, $kode_akun, $tbl_source, $source_field = '', $nama_unit = '', $exclude_id = null)
    {
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('tbl_source', $tbl_source);
        if ($this->db->field_exists('source_field', $this->table) && $source_field !== '') {
            $this->db->where('source_field', $source_field);
        }
        if (trim((string) $uuid_unit) !== '') {
            $this->db->where('uuid_unit', $uuid_unit);
        } elseif ($nama_unit !== '') {
            $this->db->where('nama_unit', $nama_unit);
        }
        if ($exclude_id) {
            $this->db->where($this->id . ' !=', (int) $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    function get_by_uuid_unit_kode_akun($uuid_unit, $kode_akun, $exclude_id = null, $tbl_source = null)
    {
        $this->db->where('uuid_unit', $uuid_unit);
        $this->db->where('kode_akun', $kode_akun);
        if ($tbl_source !== null) {
            $this->db->where('tbl_source', $tbl_source);
        }
        if ($exclude_id) {
            $this->db->where($this->id . ' !=', (int) $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    function get_tbl_source_options()
    {
        $labels = array(
            'tbl_penjualan' => 'Penjualan',
            'tbl_pembelian' => 'Pembelian',
            'jurnal_kas' => 'Jurnal Kas',
        );

        $options = array();
        foreach ($labels as $table => $label) {
            if ($this->db->table_exists($table)) {
                $options[$table] = $label . ' (' . $table . ')';
            }
        }

        $db_tables = $this->db->query("SHOW TABLES LIKE 'tbl\\_%'")->result_array();
        foreach ($db_tables as $row) {
            $table = array_values((array) $row)[0];
            if (!isset($options[$table])) {
                $options[$table] = ucwords(str_replace('_', ' ', $table)) . ' (' . $table . ')';
            }
        }

        ksort($options);
        return $options;
    }

    function total_rows($q = NULL)
    {
        if ($q) {
            $this->db->group_start();
            $this->db->like('uuid_unit', $q);
            $this->db->or_like('kode_unit', $q);
            $this->db->or_like('nama_unit', $q);
            $this->db->or_like('kode_akun', $q);
            $this->db->or_like('tbl_source', $q);
            $this->db->or_like('keterangan', $q);
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table);
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}
