<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_produk_model extends CI_Model
{

    public $table_report_summary = 'report_summary';
    public $table_report_log = 'report_log';
    public $table = 'tbl_produk';
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
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('uuid_produk', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_produk', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }


    public function get_all_data_produk()
    {
        $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk tbl_produk_a

        group by tbl_produk_a.mapel
        order by tbl_produk_a.date_input desc
        ";


        return $this->db->query($sql)->result();
    }

    public function get_all_data_produk_by_tingkat($tingkatselected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk tbl_produk_a
where tbl_produk_a.tingkat = '$tingkatselected'
        group by tbl_produk_a.mapel
        order by tbl_produk_a.mapel asc
        ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk tbl_produk_a
-- where tbl_produk_a.tingkat = '$tingkatselected'
        group by tbl_produk_a.mapel
        order by tbl_produk_a.mapel asc
        ";
        }

        return $this->db->query($sql)->result();
    }

    public function get_all_tingkat()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a
        group by tbl_produk_mapel_referensi_a.tingkat
        order by tbl_produk_mapel_referensi_a.tingkat asc
        ";

        return $this->db->query($sql)->result();
    }

    public function get_all_data_produk_mapel_reference_by_tingkat($tingkatselected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                    tbl_produk_a.uuid_produk as uuid_produk,
                    tbl_produk_a.date_input as date_input_produk,
                    tbl_produk_a.id_user_input as id_user_input_produk,
                    tbl_produk_a.kode_produk as kode_produk_produk,
                    tbl_produk_a.tingkat as tingkat_produk,
                    tbl_produk_a.kelas as kelas_produk,
                    tbl_produk_a.tahun as tahun_produk,
                    tbl_produk_a.semester as semester_produk,
                    tbl_produk_a.mapel as mapel_produk_old,
                    tbl_produk_a.mapel_display as mapel_produk,
                    tbl_produk_a.halaman as halaman_produk,
                    tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                    tbl_produk_a.keterangan as keterangan_produk

                    FROM tbl_produk_mapel_referensi tbl_produk_a
                    where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
                    group by tbl_produk_a.mapel
                    order by tbl_produk_a.id asc
                    ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                    tbl_produk_a.uuid_produk as uuid_produk,
                    tbl_produk_a.date_input as date_input_produk,
                    tbl_produk_a.id_user_input as id_user_input_produk,
                    tbl_produk_a.kode_produk as kode_produk_produk,
                    tbl_produk_a.tingkat as tingkat_produk,
                    tbl_produk_a.kelas as kelas_produk,
                    tbl_produk_a.tahun as tahun_produk,
                    tbl_produk_a.semester as semester_produk,
                    tbl_produk_a.mapel as mapel_produk_old,
                    tbl_produk_a.mapel_display as mapel_produk,
                    tbl_produk_a.halaman as halaman_produk,
                    tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                    tbl_produk_a.keterangan as keterangan_produk

                    FROM tbl_produk_mapel_referensi tbl_produk_a
                    where tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
                    group by tbl_produk_a.mapel
                    order by tbl_produk_a.id asc
                    ";
        }

        return $this->db->query($sql)->result();
    }


    public function get_all_data_produk_mapel_reference_by_tingkat_array($tingkatselected = null)
    {

        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_a
where tbl_produk_a.tingkat = '$tingkatselected'
        group by tbl_produk_a.mapel
        order by tbl_produk_a.id asc
        ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_a
-- where tbl_produk_a.tingkat = '$tingkatselected'
        group by tbl_produk_a.mapel
        order by tbl_produk_a.id asc
        ";
        }

        return $this->db->query($sql)->result_array();
    }


    // insert data
    function insert($data, $GET_uuid_produk)
    {
        // INSERT DATA
        // $this->db->set('uuid_produk', 'uuid()', FALSE);
        $this->db->set('uuid_produk', "'" . $GET_uuid_produk . "'", FALSE);
        $this->db->insert($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'INSERT',
            'id' => $this->db->insert_id()
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    // update data
    function update($id, $data)
    {

        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);

        $datainsert = array(
            'PROCESS' => 'UPDATE',
            'id' => $id
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }

    // delete data
    function delete($id)
    {
        // GET DATA BY ID
        $this->db->where($this->id, $id);
        $data = $this->db->get($this->table)->row_array();

        //PROSES DELETE
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        $datainsert = array(
            'PROCESS' => 'DELETE',
            'id' => $this->db->insert_id()
        );

        $data_merge = array_merge($datainsert, $data);

        // INSERT DATA TO "report_summary"
        $data_produk_insert = json_encode($data_merge);

        $data_summary = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );

        $this->db->set('uuid_summary', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_summary, $data_summary);

        // INSERT DATA TO "report_log"
        $data_log = array(
            'date_input' => date('Y-m-d H:i:s'),
            'process' => 'Produk',
            'detail' => $data_produk_insert
        );
        $this->db->set('uuid_log', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_report_log, $data_log);
    }
}

/* End of file Tbl_produk_model.php */
/* Location: ./application/models/Tbl_produk_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-19 12:18:53 */
/* http://harviacode.com */