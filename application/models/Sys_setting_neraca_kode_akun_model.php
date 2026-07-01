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
        $this->ensure_table();
        $this->seed_default_data();
    }

    public function ensure_table()
    {
        if ($this->db->table_exists($this->table)) {
            return true;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid_setting` varchar(32) DEFAULT NULL,
            `field_neraca` varchar(100) NOT NULL COMMENT 'nama field di tbl_neraca_data',
            `label_neraca` varchar(255) DEFAULT NULL COMMENT 'label pos neraca',
            `kode_akun` varchar(20) NOT NULL,
            `date_input` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_field_kode_akun` (`field_neraca`, `kode_akun`),
            KEY `idx_field_neraca` (`field_neraca`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        return (bool) $this->db->query($sql);
    }

    public function seed_default_data()
    {
        if (!$this->db->table_exists($this->table)) {
            return;
        }

        static $seed_checked = false;
        if ($seed_checked) {
            return;
        }
        $seed_checked = true;

        if ((int) $this->db->count_all($this->table) > 0) {
            return;
        }

        $this->insert(array(
            'field_neraca' => 'kas',
            'label_neraca' => 'Kas',
            'kode_akun' => '11101',
        ));

        foreach ($this->_default_seed_map() as $field_neraca => $config) {
            $rows = $this->db
                ->where('group', $config['group'])
                ->order_by('kode_akun', 'ASC')
                ->get('sys_kode_akun')
                ->result();

            foreach ($rows as $row) {
                $this->insert(array(
                    'field_neraca' => $field_neraca,
                    'label_neraca' => $config['label'],
                    'kode_akun' => $row->kode_akun,
                ));
            }
        }
    }

    private function _default_seed_map()
    {
        return array(
            'bank' => array('label' => 'Bank', 'group' => 'BANK'),
            'utang_usaha' => array('label' => 'Utang Usaha', 'group' => 'utang_usaha'),
            'utang_pajak' => array('label' => 'Utang Pajak', 'group' => 'utang_usaha'),
            'piutang_usaha' => array('label' => 'Piutang Usaha', 'group' => 'PIUTANGUSAHA'),
            'utang_lain_lain' => array('label' => 'Utang Lain-lain', 'group' => 'utang_lain_lain'),
            'piutang_non_usaha' => array('label' => 'Piutang Non Usaha', 'group' => 'PIUTANGUSAHA'),
            'persediaan' => array('label' => 'Persediaan', 'group' => 'PERSEDIAAN'),
            'uang_muka_pajak' => array('label' => 'Uang Muka Pajak', 'group' => 'uang_muka_pajak'),
            'aktiva_tetap_berwujud' => array('label' => 'Aktiva Tetap Berwujud', 'group' => 'aktiva_tetap_berwujud'),
            'utang_afiliasi' => array('label' => 'Utang Afiliasi', 'group' => 'utang_afiliasi'),
            'akumulasi_depresiasi_atb' => array('label' => 'Akumulasi Depresiasi ATB', 'group' => 'akumulasi_depresiasi_atb'),
            'piutang_non_usaha_pihak_ketiga' => array('label' => 'Piutang Non Usaha Pihak Ketiga', 'group' => 'PIUTANGNONUSAHAPIHAKKETIGA'),
            'modal_dasar_dan_penyertaan' => array('label' => 'Modal Dasar dan Penyertaan', 'group' => 'modal_dasar_dan_penyertaan'),
            'piutang_non_usaha_radio' => array('label' => 'Piutang Non Usaha Radio', 'group' => 'PIUTANGNONUSAHARADIO'),
            'cadangan_umum' => array('label' => 'Cadangan Umum', 'group' => 'cadangan_umum'),
            'ljpj_taman_gedung_kesenian_gabusan' => array('label' => 'LJPJ Taman Gedung Kesenian Gabusan', 'group' => 'ljpj_taman_gedung_kesenian_gabusan'),
            'laba_bumd_pad' => array('label' => 'Laba BUMD PAD', 'group' => 'laba_bumd_pad'),
            'ljpj_kompleks_gedung_kesenian' => array('label' => 'LJPJ Kompleks Gedung Kesenian', 'group' => 'ljpj_kompleks_gedung_kesenian'),
            'ljpj_radio' => array('label' => 'LJPJ Radio', 'group' => 'ljpj_radio'),
            'laba_rugi_tahun_lalu' => array('label' => 'Laba Rugi Tahun Lalu', 'group' => 'laba_rugi_tahun_lalu'),
            'ljpj_kerjasama_operasi_apotek_dharma_usaha' => array('label' => 'LJPJ Kerjasama Operasi Apotek Dharma Usaha', 'group' => 'ljpj_kerjasama_operasi_apotek_dharma_usaha'),
            'laba_rugi_tahun_berjalan' => array('label' => 'Laba Rugi Tahun Berjalan', 'group' => 'laba_rugi_tahun_berjalan'),
            'ljpj_peternakan' => array('label' => 'LJPJ Peternakan', 'group' => 'ljpj_peternakan'),
            'ljpj_kerjasama_adwm' => array('label' => 'LJPJ Kerjasama ADWM', 'group' => 'ljpj_kerjasama_adwm'),
            'ljpj_kerjasama_pdu_cabean_panggungharjo' => array('label' => 'LJPJ Kerjasama PDU Cabean Panggung Harjo', 'group' => 'ljpj_kerjasama_pdu_cabean_panggungharjo'),
        );
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
