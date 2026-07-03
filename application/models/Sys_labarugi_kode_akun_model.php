<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_labarugi_kode_akun_model extends CI_Model
{
    public $table = 'sys_labarugi_kode_akun';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('laba_rugi_kode_akun');
        labarugi_kode_akun_ensure_table($this);
    }

    function get_by_keterangan($uuid_nama_keterangan, $status_labarugi)
    {
        $this->db->where('uuid_nama_keterangan', $uuid_nama_keterangan);
        $this->db->where('status_labarugi', $status_labarugi);
        $this->db->order_by('kode_akun', 'ASC');
        return $this->db->get($this->table)->result();
    }

    function exists($uuid_nama_keterangan, $kode_akun, $status_labarugi)
    {
        $this->db->where('uuid_nama_keterangan', $uuid_nama_keterangan);
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('status_labarugi', $status_labarugi);
        return $this->db->count_all_results($this->table) > 0;
    }

    function insert_row(array $data)
    {
        $now = date('Y-m-d H:i:s');
        if (empty($data['uuid_setting_kode_akun_labarugi'])) {
            $data['uuid_setting_kode_akun_labarugi'] = labarugi_kode_akun_generate_uuid();
        }
        $data['date_input'] = $now;
        $data['date_update'] = $now;
        return (bool) $this->db->insert($this->table, $data);
    }

    function delete_row($uuid_nama_keterangan, $kode_akun, $status_labarugi)
    {
        $this->db->where('uuid_nama_keterangan', $uuid_nama_keterangan);
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('status_labarugi', $status_labarugi);
        return $this->db->delete($this->table);
    }
}
