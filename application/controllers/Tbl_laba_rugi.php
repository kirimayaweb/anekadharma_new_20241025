<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_laba_rugi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        // $this->load->model('Tbl_laba_rugi_model');
        // $this->load->library('form_validation');

        $this->load->model(array('Tbl_laba_rugi_model', 'Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model', 'Tbl_laba_rugi_detail_model', 'Sys_labarugi_keterangan_model', 'Sys_labarugi_kode_akun_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        $status_laporan = "laporan";

        $this->load->helper('dashboard');
        $current_year = (int) date('Y');
        $tahun_labarugi_data = array();
        for ($y = $current_year; $y >= 2026; $y--) {
            $tahun_labarugi_data[] = (object) array('tahun_neraca' => $y);
        }

        $bulan_neraca_labarugi_data = array();
        foreach (dashboard_bulan_list_range() as $row) {
            $bulan_neraca_labarugi_data[] = (object) array(
                'tahun_neraca' => $row->year_process,
                'bulan_neraca' => $row->month_process,
            );
        }

        $start = 0;
        $data = array(
            'Tbl_TAHUN_labarugi_data' => $tahun_labarugi_data,
            'Tbl_BULAN_labarugi_data' => $bulan_neraca_labarugi_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_labarugi_baru' => site_url('Tbl_laba_rugi/labarugi_form_input/'),
            'action_input_labarugi_baru_bulanan' => site_url('Tbl_laba_rugi/labarugi_form_input_bulanan/'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_tbl_labarugi_data_list', $data);
    }

    public function laporan($laporan_status = null)
    {

        if ($laporan_status) {
            $status_laporan = $laporan_status;
        } else {
            $status_laporan = "laporan";
        }

        $this->load->helper('dashboard');
        $current_year = (int) date('Y');
        $tahun_labarugi_data = array();
        for ($y = $current_year; $y >= 2026; $y--) {
            $tahun_labarugi_data[] = (object) array('tahun_neraca' => $y);
        }

        $bulan_neraca_labarugi_data = array();
        foreach (dashboard_bulan_list_range() as $row) {
            $bulan_neraca_labarugi_data[] = (object) array(
                'tahun_neraca' => $row->year_process,
                'bulan_neraca' => $row->month_process,
            );
        }

        $start = 0;
        $data = array(
            'Tbl_TAHUN_labarugi_data' => $tahun_labarugi_data,
            'Tbl_BULAN_labarugi_data' => $bulan_neraca_labarugi_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_labarugi_baru' => site_url('Tbl_laba_rugi/labarugi_form_input/'),
            'action_input_labarugi_baru_bulanan' => site_url('Tbl_laba_rugi/labarugi_form_input_bulanan/'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_tbl_labarugi_data_list', $data);
    }

    public function labarugi_form($Get_tahun = null, $Get_bulan = null)
    {

        // $angka = 778899991234.5678;

        // // Memformat angka dengan 2 digit desimal
        // $angka_format_1 = number_format($angka, 2);
        // echo "Angka diformat dengan 2 desimal: " . $angka_format_1 . "<br>";

        // // Memformat angka dengan 3 digit desimal dan titik sebagai pemisah ribuan
        // $angka_format_2 = number_format($angka, 3, ',', '.');
        // echo "Angka diformat dengan 3 desimal dan pemisah ribuan: " . $angka_format_2 . "<br>";

        // die;



        // print_r("labarugi_form");
        // print_r("<br/>");
        // print_r($Get_tahun);
        // print_r("<br/>");
        // print_r($Get_bulan);
        // print_r("<br/>");
        // die;

        if ($Get_bulan) {
            // Neraca bulanan
            // print_r("Bulanan");
            // print_r("<br/>");
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            }
        } else {
            // Neraca tahunan
            // print_r("Tahunan");
            // print_r("<br/>");

            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='0' ";

            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

            // print_r("GET_tbl_laba_rugi_RECORD");
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD);
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD->num_rows());
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD->row());
            // print_r("<br/>");
            // print_r("<br/>");
            // print_r("<br/>");
            // die;


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );

                // print_r($data);
                // print_r("<br/>");
                // die;


            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $Get_tahun . '/0'),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            }
        }

        $this->load->helper(array('dashboard', 'dashboard_laporan_publish'));
        $level = dashboard_session_user_level($this);
        $data['labarugi_is_published'] = false;
        $data['labarugi_can_publish'] = in_array($level, array(1, 2, 9, 99), true);
        $data['labarugi_has_record'] = isset($data['data_tbl_laba_rugi']);
        if ($Get_bulan && (int) $Get_bulan > 0) {
            $data['labarugi_is_published'] = dashboard_laporan_is_published($this, 'laba_rugi', $Get_tahun, $Get_bulan);
            if (!$data['labarugi_has_record']) {
                $data['labarugi_has_record'] = dashboard_laporan_has_saved_data($this, 'tbl_laba_rugi', $Get_tahun, $Get_bulan);
            }
        }

        $data['list_unit'] = $this->db->order_by('nama_unit', 'ASC')->get('sys_unit')->result();

        $this->load->helper('laba_rugi_detail');
        labarugi_detail_ensure_table($this);
        $uuid_laba_rugi = '';
        if (isset($data['data_tbl_laba_rugi']) && !empty($data['data_tbl_laba_rugi']->uuid_data_laba_rugi)) {
            $uuid_laba_rugi = $data['data_tbl_laba_rugi']->uuid_data_laba_rugi;
        } elseif (isset($data['uuid_data_laba_rugi']) && $data['uuid_data_laba_rugi'] !== '') {
            $uuid_laba_rugi = $data['uuid_data_laba_rugi'];
        }
        $data['uuid_data_laba_rugi'] = $uuid_laba_rugi;
        $data['labarugi_detail_maps'] = array(
            'rinci' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'rinci'),
            'sederhana' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'sederhana'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_labarugi_form', $data);
    }

    public function save_labarugi_detail()
    {
        $this->load->helper('laba_rugi_detail');
        labarugi_detail_ensure_table($this);
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $tahun = (int) $this->input->post('tahun');
        $bulan = (int) $this->input->post('bulan');
        $jenis_tab = trim((string) $this->input->post('jenis_tab'));
        $nama_laba_rugi = trim((string) $this->input->post('nama_laba_rugi'));
        $unit = trim((string) $this->input->post('unit'));
        $nominal_raw = (string) $this->input->post('nominal', FALSE);
        $keterangan_data = trim((string) $this->input->post('keterangan_data'));

        if ($tahun < 2000 || $bulan < 1 || $bulan > 12) {
            echo json_encode(array('ok' => false, 'message' => 'Periode tidak valid.'));
            return;
        }

        if (!in_array($jenis_tab, array('rinci', 'sederhana'), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Jenis tab tidak valid.'));
            return;
        }

        $this->load->helper('laba_rugi_keterangan');
        $allowed_keys = labarugi_keterangan_allowed_keys($this, $jenis_tab);
        if (!in_array($nama_laba_rugi, $allowed_keys, true)) {
            echo json_encode(array('ok' => false, 'message' => 'Keterangan tidak valid.'));
            return;
        }

        $nominal = labarugi_detail_parse_nominal($nominal_raw);
        if ($nominal === null) {
            echo json_encode(array('ok' => false, 'message' => 'Nominal tidak valid.'));
            return;
        }

        if ($unit === '') {
            echo json_encode(array('ok' => false, 'message' => 'Unit wajib diisi untuk tab per unit.'));
            return;
        }

        $uuid_laba_rugi = labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
        if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyiapkan UUID laba rugi.'));
            return;
        }

        $id = $this->Tbl_laba_rugi_detail_model->upsert(array(
            'tanggal' => labarugi_detail_tanggal_periode($tahun, $bulan),
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'nama_laba_rugi' => $nama_laba_rugi,
            'unit' => $unit,
            'nominal' => $nominal,
            'nominal_update' => $nominal,
            'auto_sistem' => null,
            'keterangan_data' => ($keterangan_data !== '') ? $keterangan_data : null,
            'jenis_tab' => $jenis_tab,
            'tahun_transaksi' => $tahun,
            'bulan_transaksi' => $bulan,
        ));

        echo json_encode(array(
            'ok' => true,
            'message' => 'Data berhasil disimpan.',
            'id' => $id,
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'nominal_formatted' => labarugi_detail_format_nominal($nominal),
        ));
    }

    public function list_labarugi_keterangan($jenis_tab = 'rinci')
    {
        $this->load->helper('laba_rugi_keterangan');
        header('Content-Type: application/json; charset=utf-8');

        $jenis_tab = trim((string) $jenis_tab);
        if (!in_array($jenis_tab, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.', 'data' => array()));
            return;
        }

        $rows = $this->Sys_labarugi_keterangan_model->get_by_tab($jenis_tab);
        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                'id' => (int) $row->id,
                'uuid_nama_keterangan' => $row->uuid_nama_keterangan,
                'nama_keterangan' => $row->nama_keterangan,
                'status_keterangan' => $row->status_keterangan,
                'status_labarugi' => $row->status_labarugi,
                'nama_group' => isset($row->nama_group) ? $row->nama_group : null,
                'keterangan' => $row->keterangan,
                'urutan' => (int) $row->urutan,
            );
        }

        echo json_encode(array(
            'ok' => true,
            'data' => $data,
            'group_options' => labarugi_keterangan_group_options($this, $jenis_tab),
        ));
    }

    public function save_labarugi_keterangan()
    {
        $this->load->helper('laba_rugi_keterangan');
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $id = (int) $this->input->post('id');
        $uuid = trim((string) $this->input->post('uuid_nama_keterangan'));
        $nama = trim((string) $this->input->post('nama_keterangan'));
        $status_keterangan = trim((string) $this->input->post('status_keterangan'));
        $status_labarugi = trim((string) $this->input->post('status_labarugi'));
        $nama_group = trim((string) $this->input->post('nama_group'));
        $keterangan = trim((string) $this->input->post('keterangan'));

        if ($nama === '') {
            echo json_encode(array('ok' => false, 'message' => 'Nama keterangan wajib diisi.'));
            return;
        }

        if (!in_array($status_keterangan, labarugi_keterangan_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status keterangan tidak valid.'));
            return;
        }

        if (!in_array($status_labarugi, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.'));
            return;
        }

        $result = $this->Sys_labarugi_keterangan_model->save_row(array(
            'id' => $id,
            'uuid_nama_keterangan' => $uuid,
            'nama_keterangan' => $nama,
            'status_keterangan' => $status_keterangan,
            'status_labarugi' => $status_labarugi,
            'nama_group' => $nama_group,
            'keterangan' => $keterangan,
        ));

        echo json_encode($result);
    }

    public function ajax_labarugi_setting_kode_akun($uuid_nama_keterangan = null)
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid_nama_keterangan = trim((string) $uuid_nama_keterangan);
        $status_labarugi = trim((string) $this->input->get('jenis_tab'));
        $nama_keterangan = trim((string) $this->input->get('nama_keterangan'));
        $tahun = (int) $this->input->get('tahun');
        $bulan = (int) $this->input->get('bulan');
        if ($tahun < 2000) { $tahun = (int) date('Y'); }
        if ($bulan < 1 || $bulan > 12) { $bulan = (int) date('m'); }

        if ($uuid_nama_keterangan === '') {
            echo json_encode(array('ok' => false, 'message' => 'UUID keterangan tidak valid.'));
            return;
        }

        if (!in_array($status_labarugi, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.'));
            return;
        }

        $selected_kodes = labarugi_kode_akun_selected_kodes($this, $uuid_nama_keterangan, $status_labarugi);
        $selected_flip = array_flip($selected_kodes);
        $all = $this->db->select('kode_akun, nama_akun')->order_by('kode_akun', 'ASC')->get('sys_kode_akun')->result();

        $rows_by_kode = array();
        if (!empty($selected_kodes)) {
            $this->load->helper('neraca_kode_akun');
            $saldo_map = neraca_get_neraca_saldo_map($this, $tahun, $bulan);
            $rows_by_kode = $saldo_map['rows_by_kode'];
        }

        $available = array();
        $terpilih = array();

        foreach ($all as $akun) {
            $kode_str = (string) $akun->kode_akun;
            $item = array(
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
            );
            if (isset($selected_flip[$kode_str])) {
                $nominal_ns = labarugi_kode_akun_neraca_saldo_nominal($this, $akun->kode_akun, $tahun, $bulan, $rows_by_kode);
                $item['nominal_ns'] = $nominal_ns;
                $item['nominal_ns_formatted'] = labarugi_kode_akun_format_nominal($nominal_ns);
                $terpilih[] = $item;
            } else {
                $available[] = $item;
            }
        }

        if ($nama_keterangan === '') {
            $ket_row = $this->db->get_where('sys_labarugi_keterangan', array(
                'uuid_nama_keterangan' => $uuid_nama_keterangan,
                'status_labarugi' => $status_labarugi,
            ))->row();
            if ($ket_row) {
                $nama_keterangan = $ket_row->nama_keterangan;
            }
        }

        echo json_encode(array(
            'ok' => true,
            'uuid_nama_keterangan' => $uuid_nama_keterangan,
            'nama_keterangan' => $nama_keterangan,
            'status_labarugi' => $status_labarugi,
            'available' => $available,
            'terpilih' => $terpilih,
        ));
    }

    public function ajax_labarugi_setting_kode_akun_pilih()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $uuid_nama_keterangan = trim((string) $this->input->post('uuid_nama_keterangan'));
        $kode_akun = trim((string) $this->input->post('kode_akun'));
        $status_labarugi = trim((string) $this->input->post('status_labarugi'));
        $nama_keterangan = trim((string) $this->input->post('nama_keterangan'));

        if ($uuid_nama_keterangan === '' || $kode_akun === '') {
            echo json_encode(array('ok' => false, 'message' => 'Keterangan dan kode akun wajib diisi.'));
            return;
        }

        if (!in_array($status_labarugi, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.'));
            return;
        }

        $row_akun = $this->db->where('kode_akun', $kode_akun)->get('sys_kode_akun')->row();
        if (!$row_akun) {
            echo json_encode(array('ok' => false, 'message' => 'Kode akun tidak ditemukan di sys_kode_akun.'));
            return;
        }

        if ($this->Sys_labarugi_kode_akun_model->exists($uuid_nama_keterangan, $kode_akun, $status_labarugi)) {
            echo json_encode(array('ok' => false, 'message' => 'Kode akun sudah dipilih untuk keterangan ini.'));
            return;
        }

        $inserted = $this->Sys_labarugi_kode_akun_model->insert_row(array(
            'uuid_setting_kode_akun_labarugi' => labarugi_kode_akun_generate_uuid(),
            'uuid_nama_keterangan' => $uuid_nama_keterangan,
            'nama_keterangan' => ($nama_keterangan !== '') ? $nama_keterangan : null,
            'kode_akun' => $kode_akun,
            'status_labarugi' => $status_labarugi,
        ));

        if (!$inserted) {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyimpan setting kode akun.'));
            return;
        }

        echo json_encode(array('ok' => true, 'message' => 'Kode akun berhasil dipilih.'));
    }

    public function ajax_labarugi_setting_kode_akun_hapus()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $uuid_nama_keterangan = trim((string) $this->input->post('uuid_nama_keterangan'));
        $kode_akun = trim((string) $this->input->post('kode_akun'));
        $status_labarugi = trim((string) $this->input->post('status_labarugi'));

        if ($uuid_nama_keterangan === '' || $kode_akun === '') {
            echo json_encode(array('ok' => false, 'message' => 'Keterangan dan kode akun wajib diisi.'));
            return;
        }

        if (!in_array($status_labarugi, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.'));
            return;
        }

        if (!$this->Sys_labarugi_kode_akun_model->exists($uuid_nama_keterangan, $kode_akun, $status_labarugi)) {
            echo json_encode(array('ok' => false, 'message' => 'Kode akun tidak ditemukan pada setting keterangan ini.'));
            return;
        }

        $deleted = $this->Sys_labarugi_kode_akun_model->delete_row($uuid_nama_keterangan, $kode_akun, $status_labarugi);
        if (!$deleted) {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menghapus kode akun terpilih.'));
            return;
        }

        echo json_encode(array('ok' => true, 'message' => 'Kode akun berhasil dihapus.'));
    }

    public function ajax_labarugi_nominal_unit()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid = trim((string) $this->input->get('uuid_nama_keterangan'));
        $jenis_tab = trim((string) $this->input->get('jenis_tab'));
        $unit_key = trim((string) $this->input->get('unit'));
        $unit_label = trim((string) $this->input->get('unit_label'));
        $tahun = (int) $this->input->get('tahun');
        $bulan = (int) $this->input->get('bulan');

        if ($uuid === '' || $unit_key === '') {
            echo json_encode(array('ok' => false, 'message' => 'Parameter tidak lengkap.'));
            return;
        }

        $nominal = labarugi_kode_akun_unit_nominal($this, $uuid, $jenis_tab, $unit_key, $unit_label, $tahun, $bulan);
        echo json_encode(array(
            'ok' => true,
            'nominal' => $nominal,
            'nominal_formatted' => labarugi_kode_akun_format_nominal($nominal),
        ));
    }

    public function ajax_labarugi_transaksi_unit()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid = trim((string) $this->input->get('uuid_nama_keterangan'));
        $jenis_tab = trim((string) $this->input->get('jenis_tab'));
        $unit_key = trim((string) $this->input->get('unit'));
        $unit_label = trim((string) $this->input->get('unit_label'));
        $nama_keterangan = trim((string) $this->input->get('nama_keterangan'));
        $tahun = (int) $this->input->get('tahun');
        $bulan = (int) $this->input->get('bulan');

        if ($uuid === '' || $unit_key === '') {
            echo json_encode(array('ok' => false, 'message' => 'Parameter tidak lengkap.', 'data' => array()));
            return;
        }

        $rows = labarugi_kode_akun_unit_transactions($this, $uuid, $jenis_tab, $unit_key, $unit_label, $tahun, $bulan);
        $total = labarugi_kode_akun_unit_nominal($this, $uuid, $jenis_tab, $unit_key, $unit_label, $tahun, $bulan);

        echo json_encode(array(
            'ok' => true,
            'nama_keterangan' => $nama_keterangan,
            'unit' => $unit_key,
            'total_formatted' => labarugi_kode_akun_format_nominal($total),
            'data' => $rows,
        ));
    }

    public function publish_labarugi($Get_tahun = null, $Get_bulan = null)
    {
        $this->load->helper(array('dashboard', 'dashboard_laporan_publish'));

        $level = dashboard_session_user_level($this);
        if (!in_array($level, array(1, 2, 9, 99), true)) {
            show_error('Akses ditolak.', 403);
            return;
        }

        if (!(int) $Get_bulan) {
            redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun));
            return;
        }

        if (!dashboard_laporan_has_saved_data($this, 'tbl_laba_rugi', $Get_tahun, $Get_bulan)) {
            $this->session->set_flashdata('message', 'Data laporan belum tersimpan. Simpan data terlebih dahulu.');
            redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun . '/' . $Get_bulan));
            return;
        }

        $action = trim((string) $this->input->post('action'));
        $publish = ($action !== 'cancel');
        $result = dashboard_laporan_set_published($this, 'laba_rugi', $Get_tahun, $Get_bulan, $publish);

        $this->session->set_flashdata('message', $result['message']);
        redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun . '/' . $Get_bulan));
    }


    public function create_action_labarugi_NEW($Get_tahun = null, $Get_bulan = null, $data_name = null)
    {
        // print_r("create_action_labarugi_NEW");
        // print_r("<br/>");
        // print_r($Get_tahun);
        // print_r("<br/>");
        // print_r($Get_bulan);
        // print_r("<br/>");
        // print_r($data_name);
        // print_r("<br/>");
        // print_r($this->input->post('input_box', TRUE));
        // print_r("<br/>");

        // die;

        // CEK JIKA BELUM ADA RECORD PADA TAHUN DAN BULAN YANG TERPILIH , MAKA BUAT 1 RECORD BARU


        if ($Get_bulan) {
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);
        } else {
            $Get_bulan = 0;
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);
        }


        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
            // Proses update dan isi data

            $data = array(
                $data_name => str_replace('.', '', $this->input->post('input_box', TRUE)),
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($GET_tbl_laba_rugi_RECORD->row()->id, $data);
        } else {
            // proses buat record baru dan isi data

            $data = array(
                'date_input' => date("Y-m-d H:i:s"),
                'date_transaksi' => date("Y-m-d H:i:s"),
                'tahun_transaksi' => $Get_tahun,
                'bulan_transaksi' => $Get_bulan,

            );

            // print_r($data);
            // print_r("<br/>");
            // die;

            $Get_id_RECORD_NEW = $this->Tbl_laba_rugi_model->insert($data);


            // print_r($Get_tahun);
            // print_r("<br/>");
            // print_r("BUlan: ");
            // print_r($Get_bulan);
            // print_r("<br/>");
            // print_r("id: ");
            // print_r($Get_id_RECORD_NEW);
            // print_r("<br/>");
            // print_r($this->input->post('input_box', TRUE));
            // print_r("<br/>");
            // // die;


            $data = array(
                $data_name => str_replace('.', '', $this->input->post('input_box', TRUE)),
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($Get_id_RECORD_NEW, $data);
        }

        redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun . '/' . $Get_bulan));
    }


    public function update_labarugi_data($Get_id_RECORD = null, $data_name = null)
    {

        // $data_name = str_replace('.', '', $this->input->post('input_box', TRUE));
        // print_r($data_name);
        // print_r("<br/>");
        // $data_name = str_replace(',', '.', $this->input->post('input_box', TRUE));
        // print_r($data_name);
        // print_r("<br/>");
        // $data_name = $data_name + $data_name;
        // print_r($data_name);
        // print_r("<br/>");

        // print_r("<br/>");
        // print_r("<br/>");

        // print_r("update_labarugi_data");
        // print_r("<br/>");
        // print_r($Get_id_RECORD);
        // print_r("<br/>");
        // print_r($data_name);
        // print_r("<br/>");
        // die;

        $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `id`='$Get_id_RECORD'";

        $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

        // print_r($GET_tbl_laba_rugi_RECORD->row());
        // die;

        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {

            // print_r("ada record");
            // print_r("<br/>");

            $data_input_box = str_replace('.', '', $this->input->post('input_box', TRUE));

            // print_r($data_name);
            // print_r("<br/>");

            $data_input_box = str_replace(',', '.', $data_input_box);

            // print_r($data_input_box);            
            // print_r("<br/>");
            // die;




            $data = array(
                $data_name => $data_input_box,
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($Get_id_RECORD, $data);
        }


        // Kembali ke form neraca
        $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `id`='$Get_id_RECORD' ";

        $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

        // print_r($GET_tbl_laba_rugi_RECORD->row());
        // die;



        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $Get_id_RECORD),
                'id' => set_value('id'),
                // 'data_detail' => $data_detail,
                'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                'tahun_neraca' => $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi,
                'bulan_transaksi' => $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi,
            );

            // print_r($data);
            // print_r("<br/>");
            // die;

        } else {
            $data = array(
                'button' => 'Simpan',
                'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi . '/' . $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi),
                'id' => set_value('id'),
                // 'data_detail' => $data_detail,
                // 'uuid_data_laba_rugi' => "",
                'tahun_neraca' => $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi,
                'bulan_transaksi' => $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi,
            );
        }


        // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_labarugi_form', $data);

        // print_r($GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi);
        // print_r("<br/>");
        // print_r($GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi);
        // print_r("<br/>");
        // die;
        redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi . '/' . $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi));
    }




    public function labarugi_form_input()
    {
        print_r("labarugi_form_input");
        die;
    }

    public function labarugi_form_input_bulanan()
    {
        print_r("labarugi_form_input_bulanan");
        die;
    }


    public function index_X()
    {

        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html';
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_laba_rugi_model->total_rows($q);
        $tbl_laba_rugi = $this->Tbl_laba_rugi_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_laba_rugi_data' => $tbl_laba_rugi,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_list', $data);
    }



    public function labarugi($Get_tahun = null, $Get_bulan = null)
    {



        if ($Get_bulan) {
            // Neraca bulanan
            // print_r("Bulanan");
            // print_r("<br/>");
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Laporan/update_laba_rugi/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            }
        } else {
            // Neraca tahunan
            // print_r("Tahunan");
            // print_r("<br/>");

            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='' ";

            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);



            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Laporan/update_laba_rugi/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            }
        }




        // $data = array(

        //     'button' => 'Simpan',
        //     'action' => site_url('tbl_pembelian/create_pembayaran_action/'),
        //     'id' => set_value('id'),


        // );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/adminlte310_labarugi', $data);
    }

    public function labarugi_print($Get_tahun = null, $Get_bulan = null)
    {

        if ($Get_bulan && (int) $Get_bulan > 0) {
            $this->load->helper('dashboard_laporan_publish');
            dashboard_laporan_require_published_or_deny($this, 'laba_rugi', $Get_tahun, $Get_bulan);
        }

        if ($Get_bulan) {
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $data_detail = $this->db->query($sql)->row();
        } else {
            $Get_bulan = 0;
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $data_detail = $this->db->query($sql)->row();
            // $Get_bulan=0;
        }

        // print_r($data_detail);
        // die;

        // $data = array(

        // 	'button' => 'Simpan',
        // 	'action' => site_url('tbl_pembelian/create_pembayaran_action/'),
        // 	'id' => set_value('id'),


        // );

        // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/adminlte310_labarugi_cetak', $data);


        // 2.a. PERSIAPAN LIBRARY
        $this->load->library('PdfGenerator');

        // 2.b. PERSIAPAN DATA
        $data = array(
            // 'button' => 'Simpan',
            // 'action' => site_url('Laporan/create_action_neraca'),
            // 'id' => set_value('id'),
            // 'id' => set_value('id'),

            'data_tbl_laba_rugi' => $data_detail,
            'tahun_laba_rugi' => $data_detail->tahun_transaksi,
            'bulan_laba_rugi' => $data_detail->bulan_transaksi,

        );

        // print_r($data);
        // die;

        // 2.C. MENAMPILKAN FILE DATA
        // $data = array_merge($data);
        $html = $this->load->view('anekadharma/tbl_laba_rugi/adminlte310_labarugi_cetak.php', $data, true);

        // 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
        $this->pdf->loadHtml($html);
        $this->pdf->render();

        $this->pdf->stream("CETAK_LABA_RUGI.pdf", array("Attachment" => 0));
    }


    public function read($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'date_input' => $row->date_input,
                'date_transaksi' => $row->date_transaksi,
                'tahun_transaksi' => $row->tahun_transaksi,
                'bulan_transaksi' => $row->bulan_transaksi,
                'uuid_data_laba_rugi' => $row->uuid_data_laba_rugi,
            );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_laba_rugi/create_action'),
            'id' => set_value('id'),
            'date_input' => set_value('date_input'),
            'date_transaksi' => set_value('date_transaksi'),
            'tahun_transaksi' => set_value('tahun_transaksi'),
            'bulan_transaksi' => set_value('bulan_transaksi'),
            'uuid_data_laba_rugi' => set_value('uuid_data_laba_rugi'),
        );
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'date_input' => $this->input->post('date_input', TRUE),
                'date_transaksi' => $this->input->post('date_transaksi', TRUE),
                'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
                'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
                'uuid_data_laba_rugi' => $this->input->post('uuid_data_laba_rugi', TRUE),
            );

            $this->Tbl_laba_rugi_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_laba_rugi/update_action'),
                'id' => set_value('id', $row->id),
                'date_input' => set_value('date_input', $row->date_input),
                'date_transaksi' => set_value('date_transaksi', $row->date_transaksi),
                'tahun_transaksi' => set_value('tahun_transaksi', $row->tahun_transaksi),
                'bulan_transaksi' => set_value('bulan_transaksi', $row->bulan_transaksi),
                'uuid_data_laba_rugi' => set_value('uuid_data_laba_rugi', $row->uuid_data_laba_rugi),
            );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'date_input' => $this->input->post('date_input', TRUE),
                'date_transaksi' => $this->input->post('date_transaksi', TRUE),
                'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
                'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
                'uuid_data_laba_rugi' => $this->input->post('uuid_data_laba_rugi', TRUE),
            );

            $this->Tbl_laba_rugi_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $this->Tbl_laba_rugi_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_laba_rugi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        $this->form_validation->set_rules('date_transaksi', 'date transaksi', 'trim|required');
        $this->form_validation->set_rules('tahun_transaksi', 'tahun transaksi', 'trim|required');
        $this->form_validation->set_rules('bulan_transaksi', 'bulan transaksi', 'trim|required');
        $this->form_validation->set_rules('uuid_data_laba_rugi', 'uuid data neraca', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Tbl_laba_rugi.php */
/* Location: ./application/controllers/Tbl_laba_rugi.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-06-17 02:28:51 */
/* http://harviacode.com */