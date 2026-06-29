<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cms_admin_access_model extends CI_Model
{
    public $table = 'cms_admin_access';
    public $id = 'id';

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function user_has_access($id_user, $id_user_level)
    {
        $this->config->load('cms', true);
        $default_levels = $this->config->item('cms_default_admin_levels', 'cms');
        if (!is_array($default_levels)) {
            $default_levels = array(1, 2, 99);
        }

        $settings_levels = array();
        if ($this->db->table_exists('cms_settings')) {
            $this->load->model('Cms_settings_model');
            $raw = $this->Cms_settings_model->get_value('allowed_admin_levels', '');
            if ($raw !== '') {
                $settings_levels = array_filter(array_map('intval', explode(',', $raw)));
            }
        }

        $levels = array_unique(array_merge($default_levels, $settings_levels));
        if (in_array((int) $id_user_level, $levels, true)) {
            return true;
        }

        if ($id_user) {
            $by_user = $this->db->get_where($this->table, array('id_user' => (int) $id_user))->row();
            if ($by_user) {
                return true;
            }
        }

        if ($id_user_level) {
            $by_level = $this->db->get_where($this->table, array('id_user_level' => (int) $id_user_level))->row();
            if ($by_level) {
                return true;
            }
        }

        return false;
    }

    function grant_user($id_user, $granted_by = null)
    {
        $existing = $this->db->get_where($this->table, array('id_user' => (int) $id_user))->row();
        $data = array(
            'id_user' => (int) $id_user,
            'id_user_level' => null,
            'granted_by' => $granted_by,
            'granted_at' => date('Y-m-d H:i:s'),
        );
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
    }

    function grant_level($id_user_level, $granted_by = null)
    {
        $existing = $this->db->get_where($this->table, array('id_user_level' => (int) $id_user_level))->row();
        $data = array(
            'id_user' => null,
            'id_user_level' => (int) $id_user_level,
            'granted_by' => $granted_by,
            'granted_at' => date('Y-m-d H:i:s'),
        );
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }
    }

    function revoke_user($id_user)
    {
        $this->db->where('id_user', (int) $id_user);
        $this->db->delete($this->table);
    }

    function revoke_level($id_user_level)
    {
        $this->db->where('id_user_level', (int) $id_user_level);
        $this->db->delete($this->table);
    }
}
