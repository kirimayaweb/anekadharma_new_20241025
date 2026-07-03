<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_labarugi_keterangan_model extends CI_Model
{
    public $table = 'sys_labarugi_keterangan';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('laba_rugi_keterangan');
        labarugi_keterangan_ensure_table($this);
        labarugi_keterangan_ensure_columns($this);
        labarugi_keterangan_seed_defaults($this);
    }

    function get_by_id($id)
    {
        return $this->db->get_where($this->table, array($this->id => (int) $id))->row();
    }

    function get_by_tab($status_labarugi)
    {
        $this->db->where('status_labarugi', $status_labarugi);
        $this->db->order_by('urutan', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table)->result();
    }

    function get_all()
    {
        $this->db->order_by('status_labarugi', 'ASC');
        $this->db->order_by('urutan', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table)->result();
    }

    function uuid_exists($uuid, $status_labarugi, $exclude_id = null)
    {
        $this->db->where('uuid_nama_keterangan', $uuid);
        $this->db->where('status_labarugi', $status_labarugi);
        if ($exclude_id) {
            $this->db->where($this->id . ' !=', (int) $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    function next_urutan($status_labarugi)
    {
        $row = $this->db
            ->select_max('urutan')
            ->where('status_labarugi', $status_labarugi)
            ->get($this->table)
            ->row();
        return ($row && $row->urutan) ? ((int) $row->urutan + 1) : 1;
    }

    function save_row(array $data)
    {
        $now = date('Y-m-d H:i:s');
        $id = isset($data['id']) ? (int) $data['id'] : 0;

        $payload = array(
            'uuid_nama_keterangan' => trim((string) $data['uuid_nama_keterangan']),
            'nama_keterangan' => trim((string) $data['nama_keterangan']),
            'status_keterangan' => trim((string) $data['status_keterangan']),
            'status_labarugi' => trim((string) $data['status_labarugi']),
            'nama_group' => isset($data['nama_group']) && trim((string) $data['nama_group']) !== ''
                ? trim((string) $data['nama_group'])
                : null,
            'keterangan' => isset($data['keterangan']) && trim((string) $data['keterangan']) !== ''
                ? trim((string) $data['keterangan'])
                : null,
            'date_update' => $now,
        );

        if ($payload['uuid_nama_keterangan'] === '') {
            $payload['uuid_nama_keterangan'] = labarugi_keterangan_generate_uuid();
        }

        if ($id > 0) {
            $existing = $this->get_by_id($id);
            if (!$existing) {
                return array('ok' => false, 'message' => 'Data tidak ditemukan.');
            }
            if ($this->uuid_exists($payload['uuid_nama_keterangan'], $payload['status_labarugi'], $id)) {
                return array('ok' => false, 'message' => 'UUID keterangan sudah digunakan untuk status laba rugi ini.');
            }
            $this->db->where($this->id, $id);
            $this->db->update($this->table, $payload);
            return array('ok' => true, 'message' => 'Data berhasil diupdate.', 'id' => $id);
        }

        if ($this->uuid_exists($payload['uuid_nama_keterangan'], $payload['status_labarugi'])) {
            return array('ok' => false, 'message' => 'UUID keterangan sudah digunakan untuk status laba rugi ini.');
        }

        $payload['urutan'] = $this->next_urutan($payload['status_labarugi']);
        $payload['date_input'] = $now;
        $this->db->insert($this->table, $payload);
        return array(
            'ok' => true,
            'message' => 'Data berhasil disimpan.',
            'id' => (int) $this->db->insert_id(),
        );
    }
}
