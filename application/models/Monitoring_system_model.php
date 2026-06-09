<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_system_model extends CI_Model
{
    public $table = 'tbl_monitoring_presence';

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table();
    }

    public function ensure_table()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `session_id` varchar(128) NOT NULL,
            `id_users` int(11) DEFAULT NULL,
            `email` varchar(190) DEFAULT NULL,
            `full_name` varchar(190) DEFAULT NULL,
            `ip_address` varchar(45) DEFAULT NULL,
            `user_agent` varchar(500) DEFAULT NULL,
            `browser_label` varchar(120) DEFAULT NULL,
            `device_label` varchar(120) DEFAULT NULL,
            `last_controller` varchar(100) DEFAULT NULL,
            `last_method` varchar(100) DEFAULT NULL,
            `last_uri` varchar(500) DEFAULT NULL,
            `login_at` datetime NOT NULL,
            `last_seen_at` datetime NOT NULL,
            `logout_at` datetime DEFAULT NULL,
            `status` enum('online','offline') NOT NULL DEFAULT 'online',
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_session_id` (`session_id`),
            KEY `idx_email` (`email`),
            KEY `idx_status_last_seen` (`status`,`last_seen_at`),
            KEY `idx_id_users` (`id_users`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->db->query($sql);
    }

    public function upsert_presence(array $data)
    {
        $session_id = isset($data['session_id']) ? (string) $data['session_id'] : '';
        if ($session_id === '') {
            return false;
        }

        $existing = $this->db->get_where($this->table, array('session_id' => $session_id))->row();
        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $update = array(
                'last_seen_at' => isset($data['last_seen_at']) ? $data['last_seen_at'] : $now,
                'status' => 'online',
                'logout_at' => null,
            );

            $optional = array(
                'id_users', 'email', 'full_name', 'ip_address', 'user_agent',
                'browser_label', 'device_label', 'last_controller', 'last_method', 'last_uri',
            );
            foreach ($optional as $field) {
                if (isset($data[$field]) && $data[$field] !== '' && $data[$field] !== null) {
                    $update[$field] = $data[$field];
                }
            }

            $this->db->where('session_id', $session_id);
            $this->db->update($this->table, $update);
            return true;
        }

        $insert = array(
            'session_id' => $session_id,
            'id_users' => isset($data['id_users']) ? $data['id_users'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'full_name' => isset($data['full_name']) ? $data['full_name'] : null,
            'ip_address' => isset($data['ip_address']) ? $data['ip_address'] : null,
            'user_agent' => isset($data['user_agent']) ? $data['user_agent'] : null,
            'browser_label' => isset($data['browser_label']) ? $data['browser_label'] : null,
            'device_label' => isset($data['device_label']) ? $data['device_label'] : null,
            'last_controller' => isset($data['last_controller']) ? $data['last_controller'] : null,
            'last_method' => isset($data['last_method']) ? $data['last_method'] : null,
            'last_uri' => isset($data['last_uri']) ? $data['last_uri'] : null,
            'login_at' => isset($data['login_at']) ? $data['login_at'] : $now,
            'last_seen_at' => isset($data['last_seen_at']) ? $data['last_seen_at'] : $now,
            'logout_at' => null,
            'status' => 'online',
        );

        $this->db->insert($this->table, $insert);
        return true;
    }

    public function mark_offline_by_session($session_id)
    {
        if ($session_id === '') {
            return;
        }

        $this->db->where('session_id', $session_id);
        $this->db->update($this->table, array(
            'status' => 'offline',
            'logout_at' => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * Tandai offline jika tidak ada aktivitas lebih dari batas (detik).
     */
    public function cleanup_stale_sessions($inactive_seconds = 7200)
    {
        $cutoff = date('Y-m-d H:i:s', time() - (int) $inactive_seconds);
        $this->db->where('status', 'online');
        $this->db->where('last_seen_at <', $cutoff);
        $this->db->update($this->table, array(
            'status' => 'offline',
            'logout_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function get_presence_rows($limit = 200)
    {
        $this->cleanup_stale_sessions(7200);

        $this->db->from($this->table);
        $this->db->order_by('status', 'ASC');
        $this->db->order_by('last_seen_at', 'DESC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    public function get_summary_counts()
    {
        $rows = $this->get_presence_rows(500);
        $summary = array(
            'total_rows' => count($rows),
            'online' => 0,
            'working' => 0,
            'idle' => 0,
            'offline' => 0,
        );

        $this->load->helper('monitoring');
        $threshold = monitoring_working_threshold_seconds();

        foreach ($rows as $row) {
            $label = monitoring_activity_label($row, $threshold);
            if ($label === 'Sedang bekerja') {
                $summary['working']++;
                $summary['online']++;
            } elseif ($label === 'Login (idle)') {
                $summary['idle']++;
                $summary['online']++;
            } else {
                $summary['offline']++;
            }
        }

        return $summary;
    }
}
