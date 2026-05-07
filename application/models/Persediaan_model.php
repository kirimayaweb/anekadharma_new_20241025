<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Persediaan_model extends CI_Model
{

    public $table = 'persediaan';
    public $id = 'id';
    public $uuid_spop = 'uuid_spop';
    public $uuid_barang = 'uuid_barang';
    public $uuid_persediaan = 'uuid_persediaan';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        $this->datatables->select('id,tanggal,kode,namabarang,satuan,hpp,sa,spop,beli,tuj,tgl_keluar,sekret,cetak,grafikita,dinas_umum,atk_rsud,ppbmp_kbs,kbs,ppbmp,medis,siiplah_bosda,sembako,fc_gose,fc_manding,fc_psamya,total_10,nilai_persediaan');
        $this->datatables->from('persediaan');
        //add this line for join
        //$this->datatables->join('table2', 'persediaan.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('persediaan/read/$1'), 'Read') . " | " . anchor(site_url('persediaan/update/$1'), 'Update') . " | " . anchor(site_url('persediaan/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), '');
        return $this->datatables->generate();
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

    // get data by id
    function get_by_uuidspop_uuid_barang($uuid_spop, $uuid_barang)
    {
        $this->db->where($this->uuid_spop, $uuid_spop);
        $this->db->where($this->uuid_barang, $uuid_barang);
        return $this->db->get($this->table)->row();
    }

    function get_by_uuid_persediaan($uuid_persediaan)
    {
        $this->db->where($this->uuid_persediaan, $uuid_persediaan);
        return $this->db->get($this->table)->row();
    }

    // get data by id
    /**
     * Filter persediaan by year-month from HTML5 month input (YYYY-MM).
     * Kolom tanggal di DB bisa varchar d/m/Y, Y-m-d, atau d-m-Y.
     */
    function get_by_year_month($ym)
    {
        $ym = trim((string) $ym);
        if ($ym === '') {
            return $this->get_all();
        }
        $ts = strtotime($ym . '-01');
        if ($ts === false) {
            return $this->get_all();
        }
        $start = date('Y-m-01', $ts);
        $end = date('Y-m-t', $ts);
        $sql = "SELECT * FROM `persediaan`
            WHERE COALESCE(
                STR_TO_DATE(`tanggal`, '%d/%m/%Y'),
                STR_TO_DATE(`tanggal`, '%e/%c/%Y'),
                STR_TO_DATE(`tanggal`, '%Y-%m-%d'),
                STR_TO_DATE(`tanggal`, '%d-%m-%Y')
            ) BETWEEN ? AND ?
            ORDER BY `id` DESC";
        return $this->db->query($sql, array($start, $end))->result();
    }

    function get_by_persediaan_month($tgl_jual)
    {
        $ts = strtotime((string) $tgl_jual);
        if ($ts === false) {
            return array();
        }
        return $this->get_by_year_month(date('Y-m', $ts));
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('', $q);
        $this->db->or_like('id', $q);
        $this->db->or_like('tanggal', $q);
        $this->db->or_like('kode', $q);
        $this->db->or_like('namabarang', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('hpp', $q);
        $this->db->or_like('sa', $q);
        $this->db->or_like('spop', $q);
        $this->db->or_like('beli', $q);
        $this->db->or_like('tuj', $q);
        $this->db->or_like('tgl_keluar', $q);
        $this->db->or_like('sekret', $q);
        $this->db->or_like('cetak', $q);
        $this->db->or_like('grafikita', $q);
        $this->db->or_like('dinas_umum', $q);
        $this->db->or_like('atk_rsud', $q);
        $this->db->or_like('ppbmp_kbs', $q);
        $this->db->or_like('kbs', $q);
        $this->db->or_like('ppbmp', $q);
        $this->db->or_like('medis', $q);
        $this->db->or_like('siiplah_bosda', $q);
        $this->db->or_like('sembako', $q);
        $this->db->or_like('fc_gose', $q);
        $this->db->or_like('fc_manding', $q);
        $this->db->or_like('fc_psamya', $q);
        $this->db->or_like('total_10', $q);
        $this->db->or_like('nilai_persediaan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('', $q);
        $this->db->or_like('id', $q);
        $this->db->or_like('tanggal', $q);
        $this->db->or_like('kode', $q);
        $this->db->or_like('namabarang', $q);
        $this->db->or_like('satuan', $q);
        $this->db->or_like('hpp', $q);
        $this->db->or_like('sa', $q);
        $this->db->or_like('spop', $q);
        $this->db->or_like('beli', $q);
        $this->db->or_like('tuj', $q);
        $this->db->or_like('tgl_keluar', $q);
        $this->db->or_like('sekret', $q);
        $this->db->or_like('cetak', $q);
        $this->db->or_like('grafikita', $q);
        $this->db->or_like('dinas_umum', $q);
        $this->db->or_like('atk_rsud', $q);
        $this->db->or_like('ppbmp_kbs', $q);
        $this->db->or_like('kbs', $q);
        $this->db->or_like('ppbmp', $q);
        $this->db->or_like('medis', $q);
        $this->db->or_like('siiplah_bosda', $q);
        $this->db->or_like('sembako', $q);
        $this->db->or_like('fc_gose', $q);
        $this->db->or_like('fc_manding', $q);
        $this->db->or_like('fc_psamya', $q);
        $this->db->or_like('total_10', $q);
        $this->db->or_like('nilai_persediaan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );
    }

    // insert data
    function insert_produk_baru($data)
    {
        $this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );

        return $datainsert['id'];
    }
    // insert data
    function insert_pecah_satuan($data)
    {
        $this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
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

        // print_r("update");
        // print_r("<br/>");
        // print_r($id);
        // print_r("<br/>");
        // print_r($data);
        // // die;

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

/* End of file Persediaan_model.php */
/* Location: ./application/models/Persediaan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 04:04:45 */
/* http://harviacode.com */