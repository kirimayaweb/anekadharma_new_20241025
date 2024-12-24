<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pembelian_model extends CI_Model
{

    public $table = 'tbl_pembelian';
    public $id = 'id';
    public $uuid_spop = 'uuid_spop';
    public $uuid_konsumen = 'uuid_konsumen';
    public $uuid_pembelian = 'uuid_pembelian';
    public $spop = 'spop';
    public $tgl_po = 'tgl_po';
    public $kas_bank = 'kas_bank';
    public $order = 'DESC';
    public $orderASC = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        $this->datatables->select('id,tgl_po,nmrsj,nmrfakturkwitansi,nmrbpb,spop,supplier_kode,supplier_nama,uraian,jumlah,satuan,konsumen,harga_satuan,harga_total,statuslu,kas_bank,tgl_bayar,id_usr');
        $this->datatables->from('tbl_pembelian');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_pembelian.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_pembelian/read/$1'), 'Read') . " | " . anchor(site_url('tbl_pembelian/update/$1'), 'Update') . " | " . anchor(site_url('tbl_pembelian/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
        return $this->datatables->generate();
    }


    function stock()
    {

        $sql = "SELECT 
        tbl_pembelian_a.id as id_pembelian,
        tbl_pembelian_a.uuid_pembelian as uuid_pembelian,
        tbl_pembelian_a.tgl_po as tgl_po,
        tbl_pembelian_a.kode_barang as kode_barang,
        tbl_pembelian_a.uraian as nama_barang_beli,
        tbl_pembelian_a.satuan as satuan,
        tbl_pembelian_a.jumlah as jumlah_belanja,
        tbl_pembelian_a.harga_satuan as harga_satuan_beli,
        tbl_pembelian_a.uuid_gudang as uuid_gudang,
        tbl_pembelian_a.nama_gudang as nama_gudang,

        -- tbl_penjualan_a.nama_barang as nama_barang_jual,
        sum(tbl_penjualan_a.jumlah) as jumlah_terjual,
        -- IFNULL(sum(tbl_penjualan_a.jumlah), 0) as jmlh_jual,
       
        tbl_pembelian_a.supplier_nama as nama_supplier

        FROM tbl_pembelian tbl_pembelian_a 
        -- FROM tbl_penjualan tbl_penjualan_a 

        left join   tbl_penjualan  tbl_penjualan_a ON  tbl_penjualan_a.uuid_barang = tbl_pembelian_a.uuid_pembelian

        group by tbl_pembelian_a.kode_barang,tbl_pembelian_a.tgl_po,tbl_pembelian_a.uraian,tbl_pembelian_a.uuid_gudang
        order by tbl_pembelian_a.uraian,tbl_pembelian_a.tgl_po ASC
        ";

        return $this->db->query($sql)->result();
    }

    function stock_by_gudang($uuid_gudang)
    {

        $sql = "SELECT 
        tbl_pembelian_a.id as id_pembelian,
        tbl_pembelian_a.uuid_pembelian as uuid_pembelian,
        tbl_pembelian_a.tgl_po as tgl_po,
        tbl_pembelian_a.kode_barang as kode_barang,
        tbl_pembelian_a.uraian as nama_barang_beli,
        tbl_pembelian_a.satuan as satuan,
        tbl_pembelian_a.jumlah as jumlah_belanja,
        tbl_pembelian_a.harga_satuan as harga_satuan_beli,
        tbl_pembelian_a.uuid_gudang as uuid_gudang,
        tbl_pembelian_a.nama_gudang as nama_gudang,

        -- tbl_penjualan_a.nama_barang as nama_barang_jual,
        sum(tbl_penjualan_a.jumlah) as jumlah_terjual,
        -- IFNULL(sum(tbl_penjualan_a.jumlah), 0) as jmlh_jual,
       
        tbl_pembelian_a.supplier_nama as nama_supplier

        FROM tbl_pembelian tbl_pembelian_a 
        -- FROM tbl_penjualan tbl_penjualan_a 

        left join   tbl_penjualan  tbl_penjualan_a ON  tbl_penjualan_a.uuid_barang = tbl_pembelian_a.uuid_pembelian

        where tbl_pembelian_a.uuid_gudang='$uuid_gudang'

        group by tbl_pembelian_a.kode_barang,tbl_pembelian_a.tgl_po,tbl_pembelian_a.uraian,tbl_pembelian_a.uuid_gudang
        order by tbl_pembelian_a.uraian,tbl_pembelian_a.tgl_po ASC
        ";

        return $this->db->query($sql)->result();
    }

    function supplier_tagihan()
    {
        $sql = "SELECT 
        -- tbl_pembelian_a.tgl_po as tgl_po,
        tbl_pembelian_a.supplier_nama as nama_supplier,
        tbl_pembelian_a.jumlah as jumlah_belanja,
        tbl_pembelian_a.harga_satuan as harga_satuan_beli,
        (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
        sys_supplier_a.nama_supplier as nama_supplier_1
       

        FROM tbl_pembelian tbl_pembelian_a 

        left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama

        group by sys_supplier_a.nama_supplier
        order by sys_supplier_a.nama_supplier ASC
        ";


        return $this->db->query($sql)->result();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->tgl_po, $this->order);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    function get_kas_kecil()
    {
        $kas_bank="kas";
        $this->db->order_by($this->tgl_po, $this->order);
        $this->db->order_by($this->id, $this->orderASC);
        $this->db->where($this->kas_bank, $kas_bank);
        return $this->db->get($this->table)->result();
    }



    
    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }


    // get data by uuid_spop
    function get_by_uuid_spop($uuid_spop)
    {
        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }
    // get data by uuid_spop
    function get_by_uuid_spop_ALL_result($uuid_spop)
    {
        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    function get_total_nominal_per_spop($uuid_spop)
    {


        $sql = "SELECT tbl_pembelian_a.uuid_spop as uuid_spop, 
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.jumlah as jumlah,
        tbl_pembelian_a.harga_satuan as harga_satuan,
        tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan as total_pembelian,
        tbl_pembelian_a.supplier_nama as supplier_nama
        
        FROM tbl_pembelian tbl_pembelian_a 
        
        -- group by tbl_pembelian_a.uuid_spop 
        where tbl_pembelian_a.uuid_spop = '$uuid_spop'
        ";

        // return $this->db->query($sql)->row_array();
        return $this->db->query($sql)->result();
    }

    function get_by_spop($spop)
    {
        $this->db->where($this->spop, $spop);
        return $this->db->get($this->table)->result();
    }


    function get_by_uuid_konsumen($uuid_konsumen)
    {
        $this->db->where($this->uuid_konsumen, $uuid_konsumen);
        return $this->db->get($this->table)->result();
    }


    function get_by_uuid_pembelian($uuid_pembelian)
    {
        $this->db->where($this->uuid_pembelian, $uuid_pembelian);
        return $this->db->get($this->table)->row();
    }



    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('tgl_po', $q);
        $this->db->or_like('nmrsj', $q);
        $this->db->or_like('nmrfakturkwitansi', $q);
        $this->db->or_like('nmrbpb', $q);
        $this->db->or_like('spop', $q);
        $this->db->or_like('supplier_kode', $q);
        $this->db->or_like('supplier_nama', $q);
        $this->db->or_like('uraian', $q);
        $this->db->or_like('jumlah', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('konsumen', $q);
        $this->db->or_like('harga_satuan', $q);
        $this->db->or_like('harga_total', $q);
        $this->db->or_like('statuslu', $q);
        $this->db->or_like('kas_bank', $q);
        $this->db->or_like('tgl_bayar', $q);
        $this->db->or_like('id_usr', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('tgl_po', $q);
        $this->db->or_like('nmrsj', $q);
        $this->db->or_like('nmrfakturkwitansi', $q);
        $this->db->or_like('nmrbpb', $q);
        $this->db->or_like('spop', $q);
        $this->db->or_like('supplier_kode', $q);
        $this->db->or_like('supplier_nama', $q);
        $this->db->or_like('uraian', $q);
        $this->db->or_like('jumlah', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('konsumen', $q);
        $this->db->or_like('harga_satuan', $q);
        $this->db->or_like('harga_total', $q);
        $this->db->or_like('statuslu', $q);
        $this->db->or_like('kas_bank', $q);
        $this->db->or_like('tgl_bayar', $q);
        $this->db->or_like('id_usr', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);

        $this->db->set('uuid_pembelian', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );
    }

    // insert data
    function insert_spop($data)
    {
        // $this->db->insert($this->table, $data);

        $this->db->set('uuid_spop', "replace(uuid(),'-','')", FALSE);
        $this->db->set('uuid_pembelian', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );
        // print_r("<br/>");
        // print_r($datainsert['id']);
        // print_r("<br/>");
        $this->db->where($this->id, $datainsert['id']);
        $uuid_spop = $this->db->get($this->table)->row()->uuid_spop;
        // print_r($uuid_spop);
        return $uuid_spop;
        // die;
    }

    // update data
    function update_status_per_spop($uuid_spop, $data)
    {

        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->update($this->table, $data);
    }
    // update data
    function update_statuslu_per_spop($uuid_spop, $data)
    {

        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->update($this->table, $data);
    }

    // update data
    function update_proses_per_spop($uuid_spop, $data)
    {
        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->update($this->table, $data);
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

/* End of file Tbl_pembelian_model.php */
/* Location: ./application/models/Tbl_pembelian_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:57:23 */
/* http://harviacode.com */