<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cms_slider_model extends CI_Model
{
    public $table = 'cms_sliders';
    public $id = 'id';

    function get_all($active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
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
}
