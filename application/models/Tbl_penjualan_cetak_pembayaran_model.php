<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_penjualan_cetak_pembayaran_model extends CI_Model
{

	public $table = 'tbl_penjualan_cetak_pembayaran';
	public $id = 'id';
	public $order = 'DESC';

	function __construct()
	{
		parent::__construct();
	}

	function get_by_uuid_cetak_pembayaran($uuid_cetak_pembayaran)
	{
		$this->db->where('uuid_cetak_pembayaran', $uuid_cetak_pembayaran);
		return $this->db->get($this->table)->row();
	}

	function get_latest_by_uuid_penjualan($uuid_penjualan)
	{
		$this->db->where('uuid_penjualan', $uuid_penjualan);
		$this->db->order_by($this->id, $this->order);
		$this->db->limit(1);
		return $this->db->get($this->table)->row();
	}

	function get_by_id($id)
	{
		$this->db->where($this->id, $id);
		return $this->db->get($this->table)->row();
	}

	function insert($data)
	{
		$this->db->set('uuid_cetak_pembayaran', "replace(uuid(),'-','')", FALSE);
		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();
		return $this->get_by_id($insert_id)->uuid_cetak_pembayaran;
	}

	function update_by_uuid_penjualan($uuid_penjualan, $data)
	{
		$row = $this->get_latest_by_uuid_penjualan($uuid_penjualan);
		if ($row) {
			$this->db->where('id', $row->id);
			$this->db->update($this->table, $data);
			return $row->uuid_cetak_pembayaran;
		}
		return $this->insert(array_merge($data, array('uuid_penjualan' => $uuid_penjualan)));
	}
}
