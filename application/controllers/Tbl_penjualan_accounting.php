<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penjualan_accounting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_penjualan_accounting_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('anekadharma/tbl_penjualan_accounting/tbl_penjualan_accounting_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Tbl_penjualan_accounting_model->json();
    }

    public function read($id) 
    {
        $row = $this->Tbl_penjualan_accounting_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_penjualan_proses' => $row->uuid_penjualan_proses,
		'uuid_penjualan' => $row->uuid_penjualan,
		'uuid_persediaan' => $row->uuid_persediaan,
		'id_persediaan_barang' => $row->id_persediaan_barang,
		'uuid_barang' => $row->uuid_barang,
		'tgl_input' => $row->tgl_input,
		'tgl_jual' => $row->tgl_jual,
		'nmrpesan' => $row->nmrpesan,
		'nmrkirim' => $row->nmrkirim,
		'uuid_konsumen' => $row->uuid_konsumen,
		'konsumen_id' => $row->konsumen_id,
		'konsumen_nama' => $row->konsumen_nama,
		'kode_barang' => $row->kode_barang,
		'nama_barang' => $row->nama_barang,
		'uuid_unit' => $row->uuid_unit,
		'unit' => $row->unit,
		'satuan' => $row->satuan,
		'harga_satuan' => $row->harga_satuan,
		'jumlah' => $row->jumlah,
		'total_nominal' => $row->total_nominal,
		'umpphpsl22' => $row->umpphpsl22,
		'piutang' => $row->piutang,
		'penjualandpp' => $row->penjualandpp,
		'utangppn' => $row->utangppn,
		'cetak_bukti_penjualan' => $row->cetak_bukti_penjualan,
		'id_usr' => $row->id_usr,
		'proses_bayar' => $row->proses_bayar,
		'tgl_bayar_input' => $row->tgl_bayar_input,
		'tgl_bayar' => $row->tgl_bayar,
		'nmr_bukti_bayar' => $row->nmr_bukti_bayar,
		'kode_akun' => $row->kode_akun,
		'proses_transaksi' => $row->proses_transaksi,
	    );
            $this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_penjualan_accounting/create_action'),
	    'id' => set_value('id'),
	    'uuid_penjualan_proses' => set_value('uuid_penjualan_proses'),
	    'uuid_penjualan' => set_value('uuid_penjualan'),
	    'uuid_persediaan' => set_value('uuid_persediaan'),
	    'id_persediaan_barang' => set_value('id_persediaan_barang'),
	    'uuid_barang' => set_value('uuid_barang'),
	    'tgl_input' => set_value('tgl_input'),
	    'tgl_jual' => set_value('tgl_jual'),
	    'nmrpesan' => set_value('nmrpesan'),
	    'nmrkirim' => set_value('nmrkirim'),
	    'uuid_konsumen' => set_value('uuid_konsumen'),
	    'konsumen_id' => set_value('konsumen_id'),
	    'konsumen_nama' => set_value('konsumen_nama'),
	    'kode_barang' => set_value('kode_barang'),
	    'nama_barang' => set_value('nama_barang'),
	    'uuid_unit' => set_value('uuid_unit'),
	    'unit' => set_value('unit'),
	    'satuan' => set_value('satuan'),
	    'harga_satuan' => set_value('harga_satuan'),
	    'jumlah' => set_value('jumlah'),
	    'total_nominal' => set_value('total_nominal'),
	    'umpphpsl22' => set_value('umpphpsl22'),
	    'piutang' => set_value('piutang'),
	    'penjualandpp' => set_value('penjualandpp'),
	    'utangppn' => set_value('utangppn'),
	    'cetak_bukti_penjualan' => set_value('cetak_bukti_penjualan'),
	    'id_usr' => set_value('id_usr'),
	    'proses_bayar' => set_value('proses_bayar'),
	    'tgl_bayar_input' => set_value('tgl_bayar_input'),
	    'tgl_bayar' => set_value('tgl_bayar'),
	    'nmr_bukti_bayar' => set_value('nmr_bukti_bayar'),
	    'kode_akun' => set_value('kode_akun'),
	    'proses_transaksi' => set_value('proses_transaksi'),
	);
        $this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_penjualan_proses' => $this->input->post('uuid_penjualan_proses',TRUE),
		'uuid_penjualan' => $this->input->post('uuid_penjualan',TRUE),
		'uuid_persediaan' => $this->input->post('uuid_persediaan',TRUE),
		'id_persediaan_barang' => $this->input->post('id_persediaan_barang',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_input' => $this->input->post('tgl_input',TRUE),
		'tgl_jual' => $this->input->post('tgl_jual',TRUE),
		'nmrpesan' => $this->input->post('nmrpesan',TRUE),
		'nmrkirim' => $this->input->post('nmrkirim',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen_id' => $this->input->post('konsumen_id',TRUE),
		'konsumen_nama' => $this->input->post('konsumen_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'total_nominal' => $this->input->post('total_nominal',TRUE),
		'umpphpsl22' => $this->input->post('umpphpsl22',TRUE),
		'piutang' => $this->input->post('piutang',TRUE),
		'penjualandpp' => $this->input->post('penjualandpp',TRUE),
		'utangppn' => $this->input->post('utangppn',TRUE),
		'cetak_bukti_penjualan' => $this->input->post('cetak_bukti_penjualan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
		'proses_bayar' => $this->input->post('proses_bayar',TRUE),
		'tgl_bayar_input' => $this->input->post('tgl_bayar_input',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'proses_transaksi' => $this->input->post('proses_transaksi',TRUE),
	    );

            $this->Tbl_penjualan_accounting_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_penjualan_accounting'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_penjualan_accounting_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_penjualan_accounting/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_penjualan_proses' => set_value('uuid_penjualan_proses', $row->uuid_penjualan_proses),
		'uuid_penjualan' => set_value('uuid_penjualan', $row->uuid_penjualan),
		'uuid_persediaan' => set_value('uuid_persediaan', $row->uuid_persediaan),
		'id_persediaan_barang' => set_value('id_persediaan_barang', $row->id_persediaan_barang),
		'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
		'tgl_input' => set_value('tgl_input', $row->tgl_input),
		'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
		'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
		'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
		'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
		'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
		'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),
		'kode_barang' => set_value('kode_barang', $row->kode_barang),
		'nama_barang' => set_value('nama_barang', $row->nama_barang),
		'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
		'unit' => set_value('unit', $row->unit),
		'satuan' => set_value('satuan', $row->satuan),
		'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
		'jumlah' => set_value('jumlah', $row->jumlah),
		'total_nominal' => set_value('total_nominal', $row->total_nominal),
		'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
		'piutang' => set_value('piutang', $row->piutang),
		'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
		'utangppn' => set_value('utangppn', $row->utangppn),
		'cetak_bukti_penjualan' => set_value('cetak_bukti_penjualan', $row->cetak_bukti_penjualan),
		'id_usr' => set_value('id_usr', $row->id_usr),
		'proses_bayar' => set_value('proses_bayar', $row->proses_bayar),
		'tgl_bayar_input' => set_value('tgl_bayar_input', $row->tgl_bayar_input),
		'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
		'nmr_bukti_bayar' => set_value('nmr_bukti_bayar', $row->nmr_bukti_bayar),
		'kode_akun' => set_value('kode_akun', $row->kode_akun),
		'proses_transaksi' => set_value('proses_transaksi', $row->proses_transaksi),
	    );
            $this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_penjualan_proses' => $this->input->post('uuid_penjualan_proses',TRUE),
		'uuid_penjualan' => $this->input->post('uuid_penjualan',TRUE),
		'uuid_persediaan' => $this->input->post('uuid_persediaan',TRUE),
		'id_persediaan_barang' => $this->input->post('id_persediaan_barang',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_input' => $this->input->post('tgl_input',TRUE),
		'tgl_jual' => $this->input->post('tgl_jual',TRUE),
		'nmrpesan' => $this->input->post('nmrpesan',TRUE),
		'nmrkirim' => $this->input->post('nmrkirim',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen_id' => $this->input->post('konsumen_id',TRUE),
		'konsumen_nama' => $this->input->post('konsumen_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'total_nominal' => $this->input->post('total_nominal',TRUE),
		'umpphpsl22' => $this->input->post('umpphpsl22',TRUE),
		'piutang' => $this->input->post('piutang',TRUE),
		'penjualandpp' => $this->input->post('penjualandpp',TRUE),
		'utangppn' => $this->input->post('utangppn',TRUE),
		'cetak_bukti_penjualan' => $this->input->post('cetak_bukti_penjualan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
		'proses_bayar' => $this->input->post('proses_bayar',TRUE),
		'tgl_bayar_input' => $this->input->post('tgl_bayar_input',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'proses_transaksi' => $this->input->post('proses_transaksi',TRUE),
	    );

            $this->Tbl_penjualan_accounting_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_penjualan_accounting'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_penjualan_accounting_model->get_by_id($id);

        if ($row) {
            $this->Tbl_penjualan_accounting_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_penjualan_accounting'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_penjualan_proses', 'uuid penjualan proses', 'trim|required');
	$this->form_validation->set_rules('uuid_penjualan', 'uuid penjualan', 'trim|required');
	$this->form_validation->set_rules('uuid_persediaan', 'uuid persediaan', 'trim|required');
	$this->form_validation->set_rules('id_persediaan_barang', 'id persediaan barang', 'trim|required');
	$this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
	$this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
	$this->form_validation->set_rules('tgl_jual', 'tgl jual', 'trim|required');
	$this->form_validation->set_rules('nmrpesan', 'nmrpesan', 'trim|required');
	$this->form_validation->set_rules('nmrkirim', 'nmrkirim', 'trim|required');
	$this->form_validation->set_rules('uuid_konsumen', 'uuid konsumen', 'trim|required');
	$this->form_validation->set_rules('konsumen_id', 'konsumen id', 'trim|required');
	$this->form_validation->set_rules('konsumen_nama', 'konsumen nama', 'trim|required');
	$this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
	$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
	$this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
	$this->form_validation->set_rules('unit', 'unit', 'trim|required');
	$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
	$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
	$this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required|numeric');
	$this->form_validation->set_rules('total_nominal', 'total nominal', 'trim|required|numeric');
	$this->form_validation->set_rules('umpphpsl22', 'umpphpsl22', 'trim|required|numeric');
	$this->form_validation->set_rules('piutang', 'piutang', 'trim|required|numeric');
	$this->form_validation->set_rules('penjualandpp', 'penjualandpp', 'trim|required|numeric');
	$this->form_validation->set_rules('utangppn', 'utangppn', 'trim|required|numeric');
	$this->form_validation->set_rules('cetak_bukti_penjualan', 'cetak bukti penjualan', 'trim|required');
	$this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');
	$this->form_validation->set_rules('proses_bayar', 'proses bayar', 'trim|required');
	$this->form_validation->set_rules('tgl_bayar_input', 'tgl bayar input', 'trim|required');
	$this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
	$this->form_validation->set_rules('nmr_bukti_bayar', 'nmr bukti bayar', 'trim|required');
	$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
	$this->form_validation->set_rules('proses_transaksi', 'proses transaksi', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_penjualan_accounting.xls";
        $judul = "tbl_penjualan_accounting";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Penjualan Proses");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Penjualan");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Persediaan");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Persediaan Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Jual");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Konsumen");
	xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
	xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
	xlsWriteLabel($tablehead, $kolomhead++, "Total Nominal");
	xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
	xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
	xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
	xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
	xlsWriteLabel($tablehead, $kolomhead++, "Cetak Bukti Penjualan");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");
	xlsWriteLabel($tablehead, $kolomhead++, "Proses Bayar");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmr Bukti Bayar");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
	xlsWriteLabel($tablehead, $kolomhead++, "Proses Transaksi");

	foreach ($this->Tbl_penjualan_accounting_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_penjualan_proses);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_penjualan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_persediaan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_persediaan_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_jual);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_konsumen);
	    xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
	    xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
	    xlsWriteNumber($tablebody, $kolombody++, $data->total_nominal);
	    xlsWriteNumber($tablebody, $kolombody++, $data->umpphpsl22);
	    xlsWriteNumber($tablebody, $kolombody++, $data->piutang);
	    xlsWriteNumber($tablebody, $kolombody++, $data->penjualandpp);
	    xlsWriteNumber($tablebody, $kolombody++, $data->utangppn);
	    xlsWriteNumber($tablebody, $kolombody++, $data->cetak_bukti_penjualan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
	    xlsWriteLabel($tablebody, $kolombody++, $data->proses_bayar);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmr_bukti_bayar);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
	    xlsWriteLabel($tablebody, $kolombody++, $data->proses_transaksi);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tbl_penjualan_accounting.php */
/* Location: ./application/controllers/Tbl_penjualan_accounting.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 10:35:02 */
/* http://harviacode.com */