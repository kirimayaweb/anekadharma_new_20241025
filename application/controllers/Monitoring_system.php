<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_system extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->helper('monitoring');
        $this->load->model('Monitoring_system_model');

        if (!monitoring_is_viewer_allowed()) {
            show_error(
                'Halaman monitoring hanya dapat diakses oleh admin.id@gmail.com dan iwanesia.id@gmail.com.',
                403,
                'Akses Ditolak'
            );
        }
    }

    public function index()
    {
        $rows = $this->Monitoring_system_model->get_presence_rows(300);
        $summary = $this->Monitoring_system_model->get_summary_counts();

        $data = array(
            'presence_rows' => $this->_decorate_presence_rows($rows),
            'summary' => $summary,
            'threshold_minutes' => (int) (monitoring_working_threshold_seconds() / 60),
            'server_time' => date('d-m-Y H:i:s'),
        );

        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/monitoring_system/adminlte310_monitoring_system',
            $data
        );
    }

    /**
     * AJAX refresh data monitoring (auto-reload halaman).
     */
    public function ajax_data()
    {
        $this->output->set_content_type('application/json');

        $rows = $this->Monitoring_system_model->get_presence_rows(300);
        $summary = $this->Monitoring_system_model->get_summary_counts();

        echo json_encode(array(
            'ok' => true,
            'server_time' => date('d-m-Y H:i:s'),
            'summary' => $summary,
            'rows' => $this->_decorate_presence_rows($rows, true),
        ));
    }

    private function _decorate_presence_rows($rows, $for_json = false)
    {
        $decorated = array();
        $threshold = monitoring_working_threshold_seconds();

        foreach ($rows as $row) {
            $activity = monitoring_activity_label($row, $threshold);
            $item = array(
                'id' => (int) $row->id,
                'email' => (string) $row->email,
                'full_name' => (string) $row->full_name,
                'ip_address' => (string) $row->ip_address,
                'browser_label' => (string) $row->browser_label,
                'device_label' => (string) $row->device_label,
                'last_controller' => (string) $row->last_controller,
                'last_method' => (string) $row->last_method,
                'last_uri' => (string) $row->last_uri,
                'login_at' => $row->login_at ? date('d-m-Y H:i:s', strtotime($row->login_at)) : '',
                'last_seen_at' => $row->last_seen_at ? date('d-m-Y H:i:s', strtotime($row->last_seen_at)) : '',
                'logout_at' => $row->logout_at ? date('d-m-Y H:i:s', strtotime($row->logout_at)) : '',
                'activity_label' => $activity,
                'activity_badge' => monitoring_activity_badge_class($activity),
                'status_db' => (string) $row->status,
            );

            if ($for_json) {
                $decorated[] = $item;
            } else {
                $decorated[] = (object) $item;
            }
        }

        return $decorated;
    }
}
