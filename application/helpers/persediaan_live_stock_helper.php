<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function persediaan_live_stock_2026_key($uuid, $nama, $satuan)
{
	$uuid = strtolower(trim((string) $uuid));
	$nama = persediaan_recalculate_normalize_nama($nama);
	$satuan = persediaan_recalculate_satuan_key($satuan);
	if ($uuid === '' || $nama === '' || $satuan === '') {
		return '';
	}
	return $uuid . '|' . $nama . '|' . $satuan;
}

function persediaan_live_stock_2026_map_add(&$map, $row)
{
	$map['by_id'][(int) $row->id] = $row;
	$key = persediaan_live_stock_2026_key(
		isset($row->uuid_persediaan) ? $row->uuid_persediaan : '',
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : ''
	);
	if ($key !== '') {
		$map['by_key'][$key] = $row;
	}
	$uuid = strtolower(trim((string) (isset($row->uuid_persediaan) ? $row->uuid_persediaan : '')));
	if ($uuid !== '') {
		if (!isset($map['by_uuid'][$uuid])) {
			$map['by_uuid'][$uuid] = array();
		}
		$map['by_uuid'][$uuid][(int) $row->id] = $row;
	}
}

function persediaan_live_stock_2026_pick($map, $uuid, $nama, $satuan, $hpp = 0, $preferred_id = 0)
{
	$preferred_id = (int) $preferred_id;
	if ($preferred_id > 0 && !empty($map['by_id'][$preferred_id])) {
		return $map['by_id'][$preferred_id];
	}
	$uuid_key = strtolower(trim((string) $uuid));
	$key = persediaan_live_stock_2026_key($uuid, $nama, $satuan);
	if ($key !== '' && !empty($map['by_key'][$key])) {
		return $map['by_key'][$key];
	}
	if ($uuid_key === '' || empty($map['by_uuid'][$uuid_key])) {
		return null;
	}

	$candidates = array_values($map['by_uuid'][$uuid_key]);
	$ref = (object) array('nama_barang' => $nama, 'satuan' => $satuan, 'harga_satuan' => $hpp);
	if ($nama !== '' && $satuan !== '') {
		$named = array();
		foreach ($candidates as $candidate) {
			if (persediaan_gen_v2_persediaan_row_matches_penjualan($candidate, $nama, $satuan)) {
				$named[] = $candidate;
			}
		}
		if (!empty($named)) {
			$candidates = $named;
		}
	}
	if (count($candidates) === 1) {
		return $candidates[0];
	}
	return $hpp > 0 ? persediaan_recalculate_pick_best_persediaan_row($candidates, $ref) : null;
}

function persediaan_live_stock_2026_update_row($CI, &$map, $row, $update)
{
	$CI->db->where('id', (int) $row->id);
	if (!$CI->db->update('persediaan', $update)) {
		$error = $CI->db->error();
		throw new Exception(!empty($error['message']) ? $error['message'] : 'UPDATE persediaan gagal untuk ID ' . (int) $row->id . '.');
	}
	foreach ($update as $field => $value) {
		$row->$field = $value;
	}
	$map['by_id'][(int) $row->id] = $row;
	$key = persediaan_live_stock_2026_key($row->uuid_persediaan, $row->namabarang, $row->satuan);
	if ($key !== '') {
		$map['by_key'][$key] = $row;
	}
	$uuid_key = strtolower(trim((string) $row->uuid_persediaan));
	if ($uuid_key !== '') {
		$map['by_uuid'][$uuid_key][(int) $row->id] = $row;
	}
}

function persediaan_live_stock_2026_insert_row($CI, &$map, $fields, $row_data)
{
	$insert = array_intersect_key($row_data, array_flip($fields));
	if (!$CI->db->insert('persediaan', $insert)) {
		$error = $CI->db->error();
		throw new Exception(!empty($error['message']) ? $error['message'] : 'INSERT persediaan gagal untuk ' . $row_data['namabarang'] . '.');
	}
	$id = (int) $CI->db->insert_id();
	$row = $CI->db->where('id', $id)->limit(1)->get('persediaan')->row();
	if (!$row) {
		throw new Exception('Record persediaan baru tidak dapat dibaca setelah insert.');
	}
	persediaan_live_stock_2026_map_add($map, $row);
	return $row;
}

