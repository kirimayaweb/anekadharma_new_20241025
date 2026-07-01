<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_setting_neraca_kode_akun_model extends CI_Model
{
    public $table = 'sys_setting_neraca_kode_akun';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    function get_by_field($field_neraca)
    {
        $this->db->where('field_neraca', $field_neraca);
        $this->db->order_by('kode_akun', 'ASC');
        return $this->db->get($this->table)->result();
    }

    function get_kode_akun_by_field($field_neraca)
    {
        $rows = $this->get_by_field($field_neraca);
        $kodes = array();
        foreach ($rows as $row) {
            $kodes[] = $row->kode_akun;
        }
        return $kodes;
    }

    function exists($field_neraca, $kode_akun)
    {
        $this->db->where('field_neraca', $field_neraca);
        $this->db->where('kode_akun', $kode_akun);
        return $this->db->count_all_results($this->table) > 0;
    }

    function insert($data)
    {
        if (!isset($data['uuid_setting']) || $data['uuid_setting'] === '') {
            $data['uuid_setting'] = str_replace('-', '', $this->_generate_uuid());
        }
        if (!isset($data['date_input'])) {
            $data['date_input'] = date('Y-m-d H:i:s');
        }
        return $this->db->insert($this->table, $data);
    }

    function delete_by_field_and_kode($field_neraca, $kode_akun)
    {
        $this->db->where('field_neraca', $field_neraca);
        $this->db->where('kode_akun', $kode_akun);
        return $this->db->delete($this->table);
    }

    private function _generate_uuid()
    {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
