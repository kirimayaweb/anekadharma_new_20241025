<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pembelian_pecah_satuan_model extends CI_Model
{

    public $table = 'tbl_pembelian_pecah_satuan';
    public $id = 'id';
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
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('proses_input', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('uuid_pembelian', $q);
	$this->db->or_like('uuid_barang', $q);
	$this->db->or_like('tgl_po', $q);
	$this->db->or_like('nmrsj', $q);
	$this->db->or_like('nmrfakturkwitansi', $q);
	$this->db->or_like('nmrbpb', $q);
	$this->db->or_like('uuid_spop', $q);
	$this->db->or_like('spop', $q);
	$this->db->or_like('status_spop', $q);
	$this->db->or_like('uuid_supplier', $q);
	$this->db->or_like('supplier_kode', $q);
	$this->db->or_like('supplier_nama', $q);
	$this->db->or_like('kode_barang', $q);
	$this->db->or_like('uraian', $q);
	$this->db->or_like('jumlah', $q);
	$this->db->or_like('satuan', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('konsumen', $q);
	$this->db->or_like('uuid_gudang', $q);
	$this->db->or_like('nama_gudang', $q);
	$this->db->or_like('harga_satuan', $q);
	$this->db->or_like('harga_total', $q);
	$this->db->or_like('statuslu', $q);
	$this->db->or_like('kas_bank', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->or_like('tgl_pengajuan_1', $q);
	$this->db->or_like('nominal_pengajuan_1', $q);
	$this->db->or_like('tgl_pengajuan_2', $q);
	$this->db->or_like('nominal_pengajuan_2', $q);
	$this->db->or_like('uuid_gudang_baru', $q);
	$this->db->or_like('kode_barang_baru', $q);
	$this->db->or_like('nama_barang_baru', $q);
	$this->db->or_like('nominal_bayar_input', $q);
	$this->db->or_like('satuan_barang_baru', $q);
	$this->db->or_like('harga_satuan_barang_baru', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('proses_input', $q);
	$this->db->or_like('date_input', $q);
	$this->db->or_like('uuid_pembelian', $q);
	$this->db->or_like('uuid_barang', $q);
	$this->db->or_like('tgl_po', $q);
	$this->db->or_like('nmrsj', $q);
	$this->db->or_like('nmrfakturkwitansi', $q);
	$this->db->or_like('nmrbpb', $q);
	$this->db->or_like('uuid_spop', $q);
	$this->db->or_like('spop', $q);
	$this->db->or_like('status_spop', $q);
	$this->db->or_like('uuid_supplier', $q);
	$this->db->or_like('supplier_kode', $q);
	$this->db->or_like('supplier_nama', $q);
	$this->db->or_like('kode_barang', $q);
	$this->db->or_like('uraian', $q);
	$this->db->or_like('jumlah', $q);
	$this->db->or_like('satuan', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('konsumen', $q);
	$this->db->or_like('uuid_gudang', $q);
	$this->db->or_like('nama_gudang', $q);
	$this->db->or_like('harga_satuan', $q);
	$this->db->or_like('harga_total', $q);
	$this->db->or_like('statuslu', $q);
	$this->db->or_like('kas_bank', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->or_like('tgl_pengajuan_1', $q);
	$this->db->or_like('nominal_pengajuan_1', $q);
	$this->db->or_like('tgl_pengajuan_2', $q);
	$this->db->or_like('nominal_pengajuan_2', $q);
	$this->db->or_like('uuid_gudang_baru', $q);
	$this->db->or_like('kode_barang_baru', $q);
	$this->db->or_like('nama_barang_baru', $q);
	$this->db->or_like('nominal_bayar_input', $q);
	$this->db->or_like('satuan_barang_baru', $q);
	$this->db->or_like('harga_satuan_barang_baru', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);


        
        $this->db->set('uuid_pecah_satuan', "replace(uuid(),'-','')", FALSE);
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

/* End of file Tbl_pembelian_pecah_satuan_model.php */
/* Location: ./application/models/Tbl_pembelian_pecah_satuan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-20 11:34:58 */
/* http://harviacode.com */