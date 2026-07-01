<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backupdatabasepertabel_model extends CI_Model
{
    public $table = 'tbl_backup_database_log';
    const CHUNK_SIZE = 1000;

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
            `id_users` int(11) DEFAULT NULL,
            `user_name` varchar(190) DEFAULT NULL,
            `folder_name` varchar(255) NOT NULL,
            `folder_path_label` varchar(500) NOT NULL,
            `database_name` varchar(100) NOT NULL,
            `total_tables` int(11) NOT NULL DEFAULT 0,
            `total_files` int(11) NOT NULL DEFAULT 0,
            `status` enum('processing','completed','failed') NOT NULL DEFAULT 'completed',
            `note` text DEFAULT NULL,
            `created_at` datetime NOT NULL,
            `finished_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_created_at` (`created_at`),
            KEY `idx_id_users` (`id_users`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->db->query($sql);
    }

    public function insert_log(array $data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_log($id, array $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update($this->table, $data);
    }

    public function get_logs($limit = 100)
    {
        $this->db->from($this->table);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    public function get_database_tables()
    {
        $db_name = $this->db->database;
        $query = $this->db->query('SHOW TABLES');
        $tables = array();

        foreach ($query->result_array() as $row) {
            $table = array_values($row)[0];
            if ($table === $this->table) {
                continue;
            }
            $count_row = $this->db->query('SELECT COUNT(*) AS cnt FROM ' . $this->db->protect_identifiers($table))->row();
            $row_count = $count_row ? (int) $count_row->cnt : 0;
            $tables[] = array(
                'name' => $table,
                'row_count' => $row_count,
                'chunk_count' => max(1, (int) ceil($row_count / self::CHUNK_SIZE)),
            );
        }

        usort($tables, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $tables;
    }

    public function chunk_filename($table, $offset)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '_', $table);
        if ((int) $offset <= 0) {
            return $table . '.sql';
        }

        return $table . '_' . ((int) $offset + 1) . '.sql';
    }

    public function export_table_chunk($table, $offset = 0, $limit = null)
    {
        $offset = (int) $offset;
        $limit = $limit === null ? self::CHUNK_SIZE : (int) $limit;
        $include_structure = ($offset === 0);
        $newline = "\n";

        if (!$this->db->table_exists($table)) {
            return false;
        }

        $output = '-- Backup Aneka Dharma' . $newline;
        $output .= '-- Database: ' . $this->db->database . $newline;
        $output .= '-- Table: ' . $table . $newline;
        $output .= '-- Offset: ' . $offset . $newline;
        $output .= '-- Generated: ' . date('Y-m-d H:i:s') . $newline . $newline;
        $output .= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' . $newline;
        $output .= 'SET time_zone = "+00:00";' . $newline;
        $output .= 'SET NAMES utf8;' . $newline;
        $output .= 'SET foreign_key_checks = 0;' . $newline . $newline;

        if ($include_structure) {
            $create_query = $this->db->query(
                'SHOW CREATE TABLE ' . $this->db->escape_identifiers($this->db->database . '.' . $table)
            );

            if ($create_query === false || $create_query->num_rows() === 0) {
                return false;
            }

            $create_row = $create_query->row_array();
            $create_sql = '';
            $i = 0;
            foreach ($create_row as $val) {
                if ($i++ % 2) {
                    $create_sql = $val;
                    break;
                }
            }

            $output .= 'DROP TABLE IF EXISTS ' . $this->db->protect_identifiers($table) . ';' . $newline . $newline;
            $output .= $create_sql . ';' . $newline . $newline;
        }

        $data_query = $this->db->query(
            'SELECT * FROM ' . $this->db->protect_identifiers($table)
            . ' LIMIT ' . $limit . ' OFFSET ' . $offset
        );

        if ($data_query && $data_query->num_rows() > 0) {
            $field_str = '';
            $is_int = array();
            $i = 0;

            while ($field = $data_query->result_id->fetch_field()) {
                $is_int[$i] = in_array(
                    strtolower($field->type),
                    array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'),
                    true
                );
                $field_str .= $this->db->escape_identifiers($field->name) . ', ';
                $i++;
            }
            $field_str = preg_replace('/, $/', '', $field_str);

            foreach ($data_query->result_array() as $row) {
                $val_str = '';
                $j = 0;
                foreach ($row as $v) {
                    if ($v === null) {
                        $val_str .= 'NULL';
                    } else {
                        $val_str .= ($is_int[$j] === false) ? $this->db->escape($v) : $v;
                    }
                    $val_str .= ', ';
                    $j++;
                }
                $val_str = preg_replace('/, $/', '', $val_str);
                $output .= 'INSERT INTO ' . $this->db->protect_identifiers($table)
                    . ' (' . $field_str . ') VALUES (' . $val_str . ');' . $newline;
            }
            $output .= $newline;
        }

        $output .= 'SET foreign_key_checks = 1;' . $newline;

        return $output;
    }
}
