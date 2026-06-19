<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_nama_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Sys_nama_barang_model', 'Persediaan_model'));
        $this->load->library('form_validation');
    }

    public function refresh_data_from_persediaan_data()
    {
        $sql = "SELECT `namabarang`,`satuan` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`";

        foreach ($this->db->query($sql)->result() as $m) {
            // echo "<option value='$m->uuid_barang' ";
            // echo ">  " . strtoupper($m->kode_barang) . strtoupper($m->namabarang)  . "</option>";

            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => str_replace(" ", "", $m->namabarang),
                'nama_barang' => $m->namabarang,
                'satuan' => $m->satuan,
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_nama_barang_model->insert($data);
        }
        print_r("sys_data_barang selesai");
    }


    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_nama_barang/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_nama_barang/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_nama_barang/index.html';
            $config['first_url'] = base_url() . 'sys_nama_barang/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_nama_barang_model->total_rows($q);
        $sys_nama_barang = $this->Sys_nama_barang_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_nama_barang_data' => $sys_nama_barang,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->load->view('sys_nama_barang/sys_nama_barang_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_nama_barang/sys_nama_barang_list', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_barang' => $row->uuid_barang,
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'satuan' => $row->satuan,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_nama_barang/sys_nama_barang_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function create($source_form = null, $Get_create_add_uraian = null, $Get_uuid_spop = null)
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('sys_nama_barang/create_action/' . $source_form . '/' . $Get_create_add_uraian . '/' . $Get_uuid_spop),
            'id' => set_value('id'),
            'uuid_barang' => set_value('uuid_barang'),
            'kode_barang' => set_value('kode_barang'),
            'nama_barang' => set_value('nama_barang'),
            'satuan' => set_value('satuan'),
            'keterangan' => set_value('keterangan'),
            'source_form' => $source_form,
            'kategori' => set_value('kategori'),
            'kategori_barang_options' => $this->get_kategori_barang_options(),
        );
        // $this->load->view('sys_nama_barang/sys_nama_barang_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_nama_barang/sys_nama_barang_form', $data);
    }

    public function pembelian_modal_form()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('sys_nama_barang/create_action_ajax'),
            'id' => set_value('id'),
            'kode_barang' => set_value('kode_barang'),
            'nama_barang' => set_value('nama_barang'),
            'satuan' => set_value('satuan'),
            'keterangan' => set_value('keterangan'),
            'kategori' => set_value('kategori'),
            'kategori_barang_options' => $this->get_kategori_barang_options(),
        );

        $this->load->view('anekadharma/sys_nama_barang/sys_nama_barang_form_pembelian_modal', $data);
    }

    public function list_barang_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->load->helper('pembelian_persediaan');

        $tanggal_po = trim((string) $this->input->get('tanggal_po', TRUE));
        if ($tanggal_po === '') {
            $tanggal_po = trim((string) $this->input->post('tanggal_po', TRUE));
        }
        if ($tanggal_po !== '') {
            $tgl = pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);
        } else {
            $tgl = pembelian_get_filter_tanggal($this);
        }

        $rows = pembelian_get_barang_list_rows($this);
        foreach ($rows as $row) {
            if (!isset($row->kategori)) {
                $row->kategori = '';
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'data' => $rows,
            'bulan_label' => $tgl['bulan_label'],
            'tanggal_awal' => $tgl['awal'],
            'tanggal_akhir' => $tgl['akhir'],
        ));
    }

    /**
     * AJAX combobox modal Tambah Barang Beli (create pembelian): group by nama, satuan, HPP.
     */
    public function list_barang_combobox_modal_ajax()
    {
        $this->load->helper('pembelian_persediaan');

        $rows = pembelian_get_barang_combobox_modal_rows($this);
        foreach ($rows as $row) {
            $row->label_barang = pembelian_format_barang_combobox_label(
                isset($row->nama_barang) ? $row->nama_barang : '',
                isset($row->satuan) ? $row->satuan : '',
                isset($row->harga_satuan) ? $row->harga_satuan : ''
            );
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'data' => $rows,
            'total' => count($rows),
        ));
    }

    public function cek_nama_barang_persediaan_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        header('Content-Type: application/json');

        $nama_barang = trim((string) $this->input->get_post('nama_barang', TRUE));
        if ($nama_barang === '') {
            echo json_encode(array(
                'success' => false,
                'message' => 'Nama barang wajib diisi.'
            ));
            return;
        }

        $this->load->helper('pembelian_persediaan');

        $tanggal_po = trim((string) $this->input->get_post('tanggal_po', TRUE));
        if ($tanggal_po !== '') {
            $tgl = pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);
        } else {
            $tgl = pembelian_get_filter_tanggal($this);
        }

        $nama_norm = pembelian_normalize_nama_barang($nama_barang);
        $di_bulan = pembelian_find_barang_by_nama($this, $nama_norm, $tanggal_po !== '' ? $tanggal_po : null);
        $semua_bulan = pembelian_find_barang_referensi_persediaan($this, $nama_norm);

        $rows = array();
        $bulan_pilih = $tgl['bulan_label'];
        foreach ($semua_bulan as $row) {
            $rows[] = array(
                'id' => isset($row->id) ? (int) $row->id : 0,
                'uuid_barang' => isset($row->uuid_barang) ? $row->uuid_barang : '',
                'kode_barang' => isset($row->kode_barang) ? $row->kode_barang : '',
                'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
                'satuan' => isset($row->satuan) ? $row->satuan : '',
                'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
                'tanggal_beli' => isset($row->tanggal_beli) ? $row->tanggal_beli : '',
                'bulan_label' => isset($row->bulan_label) ? $row->bulan_label : '',
            );
        }

        $rows_lain_bulan = array();
        foreach ($rows as $r) {
            if (!isset($r['bulan_label']) || $r['bulan_label'] !== $bulan_pilih) {
                $rows_lain_bulan[] = $r;
            }
        }

        $exists_in_month = ($di_bulan !== null);
        if (!$exists_in_month && count($rows) > 0 && count($rows_lain_bulan) === 0) {
            $exists_in_month = true;
        }

        $rows_tampil = $rows_lain_bulan;
        $show_referensi_modal = (!$exists_in_month && count($rows_tampil) > 0);

        echo json_encode(array(
            'success' => true,
            'exists_in_month' => $exists_in_month,
            'exists_in_system' => (count($rows) > 0),
            'show_referensi_modal' => $show_referensi_modal,
            'bulan_label' => $tgl['bulan_label'],
            'data_in_month' => $di_bulan ? array(
                'id' => isset($di_bulan->id) ? (int) $di_bulan->id : 0,
                'uuid_barang' => $di_bulan->uuid_barang,
                'kode_barang' => isset($di_bulan->kode_barang) ? $di_bulan->kode_barang : '',
                'nama_barang' => $di_bulan->nama_barang,
                'satuan' => $di_bulan->satuan,
                'harga_satuan' => $di_bulan->harga_satuan,
                'tanggal_beli' => isset($di_bulan->tanggal_beli) ? $di_bulan->tanggal_beli : '',
            ) : null,
            'data' => $rows_tampil,
            'total_referensi' => count($rows_tampil),
            'message' => ($exists_in_month)
                ? 'Nama barang sudah ada di persediaan bulan ' . $tgl['bulan_label'] . '.'
                : (($show_referensi_modal)
                    ? 'Nama barang sudah ada di sistem (bulan lain). Pilih dan gunakan salah satu referensi di daftar, atau lanjutkan isian manual.'
                    : 'Nama barang belum ada di sistem (bulan ' . $tgl['bulan_label'] . ').'),
        ));
    }

    public function generate_barang_referensi_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        header('Content-Type: application/json');

        $this->load->helper('pembelian_persediaan');

        $persediaan_id = (int) $this->input->get_post('persediaan_id', TRUE);
        $uuid_barang = trim((string) $this->input->get_post('uuid_barang', TRUE));

        $row = null;
        if ($persediaan_id > 0) {
            $row = pembelian_get_persediaan_record_by_id($this, $persediaan_id);
        }

        if (!$row && $uuid_barang !== '') {
            $sql = "SELECT
					`id`,
					COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
					`kode` AS kode_barang,
					`namabarang` AS nama_barang,
					`satuan`,
					`hpp` AS harga_satuan,
					`tanggal_beli`,
					DATE_FORMAT(`tanggal_beli`, '%m/%Y') AS bulan_label
				FROM `persediaan`
				WHERE (`uuid_barang` = ? OR `uuid_persediaan` = ?)
				ORDER BY `id` DESC
				LIMIT 1";
            $row = $this->db->query($sql, array($uuid_barang, $uuid_barang))->row();
        }

        if (!$row) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Record persediaan tidak ditemukan.'
            ));
            return;
        }

        echo json_encode(array(
            'success' => true,
            'message' => 'Data referensi berhasil diambil.',
            'data' => array(
                'id' => isset($row->id) ? (int) $row->id : 0,
                'uuid_barang' => isset($row->uuid_barang) ? $row->uuid_barang : '',
                'kode_barang' => isset($row->kode_barang) ? $row->kode_barang : '',
                'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
                'satuan' => isset($row->satuan) ? $row->satuan : '',
                'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
                'bulan_label' => isset($row->bulan_label) ? $row->bulan_label : '',
                'tanggal_beli' => isset($row->tanggal_beli) ? $row->tanggal_beli : '',
            ),
        ));
    }

    public function detail_barang_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        header('Content-Type: application/json');

        $uuid_barang = trim((string) $this->input->get('uuid_barang', TRUE));
        if ($uuid_barang === '') {
            echo json_encode(array('success' => false, 'message' => 'Barang belum dipilih.'));
            return;
        }

        $this->load->helper('pembelian_persediaan');

        $tanggal_po = trim((string) $this->input->get('tanggal_po', TRUE));
        if ($tanggal_po !== '') {
            pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);
        }

        $barang = pembelian_get_barang_by_uuid($this, $uuid_barang);

        if (!$barang) {
            echo json_encode(array('success' => false, 'message' => 'Data barang tidak ditemukan di persediaan untuk bulan terpilih.'));
            return;
        }

        $harga_satuan = isset($barang->harga_satuan) ? $barang->harga_satuan : '';
        $kategori = '';

        echo json_encode(array(
            'success' => true,
            'data' => array(
                'uuid_barang' => $barang->uuid_barang,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $kategori,
                'satuan' => $barang->satuan,
                'harga_satuan' => $harga_satuan,
            )
        ));
    }

    public function create_action_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        header('Content-Type: application/json');

        $this->load->helper('pembelian_persediaan');

        $tanggal_po = trim((string) $this->input->post('tanggal_po', TRUE));
        if ($tanggal_po === '') {
            echo json_encode(array(
                'success' => false,
                'message' => 'Silakan pilih Tgl PO di halaman pembelian terlebih dahulu (datepicker tanggal PO).'
            ));
            return;
        }

        if (pembelian_parse_tanggal_po($tanggal_po) === false) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Format Tgl PO tidak valid. Silakan pilih tanggal dari datepicker.'
            ));
            return;
        }

        $tgl = pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);

        $nama_barang = pembelian_normalize_nama_barang($this->input->post('nama_barang', TRUE));
        $kategori = trim((string) $this->input->post('kategori', TRUE));
        $kode_barang = pembelian_kode_barang_opsional($nama_barang, $this->input->post('kode_barang', TRUE));

        if ($nama_barang === '') {
            echo json_encode(array(
                'success' => false,
                'message' => 'Nama barang wajib diisi.'
            ));
            return;
        }

        // Nama boleh sama di bulan yang sama (satuan/HPP bisa berbeda) → selalu insert baris persediaan baru + uuid baru
        $uuid_barang_baru = str_replace('-', '', $this->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row()->u);
        $hpp_raw = trim((string) $this->input->post('harga_satuan', TRUE));
        $hpp_baru = preg_replace('/[^0-9]/', '', str_replace('.', '', $hpp_raw));
        if ($hpp_baru === '') {
            $hpp_baru = '0';
        }

        $data_persediaan = array(
            'tanggal' => date('Y-m-d H:i:s'),
            'tanggal_beli' => $tgl['awal_bulan'],
            'uuid_barang' => $uuid_barang_baru,
            'kode' => $kode_barang,
            'namabarang' => $nama_barang,
            'satuan' => $this->input->post('satuan', TRUE),
            'hpp' => $hpp_baru !== '' ? $hpp_baru : 0,
            'sa' => 0,
            'beli' => 0,
            'total_10' => 0,
            'nilai_persediaan' => 0,
        );

        $id_persediaan = $this->Persediaan_model->insert_produk_baru($data_persediaan);
        $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan);

        $row = (object) array(
            'uuid_barang' => $row_persediaan && !empty($row_persediaan->uuid_barang) ? $row_persediaan->uuid_barang : ($row_persediaan ? $row_persediaan->uuid_persediaan : $uuid_barang_baru),
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'satuan' => $this->input->post('satuan', TRUE),
            'kategori' => $kategori,
            'harga_satuan' => $hpp_baru,
        );

        echo json_encode(array(
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke persediaan bulan ' . $tgl['bulan_label']
                . ' (tanggal beli: ' . $tgl['awal_bulan'] . ').',
            'bulan_label' => $tgl['bulan_label'],
            'tanggal_beli' => $tgl['awal_bulan'],
            'data' => $row
        ));
    }




    public function create_action($source_form = null, $Get_create_add_uraian = null, $Get_uuid_spop = null)
    {


        $this->_rules();


        // CEK Apakah Sudah ada nama barang yang sama
        // query chek sys_nama_barang
        $this->db->where('nama_barang', $this->input->post('nama_barang', TRUE));
        $sys_nama_barang = $this->db->get('sys_nama_barang');

        if ($sys_nama_barang->num_rows() > 0) {
            $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
            $this->create();
        }



        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {


            // print_r("create_action");
            // print_r("<br/>");
            // print_r($source_form);
            // print_r("<br/>");
            // print_r($Get_create_add_uraian);
            // print_r("<br/>");
            // print_r($Get_uuid_spop);
            // print_r("<br/>");


            $kode_barang_input = strtoupper(trim((string) $this->input->post('kode_barang', TRUE)));
            $get_kode_barang = $kode_barang_input;
            if ($get_kode_barang === '') {
                // fallback lama: auto generate jika input kosong
                $teks = $this->input->post('nama_barang', TRUE);
                $split = explode(' ', (string) $teks);
                foreach ($split as $kata) {
                    if (trim($kata) === '') {
                        continue;
                    }
                    $get_kode_barang .= substr($kata, 0, 2);
                }
                $this->db->where('kode_barang', $get_kode_barang);
                $sys_nama_barang = $this->db->get('sys_nama_barang');
                if ($sys_nama_barang->num_rows() > 0) {
                    $get_kode_barang = $get_kode_barang . "_" . $sys_nama_barang->num_rows();
                }
                $get_kode_barang = strtoupper($get_kode_barang);
            }

            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => $get_kode_barang,
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );
            if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
                $data['kategori'] = trim((string) $this->input->post('kategori', TRUE));
            }
            if ($this->db->field_exists('uuid_kategori', 'sys_nama_barang')) {
                $kategori = trim((string) $this->input->post('kategori', TRUE));
                if ($kategori !== '') {
                    $rowKat = $this->db->select('uuid_kategori')->where('kategori', $kategori)->get('sys_kategori_barang')->row();
                    $data['uuid_kategori'] = $rowKat ? $rowKat->uuid_kategori : null;
                } else {
                    $data['uuid_kategori'] = null;
                }
            }

            $id_barang_insert = $this->Sys_nama_barang_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');

            // simpan ke data persediaan


            $row = $this->Sys_nama_barang_model->get_by_id($id_barang_insert);

            // print_r($id_barang_insert);
            // print_r("<br/>");
            // print_r($row->uuid_barang);
            // print_r("<br/>");
            // print_r($row->kode_barang);
            // print_r("<br/>");
            // print_r($row->nama_barang);
            // print_r("<br/>");
            // print_r($row->satuan);
            // print_r("<br/>");
            // print_r($row->keterangan);
            // print_r("<br/>");
            // die;

            // $data = array(
            //     'button' => 'Update',
            //     'action' => site_url('sys_nama_barang/update_action'),
            //     'id' => set_value('id', $row->id),
            //     // 'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
            //     'kode_barang' => set_value('kode_barang', $row->kode_barang),
            //     'nama_barang' => set_value('nama_barang', $row->nama_barang),
            //     'satuan' => set_value('satuan', $row->satuan),
            //     'keterangan' => set_value('keterangan', $row->keterangan),
            // );



            // $data = array(
            //     // 'id' => $this->input->post('id', TRUE),
            //     // 'tanggal' => $this->input->post('tanggal', TRUE),
            //     'uuid_barang' => $row->uuid_barang,
            //     'kode' => $row->kode_barang,
            //     'namabarang' => $row->nama_barang,
            //     'satuan' => $row->satuan,
            //     // 'hpp' => $this->input->post('hpp', TRUE),
            //     // 'sa' => $this->input->post('sa', TRUE),
            //     // 'spop' => $this->input->post('spop', TRUE),
            //     // 'beli' => $this->input->post('beli', TRUE),
            //     // 'tuj' => $this->input->post('tuj', TRUE),
            //     // 'tgl_keluar' => $this->input->post('tgl_keluar', TRUE),
            //     // 'sekret' => $this->input->post('sekret', TRUE),
            //     // 'cetak' => $this->input->post('cetak', TRUE),
            //     // 'grafikita' => $this->input->post('grafikita', TRUE),
            //     // 'dinas_umum' => $this->input->post('dinas_umum', TRUE),
            //     // 'atk_rsud' => $this->input->post('atk_rsud', TRUE),
            //     // 'ppbmp_kbs' => $this->input->post('ppbmp_kbs', TRUE),
            //     // 'kbs' => $this->input->post('kbs', TRUE),
            //     // 'ppbmp' => $this->input->post('ppbmp', TRUE),
            //     // 'medis' => $this->input->post('medis', TRUE),
            //     // 'siiplah_bosda' => $this->input->post('siiplah_bosda', TRUE),
            //     // 'sembako' => $this->input->post('sembako', TRUE),
            //     // 'fc_gose' => $this->input->post('fc_gose', TRUE),
            //     // 'fc_manding' => $this->input->post('fc_manding', TRUE),
            //     // 'fc_psamya' => $this->input->post('fc_psamya', TRUE),
            //     // 'total_10' => $this->input->post('total_10', TRUE),
            //     // 'nilai_persediaan' => $this->input->post('nilai_persediaan', TRUE),
            // );

            // $this->Persediaan_model->insert($data);



            if ($source_form) {
                // print_r("create_action");
                // print_r("<br/>");
                // print_r("source_form");
                // print_r("<br/>");
                // print_r($source_form);
                // die;

                // $previous = "javascript:history.go(-1)";
                // if(isset($_SERVER['HTTP_REFERER'])) {
                //     $previous = $_SERVER['HTTP_REFERER'];
                // }

                // sleep(5);

                // header( base_url("/tbl_pembelian/create"));

                // print_r($source_form);

                // print_r($source_form);
                // print_r("<br/>");
                // print_r($Get_create_add_uraian);
                // print_r("<br/>");
                // print_r($Get_uuid_spop);
                // print_r("<br/>");


                // die;


                redirect(site_url($source_form .'/'. $Get_create_add_uraian .'/'. $Get_uuid_spop));
            } else {
                redirect(site_url('sys_nama_barang'));
            }
        }
    }

    public function update($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_nama_barang/update_action'),
                'id' => set_value('id', $row->id),
                // 'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
                'kode_barang' => set_value('kode_barang', $row->kode_barang),
                'nama_barang' => set_value('nama_barang', $row->nama_barang),
                'satuan' => set_value('satuan', $row->satuan),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kategori' => set_value('kategori', isset($row->kategori) ? $row->kategori : ''),
                'kategori_barang_options' => $this->get_kategori_barang_options(),
                'source_form' => '',
            );
            // $this->load->view('sys_nama_barang/sys_nama_barang_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_nama_barang/sys_nama_barang_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => strtoupper(trim((string) $this->input->post('kode_barang', TRUE))),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );
            if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
                $data['kategori'] = trim((string) $this->input->post('kategori', TRUE));
            }
            if ($this->db->field_exists('uuid_kategori', 'sys_nama_barang')) {
                $kategori = trim((string) $this->input->post('kategori', TRUE));
                if ($kategori !== '') {
                    $rowKat = $this->db->select('uuid_kategori')->where('kategori', $kategori)->get('sys_kategori_barang')->row();
                    $data['uuid_kategori'] = $rowKat ? $rowKat->uuid_kategori : null;
                } else {
                    $data['uuid_kategori'] = null;
                }
            }

            $this->Sys_nama_barang_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);

        if ($row) {
            $this->Sys_nama_barang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_nama_barang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
        $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
        // $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    private function get_kategori_barang_options()
    {
        if ($this->db->table_exists('sys_kategori_barang')) {
            return $this->db->select('id, uuid_kategori, kategori')
                ->from('sys_kategori_barang')
                ->where('TRIM(kategori) <>', '')
                ->order_by('kategori', 'ASC')
                ->get()
                ->result();
        }
        if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
            return $this->db->query(
                "SELECT `kategori` FROM `sys_nama_barang` WHERE `kategori` IS NOT NULL AND TRIM(`kategori`) <> '' GROUP BY `kategori` ORDER BY `kategori` ASC"
            )->result();
        }
        return array();
    }

    private function find_kategori_existing($kategori)
    {
        $kategoriKey = strtolower(trim($kategori));
        if ($kategoriKey === '') {
            return null;
        }

        if ($this->db->table_exists('sys_kategori_barang')) {
            $row = $this->db->query(
                "SELECT `id`, `uuid_kategori`, `kategori` FROM `sys_kategori_barang` WHERE TRIM(`kategori`) <> '' AND LOWER(TRIM(`kategori`)) = ? LIMIT 1",
                array($kategoriKey)
            )->row();
            if ($row) {
                return $row;
            }
        }

        if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
            $row = $this->db->query(
                "SELECT `kategori` FROM `sys_nama_barang` WHERE TRIM(`kategori`) <> '' AND LOWER(TRIM(`kategori`)) = ? LIMIT 1",
                array($kategoriKey)
            )->row();
            if ($row) {
                return $row;
            }
        }

        return null;
    }

    public function add_kategori_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        header('Content-Type: application/json');

        $kategori = trim((string) $this->input->post('kategori', TRUE));
        if ($kategori === '') {
            echo json_encode(array('success' => false, 'message' => 'Kategori wajib diisi.'));
            return;
        }

        $existing = $this->find_kategori_existing($kategori);
        if ($existing) {
            $kategoriTersimpan = isset($existing->kategori) ? trim($existing->kategori) : $kategori;
            echo json_encode(array(
                'success' => false,
                'exists' => true,
                'duplicate' => true,
                'message' => 'Kategori sudah ada di sistem, silahkan digunakan.',
                'data' => array('kategori' => $kategoriTersimpan)
            ));
            return;
        }

        if ($this->db->table_exists('sys_kategori_barang')) {
            $this->db->set('uuid_kategori', "replace(uuid(),'-','')", FALSE);
            $this->db->set('kategori', $kategori);
            $this->db->insert('sys_kategori_barang');
            $id = $this->db->insert_id();
            $newRow = $this->db->select('id, uuid_kategori, kategori')->where('id', $id)->get('sys_kategori_barang')->row();
            echo json_encode(array(
                'success' => true,
                'message' => 'Kategori berhasil disimpan dan siap digunakan.',
                'data' => array(
                    'kategori' => $newRow ? $newRow->kategori : $kategori,
                    'uuid_kategori' => $newRow && isset($newRow->uuid_kategori) ? $newRow->uuid_kategori : null
                )
            ));
            return;
        }

        if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Kategori siap digunakan. Simpan data jasa untuk menyimpan ke sistem.',
                'data' => array('kategori' => $kategori)
            ));
            return;
        }

        echo json_encode(array('success' => false, 'message' => 'Tabel kategori tidak tersedia di sistem.'));
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_nama_barang.xls";
        $judul = "sys_nama_barang";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_nama_barang_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_nama_barang.php */
/* Location: ./application/controllers/Sys_nama_barang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-11 15:47:14 */
/* http://harviacode.com */