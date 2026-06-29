<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cms_settings_model extends CI_Model
{
    public $table = 'cms_settings';

    function get_all_map()
    {
        $rows = $this->db->get($this->table)->result();
        $map = array();
        foreach ($rows as $row) {
            $map[$row->setting_key] = $row->setting_value;
        }
        return $map;
    }

    function get_value($key, $default = '')
    {
        $row = $this->db->get_where($this->table, array('setting_key' => $key))->row();
        return $row ? $row->setting_value : $default;
    }

    function set_value($key, $value, $user_id = null)
    {
        $existing = $this->db->get_where($this->table, array('setting_key' => $key))->row();
        $payload = array(
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $user_id,
        );

        if ($existing) {
            $this->db->where('setting_key', $key);
            $this->db->update($this->table, $payload);
        } else {
            $payload['setting_key'] = $key;
            $this->db->insert($this->table, $payload);
        }
    }

    function set_many($data, $user_id = null)
    {
        foreach ($data as $key => $value) {
            $this->set_value($key, $value, $user_id);
        }
    }
}
