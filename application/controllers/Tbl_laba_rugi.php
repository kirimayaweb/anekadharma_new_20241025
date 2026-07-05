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

        $this->load->model(array('Tbl_laba_rugi_model', 'Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model', 'Tbl_laba_rugi_detail_model', 'Sys_labarugi_keterangan_model', 'Sys_labarugi_kode_akun_model', 'Sys_labarugi_unit_publish_model'));
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
        $this->load->helper('laba_rugi_unit_publish');
        labarugi_unit_publish_ensure_table($this);
        $uuid_laba_rugi = '';
        if (isset($data['data_tbl_laba_rugi']) && !empty($data['data_tbl_laba_rugi']->uuid_data_laba_rugi)) {
            $uuid_laba_rugi = $data['data_tbl_laba_rugi']->uuid_data_laba_rugi;
        } elseif (isset($data['uuid_data_laba_rugi']) && $data['uuid_data_laba_rugi'] !== '') {
            $uuid_laba_rugi = $data['uuid_data_laba_rugi'];
        } elseif ($Get_bulan && (int) $Get_bulan > 0) {
            $uuid_laba_rugi = labarugi_unit_publish_resolve_uuid($this, '', $Get_tahun, $Get_bulan);
        }
        $data['uuid_data_laba_rugi'] = $uuid_laba_rugi;
        $data['labarugi_detail_maps'] = array(
            'rinci' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'rinci'),
            'sederhana' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'sederhana'),
        );
        $this->load->helper('laba_rugi_unit_tab_sync');
        if ($uuid_laba_rugi !== '' && $Get_bulan && (int) $Get_bulan > 0) {
            $data['labarugi_detail_maps'] = labarugi_unit_tab_sync_reconcile_derived_maps(
                $this,
                $uuid_laba_rugi,
                $Get_tahun,
                $Get_bulan,
                $data['labarugi_detail_maps'],
                $data['list_unit']
            );
        }
        $data['labarugi_unit_publish_maps'] = array(
            'rinci' => labarugi_unit_publish_load_map($this, $uuid_laba_rugi, 'rinci', $Get_tahun, $Get_bulan),
            'sederhana' => labarugi_unit_publish_load_map($this, $uuid_laba_rugi, 'sederhana', $Get_tahun, $Get_bulan),
        );

        $this->load->helper(array('laba_rugi_keterangan', 'laba_rugi_kode_akun', 'laba_rugi_utama'));
        labarugi_keterangan_seed_utama($this);
        labarugi_detail_ensure_table($this);
        $data['labarugi_utama_ka_map'] = labarugi_kode_akun_selected_map_by_tab($this, 'utama');
        $data['labarugi_utama_bb_rows'] = array();
        $data['labarugi_utama_sync_map'] = array();
        if ($Get_bulan && (int) $Get_bulan > 0) {
            $data['labarugi_utama_bb_rows'] = labarugi_kode_akun_merged_rows($this, $Get_tahun, $Get_bulan);
            $data['labarugi_utama_sync_map'] = labarugi_utama_sync_load_map($this, $uuid_laba_rugi);
        }
        $data['labarugi_utama_save_url'] = site_url('Tbl_laba_rugi/save_labarugi_utama_field');
        $data['labarugi_utama_sync_url'] = site_url('Tbl_laba_rugi/save_labarugi_utama_sync_auto');
        $data['labarugi_utama_record_id'] = 0;
        if (isset($data['data_tbl_laba_rugi']) && isset($data['data_tbl_laba_rugi']->id)) {
            $data['labarugi_utama_record_id'] = (int) $data['data_tbl_laba_rugi']->id;
        }

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_labarugi_form', $data);
    }

    /**
     * Cek file deploy tab Laba Rugi per unit (admin/superadmin).
     * URL: Tbl_laba_rugi/labarugi_deploy_check
     */
    public function labarugi_deploy_check()
    {
        $this->load->helper('dashboard');
        $level = dashboard_session_user_level($this);
        if (!in_array($level, array(1, 2, 9, 99), true)) {
            show_error('Akses ditolak.', 403);
            return;
        }

        header('Content-Type: text/plain; charset=utf-8');

        $required_files = array(
            'form_view' => APPPATH . 'views/anekadharma/tbl_laba_rugi/adminlte310_labarugi_form.php',
            'form_panel' => APPPATH . 'views/anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_form_panel.php',
            'unit_grid' => APPPATH . 'views/anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_unit_grid.php',
            'detail_helper' => APPPATH . 'helpers/laba_rugi_detail_helper.php',
            'publish_helper' => APPPATH . 'helpers/laba_rugi_unit_publish_helper.php',
            'detail_model' => APPPATH . 'models/Tbl_laba_rugi_detail_model.php',
            'publish_model' => APPPATH . 'models/Sys_labarugi_unit_publish_model.php',
        );

        echo "LABA RUGI PER UNIT DEPLOY CHECK\n";
        echo "version: v2-per-unit-tabs\n";
        echo "git_head: " . $this->labarugi_deploy_git_head() . "\n\n";

        foreach ($required_files as $label => $path) {
            $exists = file_exists($path);
            echo strtoupper($label) . ': ' . ($exists ? 'OK' : 'MISSING') . "\n";
            echo "  path: " . $path . "\n";
            if ($exists && $label === 'form_view') {
                $content = @file_get_contents($path);
                $has_tabs = ($content !== false && strpos($content, 'labarugi-tabs-shell') !== false);
                echo "  has_tabs: " . ($has_tabs ? 'YES' : 'NO (PULL BRANCH main)') . "\n";
            }
        }

        echo "\nJika has_tabs=NO atau file MISSING, jalankan di server:\n";
        echo "  git fetch origin\n";
        echo "  git checkout main\n";
        echo "  git pull origin main\n";
        echo "  (lalu clear OPcache / restart PHP jika perlu)\n";
    }

    private function labarugi_deploy_git_head()
    {
        $head_file = FCPATH . '.git/HEAD';
        if (!is_readable($head_file)) {
            return 'unknown';
        }
        $head = trim((string) file_get_contents($head_file));
        if (strpos($head, 'ref:') === 0) {
            $ref = trim(substr($head, 5));
            $ref_file = FCPATH . '.git/' . $ref;
            if (is_readable($ref_file)) {
                return $ref . ' @ ' . substr(trim((string) file_get_contents($ref_file)), 0, 7);
            }
            return $ref;
        }
        return substr($head, 0, 7);
    }

    public function save_labarugi_detail()
    {
        $this->load->helper(array('laba_rugi_detail', 'laba_rugi_unit_tab_sync'));
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

        if ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($nama_laba_rugi)) {
            echo json_encode(array('ok' => false, 'message' => 'Field ini diisi otomatis dari tab RINCI.'));
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

        labarugi_unit_tab_sync_mirror_detail(
            $this,
            $uuid_laba_rugi,
            $tahun,
            $bulan,
            $jenis_tab,
            $nama_laba_rugi,
            $unit,
            $nominal,
            $keterangan_data
        );

        $derived_peer_values = array();
        if ($jenis_tab === 'rinci' && labarugi_unit_tab_sync_should_refresh_derived_after_rinci_save($nama_laba_rugi)) {
            $detail_maps = array(
                'rinci' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'rinci'),
                'sederhana' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'sederhana'),
            );
            labarugi_unit_tab_sync_push_derived_to_sederhana(
                $this,
                $uuid_laba_rugi,
                $tahun,
                $bulan,
                $unit,
                $detail_maps,
                $keterangan_data
            );
            foreach (labarugi_unit_tab_sync_rinci_derived_keys() as $derived_key) {
                $derived_peer_values[$derived_key] = labarugi_detail_format_nominal(
                    labarugi_unit_tab_sync_rinci_derived_nominal($detail_maps, $derived_key, $unit)
                );
            }
        }

        echo json_encode(array(
            'ok' => true,
            'message' => 'Data berhasil disimpan.',
            'id' => $id,
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'nominal_formatted' => labarugi_detail_format_nominal($nominal),
            'peer_synced' => labarugi_unit_tab_sync_should_mirror($nama_laba_rugi, $jenis_tab),
            'derived_peer_values' => $derived_peer_values,
        ));
    }

    public function save_labarugi_detail_sync_auto()
    {
        $this->load->helper(array('laba_rugi_detail', 'laba_rugi_unit_tab_sync'));
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
        $status_sync_auto = (int) $this->input->post('status_sync_auto') === 1 ? 1 : 0;
        $auto_sistem_raw = (string) $this->input->post('auto_sistem', FALSE);
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

        if ($unit === '') {
            echo json_encode(array('ok' => false, 'message' => 'Unit wajib diisi untuk tab per unit.'));
            return;
        }

        if ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($nama_laba_rugi)) {
            echo json_encode(array('ok' => false, 'message' => 'Field ini diisi otomatis dari tab RINCI.'));
            return;
        }

        $auto_sistem = labarugi_detail_parse_nominal($auto_sistem_raw);
        if ($auto_sistem === null) {
            $auto_sistem = 0.0;
        }

        $uuid_laba_rugi = labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
        if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyiapkan UUID laba rugi.'));
            return;
        }

        $existing = $this->Tbl_laba_rugi_detail_model->find_existing($uuid_laba_rugi, $nama_laba_rugi, $unit, $jenis_tab);
        $nominal_update = ($existing && $existing->nominal_update !== null) ? (float) $existing->nominal_update : null;
        if ($nominal_update === null && $existing && $existing->nominal !== null) {
            $nominal_update = (float) $existing->nominal;
        }
        if ($nominal_update === null) {
            $nominal_update = 0.0;
        }

        if ($status_sync_auto === 1) {
            $nominal_update = $auto_sistem;
        }

        $id = $this->Tbl_laba_rugi_detail_model->upsert(array(
            'tanggal' => labarugi_detail_tanggal_periode($tahun, $bulan),
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'nama_laba_rugi' => $nama_laba_rugi,
            'unit' => $unit,
            'nominal' => $nominal_update,
            'nominal_update' => $nominal_update,
            'auto_sistem' => $auto_sistem,
            'status_sync_auto' => $status_sync_auto,
            'keterangan_data' => ($keterangan_data !== '') ? $keterangan_data : null,
            'jenis_tab' => $jenis_tab,
            'tahun_transaksi' => $tahun,
            'bulan_transaksi' => $bulan,
        ));

        labarugi_unit_tab_sync_mirror_detail(
            $this,
            $uuid_laba_rugi,
            $tahun,
            $bulan,
            $jenis_tab,
            $nama_laba_rugi,
            $unit,
            $nominal_update,
            $keterangan_data,
            array(
                'auto_sistem' => $auto_sistem,
                'status_sync_auto' => $status_sync_auto,
            )
        );

        echo json_encode(array(
            'ok' => true,
            'message' => $status_sync_auto === 1 ? 'Nilai otomatis disalin ke input.' : 'Status sync otomatis disimpan.',
            'id' => $id,
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'status_sync_auto' => $status_sync_auto,
            'nominal_formatted' => labarugi_detail_format_nominal($nominal_update),
            'auto_sistem_formatted' => labarugi_detail_format_nominal($auto_sistem),
            'peer_synced' => labarugi_unit_tab_sync_should_mirror($nama_laba_rugi, $jenis_tab),
        ));
    }

    public function save_labarugi_utama_field()
    {
        $this->load->helper(array('laba_rugi_utama', 'laba_rugi_detail'));
        labarugi_detail_ensure_table($this);
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $field_key = trim((string) $this->input->post('field_key'));
        if ($field_key === '') {
            $field_key = trim((string) $this->input->post('nama_laba_rugi'));
        }
        $record_id = (int) $this->input->post('record_id');
        $tahun = (int) $this->input->post('tahun');
        $bulan = (int) $this->input->post('bulan');
        $nominal_raw = (string) $this->input->post('nominal', FALSE);

        if (!labarugi_utama_is_editable_field($field_key)) {
            echo json_encode(array('ok' => false, 'message' => 'Field laba rugi tidak valid.'));
            return;
        }

        $nominal = labarugi_detail_parse_nominal($nominal_raw);
        if ($nominal === null) {
            echo json_encode(array('ok' => false, 'message' => 'Nominal tidak valid.'));
            return;
        }

        $record_id = labarugi_utama_resolve_record_id($this, $record_id, $tahun, $bulan);
        if ($record_id < 1) {
            labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
            $record_id = labarugi_utama_resolve_record_id($this, 0, $tahun, $bulan);
        }
        if ($record_id < 1) {
            echo json_encode(array('ok' => false, 'message' => 'Data laba rugi belum tersedia. Simpan data laba rugi terlebih dahulu.'));
            return;
        }

        if (!labarugi_utama_update_field($this, $record_id, $field_key, $nominal, $tahun, $bulan)) {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyimpan field laba rugi.'));
            return;
        }

        echo json_encode(array(
            'ok' => true,
            'message' => 'Data berhasil disimpan.',
            'record_id' => $record_id,
            'field_key' => $field_key,
            'nominal_formatted' => labarugi_detail_format_nominal($nominal),
        ));
    }

    public function save_labarugi_utama_sync_auto()
    {
        $this->load->helper(array('laba_rugi_utama', 'laba_rugi_detail', 'laba_rugi_kode_akun', 'laba_rugi_keterangan'));
        labarugi_detail_ensure_table($this);
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $field_key = trim((string) $this->input->post('field_key'));
        if ($field_key === '') {
            $field_key = trim((string) $this->input->post('nama_laba_rugi'));
        }
        $record_id = (int) $this->input->post('record_id');
        $tahun = (int) $this->input->post('tahun');
        $bulan = (int) $this->input->post('bulan');
        $status_sync_auto = (int) $this->input->post('status_sync_auto') === 1 ? 1 : 0;
        $auto_sistem_raw = (string) $this->input->post('auto_sistem', FALSE);
        $keterangan_data = trim((string) $this->input->post('keterangan_data'));

        if ($tahun < 2000 || $bulan < 1 || $bulan > 12) {
            echo json_encode(array('ok' => false, 'message' => 'Periode tidak valid.'));
            return;
        }

        if (!labarugi_utama_is_editable_field($field_key)) {
            echo json_encode(array('ok' => false, 'message' => 'Field laba rugi tidak valid.'));
            return;
        }

        $auto_sistem = labarugi_detail_parse_nominal($auto_sistem_raw);
        if ($auto_sistem === null) {
            $auto_sistem = labarugi_utama_system_nominal($this, $field_key, labarugi_kode_akun_selected_map_by_tab($this, 'utama'), null, $tahun, $bulan);
        }

        $record_id = labarugi_utama_resolve_record_id($this, $record_id, $tahun, $bulan);
        if ($record_id < 1) {
            labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
            $record_id = labarugi_utama_resolve_record_id($this, 0, $tahun, $bulan);
        }
        if ($record_id < 1) {
            echo json_encode(array('ok' => false, 'message' => 'Data laba rugi belum tersedia. Simpan data laba rugi terlebih dahulu.'));
            return;
        }

        $uuid_laba_rugi = labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
        if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyiapkan UUID laba rugi.'));
            return;
        }

        $sync_map = labarugi_utama_sync_load_map($this, $uuid_laba_rugi);
        $row = $this->db->get_where('tbl_laba_rugi', array('id' => $record_id))->row();
        $nominal_update = labarugi_utama_field_value($row, $field_key, $sync_map);
        if ($status_sync_auto === 1) {
            $nominal_update = $auto_sistem;
            if (!labarugi_utama_update_field($this, $record_id, $field_key, $nominal_update, $tahun, $bulan, $uuid_laba_rugi)) {
                echo json_encode(array('ok' => false, 'message' => 'Gagal menyimpan nilai ke data cetak laba rugi.'));
                return;
            }
        }

        $unit_key = labarugi_utama_unit_key();
        $this->Tbl_laba_rugi_detail_model->upsert(array(
            'tanggal' => labarugi_detail_tanggal_periode($tahun, $bulan),
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'nama_laba_rugi' => $field_key,
            'unit' => $unit_key,
            'nominal' => $nominal_update,
            'nominal_update' => $nominal_update,
            'auto_sistem' => $auto_sistem,
            'status_sync_auto' => $status_sync_auto,
            'keterangan_data' => ($keterangan_data !== '') ? $keterangan_data : null,
            'jenis_tab' => 'utama',
            'tahun_transaksi' => $tahun,
            'bulan_transaksi' => $bulan,
        ));

        echo json_encode(array(
            'ok' => true,
            'message' => $status_sync_auto === 1 ? 'Nilai otomatis disalin ke data cetak laba rugi.' : 'Status sync otomatis disimpan.',
            'record_id' => $record_id,
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'status_sync_auto' => $status_sync_auto,
            'nominal_formatted' => labarugi_detail_format_nominal($nominal_update),
            'auto_sistem_formatted' => labarugi_detail_format_nominal($auto_sistem),
        ));
    }

    public function save_labarugi_unit_publish()
    {
        $this->load->helper(array('laba_rugi_detail', 'laba_rugi_unit_publish'));
        labarugi_unit_publish_ensure_table($this);
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $tahun = (int) $this->input->post('tahun');
        $bulan = (int) $this->input->post('bulan');
        $jenis_tab = trim((string) $this->input->post('jenis_tab'));
        $unit = trim((string) $this->input->post('unit'));
        $is_publish = (int) $this->input->post('is_publish');
        if ($this->input->post('status_publish_unit') !== null && $this->input->post('status_publish_unit') !== '') {
            $is_publish = (int) $this->input->post('status_publish_unit');
        }
        $status_publish_unit = ($is_publish === 1) ? 1 : 0;

        if ($tahun < 2000 || $bulan < 1 || $bulan > 12) {
            echo json_encode(array('ok' => false, 'message' => 'Periode tidak valid.'));
            return;
        }

        if (!in_array($jenis_tab, array('rinci', 'sederhana'), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Jenis tab tidak valid.'));
            return;
        }

        if ($unit === '') {
            echo json_encode(array('ok' => false, 'message' => 'Unit wajib diisi.'));
            return;
        }

        $uuid_laba_rugi = labarugi_detail_resolve_parent_uuid($this, $tahun, $bulan);
        if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
            echo json_encode(array('ok' => false, 'message' => 'Gagal menyiapkan UUID laba rugi.'));
            return;
        }

        $id = $this->Sys_labarugi_unit_publish_model->set_publish(array(
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'unit' => $unit,
            'jenis_tab' => $jenis_tab,
            'tahun_transaksi' => $tahun,
            'bulan_transaksi' => $bulan,
            'status_publish_unit' => $status_publish_unit,
            'is_publish' => ($status_publish_unit === 1),
        ));

        echo json_encode(array(
            'ok' => true,
            'message' => ($status_publish_unit === 1) ? 'Unit berhasil dipublish.' : 'Publish unit dibatalkan.',
            'id' => $id,
            'uuid_laba_rugi' => $uuid_laba_rugi,
            'status_publish_unit' => $status_publish_unit,
            'is_publish' => $status_publish_unit,
        ));
    }

    public function labarugi_print_unit($Get_tahun = null, $Get_bulan = null, $jenis_tab = 'rinci')
    {
        $this->load->helper(array('laba_rugi_detail', 'laba_rugi_keterangan', 'laba_rugi_unit_publish', 'laba_rugi_unit_merge', 'laba_rugi_unit_tab_sync', 'dashboard_laporan_publish'));

        $Get_tahun = (int) $Get_tahun;
        $Get_bulan = (int) $Get_bulan;
        $jenis_tab = trim((string) $jenis_tab);
        if (!in_array($jenis_tab, array('rinci', 'sederhana'), true)) {
            $jenis_tab = 'rinci';
        }

        if ($Get_bulan > 0) {
            dashboard_laporan_require_published_or_deny($this, 'laba_rugi', $Get_tahun, $Get_bulan);
        }

        $data_detail = $this->db->get_where('tbl_laba_rugi', array(
            'tahun_transaksi' => $Get_tahun,
            'bulan_transaksi' => $Get_bulan,
        ))->row();

        if (!$data_detail) {
            show_error('Data laba rugi periode ini belum tersedia.', 404);
            return;
        }

        $uuid_laba_rugi = $data_detail->uuid_data_laba_rugi;
        $list_unit = $this->db->order_by('nama_unit', 'ASC')->get('sys_unit')->result();
        $list_unit = labarugi_unit_merge_display_units($list_unit);
        $publish_map = labarugi_unit_publish_load_map($this, $uuid_laba_rugi, $jenis_tab, $Get_tahun, $Get_bulan);
        $published_units = labarugi_unit_merge_published_units($list_unit, $publish_map);

        if (empty($published_units)) {
            show_error('Belum ada unit yang dipublish (centang) untuk dicetak.', 404);
            return;
        }

        $this->load->library('PdfGenerator');
        $data = array(
            'tahun_laba_rugi' => $Get_tahun,
            'bulan_laba_rugi' => $Get_bulan,
            'jenis_tab' => $jenis_tab,
            'published_units' => $published_units,
            'keterangan_rows' => labarugi_keterangan_rows_by_tab($this, $jenis_tab),
            'detail_map' => labarugi_detail_load_map($this, $uuid_laba_rugi, $jenis_tab),
            'detail_maps' => array(
                'rinci' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'rinci'),
                'sederhana' => labarugi_detail_load_map($this, $uuid_laba_rugi, 'sederhana'),
            ),
        );

        $html = $this->load->view('anekadharma/tbl_laba_rugi/adminlte310_labarugi_cetak_unit.php', $data, true);
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $suffix = ($jenis_tab === 'sederhana') ? 'SEDERHANA' : 'RINCI';
        $this->pdf->stream('CETAK_LABA_RUGI_PER_UNIT_' . $suffix . '.pdf', array('Attachment' => 0));
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

    public function move_up_labarugi_keterangan()
    {
        $this->load->helper('laba_rugi_keterangan');
        header('Content-Type: application/json; charset=utf-8');

        if (strtolower($this->input->method()) !== 'post') {
            echo json_encode(array('ok' => false, 'message' => 'Method tidak valid.'));
            return;
        }

        $id = (int) $this->input->post('id');
        $result = $this->Sys_labarugi_keterangan_model->move_urutan_up($id);
        echo json_encode($result);
    }

    public function ajax_labarugi_setting_kode_akun($uuid_nama_keterangan = null)
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan', 'laba_rugi_unit_merge'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid_nama_keterangan = trim((string) $uuid_nama_keterangan);
        $status_labarugi = trim((string) $this->input->get('jenis_tab'));
        $nama_keterangan = trim((string) $this->input->get('nama_keterangan'));

        if ($uuid_nama_keterangan === '') {
            echo json_encode(array('ok' => false, 'message' => 'UUID keterangan tidak valid.'));
            return;
        }

        if (!in_array($status_labarugi, labarugi_keterangan_labarugi_status_options(), true)) {
            echo json_encode(array('ok' => false, 'message' => 'Status laba rugi tidak valid.'));
            return;
        }

        $payload = labarugi_kode_akun_setting_list_payload($this, $uuid_nama_keterangan, $status_labarugi, $nama_keterangan);

        echo json_encode(array(
            'ok' => true,
            'uuid_nama_keterangan' => $uuid_nama_keterangan,
            'nama_keterangan' => $payload['nama_keterangan'],
            'status_labarugi' => $status_labarugi,
            'available' => $payload['available'],
            'terpilih' => $payload['terpilih'],
        ));
    }

    public function ajax_labarugi_setting_kode_akun_pilih()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan', 'laba_rugi_unit_merge'));
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

        echo json_encode(array(
            'ok' => true,
            'message' => 'Kode akun berhasil dipilih.',
            'row' => array(
                'kode_akun' => $row_akun->kode_akun,
                'nama_akun' => isset($row_akun->nama_akun) ? $row_akun->nama_akun : '',
            ),
        ));
    }

    public function ajax_labarugi_setting_kode_akun_hapus()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan', 'laba_rugi_unit_merge'));
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
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan', 'laba_rugi_unit_merge'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid = trim((string) $this->input->get('uuid_nama_keterangan'));
        $jenis_tab = trim((string) $this->input->get('jenis_tab'));
        $unit_key = trim((string) $this->input->get('unit'));
        $unit_label = trim((string) $this->input->get('unit_label'));
        $tahun = (int) $this->input->get('tahun');
        $bulan = (int) $this->input->get('bulan');

        if ($uuid === '') {
            echo json_encode(array('ok' => false, 'message' => 'Parameter keterangan tidak lengkap.'));
            return;
        }

        $view_mode = trim((string) $this->input->get('view_mode'));
        if ($view_mode === 'utama') {
            $jenis_tab = 'utama';
            $nominal = labarugi_kode_akun_unit_nominal($this, $uuid, $jenis_tab, '', '', $tahun, $bulan, 'utama');
        } else {
            if ($unit_key === '') {
                echo json_encode(array('ok' => false, 'message' => 'Parameter unit tidak lengkap.'));
                return;
            }
            if (labarugi_unit_merge_is_merged_key($unit_key)) {
                $kodes = labarugi_kode_akun_selected_kodes($this, $uuid, $jenis_tab);
                $nominal = labarugi_unit_merge_kode_akun_nominal(
                    $this,
                    labarugi_kode_akun_merged_rows($this, $tahun, $bulan),
                    $kodes,
                    $unit_key,
                    $unit_label
                );
            } else {
                $nominal = labarugi_kode_akun_unit_nominal($this, $uuid, $jenis_tab, $unit_key, $unit_label, $tahun, $bulan, 'unit');
            }
        }
        echo json_encode(array(
            'ok' => true,
            'nominal' => $nominal,
            'nominal_formatted' => labarugi_kode_akun_format_nominal($nominal),
        ));
    }

    public function ajax_labarugi_transaksi_unit()
    {
        $this->load->helper(array('laba_rugi_kode_akun', 'laba_rugi_keterangan', 'laba_rugi_unit_merge'));
        header('Content-Type: application/json; charset=utf-8');

        $uuid = trim((string) $this->input->get('uuid_nama_keterangan'));
        $jenis_tab = trim((string) $this->input->get('jenis_tab'));
        $view_mode = trim((string) $this->input->get('view_mode'));
        $unit_key = trim((string) $this->input->get('unit'));
        $unit_label = trim((string) $this->input->get('unit_label'));
        $nama_keterangan = trim((string) $this->input->get('nama_keterangan'));
        $tahun = (int) $this->input->get('tahun');
        $bulan = (int) $this->input->get('bulan');

        if ($uuid === '') {
            echo json_encode(array('ok' => false, 'message' => 'Parameter keterangan tidak lengkap.', 'data' => array()));
            return;
        }

        if ($view_mode === 'utama') {
            $jenis_tab = 'utama';
            $filter_by_unit = false;
        } else {
            $view_mode = 'unit';
            if ($unit_key === '') {
                echo json_encode(array('ok' => false, 'message' => 'Parameter unit tidak lengkap.', 'data' => array()));
                return;
            }
            if (!in_array($jenis_tab, array('rinci', 'sederhana'), true)) {
                echo json_encode(array('ok' => false, 'message' => 'Jenis tab tidak valid.', 'data' => array()));
                return;
            }
        }

        if ($view_mode === 'utama') {
            $jenis_tab = 'utama';
            $payload = labarugi_kode_akun_transactions_payload($this, $uuid, $jenis_tab, $tahun, $bulan, $unit_key, $unit_label, $view_mode);
        } else {
            $payload = labarugi_unit_merge_transactions_payload($this, $uuid, $jenis_tab, $tahun, $bulan, $unit_key, $unit_label);
        }

        echo json_encode(array(
            'ok' => true,
            'nama_keterangan' => $nama_keterangan,
            'unit' => $unit_key,
            'view_mode' => $view_mode,
            'total_formatted' => labarugi_kode_akun_format_nominal(isset($payload['total']) ? $payload['total'] : 0),
            'empty_code' => isset($payload['empty_code']) ? $payload['empty_code'] : '',
            'empty_message' => isset($payload['empty_message']) ? $payload['empty_message'] : '',
            'effective_kodes' => isset($payload['effective_kodes']) ? $payload['effective_kodes'] : array(),
            'data' => isset($payload['rows']) ? $payload['rows'] : array(),
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

        $action = trim((string) $this->input->post('action'));
        $publish = ($action !== 'cancel');

        if ($publish && !dashboard_laporan_has_saved_data($this, 'tbl_laba_rugi', $Get_tahun, $Get_bulan)) {
            $this->session->set_flashdata('message', 'Data laporan belum tersimpan. Simpan data terlebih dahulu.');
            redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun . '/' . $Get_bulan));
            return;
        }

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