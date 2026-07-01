<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backupdatabasepertabel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Backupdatabasepertabel_model', 'backup_model');
    }

    public function index()
    {
        $data = array(
            'title' => 'Backup Database Per Tabel',
            'database_name' => $this->db->database,
            'chunk_size' => Backupdatabasepertabel_model::CHUNK_SIZE,
            'backup_logs' => $this->backup_model->get_logs(100),
            'api_get_tables' => site_url('backupdatabasepertabel/get_tables'),
            'api_export_sql' => site_url('backupdatabasepertabel/export_sql'),
            'api_save_log' => site_url('backupdatabasepertabel/save_log'),
            'api_update_log' => site_url('backupdatabasepertabel/update_log'),
        );

        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/backupdatabasepertabel/adminlte310_backupdatabasepertabel',
            $data
        );
    }

    public function get_tables()
    {
        $this->_json_response(array(
            'success' => true,
            'database' => $this->db->database,
            'chunk_size' => Backupdatabasepertabel_model::CHUNK_SIZE,
            'tables' => $this->backup_model->get_database_tables(),
        ));
    }

    public function export_sql()
    {
        $table = $this->input->get('table', true);
        $offset = (int) $this->input->get('offset');

        if ($table === null || $table === '') {
            $this->_json_response(array('success' => false, 'message' => 'Nama tabel wajib diisi.'), 400);
            return;
        }

        if (!$this->db->table_exists($table)) {
            $this->_json_response(array('success' => false, 'message' => 'Tabel tidak ditemukan.'), 404);
            return;
        }

        $sql = $this->backup_model->export_table_chunk($table, $offset);
        if ($sql === false) {
            $this->_json_response(array('success' => false, 'message' => 'Gagal mengekspor tabel.'), 500);
            return;
        }

        $this->_json_response(array(
            'success' => true,
            'table' => $table,
            'offset' => $offset,
            'filename' => $this->backup_model->chunk_filename($table, $offset),
            'sql' => $sql,
        ));
    }

    public function save_log()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->_json_response(array('success' => false, 'message' => 'Method not allowed.'), 405);
            return;
        }

        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            $payload = $this->input->post(null, true);
        }

        $folder_name = isset($payload['folder_name']) ? trim($payload['folder_name']) : '';
        $folder_path_label = isset($payload['folder_path_label']) ? trim($payload['folder_path_label']) : $folder_name;

        if ($folder_name === '') {
            $this->_json_response(array('success' => false, 'message' => 'Folder backup wajib diisi.'), 400);
            return;
        }

        $now = date('Y-m-d H:i:s');
        $log_id = $this->backup_model->insert_log(array(
            'id_users' => $this->session->userdata('id_users'),
            'user_name' => $this->session->userdata('full_name') ?: $this->session->userdata('email'),
            'folder_name' => $folder_name,
            'folder_path_label' => $folder_path_label,
            'database_name' => $this->db->database,
            'total_tables' => isset($payload['total_tables']) ? (int) $payload['total_tables'] : 0,
            'total_files' => isset($payload['total_files']) ? (int) $payload['total_files'] : 0,
            'status' => isset($payload['status']) ? $payload['status'] : 'processing',
            'note' => isset($payload['note']) ? $payload['note'] : null,
            'created_at' => $now,
            'finished_at' => null,
        ));

        $this->_json_response(array(
            'success' => true,
            'log_id' => $log_id,
            'message' => 'Log backup tersimpan.',
        ));
    }

    public function update_log()
    {
        if ($this->input->method(true) !== 'POST') {
            $this->_json_response(array('success' => false, 'message' => 'Method not allowed.'), 405);
            return;
        }

        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            $payload = $this->input->post(null, true);
        }

        $log_id = isset($payload['log_id']) ? (int) $payload['log_id'] : 0;
        if ($log_id <= 0) {
            $this->_json_response(array('success' => false, 'message' => 'Log ID tidak valid.'), 400);
            return;
        }

        $update = array();
        if (isset($payload['status'])) {
            $update['status'] = $payload['status'];
        }
        if (isset($payload['total_files'])) {
            $update['total_files'] = (int) $payload['total_files'];
        }
        if (isset($payload['note'])) {
            $update['note'] = $payload['note'];
        }
        if (isset($payload['status']) && in_array($payload['status'], array('completed', 'failed'), true)) {
            $update['finished_at'] = date('Y-m-d H:i:s');
        }

        $this->backup_model->update_log($log_id, $update);

        $this->_json_response(array(
            'success' => true,
            'message' => 'Log backup diperbarui.',
        ));
    }

    public function json_logs()
    {
        $logs = $this->backup_model->get_logs(100);
        $rows = array();

        foreach ($logs as $log) {
            $rows[] = array(
                'id' => (int) $log->id,
                'created_at' => $log->created_at,
                'finished_at' => $log->finished_at,
                'user_name' => $log->user_name,
                'folder_name' => $log->folder_name,
                'folder_path_label' => $log->folder_path_label,
                'database_name' => $log->database_name,
                'total_tables' => (int) $log->total_tables,
                'total_files' => (int) $log->total_files,
                'status' => $log->status,
                'note' => $log->note,
            );
        }

        $this->_json_response(array(
            'success' => true,
            'logs' => $rows,
        ));
    }

    private function _json_response(array $data, $http_code = 200)
    {
        $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
