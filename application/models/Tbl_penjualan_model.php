<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penjualan_model extends CI_Model
{

    public $table = 'tbl_penjualan';
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

    // get all
    function get_all()
    {
        $this->db->order_by($this->tgl_jual, $this->order);
        // $this->db->order_by($this->id, $this->orderASC);
        $this->db->order_by($this->tgl_jual, $this->order);
        $this->db->order_by($this->uuid_penjualan, $this->orderASC);
        return $this->db->get($this->table)->result();
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

    function get_all_by_uuid_penjualan_tgl_jual_nmrkirim($uuid_penjualan=null,$tgl_jual=null,$nmrkirim=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->where($this->tgl_jual, $tgl_jual);
        $this->db->where($this->nmrkirim, $nmrkirim);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }
   

   

    function get_all_by_tgl_jual_nmrkirim($tgl_jual=null,$nmrkirim=null)
    {
        $this->db->where($this->tgl_jual, $tgl_jual);
        $this->db->where($this->nmrkirim, $nmrkirim);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }
   

    function get_all_by_uuid_penjualan($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }
   

    function get_ROW_by_uuid_penjualan_first_row($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }
   

    function get_all_by_uuid_penjualan_tgl_jual_nmrkirim_first_row($uuid_penjualan=null,$tgl_jual=null,$nmrkirim=null)
    {

       

        // $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->where($this->tgl_jual, $tgl_jual);
        $this->db->where($this->nmrkirim, $nmrkirim);
        $this->db->order_by($this->id, $this->orderASC);
       
        // print_r($this->db->get($this->table)->row());
        // die;
       
        return $this->db->get($this->table)->row();
    }



    function get_all_by_tgl_jual_nmrkirim_first_row($tgl_jual=null,$nmrkirim=null)
    {

    //     print_r("MODEL");
    //     print_r("<br/>");
    //    print_r($tgl_jual);
    //    print_r("<br/>");
    //    print_r($nmrkirim);
    //    print_r("<br/>");
    //    print_r("<br/>");

        // $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->where($this->tgl_jual, $tgl_jual);
        $this->db->where($this->nmrkirim, $nmrkirim);
        $this->db->order_by($this->id, $this->orderASC);
       
        // print_r($this->db->get($this->table)->row());
        // die;
       
        return $this->db->get($this->table)->row();
    }

    function get_all_by_uuid_penjualan_first_row($uuid_penjualan=null)
    {
        $this->db->where($this->uuid_penjualan, $uuid_penjualan);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }

    function get_all_by_uuid_penjualan_proses($uuid_penjualan_proses=null)
    {
        $this->db->where($this->uuid_penjualan_proses, $uuid_penjualan_proses);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->row();
    }


    function get_all_by_uuid_konsumen($uuid_konsumen=null)
    {
        $this->db->where($this->uuid_konsumen, $uuid_konsumen);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    function konsumen_tagihan()
    {
        $sql = "SELECT 
        -- tbl_penjualan_a.tgl_po as tgl_po,
        tbl_penjualan_a.uuid_konsumen as uuid_konsumen,
        tbl_penjualan_a.konsumen_nama as nama_konsumen,
        tbl_penjualan_a.jumlah as jumlah_belanja,
        tbl_penjualan_a.harga_satuan as harga_satuan_beli,
        (tbl_penjualan_a.jumlah*tbl_penjualan_a.harga_satuan) as total_belanja,
        sys_konsumen_a.nama_konsumen as nama_konsumen_1
       
        FROM tbl_penjualan tbl_penjualan_a 

        left join   sys_konsumen  sys_konsumen_a ON  sys_konsumen_a.uuid_konsumen = tbl_penjualan_a.uuid_konsumen

        group by sys_konsumen_a.nama_konsumen
        order by sys_konsumen_a.nama_konsumen ASC
        ";

        return $this->db->query($sql)->result();
    }


    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('tgl_input', $q);
        $this->db->or_like('nmrpesan', $q);
        $this->db->or_like('nmrkirim', $q);
        $this->db->or_like('konsumen_id', $q);
        $this->db->or_like('konsumen_nama', $q);
        $this->db->or_like('kode_barang', $q);
        $this->db->or_like('nama_barang', $q);
        $this->db->or_like('unit', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('harga_satuan', $q);
        $this->db->or_like('jumlah', $q);
        $this->db->or_like('umpphpsl22', $q);
        $this->db->or_like('piutang', $q);
        $this->db->or_like('penjualandpp', $q);
        $this->db->or_like('utangppn', $q);
        $this->db->or_like('id_usr', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('tgl_input', $q);
        $this->db->or_like('nmrpesan', $q);
        $this->db->or_like('nmrkirim', $q);
        $this->db->or_like('konsumen_id', $q);
        $this->db->or_like('konsumen_nama', $q);
        $this->db->or_like('kode_barang', $q);
        $this->db->or_like('nama_barang', $q);
        $this->db->or_like('unit', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('harga_satuan', $q);
        $this->db->or_like('jumlah', $q);
        $this->db->or_like('umpphpsl22', $q);
        $this->db->or_like('piutang', $q);
        $this->db->or_like('penjualandpp', $q);
        $this->db->or_like('utangppn', $q);
        $this->db->or_like('id_usr', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        // $this->db->insert($this->table, $data);

        $this->db->set('uuid_penjualan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );
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


    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // update data
    function update_proses($uuid_penjualan_proses, $data)
    {

        // print_r($uuid_penjualan_proses);
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r($data);
        // die;

        $this->db->where($this->uuid_penjualan_proses, $uuid_penjualan_proses);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}

/* End of file Tbl_penjualan_model.php */
/* Location: ./application/models/Tbl_penjualan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:59:44 */
/* http://harviacode.com */