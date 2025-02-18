<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penjualan_accounting_model extends CI_Model
{

    public $table = 'tbl_penjualan_accounting';
	public $id = 'id';
    public $tgl_jual = 'tgl_jual';
    public $nmrpesan = 'nmrpesan';
    public $nmrkirim = 'nmrkirim';
    public $uuid_penjualan_proses = 'uuid_penjualan_proses';
    public $uuid_penjualan = 'uuid_penjualan';
    public $uuid_konsumen = 'uuid_konsumen';
    public $order = 'DESC';
    public $orderASC = 'ASC';

	
    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,uuid_penjualan_proses,uuid_penjualan,uuid_persediaan,id_persediaan_barang,uuid_barang,tgl_input,tgl_jual,nmrpesan,nmrkirim,uuid_konsumen,konsumen_id,konsumen_nama,kode_barang,nama_barang,uuid_unit,unit,satuan,harga_satuan,jumlah,total_nominal,umpphpsl22,piutang,penjualandpp,utangppn,cetak_bukti_penjualan,id_usr,proses_bayar,tgl_bayar_input,tgl_bayar,nmr_bukti_bayar,kode_akun,proses_transaksi');
        $this->datatables->from('tbl_penjualan_accounting');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_penjualan_accounting.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_penjualan_accounting/read/$1'),'Read')." | ".anchor(site_url('tbl_penjualan_accounting/update/$1'),'Update')." | ".anchor(site_url('tbl_penjualan_accounting/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
        return $this->datatables->generate();
    }

    // get all
    function get_all_group_by_tgl_jual_nmrpesan_nmr_kirim()
    {

        // $this->db->group_by($this->tgl_jual, $this->orderASC);
        // $this->db->group_by($this->nmrpesan, $this->orderASC);
        // $this->db->group_by($this->nmrkirim, $this->orderASC);

        $this->db->order_by($this->tgl_jual, $this->order);
        // $this->db->order_by($this->nmrpesan, $this->orderASC);
        $this->db->order_by($this->nmrkirim, $this->orderASC);
        // $this->db->order_by($this->uuid_penjualan, $this->orderASC);

        return $this->db->get($this->table)->result();
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
	$this->db->or_like('uuid_penjualan_proses', $q);
	$this->db->or_like('uuid_penjualan', $q);
	$this->db->or_like('uuid_persediaan', $q);
	$this->db->or_like('id_persediaan_barang', $q);
	$this->db->or_like('uuid_barang', $q);
	$this->db->or_like('tgl_input', $q);
	$this->db->or_like('tgl_jual', $q);
	$this->db->or_like('nmrpesan', $q);
	$this->db->or_like('nmrkirim', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('konsumen_id', $q);
	$this->db->or_like('konsumen_nama', $q);
	$this->db->or_like('kode_barang', $q);
	$this->db->or_like('nama_barang', $q);
	$this->db->or_like('uuid_unit', $q);
	$this->db->or_like('unit', $q);
	$this->db->or_like('satuan', $q);
	$this->db->or_like('harga_satuan', $q);
	$this->db->or_like('jumlah', $q);
	$this->db->or_like('total_nominal', $q);
	$this->db->or_like('umpphpsl22', $q);
	$this->db->or_like('piutang', $q);
	$this->db->or_like('penjualandpp', $q);
	$this->db->or_like('utangppn', $q);
	$this->db->or_like('cetak_bukti_penjualan', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->or_like('proses_bayar', $q);
	$this->db->or_like('tgl_bayar_input', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('nmr_bukti_bayar', $q);
	$this->db->or_like('kode_akun', $q);
	$this->db->or_like('proses_transaksi', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('uuid_penjualan_proses', $q);
	$this->db->or_like('uuid_penjualan', $q);
	$this->db->or_like('uuid_persediaan', $q);
	$this->db->or_like('id_persediaan_barang', $q);
	$this->db->or_like('uuid_barang', $q);
	$this->db->or_like('tgl_input', $q);
	$this->db->or_like('tgl_jual', $q);
	$this->db->or_like('nmrpesan', $q);
	$this->db->or_like('nmrkirim', $q);
	$this->db->or_like('uuid_konsumen', $q);
	$this->db->or_like('konsumen_id', $q);
	$this->db->or_like('konsumen_nama', $q);
	$this->db->or_like('kode_barang', $q);
	$this->db->or_like('nama_barang', $q);
	$this->db->or_like('uuid_unit', $q);
	$this->db->or_like('unit', $q);
	$this->db->or_like('satuan', $q);
	$this->db->or_like('harga_satuan', $q);
	$this->db->or_like('jumlah', $q);
	$this->db->or_like('total_nominal', $q);
	$this->db->or_like('umpphpsl22', $q);
	$this->db->or_like('piutang', $q);
	$this->db->or_like('penjualandpp', $q);
	$this->db->or_like('utangppn', $q);
	$this->db->or_like('cetak_bukti_penjualan', $q);
	$this->db->or_like('id_usr', $q);
	$this->db->or_like('proses_bayar', $q);
	$this->db->or_like('tgl_bayar_input', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('nmr_bukti_bayar', $q);
	$this->db->or_like('kode_akun', $q);
	$this->db->or_like('proses_transaksi', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
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

    function insert_new($data)
    {

        // print_r("inser new");
        // die;

        // $this->db->insert($this->table, $data);

        // $this->db->set('uuid_spop', "replace(uuid(),'-','')", FALSE);
        // $this->db->set('uuid_pembelian', "replace(uuid(),'-','')", FALSE);
        // $this->db->insert($this->table, $data);

        // $datainsert = array(
        //     'PROCESS' => 'INSERT',
        //     'id' => $this->db->insert_id()
        // );


        $this->db->set('uuid_penjualan_proses', "replace(uuid(),'-','')", FALSE);
        $this->db->set('uuid_penjualan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );


        // print_r("<br/>");
        // print_r($datainsert['id']);
        // print_r("<br/>");
        $this->db->where($this->id, $datainsert['id']);
        $uuid_penjualan = $this->db->get($this->table)->row()->uuid_penjualan;
        // print_r($uuid_penjualan);
        // die;
        return $uuid_penjualan;
        // die;


    }

    function get_ROW_by_uuid_penjualan_first_row($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }
   

    function get_all_by_uuid_penjualan($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }
   

    function get_all_by_uuid_penjualan_first_row($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }

    function insert_add_barang($data)
    {

        // print_r("insert_add_barang");
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r($data);
        // die;

        $this->db->set('uuid_penjualan_proses', "replace(uuid(),'-','')", FALSE);
        // $this->db->set('uuid_penjualan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );


        // print_r("<br/>");
        // print_r($datainsert['id']);
        // print_r("<br/>");
        $this->db->where($this->id, $datainsert['id']);
        $uuid_penjualan = $this->db->get($this->table)->row()->uuid_penjualan;
        // print_r($uuid_penjualan);
        // die;
        return $uuid_penjualan;
        // die;


    }

}

/* End of file Tbl_penjualan_accounting_model.php */
/* Location: ./application/models/Tbl_penjualan_accounting_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 10:35:02 */
/* http://harviacode.com */