<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_laba_rugi_detail_model extends CI_Model
{
    public $table = 'tbl_laba_rugi_detail';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('laba_rugi_detail');
        labarugi_detail_ensure_table($this);
    }

    function get_by_id($id)
    {
        return $this->db->get_where($this->table, array($this->id => (int) $id))->row();
    }

    function find_existing($uuid_laba_rugi, $nama_laba_rugi, $unit, $jenis_tab)
    {
        $unit_val = ($unit === null || $unit === '') ? null : (string) $unit;
        $this->db->where('uuid_laba_rugi', $uuid_laba_rugi);
        $this->db->where('nama_laba_rugi', $nama_laba_rugi);
        $this->db->where('jenis_tab', $jenis_tab);
        if ($unit_val === null) {
            $this->db->group_start();
            $this->db->where('unit IS NULL', null, false);
            $this->db->or_where('unit', '');
            $this->db->group_end();
        } else {
            $this->db->where('unit', $unit_val);
        }
        return $this->db->get($this->table)->row();
    }

    function upsert(array $data)
    {
        $existing = $this->find_existing(
            $data['uuid_laba_rugi'],
            $data['nama_laba_rugi'],
            isset($data['unit']) ? $data['unit'] : null,
            $data['jenis_tab']
        );

        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $update = array(
                'tanggal' => $data['tanggal'],
                'nominal_update' => $data['nominal_update'],
                'date_update' => $now,
            );
            if (isset($data['nominal']) && $existing->nominal === null) {
                $update['nominal'] = $data['nominal'];
            }
            if (array_key_exists('auto_sistem', $data)) {
                $update['auto_sistem'] = $data['auto_sistem'];
            }
            if (array_key_exists('keterangan_data', $data)) {
                $update['keterangan_data'] = $data['keterangan_data'];
            }
            $this->db->where($this->id, (int) $existing->id);
            $this->db->update($this->table, $update);
            return (int) $existing->id;
        }

        $insert = array(
            'tanggal' => $data['tanggal'],
            'uuid_laba_rugi' => $data['uuid_laba_rugi'],
            'nama_laba_rugi' => $data['nama_laba_rugi'],
            'unit' => ($data['unit'] === '' ? null : $data['unit']),
            'nominal' => $data['nominal'],
            'nominal_update' => $data['nominal_update'],
            'auto_sistem' => isset($data['auto_sistem']) ? $data['auto_sistem'] : null,
            'keterangan_data' => isset($data['keterangan_data']) ? $data['keterangan_data'] : null,
            'jenis_tab' => $data['jenis_tab'],
            'tahun_transaksi' => (int) $data['tahun_transaksi'],
            'bulan_transaksi' => (int) $data['bulan_transaksi'],
            'date_input' => $now,
            'date_update' => $now,
        );
        $this->db->insert($this->table, $insert);
        return (int) $this->db->insert_id();
    }
}
