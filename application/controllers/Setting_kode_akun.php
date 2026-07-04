<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_kode_akun extends CI_Controller
{
    const MUTIPLY_MIN = 0.000001;
    const MUTIPLY_MAX = 100;
    const MUTIPLY_DEFAULT = 1;

    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array(
            'Sys_unit_kode_akun_model',
            'Sys_unit_model',
            'Sys_kode_akun_model',
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
            'data_list' => $this->Sys_unit_kode_akun_model->get_all(),
            'data_unit' => $this->Sys_unit_model->get_all(),
            'data_kode_akun' => $this->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
            'tbl_source_options' => $this->Sys_unit_kode_akun_model->get_tbl_source_options(),
            'url_create' => site_url('Setting_kode_akun/create_action_ajax'),
            'url_update' => site_url('Setting_kode_akun/update_action_ajax'),
            'url_delete' => site_url('Setting_kode_akun/delete_action_ajax'),
            'url_excel' => site_url('Setting_kode_akun/excel'),
        );

        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/setting_kode_akun/adminlte310_setting_kode_akun_list',
            $data
        );
    }

    public function create()
    {
        $data = $this->_form_data('SIMPAN', site_url('Setting_kode_akun/create_action'));
        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/setting_kode_akun/adminlte310_setting_kode_akun_form',
            $data
        );
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $payload = $this->_collect_post_data();
        if (isset($payload['_error'])) {
            $this->session->set_flashdata('message', $payload['_error']);
            redirect(site_url('Setting_kode_akun/create'));
            return;
        }

        if ($this->_is_duplicate($payload['uuid_unit'], $payload['kode_akun'], null, $payload['tbl_source'])) {
            $this->session->set_flashdata('message', 'Kombinasi unit, kode akun, dan tabel sumber sudah ada.');
            redirect(site_url('Setting_kode_akun/create'));
            return;
        }

        $this->Sys_unit_kode_akun_model->insert($payload);
        $this->session->set_flashdata('message', 'Data berhasil disimpan.');
        redirect(site_url('Setting_kode_akun'));
    }

    public function update($id)
    {
        $row = $this->Sys_unit_kode_akun_model->get_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', 'Data tidak ditemukan.');
            redirect(site_url('Setting_kode_akun'));
            return;
        }

        $data = $this->_form_data('UPDATE', site_url('Setting_kode_akun/update_action'), $row);
        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/setting_kode_akun/adminlte310_setting_kode_akun_form',
            $data
        );
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
            return;
        }

        $row = $this->Sys_unit_kode_akun_model->get_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', 'Data tidak ditemukan.');
            redirect(site_url('Setting_kode_akun'));
            return;
        }

        $payload = $this->_collect_post_data();
        if (isset($payload['_error'])) {
            $this->session->set_flashdata('message', $payload['_error']);
            redirect(site_url('Setting_kode_akun/update/' . $id));
            return;
        }

        if ($this->_is_duplicate($payload['uuid_unit'], $payload['kode_akun'], $id, $payload['tbl_source'])) {
            $this->session->set_flashdata('message', 'Kombinasi unit, kode akun, dan tabel sumber sudah ada.');
            redirect(site_url('Setting_kode_akun/update/' . $id));
            return;
        }

        $this->Sys_unit_kode_akun_model->update($id, $payload);
        $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
        redirect(site_url('Setting_kode_akun'));
    }

    public function delete($id)
    {
        $row = $this->Sys_unit_kode_akun_model->get_by_id($id);
        if ($row) {
            $this->Sys_unit_kode_akun_model->delete($id);
            $this->session->set_flashdata('message', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('message', 'Data tidak ditemukan.');
        }
        redirect(site_url('Setting_kode_akun'));
    }

    public function create_action_ajax()
    {
        $this->_json_response(function () {
            $payload = $this->_collect_post_data(true);
            if (isset($payload['_error'])) {
                return array('success' => false, 'message' => $payload['_error']);
            }

            if ($payload['uuid_unit'] === '' || $payload['kode_akun'] === '' || $payload['tbl_source'] === '') {
                return array('success' => false, 'message' => 'Unit, kode akun, dan tabel sumber wajib diisi.');
            }

            if ($this->_is_duplicate($payload['uuid_unit'], $payload['kode_akun'], null, $payload['tbl_source'])) {
                return array('success' => false, 'message' => 'Kombinasi unit, kode akun, dan tabel sumber sudah ada.');
            }

            $this->Sys_unit_kode_akun_model->insert($payload);
            return array('success' => true, 'message' => 'Data berhasil ditambahkan.');
        });
    }

    public function update_action_ajax()
    {
        $this->_json_response(function () {
            $id = $this->input->post('id', TRUE);
            if (empty($id)) {
                return array('success' => false, 'message' => 'Data tidak valid.');
            }

            $row = $this->Sys_unit_kode_akun_model->get_by_id($id);
            if (!$row) {
                return array('success' => false, 'message' => 'Data tidak ditemukan.');
            }

            $payload = $this->_collect_post_data(true);
            if (isset($payload['_error'])) {
                return array('success' => false, 'message' => $payload['_error']);
            }

            if ($payload['uuid_unit'] === '' || $payload['kode_akun'] === '' || $payload['tbl_source'] === '') {
                return array('success' => false, 'message' => 'Unit, kode akun, dan tabel sumber wajib diisi.');
            }

            if ($this->_is_duplicate($payload['uuid_unit'], $payload['kode_akun'], $id, $payload['tbl_source'])) {
                return array('success' => false, 'message' => 'Kombinasi unit, kode akun, dan tabel sumber sudah ada.');
            }

            $this->Sys_unit_kode_akun_model->update($id, $payload);
            return array('success' => true, 'message' => 'Data berhasil diperbarui.');
        });
    }

    public function delete_action_ajax()
    {
        $this->_json_response(function () {
            $id = $this->input->post('id', TRUE);
            if (empty($id)) {
                return array('success' => false, 'message' => 'Data tidak valid.');
            }

            $row = $this->Sys_unit_kode_akun_model->get_by_id($id);
            if (!$row) {
                return array('success' => false, 'message' => 'Data tidak ditemukan.');
            }

            $this->Sys_unit_kode_akun_model->delete($id);
            return array('success' => true, 'message' => 'Data berhasil dihapus.');
        });
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = 'sys_unit_kode_akun.xls';
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Disposition: attachment;filename=' . $namaFile);
        header('Content-Transfer-Encoding: binary');

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, 'No');
        xlsWriteLabel($tablehead, $kolomhead++, 'UUID Unit');
        xlsWriteLabel($tablehead, $kolomhead++, 'Kode Unit');
        xlsWriteLabel($tablehead, $kolomhead++, 'Nama Unit');
        xlsWriteLabel($tablehead, $kolomhead++, 'Kode Akun');
        xlsWriteLabel($tablehead, $kolomhead++, 'Tabel Sumber');
        xlsWriteLabel($tablehead, $kolomhead++, 'Pengali (×)');
        xlsWriteLabel($tablehead, $kolomhead++, 'Keterangan');

        foreach ($this->Sys_unit_kode_akun_model->get_all() as $data) {
            $kolombody = 0;
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
            xlsWriteLabel($tablebody, $kolombody++, isset($data->tbl_source) ? $data->tbl_source : '');
            xlsWriteLabel($tablebody, $kolombody++, $data->mutiply_processing);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    private function _form_data($button, $action, $row = null)
    {
        return array(
            'button' => $button,
            'action' => $action,
            'id' => set_value('id', $row ? $row->id : ''),
            'uuid_unit' => set_value('uuid_unit', $row ? $row->uuid_unit : ''),
            'kode_unit' => set_value('kode_unit', $row ? $row->kode_unit : ''),
            'nama_unit' => set_value('nama_unit', $row ? $row->nama_unit : ''),
            'kode_akun' => set_value('kode_akun', $row ? $row->kode_akun : ''),
            'tbl_source' => set_value('tbl_source', $row ? (isset($row->tbl_source) ? $row->tbl_source : '') : 'tbl_penjualan'),
            'mutiply_processing' => set_value('mutiply_processing', $row ? $row->mutiply_processing : (string) self::MUTIPLY_DEFAULT),
            'keterangan' => set_value('keterangan', $row ? $row->keterangan : ''),
            'data_unit' => $this->Sys_unit_model->get_all(),
            'data_kode_akun' => $this->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
            'tbl_source_options' => $this->Sys_unit_kode_akun_model->get_tbl_source_options(),
        );
    }

    private function _collect_post_data($ajax = false)
    {
        $uuid_unit = trim((string) $this->input->post('uuid_unit', TRUE));
        $kode_unit = trim((string) $this->input->post('kode_unit', TRUE));
        $nama_unit = trim((string) $this->input->post('nama_unit', TRUE));
        $kode_akun = trim((string) $this->input->post('kode_akun', TRUE));
        $tbl_source = trim((string) $this->input->post('tbl_source', TRUE));
        $tbl_source_parsed = $this->_parse_tbl_source_value($tbl_source);
        if (!$tbl_source_parsed['ok']) {
            return array('_error' => $tbl_source_parsed['message']);
        }
        $tbl_source = $tbl_source_parsed['value'];
        $mutiply_raw = trim((string) $this->input->post('mutiply_processing', TRUE));
        $mutiply_parsed = $this->_parse_mutiply_value($mutiply_raw);
        if (!$mutiply_parsed['ok']) {
            return array('_error' => $mutiply_parsed['message']);
        }
        $mutiply_processing = $mutiply_parsed['value'];

        if ($uuid_unit !== '' && ($kode_unit === '' || $nama_unit === '')) {
            $unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit);
            if ($unit) {
                if ($kode_unit === '') {
                    $kode_unit = $unit->kode_unit;
                }
                if ($nama_unit === '') {
                    $nama_unit = $unit->nama_unit;
                }
            }
        }

        $keterangan = trim((string) $this->input->post('keterangan', TRUE));

        return array(
            'uuid_unit' => $uuid_unit,
            'kode_unit' => $kode_unit,
            'nama_unit' => $nama_unit,
            'kode_akun' => $kode_akun,
            'tbl_source' => $tbl_source,
            'mutiply_processing' => $mutiply_processing,
            'keterangan' => $keterangan,
        );
    }

    private function _parse_tbl_source_value($raw)
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return array(
                'ok' => false,
                'message' => 'Tabel sumber wajib dipilih.',
                'value' => null,
            );
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $raw)) {
            return array(
                'ok' => false,
                'message' => 'Nama tabel sumber tidak valid.',
                'value' => null,
            );
        }

        if (!$this->db->table_exists($raw)) {
            return array(
                'ok' => false,
                'message' => 'Tabel sumber "' . $raw . '" tidak ditemukan di database.',
                'value' => null,
            );
        }

        return array(
            'ok' => true,
            'message' => '',
            'value' => $raw,
        );
    }

    public function _validate_tbl_source($value)
    {
        $parsed = $this->_parse_tbl_source_value($value);
        if (!$parsed['ok']) {
            $this->form_validation->set_message('_validate_tbl_source', $parsed['message']);
            return false;
        }

        $_POST['tbl_source'] = $parsed['value'];
        return true;
    }

    private function _parse_mutiply_value($raw)
    {
        $raw = trim(str_replace(',', '.', (string) $raw));

        if ($raw === '') {
            return array(
                'ok' => false,
                'message' => 'Koefisien pengali wajib diisi.',
                'value' => null,
            );
        }

        if (!is_numeric($raw)) {
            return array(
                'ok' => false,
                'message' => 'Koefisien pengali harus berupa angka desimal.',
                'value' => null,
            );
        }

        $value = (float) $raw;
        if ($value < self::MUTIPLY_MIN || $value > self::MUTIPLY_MAX) {
            return array(
                'ok' => false,
                'message' => 'Koefisien pengali harus antara ' . self::MUTIPLY_MIN . ' sampai ' . self::MUTIPLY_MAX . '.',
                'value' => null,
            );
        }

        return array(
            'ok' => true,
            'message' => '',
            'value' => $this->_format_mutiply_storage($value),
        );
    }

    private function _format_mutiply_storage($value)
    {
        $formatted = number_format((float) $value, 6, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');

        if ($formatted === '' || $formatted === '0') {
            return number_format(self::MUTIPLY_MIN, 6, '.', '');
        }

        return $formatted;
    }

    public function _validate_mutiply_processing($value)
    {
        $parsed = $this->_parse_mutiply_value($value);
        if (!$parsed['ok']) {
            $this->form_validation->set_message('_validate_mutiply_processing', $parsed['message']);
            return false;
        }

        $_POST['mutiply_processing'] = $parsed['value'];
        return true;
    }

    private function _is_duplicate($uuid_unit, $kode_akun, $exclude_id = null, $tbl_source = null)
    {
        if ($uuid_unit === '' || $kode_akun === '' || $tbl_source === null || $tbl_source === '') {
            return false;
        }

        return (bool) $this->Sys_unit_kode_akun_model->get_by_uuid_unit_kode_akun(
            $uuid_unit,
            $kode_akun,
            $exclude_id,
            $tbl_source
        );
    }

    private function _json_response($callback)
    {
        header('Content-Type: application/json');
        try {
            $result = $callback();
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.',
            ));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_unit', 'unit', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        $this->form_validation->set_rules('tbl_source', 'tabel sumber', 'trim|required|callback__validate_tbl_source');
        $this->form_validation->set_rules('kode_unit', 'kode unit', 'trim');
        $this->form_validation->set_rules('nama_unit', 'nama unit', 'trim');
        $this->form_validation->set_rules('mutiply_processing', 'koefisien pengali', 'trim|required|callback__validate_mutiply_processing');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}