function persediaan_live_stock_2026_write_progress($callback, $phase, $label, $processed, $total, $message)
{
	if (!is_callable($callback)) {
		return;
	}
	$callback(array(
		'phase' => $phase,
		'phase_label' => $label,
		'message' => $message,
		'processed' => (int) $processed,
		'total' => (int) $total,
		'percent' => $total > 0 ? min(100, (int) floor(100 * $processed / $total)) : 100,
		'record' => '',
	));
}

function persediaan_live_stock_rebuild_2026($CI, $progress_callback = null)
{
	$CI->load->helper('persediaan_display');
	$CI->load->helper('pembelian_persediaan');
	$year = 2026;
	$opening_start = '2025-12-01';
	$year_start = '2026-01-01';
	$today = date('Y-m-d');
	$year_end_exclusive = '2027-01-01';
	$end_exclusive = min(date('Y-m-d', strtotime($today . ' +1 day')), $year_end_exclusive);
	if ($today < $year_start) {
		return array('ok' => false, 'message' => 'Tanggal server belum memasuki tahun 2026.');
	}

	foreach (array('persediaan', 'tbl_pembelian', 'sys_unit_produk_bahan', 'sys_unit_produk', 'tbl_penjualan') as $table) {
		if (!$CI->db->table_exists($table)) {
			return array('ok' => false, 'message' => 'Tabel ' . $table . ' tidak tersedia.');
		}
	}

	if ($CI->db->field_exists('uuid_persediaan_bahan', 'sys_unit_produk_bahan')
		&& !$CI->db->field_exists('persediaan_proses', 'sys_unit_produk_bahan')) {
		$CI->db->query("ALTER TABLE `sys_unit_produk_bahan` ADD COLUMN `persediaan_proses` VARCHAR(32) NULL DEFAULT NULL AFTER `uuid_persediaan_bahan`");
	}
	if ($CI->db->field_exists('uuid_persediaan_bahan', 'sys_unit_produk_bahan')
		&& !$CI->db->field_exists('persediaan_proses', 'sys_unit_produk_bahan')) {
		return array('ok' => false, 'message' => 'Kolom sys_unit_produk_bahan.persediaan_proses tidak dapat disiapkan; data persediaan 2026 tidak dihapus.');
	}
	if (!$CI->db->field_exists('verified_persediaan', 'sys_unit_produk')) {
		$CI->db->query("ALTER TABLE `sys_unit_produk` ADD COLUMN `verified_persediaan` VARCHAR(32) NULL DEFAULT NULL AFTER `uuid_persediaan`");
	}
	if (!$CI->db->field_exists('verified_persediaan', 'sys_unit_produk')) {
		return array('ok' => false, 'message' => 'Kolom sys_unit_produk.verified_persediaan tidak dapat disiapkan; data persediaan 2026 tidak dihapus.');
	}
	if (!$CI->db->field_exists('verified_persediaan', 'tbl_penjualan')) {
		tbl_penjualan_ensure_verified_persediaan_column($CI);
	}
	if (!$CI->db->field_exists('verified_persediaan', 'tbl_penjualan')) {
		return array('ok' => false, 'message' => 'Kolom tbl_penjualan.verified_persediaan tidak dapat disiapkan; data persediaan 2026 tidak dihapus.');
	}
	if (!$CI->db->field_exists('id_persediaan_barang', 'tbl_penjualan')) {
		return array('ok' => false, 'message' => 'Kolom tbl_penjualan.id_persediaan_barang tidak tersedia.');
	}

	$opening_rows = $CI->db->query(
		"SELECT * FROM `persediaan`
		WHERE `tanggal_beli` >= ? AND `tanggal_beli` < ?
		AND CAST(COALESCE(NULLIF(TRIM(`total_10`), ''), '0') AS DECIMAL(18,4)) > 0
		ORDER BY `id` ASC",
		array($opening_start, $year_start)
	)->result();
	if (empty($opening_rows)) {
		return array('ok' => false, 'message' => 'Tidak ada saldo persediaan Desember 2025 dengan total_10 > 0; data 2026 belum dihapus.');
	}

	$purchase_rows = $CI->db->query(
		"SELECT * FROM `tbl_pembelian`
		WHERE `tgl_po` >= ? AND `tgl_po` < ?
		AND DATE(`tgl_po`) <= ?
		ORDER BY `tgl_po` ASC, `id` ASC",
		array($year_start, $end_exclusive, $today)
	)->result();
	$material_rows = $CI->db->query(
		"SELECT * FROM `sys_unit_produk_bahan`
		WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?
		AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `tgl_transaksi` ASC, `id` ASC",
		array($year_start, $end_exclusive, $today)
	)->result();
	$product_rows = $CI->db->query(
		"SELECT * FROM `sys_unit_produk`
		WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?
		AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `tgl_transaksi` ASC, `id` ASC",
		array($year_start, $end_exclusive, $today)
	)->result();
	$sale_rows = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE `tgl_jual` >= ? AND `tgl_jual` < ?
		AND DATE(`tgl_jual`) <= ?
		ORDER BY `tgl_jual` ASC, `id` ASC",
		array($year_start, $end_exclusive, $today)
	)->result();

	$fields = $CI->db->list_fields('persediaan');
	$unit_fields = function_exists('persediaan_list_unit_columns') ? persediaan_list_unit_columns($CI) : array();
	$unit_fields = array_values(array_intersect($unit_fields, $fields));
	$stats = array(
		'opening_copied' => 0,
		'purchase_processed' => 0,
		'purchase_updated' => 0,
		'purchase_inserted' => 0,
		'purchase_unmatched' => 0,
		'material_processed' => 0,
		'material_unverified' => 0,
		'product_processed' => 0,
		'product_unmatched' => 0,
		'sale_processed' => 0,
		'sale_unverified' => 0,
		'sale_qty_processed' => 0,
	);
	$purchase_unmatched = array();
	$material_unverified = array();
	$product_unmatched = array();
	$sale_unverified = array();

	$CI->db->trans_begin();
	try {
		$CI->db->query(
			"DELETE FROM `persediaan` WHERE `tanggal_beli` >= ? AND `tanggal_beli` < ?",
			array($year_start, $year_end_exclusive)
		);
		if ($CI->db->field_exists('verified_persediaan', 'tbl_pembelian')) {
			$CI->db->query("UPDATE `tbl_pembelian` SET `verified_persediaan` = NULL WHERE `tgl_po` >= ? AND `tgl_po` < ?", array($year_start, $year_end_exclusive));
		}
		$CI->db->query("UPDATE `sys_unit_produk_bahan` SET `persediaan_proses` = 'belum_verified' WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?", array($year_start, $year_end_exclusive));
		$CI->db->query("UPDATE `sys_unit_produk` SET `verified_persediaan` = 'belum_persediaan' WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?", array($year_start, $year_end_exclusive));
		$CI->db->query("UPDATE `tbl_penjualan` SET `verified_persediaan` = NULL WHERE `tgl_jual` >= ? AND `tgl_jual` < ?", array($year_start, $year_end_exclusive));
		if ($CI->db->trans_status() === false) {
			throw new Exception('Gagal menghapus data persediaan 2026.');
		}

		$opening_groups = array();
		foreach ($opening_rows as $source) {
			$uuid = isset($source->uuid_persediaan) ? trim((string) $source->uuid_persediaan) : '';
			if ($uuid === '') {
				$uuid = bin2hex(random_bytes(16));
			}
			$key = 'uuid:' . strtolower($uuid);
			$qty = max(0, persediaan_parse_angka(isset($source->total_10) ? $source->total_10 : 0));
			$hpp = persediaan_parse_angka(isset($source->hpp) ? $source->hpp : 0);
			if (!isset($opening_groups[$key])) {
				$opening_groups[$key] = array('source' => $source, 'uuid' => $uuid, 'qty' => 0, 'value' => 0);
			}
			$opening_groups[$key]['qty'] += $qty;
			$opening_groups[$key]['value'] += $qty * $hpp;
		}

		$map = array('by_id' => array(), 'by_uuid' => array(), 'by_key' => array());
		$opening_total = count($opening_groups);
		$index = 0;
		foreach ($opening_groups as $group) {
			$index++;
			$source = $group['source'];
			$qty = (float) $group['qty'];
			$hpp = $qty > 0 ? $group['value'] / $qty : persediaan_parse_angka($source->hpp);
			$row_data = (array) $source;
			unset($row_data['id']);
			$row_data['uuid_persediaan'] = $group['uuid'];
			$row_data['tanggal_beli'] = $year_start;
			if (in_array('tgl_persediaan', $fields, true)) {
				$row_data['tgl_persediaan'] = $year_start;
			}
			if (in_array('tanggal', $fields, true)) {
				$row_data['tanggal'] = date('d/m/Y', strtotime($year_start));
			}
			$row_data['sa'] = (string) (int) floor($qty);
			$row_data['beli'] = '0';
			$row_data['penjualan'] = '0';
			$row_data['total_10'] = (string) (int) floor($qty);
			$row_data['hpp'] = (string) $hpp;
			foreach (array('pecah_satuan', 'bahan_produksi') as $field) {
				if (in_array($field, $fields, true)) {
					$row_data[$field] = '0';
				}
			}
			foreach ($unit_fields as $field) {
				$row_data[$field] = '0';
			}
			if (in_array('tuj', $fields, true)) {
				$row_data['tuj'] = (string) (int) floor($qty);
			}
			if (in_array('nilai_persediaan', $fields, true)) {
				$row_data['nilai_persediaan'] = (string) (int) floor($qty * $hpp);
			}
			if (in_array('asal_generate', $fields, true)) {
				$row_data['asal_generate'] = 1;
			}
			$row = persediaan_live_stock_2026_insert_row($CI, $map, $fields, $row_data);
			$stats['opening_copied']++;
			if ($index % 100 === 0 || $index === $opening_total) {
				persediaan_live_stock_2026_write_progress($progress_callback, 'opening', 'Tahap 1/5 — Saldo awal', $index, $opening_total, 'Menyalin total_10 Desember 2025 ke stock live Januari 2026.');
			}
		}

		$purchase_total = count($purchase_rows);
		foreach ($purchase_rows as $index => $purchase) {
			$name = trim((string) (isset($purchase->uraian) ? $purchase->uraian : ''));
			$unit = trim((string) (isset($purchase->satuan) ? $purchase->satuan : ''));
			$qty = max(0, (int) floor(persediaan_parse_angka(isset($purchase->jumlah) ? $purchase->jumlah : 0)));
			$hpp = persediaan_parse_angka(isset($purchase->harga_satuan) ? $purchase->harga_satuan : 0);
			$uuid = trim((string) (isset($purchase->uuid_persediaan) ? $purchase->uuid_persediaan : ''));
			if ($name === '' || $unit === '' || $qty <= 0) {
				$purchase_unmatched[] = array('id' => (int) $purchase->id, 'nama' => $name, 'jumlah' => $qty, 'keterangan' => 'Uraian/satuan kosong atau jumlah tidak positif.');
				$stats['purchase_unmatched']++;
				continue;
			}
			if ($uuid === '') {
				$uuid = bin2hex(random_bytes(16));
			}
			$stock = persediaan_live_stock_2026_pick($map, $uuid, $name, $unit, $hpp);
			if (!$stock) {
				$row_data = array(
					'uuid_persediaan' => $uuid,
					'tanggal_beli' => date('Y-m-d', strtotime($purchase->tgl_po)),
					'namabarang' => $name,
					'satuan' => $unit,
					'hpp' => (string) $hpp,
					'sa' => '0',
					'beli' => (string) $qty,
					'penjualan' => '0',
					'total_10' => (string) $qty,
					'nilai_persediaan' => (string) (int) floor($qty * $hpp),
					'tuj' => (string) $qty,
					'spop' => isset($purchase->spop) ? (string) $purchase->spop : '0',
				);
				if (in_array('tgl_persediaan', $fields, true)) {
					$row_data['tgl_persediaan'] = date('Y-m-d', strtotime($purchase->tgl_po));
				}
				if (in_array('tanggal', $fields, true)) {
					$row_data['tanggal'] = date('d/m/Y', strtotime($purchase->tgl_po));
				}
				foreach (array('pecah_satuan', 'bahan_produksi') as $field) {
					if (in_array($field, $fields, true)) {
						$row_data[$field] = '0';
					}
				}
				foreach ($unit_fields as $field) {
					$row_data[$field] = '0';
				}
				foreach (array('uuid_barang', 'uuid_spop', 'kode_barang', 'kategori') as $field) {
					if (in_array($field, $fields, true) && isset($purchase->$field)) {
						$row_data[$field] = $purchase->$field;
					}
				}
				$stock = persediaan_live_stock_2026_insert_row($CI, $map, $fields, $row_data);
				$stats['purchase_inserted']++;
			} else {
				$beli = max(0, (int) floor(persediaan_parse_angka(isset($stock->beli) ? $stock->beli : 0))) + $qty;
				$total_10 = max(0, (int) floor(persediaan_parse_angka(isset($stock->total_10) ? $stock->total_10 : 0))) + $qty;
				$update = array('beli' => (string) $beli, 'total_10' => (string) $total_10);
				if (in_array('tuj', $fields, true)) {
					$update['tuj'] = (string) $total_10;
				}
				if (in_array('nilai_persediaan', $fields, true)) {
					$update['nilai_persediaan'] = (string) (int) floor($total_10 * persediaan_parse_angka($stock->hpp));
				}
				persediaan_live_stock_2026_update_row($CI, $map, $stock, $update);
				$stats['purchase_updated']++;
			}
			$purchase_update = array();
			if ($CI->db->field_exists('uuid_persediaan', 'tbl_pembelian')) {
				$purchase_update['uuid_persediaan'] = $uuid;
			}
			if ($CI->db->field_exists('id_persediaan_barang', 'tbl_pembelian')) {
				$purchase_update['id_persediaan_barang'] = (int) $stock->id;
			}
			if ($CI->db->field_exists('verified_persediaan', 'tbl_pembelian')) {
				$purchase_update['verified_persediaan'] = 'refered';
			}
			if (!empty($purchase_update)) {
				$CI->db->where('id', (int) $purchase->id)->update('tbl_pembelian', $purchase_update);
			}
			$stats['purchase_processed']++;
			if (($index + 1) % 100 === 0 || $index + 1 === $purchase_total) {
				persediaan_live_stock_2026_write_progress($progress_callback, 'purchase', 'Tahap 2/5 — Pembelian', $index + 1, $purchase_total, 'Memasukkan pembelian ke persediaan berdasarkan tgl_po.');
			}
		}

		$material_total = count($material_rows);
		foreach ($material_rows as $index => $material) {
			$id = (int) $material->id;
			$qty = max(0, (int) floor(persediaan_parse_angka(isset($material->jumlah_bahan) ? $material->jumlah_bahan : 0)));
			$uuid = $CI->db->field_exists('uuid_persediaan_bahan', 'sys_unit_produk_bahan')
				? trim((string) (isset($material->uuid_persediaan_bahan) ? $material->uuid_persediaan_bahan : ''))
				: trim((string) (isset($material->uuid_persediaan) ? $material->uuid_persediaan : ''));
			$stock = persediaan_live_stock_2026_pick($map, $uuid, $material->nama_barang_bahan, $material->satuan_bahan, isset($material->harga_satuan_bahan) ? $material->harga_satuan_bahan : 0);
			if (!$stock || $qty <= 0) {
				$material_unverified[] = array('id' => $id, 'tanggal' => $material->tgl_transaksi, 'nama' => $material->nama_barang_bahan, 'jumlah' => $qty, 'uuid' => $uuid, 'keterangan' => !$stock ? 'UUID bahan tidak ditemukan pada persediaan live.' : 'Jumlah bahan tidak positif.');
				$stats['material_unverified']++;
			} else {
				$total_10 = max(0, (int) floor(persediaan_parse_angka($stock->total_10)));
				if ($total_10 < $qty) {
					$material_unverified[] = array('id' => $id, 'tanggal' => $material->tgl_transaksi, 'nama' => $material->nama_barang_bahan, 'jumlah' => $qty, 'uuid' => $uuid, 'keterangan' => 'Stok total_10=' . $total_10 . ' kurang dari bahan=' . $qty . '.');
					$stats['material_unverified']++;
				} else {
					$bahan_new = max(0, (int) floor(persediaan_parse_angka($stock->bahan_produksi))) + $qty;
					$total_new = $total_10 - $qty;
					$update = array('bahan_produksi' => (string) $bahan_new, 'total_10' => (string) $total_new);
					if (in_array('tuj', $fields, true)) {
						$update['tuj'] = (string) $total_new;
					}
					if (in_array('nilai_persediaan', $fields, true)) {
						$update['nilai_persediaan'] = (string) (int) floor($total_new * persediaan_parse_angka($stock->hpp));
					}
					persediaan_live_stock_2026_update_row($CI, $map, $stock, $update);
					$CI->db->where('id', $id)->update('sys_unit_produk_bahan', array('persediaan_proses' => 'verified'));
					$stats['material_processed']++;
				}
			}
			if (($index + 1) % 50 === 0 || $index + 1 === $material_total) {
				persediaan_live_stock_2026_write_progress($progress_callback, 'material', 'Tahap 3/5 — Bahan produksi', $index + 1, $material_total, 'Memproses bahan produksi dengan UUID yang sama.');
			}
		}

		$product_total = count($product_rows);
		foreach ($product_rows as $index => $product) {
			$id = (int) $product->id;
			$qty = max(0, (int) floor(persediaan_parse_angka(isset($product->jumlah_produksi) ? $product->jumlah_produksi : 0)));
			$uuid = trim((string) (isset($product->uuid_persediaan) ? $product->uuid_persediaan : ''));
			$name = trim((string) (isset($product->nama_barang) ? $product->nama_barang : ''));
			$unit = trim((string) (isset($product->satuan) ? $product->satuan : ''));
			$hpp = persediaan_parse_angka(isset($product->harga_satuan) ? $product->harga_satuan : 0);
			if ($uuid === '' && $name !== '' && $unit !== '' && $qty > 0) {
				$uuid = bin2hex(random_bytes(16));
				if ($CI->db->field_exists('uuid_persediaan', 'sys_unit_produk')) {
					$CI->db->where('id', $id)->update('sys_unit_produk', array('uuid_persediaan' => $uuid));
				}
			}
			$stock = persediaan_live_stock_2026_pick($map, $uuid, $name, $unit, $hpp);
			$created_for_product = false;
			if (!$stock && $name !== '' && $unit !== '' && $qty > 0) {
				$row_data = array(
					'uuid_persediaan' => $uuid,
					'tanggal_beli' => $year_start,
					'namabarang' => $name,
					'satuan' => $unit,
					'hpp' => (string) $hpp,
					'sa' => (string) $qty,
					'beli' => '0',
					'penjualan' => '0',
					'total_10' => (string) $qty,
					'nilai_persediaan' => (string) (int) floor($qty * $hpp),
					'tuj' => (string) $qty,
				);
				if (in_array('tgl_persediaan', $fields, true)) {
					$row_data['tgl_persediaan'] = $year_start;
				}
				if (in_array('tanggal', $fields, true)) {
					$row_data['tanggal'] = date('d/m/Y', strtotime($year_start));
				}
				foreach (array('pecah_satuan', 'bahan_produksi') as $field) {
					if (in_array($field, $fields, true)) {
						$row_data[$field] = '0';
					}
				}
				foreach ($unit_fields as $field) {
					$row_data[$field] = '0';
				}
				foreach (array('uuid_barang', 'uuid_spop', 'kode_barang', 'kategori') as $field) {
					if (in_array($field, $fields, true) && isset($product->$field)) {
						$row_data[$field] = $product->$field;
					}
				}
				$stock = persediaan_live_stock_2026_insert_row($CI, $map, $fields, $row_data);
				$created_for_product = true;
			}
			if (!$stock || $qty <= 0) {
				$product_unmatched[] = array('id' => $id, 'tanggal' => $product->tgl_transaksi, 'nama' => $name, 'jumlah' => $qty, 'uuid' => $uuid, 'keterangan' => !$stock ? 'Nama/satuan/UUID produk tidak cukup untuk membuat stock live.' : 'Jumlah produksi tidak positif.');
				$stats['product_unmatched']++;
			} else {
				if (!$created_for_product) {
					$sa_new = max(0, (int) floor(persediaan_parse_angka(isset($stock->sa) ? $stock->sa : 0))) + $qty;
					$total_new = max(0, (int) floor(persediaan_parse_angka($stock->total_10))) + $qty;
					$update = array('sa' => (string) $sa_new, 'total_10' => (string) $total_new);
					if (in_array('tuj', $fields, true)) {
						$update['tuj'] = (string) $total_new;
					}
					if (in_array('nilai_persediaan', $fields, true)) {
						$update['nilai_persediaan'] = (string) (int) floor($total_new * persediaan_parse_angka($stock->hpp));
					}
					persediaan_live_stock_2026_update_row($CI, $map, $stock, $update);
				}
				$CI->db->where('id', $id)->update('sys_unit_produk', array('verified_persediaan' => 'refered'));
				$stats['product_processed']++;
			}
			if (($index + 1) % 50 === 0 || $index + 1 === $product_total) {
				persediaan_live_stock_2026_write_progress($progress_callback, 'product', 'Tahap 4/5 — Produk jadi', $index + 1, $product_total, 'Menambahkan hasil produksi ke total_10 persediaan.');
			}
		}

		$sale_total = count($sale_rows);
		foreach ($sale_rows as $index => $sale) {
			$id = (int) $sale->id;
			$qty = max(0, (int) floor(persediaan_parse_angka(isset($sale->jumlah) ? $sale->jumlah : 0)));
			if (penjualan_row_is_jasa($sale) || $qty <= 0) {
				if (isset($sale->barang_jasa) && strtolower(trim((string) $sale->barang_jasa)) === 'jasa') {
					$CI->db->where('id', $id)->update('tbl_penjualan', array('verified_persediaan' => 'jasa'));
				}
				continue;
			}
			$uuid = trim((string) (isset($sale->uuid_persediaan) ? $sale->uuid_persediaan : ''));
			$stock = persediaan_live_stock_2026_pick($map, $uuid, $sale->nama_barang, $sale->satuan, isset($sale->harga_satuan) ? $sale->harga_satuan : 0, isset($sale->id_persediaan_barang) ? (int) $sale->id_persediaan_barang : 0);
			if (!$stock) {
				$sale_unverified[] = array('id' => $id, 'tanggal' => $sale->tgl_jual, 'nama' => $sale->nama_barang, 'jumlah' => $qty, 'uuid' => $uuid, 'keterangan' => 'UUID persediaan penjualan tidak ditemukan pada persediaan live.');
				$stats['sale_unverified']++;
			} else {
				$total_10 = max(0, (int) floor(persediaan_parse_angka($stock->total_10)));
				if ($total_10 < $qty) {
					$sale_unverified[] = array('id' => $id, 'tanggal' => $sale->tgl_jual, 'nama' => $sale->nama_barang, 'jumlah' => $qty, 'uuid' => $uuid, 'id_persediaan' => (int) $stock->id, 'keterangan' => 'Stok total_10=' . $total_10 . ' kurang dari jumlah penjualan=' . $qty . '.');
					$stats['sale_unverified']++;
				} else {
					$penjualan_new = max(0, (int) floor(persediaan_parse_angka($stock->penjualan))) + $qty;
					$total_new = $total_10 - $qty;
					$update = array('penjualan' => (string) $penjualan_new, 'total_10' => (string) $total_new);
					if (in_array('tuj', $fields, true)) {
						$update['tuj'] = (string) $total_new;
					}
					if (in_array('nilai_persediaan', $fields, true)) {
						$update['nilai_persediaan'] = (string) (int) floor($total_new * persediaan_parse_angka($stock->hpp));
					}
					persediaan_live_stock_2026_update_row($CI, $map, $stock, $update);
					$sale_update = array('verified_persediaan' => 'refered', 'id_persediaan_barang' => (int) $stock->id);
					if ($uuid !== '' && $CI->db->field_exists('uuid_persediaan', 'tbl_penjualan')) {
						$sale_update['uuid_persediaan'] = (string) $stock->uuid_persediaan;
					}
					$CI->db->where('id', $id)->update('tbl_penjualan', $sale_update);
					$stats['sale_processed']++;
					$stats['sale_qty_processed'] += $qty;
				}
			}
			if (($index + 1) % 100 === 0 || $index + 1 === $sale_total) {
				persediaan_live_stock_2026_write_progress($progress_callback, 'sales', 'Tahap 5/5 — Penjualan', $index + 1, $sale_total, 'Mengurangi total_10 dan menambah field penjualan untuk transaksi terpetakan.');
			}
		}

		$duplicate_uuid = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM (
				SELECT LOWER(TRIM(`uuid_persediaan`)) AS uuid_key
				FROM `persediaan`
				WHERE `tanggal_beli` >= ? AND `tanggal_beli` < ?
				AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''
				GROUP BY LOWER(TRIM(`uuid_persediaan`))
				HAVING COUNT(*) > 1
			) AS duplicate_rows",
			array($year_start, $year_end_exclusive)
		)->row();
		if ($duplicate_uuid && (int) $duplicate_uuid->jml > 0) {
			throw new Exception('Ditemukan ' . (int) $duplicate_uuid->jml . ' UUID persediaan ganda pada stock live 2026. Rebuild di-rollback.');
		}

		if ($CI->db->trans_status() === false) {
			$error = $CI->db->error();
			throw new Exception(!empty($error['message']) ? $error['message'] : 'Transaksi rebuild live stock gagal.');
		}
		$CI->db->trans_commit();
		return array(
			'ok' => true,
			'tahun' => $year,
			'tanggal_awal' => $year_start,
			'tanggal_akhir' => $today,
			'snapshot_diubah' => false,
			'stats' => $stats,
			'purchase_unmatched' => $purchase_unmatched,
			'material_unverified' => $material_unverified,
			'product_unmatched' => $product_unmatched,
			'sale_unverified' => $sale_unverified,
		);
	} catch (Throwable $e) {
		$CI->db->trans_rollback();
		return array('ok' => false, 'message' => 'Rebuild stock live 2026 dibatalkan; perubahan di-rollback. ' . $e->getMessage());
	}
}