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

        $select_barang = 'uuid_barang, kode_barang, nama_barang, satuan';
        if ($this->db->field_exists('harga_satuan', 'sys_nama_barang')) {
            $select_barang .= ', harga_satuan';
        }

        $rows = $this->db->select($select_barang)
            ->from('sys_nama_barang')
            ->group_by('nama_barang')
            ->order_by('nama_barang', 'ASC')
            ->get()
            ->result();

        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'data' => $rows));
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

        $select_barang = 'uuid_barang, kode_barang, nama_barang, satuan';
        if ($this->db->field_exists('harga_satuan', 'sys_nama_barang')) {
            $select_barang .= ', harga_satuan';
        }

        $barang = $this->db->select($select_barang)
            ->where('uuid_barang', $uuid_barang)
            ->get('sys_nama_barang')
            ->row();

        if (!$barang) {
            echo json_encode(array('success' => false, 'message' => 'Data barang tidak ditemukan.'));
            return;
        }

        $harga_satuan = isset($barang->harga_satuan) ? $barang->harga_satuan : '';

        echo json_encode(array(
            'success' => true,
            'data' => array(
                'uuid_barang' => $barang->uuid_barang,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
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

        $kode_barang = strtoupper(trim((string) $this->input->post('kode_barang', TRUE)));
        $nama_barang = trim((string) $this->input->post('nama_barang', TRUE));
        $kategori = trim((string) $this->input->post('kategori', TRUE));

        if ($kode_barang === '' || $nama_barang === '') {
            echo json_encode(array(
                'success' => false,
                'message' => 'Kode barang dan nama barang wajib diisi.'
            ));
            return;
        }

        $existing = $this->db->select('id, uuid_barang, kode_barang, nama_barang')
            ->where('LOWER(nama_barang)=', strtolower($nama_barang))
            ->get('sys_nama_barang')
            ->row();

        if ($existing) {
            echo json_encode(array(
                'success' => false,
                'duplicate' => true,
                'message' => 'Nama barang sudah ada. Silakan pilih barang tersebut dari combobox.',
                'data' => $existing
            ));
            return;
        }

        $data = array(
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'satuan' => $this->input->post('satuan', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
        );

        if ($this->db->field_exists('kategori', 'sys_nama_barang')) {
            $data['kategori'] = $kategori;
        }

        if ($this->db->field_exists('uuid_kategori', 'sys_nama_barang')) {
            if ($kategori !== '') {
                $rowKat = $this->db->select('uuid_kategori')
                    ->where('kategori', $kategori)
                    ->get('sys_kategori_barang')
                    ->row();
                $data['uuid_kategori'] = $rowKat ? $rowKat->uuid_kategori : null;
            } else {
                $data['uuid_kategori'] = null;
            }
        }

        $id_barang_insert = $this->Sys_nama_barang_model->insert($data);
        $row = $this->Sys_nama_barang_model->get_by_id($id_barang_insert);

        echo json_encode(array(
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
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
        if (!$this->db->table_exists('sys_kategori_barang')) {
            return array();
        }
        return $this->db->select('id, uuid_kategori, kategori')
            ->from('sys_kategori_barang')
            ->order_by('kategori', 'ASC')
            ->get()
            ->result();
    }

    public function add_kategori_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        if (!$this->db->table_exists('sys_kategori_barang')) {
            echo json_encode(array('success' => false, 'message' => 'Tabel sys_kategori_barang tidak ditemukan.'));
            return;
        }
        $kategori = trim((string) $this->input->post('kategori', TRUE));
        if ($kategori === '') {
            echo json_encode(array('success' => false, 'message' => 'Kategori wajib diisi.'));
            return;
        }
        $row = $this->db->select('id, uuid_kategori, kategori')
            ->where('LOWER(kategori)=', strtolower($kategori))
            ->get('sys_kategori_barang')->row();
        if ($row) {
            $matches = $this->db->select('id, uuid_kategori, kategori')
                ->from('sys_kategori_barang')
                ->like('kategori', $kategori)
                ->order_by('kategori', 'ASC')
                ->get()->result();
            if (empty($matches)) {
                $matches = array($row);
            }
            echo json_encode(array(
                'success' => false,
                'duplicate' => true,
                'message' => 'Kategori sudah ada. Silakan pilih kategori yang tersedia.',
                'data' => $row,
                'matches' => $matches
            ));
            return;
        }
        $this->db->set('uuid_kategori', "replace(uuid(),'-','')", FALSE);
        $this->db->set('kategori', $kategori);
        $this->db->insert('sys_kategori_barang');
        $id = $this->db->insert_id();
        $newRow = $this->db->select('id, uuid_kategori, kategori')->where('id', $id)->get('sys_kategori_barang')->row();
        echo json_encode(array('success' => true, 'message' => 'Kategori berhasil ditambahkan.', 'data' => $newRow));
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