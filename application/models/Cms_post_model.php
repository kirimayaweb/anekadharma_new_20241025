<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cms_post_model extends CI_Model
{
    public $table = 'cms_posts';
    public $id = 'id';
    public $order = 'DESC';

    function get_all($include_unpublished = true)
    {
        if (!$include_unpublished) {
            $this->db->where('is_published', 1);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('published_at', $this->order);
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_published($limit = null, $offset = 0, $post_type = null, $category_id = null, $featured_only = false)
    {
        $this->db->where('is_published', 1);
        $this->db->where('(published_at IS NULL OR published_at <= NOW())', null, false);

        if ($post_type !== null && $post_type !== '') {
            $this->db->where('post_type', $post_type);
        }
        if ($category_id !== null && $category_id !== '') {
            $this->db->where('category_id', (int) $category_id);
        }
        if ($featured_only) {
            $this->db->where('is_featured', 1);
        }

        $this->db->order_by('is_featured', 'DESC');
        $this->db->order_by('published_at', 'DESC');
        $this->db->order_by($this->id, 'DESC');

        if ($limit !== null) {
            $this->db->limit((int) $limit, (int) $offset);
        }

        return $this->db->get($this->table)->result();
    }

    function count_published($post_type = null, $category_id = null)
    {
        $this->db->where('is_published', 1);
        $this->db->where('(published_at IS NULL OR published_at <= NOW())', null, false);
        if ($post_type !== null && $post_type !== '') {
            $this->db->where('post_type', $post_type);
        }
        if ($category_id !== null && $category_id !== '') {
            $this->db->where('category_id', (int) $category_id);
        }
        return $this->db->count_all_results($this->table);
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, (int) $id);
        return $this->db->get($this->table)->row();
    }

    function get_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        return $this->db->get($this->table)->row();
    }

    function get_published_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->where('is_published', 1);
        $this->db->where('(published_at IS NULL OR published_at <= NOW())', null, false);
        return $this->db->get($this->table)->row();
    }

    function search_published($keyword, $limit = 12, $offset = 0)
    {
        $keyword = trim((string) $keyword);
        $this->db->where('is_published', 1);
        $this->db->where('(published_at IS NULL OR published_at <= NOW())', null, false);
        if ($keyword !== '') {
            $this->db->group_start();
            $this->db->like('title', $keyword);
            $this->db->or_like('excerpt', $keyword);
            $this->db->or_like('content', $keyword);
            $this->db->or_like('tags', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('published_at', 'DESC');
        $this->db->limit((int) $limit, (int) $offset);
        return $this->db->get($this->table)->result();
    }

    function increment_view($id)
    {
        $this->db->set('view_count', 'view_count + 1', false);
        $this->db->where($this->id, (int) $id);
        $this->db->update($this->table);
    }

    function insert($data)
    {
        $this->db->set('uuid', "replace(uuid(),'-','')", false);
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
