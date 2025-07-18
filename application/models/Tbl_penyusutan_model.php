<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penyusutan_model extends CI_Model
{

    public $table = 'tbl_penyusutan';
    public $id = 'id';
    public $tahun_transaksi = 'tahun_transaksi';
    public $bulan_transaksi = 'bulan_transaksi';
    public $group_kelompok_harta = 'group_kelompok_harta';
    public $uuid_penyusutan = 'uuid_penyusutan';
    public $order = 'DESC';
    public $orderASC = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        $this->datatables->select('id,uuid_penyusutan,kelompok_harta,tanggal_perolehan,harga_perolehan,user,armorst_penyusutan_thn_lalu,nilai_buku_thn_lalu,penyusutan_bulan_ini,armorst_penyusutan_bulan_ini,nilai_buku_bulan_ini');
        $this->datatables->from('tbl_penyusutan');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_penyusutan.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_penyusutan/read/$1'), 'Read') . " | " . anchor(site_url('tbl_penyusutan/update/$1'), 'Update') . " | " . anchor(site_url('tbl_penyusutan/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
        return $this->datatables->generate();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->tahun_transaksi, $this->orderASC);
        $this->db->order_by($this->bulan_transaksi, $this->orderASC);
        $this->db->order_by($this->group_kelompok_harta, $this->orderASC);
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get data by id
    function get_by_uuid_penyusutan($uuid_penyusutan)
    {
        $this->db->where($this->uuid_penyusutan, $uuid_penyusutan);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('uuid_penyusutan', $q);
        $this->db->or_like('kelompok_harta', $q);
        $this->db->or_like('tanggal_perolehan', $q);
        $this->db->or_like('harga_perolehan', $q);
        $this->db->or_like('user', $q);
        $this->db->or_like('armorst_penyusutan_thn_lalu', $q);
        $this->db->or_like('nilai_buku_thn_lalu', $q);
        $this->db->or_like('penyusutan_bulan_ini', $q);
        $this->db->or_like('armorst_penyusutan_bulan_ini', $q);
        $this->db->or_like('nilai_buku_bulan_ini', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_penyusutan', $q);
        $this->db->or_like('kelompok_harta', $q);
        $this->db->or_like('tanggal_perolehan', $q);
        $this->db->or_like('harga_perolehan', $q);
        $this->db->or_like('user', $q);
        $this->db->or_like('armorst_penyusutan_thn_lalu', $q);
        $this->db->or_like('nilai_buku_thn_lalu', $q);
        $this->db->or_like('penyusutan_bulan_ini', $q);
        $this->db->or_like('armorst_penyusutan_bulan_ini', $q);
        $this->db->or_like('nilai_buku_bulan_ini', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {

        // print_r("insert");
        // print_r("<br/>");
        // print_r($data);
        // print_r("<br/>");

        // $this->db->insert($this->table, $data);

        $this->db->set('uuid_penyusutan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );

        // print_r($datainsert);
        // die;
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

/* End of file Tbl_penyusutan_model.php */
/* Location: ./application/models/Tbl_penyusutan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 09:40:17 */
/* http://harviacode.com */