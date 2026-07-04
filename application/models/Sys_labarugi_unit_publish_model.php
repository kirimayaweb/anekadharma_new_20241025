<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_labarugi_unit_publish_model extends CI_Model
{
    public $table = 'sys_labarugi_unit_publish';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('laba_rugi_unit_publish');
        labarugi_unit_publish_ensure_table($this);
    }

    function find_existing($uuid_laba_rugi, $unit, $jenis_tab)
    {
        return $this->db->get_where($this->table, array(
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'unit' => (string) $unit,
            'jenis_tab' => $jenis_tab,
        ))->row();
    }

    function set_publish(array $data)
    {
        $now = date('Y-m-d H:i:s');
        $status = !empty($data['status_publish_unit']) || !empty($data['is_publish']) ? 1 : 0;

        $existing = $this->find_existing(
            $data['uuid_laba_rugi'],
            $data['unit'],
            $data['jenis_tab']
        );

        $payload = array(
            'status_publish_unit' => $status,
            'is_publish' => $status,
            'tahun_transaksi' => (int) $data['tahun_transaksi'],
            'bulan_transaksi' => (int) $data['bulan_transaksi'],
            'date_update' => $now,
        );

        if ($existing) {
            $this->db->where($this->id, (int) $existing->id);
            $this->db->update($this->table, $payload);
            return (int) $existing->id;
        }

        $payload['uuid_laba_rugi'] = $data['uuid_laba_rugi'];
        $payload['unit'] = (string) $data['unit'];
        $payload['jenis_tab'] = $data['jenis_tab'];
        $payload['date_input'] = $now;
        $this->db->insert($this->table, $payload);
        return (int) $this->db->insert_id();
    }
}
