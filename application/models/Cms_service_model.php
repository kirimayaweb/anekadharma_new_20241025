<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cms_service_model extends CI_Model
{
    public $table = 'cms_services';
    public $id = 'id';

    function get_all($published_only = false)
    {
        if ($published_only) {
            $this->db->where('is_active', 1);
            $this->db->where('is_published', 1);
            $this->db->where('(published_at IS NULL OR published_at <= NOW())', null, false);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by($this->id, 'ASC');
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, (int) $id);
        return $this->db->get($this->table)->row();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $this->db->where($this->id, (int) $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, (int) $id);
        $this->db->delete($this->table);
    }

    function toggle_active($id)
    {
        $row = $this->get_by_id($id);
        if (!$row) {
            return false;
        }
        $this->update($id, array('is_active' => $row->is_active ? 0 : 1, 'updated_at' => date('Y-m-d H:i:s')));
        return true;
    }

    function toggle_published($id)
    {
        $row = $this->get_by_id($id);
        if (!$row) {
            return false;
        }
        $new = $row->is_published ? 0 : 1;
        $data = array('is_published' => $new, 'updated_at' => date('Y-m-d H:i:s'));
        if ($new && (!$row->published_at || $row->published_at === '0000-00-00 00:00:00')) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        $this->update($id, $data);
        return true;
    }
}
