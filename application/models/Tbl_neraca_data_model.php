<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_neraca_data_model extends CI_Model
{

    public $table = 'tbl_neraca_data';
    public $id = 'id';
    // public $tahun_transaksi = 'tahun_transaksi';
    public $uuid_data_neraca = 'uuid_data_neraca';
    public $tahun_transaksi = 'tahun_transaksi';
    public $bulan_transaksi = 'bulan_transaksi';
    public $order = 'DESC';
    public $orderASC = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->bulan_transaksi, $this->orderASC);
        $this->db->order_by($this->tahun_transaksi, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get all
    function get_all_by_year($tahun_transaksi)
    {
        $this->db->where($this->tahun_transaksi, $tahun_transaksi);
        return $this->db->get($this->table)->row();
    }
    // get all
    function get_all_by_MONTH_year($bulan_transaksi,$tahun_transaksi)
    {
        $this->db->where($this->bulan_transaksi, $bulan_transaksi);
        $this->db->where($this->tahun_transaksi, $tahun_transaksi);
        return $this->db->get($this->table)->row();
    }
    // get by uuid_data_neraca
    function get_all_by_uuid_data_neraca($uuid_data_neraca)
    {
        $this->db->where($this->uuid_data_neraca, $uuid_data_neraca);
        return $this->db->get($this->table)->row();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('date_transaksi', $q);
	$this->db->or_like('tahun_transaksi', $q);
	$this->db->or_like('bulan_transaksi', $q);
	$this->db->or_like('uuid_data_neraca', $q);
	$this->db->or_like('kas', $q);
	$this->db->or_like('bank', $q);
	$this->db->or_like('piutang_usaha', $q);
	$this->db->or_like('piutang_non_usaha', $q);
	$this->db->or_like('persediaan', $q);
	$this->db->or_like('uang_muka_pajak', $q);
	$this->db->or_like('aktiva_tetap', $q);
	$this->db->or_like('aktiva_tetap_berwujud', $q);
	$this->db->or_like('akumulasi_depresiasi_atb', $q);
	$this->db->or_like('piutang_non_usaha_pihak_ketiga', $q);
	$this->db->or_like('piutang_non_usaha_radio', $q);
	$this->db->or_like('ljpj_taman_gedung_kesenian_gabusan', $q);
	$this->db->or_like('ljpj_kompleks_gedung_kesenian', $q);
	$this->db->or_like('ljpj_radio', $q);
	$this->db->or_like('ljpj_kerjasama_operasi_apotek_dharma_usaha', $q);
	$this->db->or_like('ljpj_peternakan', $q);
	$this->db->or_like('ljpj_kerjasama_adwm', $q);
	$this->db->or_like('ljpj_kerjasama_pdu_cabean_panggungharjo', $q);
	$this->db->or_like('utang_usaha', $q);
	$this->db->or_like('utang_pajak', $q);
	$this->db->or_like('utang_lain_lain', $q);
	$this->db->or_like('utang_afiliasi', $q);
	$this->db->or_like('modal_dasar_dan_penyertaan', $q);
	$this->db->or_like('cadangan_umum', $q);
	$this->db->or_like('laba_bumd_pad', $q);
	$this->db->or_like('laba_rugi_tahun_lalu', $q);
	$this->db->or_like('laba_rugi_tahun_berjalan', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('date_transaksi', $q);
	$this->db->or_like('tahun_transaksi', $q);
	$this->db->or_like('bulan_transaksi', $q);
	$this->db->or_like('uuid_data_neraca', $q);
	$this->db->or_like('kas', $q);
	$this->db->or_like('bank', $q);
	$this->db->or_like('piutang_usaha', $q);
	$this->db->or_like('piutang_non_usaha', $q);
	$this->db->or_like('persediaan', $q);
	$this->db->or_like('uang_muka_pajak', $q);
	$this->db->or_like('aktiva_tetap', $q);
	$this->db->or_like('aktiva_tetap_berwujud', $q);
	$this->db->or_like('akumulasi_depresiasi_atb', $q);
	$this->db->or_like('piutang_non_usaha_pihak_ketiga', $q);
	$this->db->or_like('piutang_non_usaha_radio', $q);
	$this->db->or_like('ljpj_taman_gedung_kesenian_gabusan', $q);
	$this->db->or_like('ljpj_kompleks_gedung_kesenian', $q);
	$this->db->or_like('ljpj_radio', $q);
	$this->db->or_like('ljpj_kerjasama_operasi_apotek_dharma_usaha', $q);
	$this->db->or_like('ljpj_peternakan', $q);
	$this->db->or_like('ljpj_kerjasama_adwm', $q);
	$this->db->or_like('ljpj_kerjasama_pdu_cabean_panggungharjo', $q);
	$this->db->or_like('utang_usaha', $q);
	$this->db->or_like('utang_pajak', $q);
	$this->db->or_like('utang_lain_lain', $q);
	$this->db->or_like('utang_afiliasi', $q);
	$this->db->or_like('modal_dasar_dan_penyertaan', $q);
	$this->db->or_like('cadangan_umum', $q);
	$this->db->or_like('laba_bumd_pad', $q);
	$this->db->or_like('laba_rugi_tahun_lalu', $q);
	$this->db->or_like('laba_rugi_tahun_berjalan', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);

		$this->db->set('uuid_data_neraca', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );

         return $datainsert['id'];
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }
    // update data
    function update_by_uuid_data_neraca($uuid_data_neraca, $data)
    {
        $this->db->where($this->uuid_data_neraca, $uuid_data_neraca);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Tbl_neraca_data_model.php */
/* Location: ./application/models/Tbl_neraca_data_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-28 09:33:24 */
/* http://harviacode.com */