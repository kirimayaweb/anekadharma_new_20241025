<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function persediaan_stock_bulanan_month_meta($CI, $bulan_target)
{
	$ts_target = strtotime($bulan_target . '-01');
	if ($ts_target === false) {
		throw new Exception('Bulan target tidak valid.');
	}

	$tanggal_target = date('Y-m-01', $ts_target);
	$tanggal_sumber = date('Y-m-01', strtotime('-1 month', $ts_target));
	$bulan_sumber = date('Y-m', strtotime('-1 month', $ts_target));
	$tanggal_akhir = date('Y-m-t', $ts_target);
	$tabel_sumber = 'persediaan';

	if ($CI->db->table_exists('persediaan_stock_bulanan')) {
		$count_stock_sumber = persediaan_stock_bulanan_count_month(
			$CI,
			'persediaan_stock_bulanan',
			$tanggal_sumber
		);
		if ($count_stock_sumber > 0) {
			$tabel_sumber = 'persediaan_stock_bulanan';
		}
	}

	if (!$CI->db->table_exists($tabel_sumber)) {
		throw new Exception('Tabel sumber persediaan tidak ditemukan.');
	}

	$count_sumber_all = persediaan_stock_bulanan_count_month($CI, $tabel_sumber, $tanggal_sumber);
	$count_sumber_layak = persediaan_stock_bulanan_count_month($CI, $tabel_sumber, $tanggal_sumber, true);
	$count_target = 0;
	if ($CI->db->table_exists('persediaan_stock_bulanan')) {
		$count_target = persediaan_stock_bulanan_count_month(
			$CI,
			'persediaan_stock_bulanan',
			$tanggal_target
		);
	}

	return array(
		'bulan_target' => $bulan_target,
		'bulan_sumber' => $bulan_sumber,
		'tanggal_target' => $tanggal_target,
		'tanggal_sumber' => $tanggal_sumber,
		'tanggal_akhir' => $tanggal_akhir,
		'tabel_sumber' => $tabel_sumber,
		'count_sumber_all' => $count_sumber_all,
		'count_sumber_layak' => $count_sumber_layak,
		'count_target' => $count_target,
	);
}

function persediaan_stock_bulanan_count_month($CI, $tabel, $tanggal_beli, $positive_only = false)
{
	if (!$CI->db->table_exists($tabel)) {
		return 0;
	}

	$sql = "SELECT COUNT(*) AS jml FROM `{$tabel}`
		WHERE `tanggal_beli` >= ? AND `tanggal_beli` < DATE_ADD(?, INTERVAL 1 MONTH)";
	if ($positive_only) {
		$sql .= " AND CAST(COALESCE(NULLIF(TRIM(`total_10`), ''), '0') AS DECIMAL(18,4)) > 0";
	}
	$row = $CI->db->query($sql, array($tanggal_beli, $tanggal_beli))->row();
	return $row ? (int) $row->jml : 0;
}

function persediaan_stock_bulanan_ensure_table($CI)
{
	if (!$CI->db->table_exists('persediaan')) {
		throw new Exception('Tabel persediaan tidak ditemukan sebagai sumber struktur.');
	}
	if (!$CI->db->table_exists('persediaan_stock_bulanan')) {
		if (!$CI->db->query('CREATE TABLE `persediaan_stock_bulanan` LIKE `persediaan`')) {
			$error = $CI->db->error();
			throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal membuat tabel persediaan_stock_bulanan.');
		}
	}

	$required_fields = array('id', 'tanggal_beli', 'tgl_persediaan', 'uuid_persediaan', 'namabarang', 'satuan', 'hpp', 'sa', 'beli', 'total_10');
	foreach ($required_fields as $field) {
		if (!$CI->db->field_exists($field, 'persediaan_stock_bulanan')) {
			throw new Exception('Kolom wajib ' . $field . ' tidak tersedia di persediaan_stock_bulanan.');
		}
	}

	$id_column = $CI->db->query(
		"SHOW COLUMNS FROM `persediaan_stock_bulanan` LIKE 'id'"
	)->row_array();
	if (!$id_column) {
		throw new Exception('Tidak dapat membaca struktur kolom id di persediaan_stock_bulanan.');
	}
	$id_extra = '';
	foreach ($id_column as $key => $value) {
		if (strtolower((string) $key) === 'extra') {
			$id_extra = strtolower(trim((string) $value));
			break;
		}
	}
	if (strpos($id_extra, 'auto_increment') === false) {
		if (!$CI->db->query(
			"ALTER TABLE `persediaan_stock_bulanan`
			MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT"
		)) {
			$error = $CI->db->error();
			throw new Exception(
				isset($error['message'])
					? 'Gagal memperbaiki auto-increment id persediaan_stock_bulanan: ' . $error['message']
					: 'Gagal memperbaiki auto-increment id persediaan_stock_bulanan.'
			);
		}
	}
}

function persediaan_stock_bulanan_generate($CI, $bulan_target, $progress_callback = null)
{
	if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan_target)) {
		throw new Exception('Format bulan tidak valid. Gunakan YYYY-MM.');
	}
	if (!$CI->db->table_exists('tbl_pembelian')) {
		throw new Exception('Tabel tbl_pembelian tidak ditemukan.');
	}
	if (!$CI->db->field_exists('tgl_po', 'tbl_pembelian')
		|| !$CI->db->field_exists('jumlah', 'tbl_pembelian')
		|| !$CI->db->field_exists('uuid_persediaan', 'tbl_pembelian')) {
		throw new Exception('Kolom tgl_po, jumlah, atau uuid_persediaan tidak tersedia di tbl_pembelian.');
	}

	persediaan_stock_bulanan_ensure_table($CI);
	$meta = persediaan_stock_bulanan_month_meta($CI, $bulan_target);
	$tabel_sumber = $meta['tabel_sumber'];
	$source_rows = $CI->db->query(
		"SELECT * FROM `{$tabel_sumber}`
		WHERE `tanggal_beli` >= ? AND `tanggal_beli` < DATE_ADD(?, INTERVAL 1 MONTH)
		AND CAST(COALESCE(NULLIF(TRIM(`total_10`), ''), '0') AS DECIMAL(18,4)) > 0
		ORDER BY `id` ASC",
		array($meta['tanggal_sumber'], $meta['tanggal_sumber'])
	)->result();
	$purchases = $CI->db->query(
		"SELECT * FROM `tbl_pembelian`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND `tgl_po` >= ? AND `tgl_po` < DATE_ADD(?, INTERVAL 1 MONTH)
		ORDER BY `id` ASC",
		array($meta['tanggal_target'] . ' 00:00:00', $meta['tanggal_target'] . ' 00:00:00')
	)->result();
	$copy_total = count($source_rows);
	$purchase_total = count($purchases);
	$report_progress = function ($phase, $message, $processed, $total, $percent, $record = '') use ($progress_callback) {
		if (!is_callable($progress_callback)) {
			return;
		}
		$progress_callback(array(
			'phase' => $phase,
			'phase_label' => ($phase === 'copy')
				? 'Tahap 1 dari 2 — Salin persediaan_stock_bulanan'
				: 'Tahap 2 dari 2 — Proses tbl_pembelian',
			'message' => $message,
			'processed' => (int) $processed,
			'total' => (int) $total,
			'percent' => max(0, min(100, (int) $percent)),
			'record' => $record,
		));
	};
	$report_progress('copy', 'Menyiapkan penyalinan stock bulan sebelumnya.', 0, $copy_total, 0);

	$CI->db->trans_begin();
	try {
		$CI->db->query(
			"DELETE FROM `persediaan_stock_bulanan`
			WHERE `tanggal_beli` >= ? AND `tanggal_beli` < DATE_ADD(?, INTERVAL 1 MONTH)",
			array($meta['tanggal_target'], $meta['tanggal_target'])
		);
		$count_deleted = (int) $CI->db->affected_rows();

		$fields = $CI->db->list_fields('persediaan_stock_bulanan');
		$unit_fields = function_exists('persediaan_list_unit_columns')
			? persediaan_list_unit_columns($CI)
			: array();
		$insert_fields = array_values(array_diff($fields, array('id')));
		$copy_data_rows = array();
		foreach ($source_rows as $source_row) {
			$data = (array) $source_row;
			unset($data['id']);
			$data['tanggal_beli'] = $meta['tanggal_target'];
			$data['tgl_persediaan'] = $meta['tanggal_akhir'];
			if (in_array('tanggal', $fields, true)) {
				$data['tanggal'] = $meta['tanggal_target'];
			}

			$total_awal = persediaan_parse_angka(isset($source_row->total_10) ? $source_row->total_10 : 0);
			$hpp = persediaan_parse_angka(isset($source_row->hpp) ? $source_row->hpp : 0);
			$data['sa'] = $total_awal;
			$data['beli'] = 0;
			$data['total_10'] = $total_awal;
			if (in_array('tuj', $fields, true)) {
				$data['tuj'] = $total_awal;
			}
			if (in_array('nilai_persediaan', $fields, true)) {
				$data['nilai_persediaan'] = $total_awal * $hpp;
			}
			foreach ($unit_fields as $movement_field) {
				if (in_array($movement_field, $fields, true)) {
					$data[$movement_field] = 0;
				}
			}
			if (in_array('tgl_keluar', $fields, true)) {
				$data['tgl_keluar'] = '';
			}
			foreach (array('penjualan', 'pecah_satuan', 'bahan_produksi') as $movement_field) {
				if (in_array($movement_field, $fields, true)) {
					$data[$movement_field] = 0;
				}
			}
			foreach ($fields as $field) {
				if (stripos($field, 'nominal') !== false) {
					$data[$field] = 0;
				}
			}

			$uuid = isset($data['uuid_persediaan']) ? trim((string) $data['uuid_persediaan']) : '';
			if ($uuid === '') {
				$uuid = bin2hex(random_bytes(16));
				$data['uuid_persediaan'] = $uuid;
			}
			$copy_data_rows[] = array_intersect_key(
				array_merge(array_fill_keys($insert_fields, ''), $data),
				array_flip($insert_fields)
			);
		}

		if (!empty($copy_data_rows)) {
			$copy_total = count($copy_data_rows);
			for ($offset = 0; $offset < $copy_total; $offset += 100) {
				$copy_batch = array_slice($copy_data_rows, $offset, 100);
				$insert_result = $CI->db->insert_batch('persediaan_stock_bulanan', $copy_batch, null, 100);
				if ($insert_result === false) {
					$error = $CI->db->error();
					throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal menyalin record persediaan bulan sebelumnya.');
				}
				$copy_processed = min($offset + count($copy_batch), $copy_total);
				$current_source = $source_rows[$offset + count($copy_batch) - 1];
				$current_name = isset($current_source->namabarang) ? trim((string) $current_source->namabarang) : '';
				$report_progress(
					'copy',
					'Menyalin stock bulan sebelumnya.',
					$copy_processed,
					$copy_total,
					($copy_total > 0 ? floor(100 * $copy_processed / $copy_total) : 100),
					($current_name !== '' ? 'Record terakhir: ' . $current_name : '')
				);
			}
		}

		$copied_stock_rows = $CI->db->query(
			"SELECT * FROM `persediaan_stock_bulanan`
			WHERE `tanggal_beli` >= ? AND `tanggal_beli` < DATE_ADD(?, INTERVAL 1 MONTH)
			ORDER BY `id` ASC",
			array($meta['tanggal_target'], $meta['tanggal_target'])
		)->result();
		if (count($copied_stock_rows) !== count($copy_data_rows)) {
			throw new Exception('Jumlah record hasil copy tidak sama dengan jumlah stock sumber.');
		}
		if ($copy_total === 0) {
			$report_progress('copy', 'Tidak ada record stock sumber untuk disalin.', 0, 0, 100);
		} else {
			$report_progress(
				'copy',
				'Penyalinan stock bulan sebelumnya selesai.',
				$copy_total,
				$copy_total,
				100
			);
		}

		$uuid_to_stock_id = array();
		$copied_rows = array();
		foreach ($copied_stock_rows as $index => $stock_row) {
			$uuid = trim((string) $stock_row->uuid_persediaan);
			if ($uuid !== '' && !isset($uuid_to_stock_id[$uuid])) {
				$uuid_to_stock_id[$uuid] = (int) $stock_row->id;
			}
			$copied_rows[] = $stock_row;
		}
		$source_uuid_to_stock_id = $uuid_to_stock_id;

		$purchase_rows = array();
		$purchase_matched = array();
		$purchase_new = array();
		$purchase_insert_rows = array();
		$purchase_insert_uuid_by_index = array();
		$stock_deltas = array();
		$report_progress(
			'purchase',
			'Memulai pemrosesan pembelian bulan target.',
			0,
			$purchase_total,
			0
		);
		foreach ($purchases as $purchase_index => $purchase) {
			$uuid_purchase = isset($purchase->uuid_persediaan) ? trim((string) $purchase->uuid_persediaan) : '';
			$baseline_match = ($uuid_purchase !== '' && isset($source_uuid_to_stock_id[$uuid_purchase]));
			$jumlah = persediaan_parse_angka(isset($purchase->jumlah) ? $purchase->jumlah : 0);
			$action = 'INSERT';
			$stock_ref = null;

			if ($uuid_purchase !== '' && isset($uuid_to_stock_id[$uuid_purchase])) {
				$stock_ref = $uuid_to_stock_id[$uuid_purchase];
				$action = 'UPDATE';
				if (is_int($stock_ref)) {
					if (!isset($stock_deltas[$stock_ref])) {
						$stock_deltas[$stock_ref] = 0;
					}
					$stock_deltas[$stock_ref] += $jumlah;
				} else {
					$new_index = (int) substr($stock_ref, 4);
					$purchase_insert_rows[$new_index]['beli'] += $jumlah;
					$purchase_insert_rows[$new_index]['total_10'] += $jumlah;
					$purchase_insert_rows[$new_index]['tuj'] += $jumlah;
					$purchase_insert_rows[$new_index]['nilai_persediaan'] =
						$purchase_insert_rows[$new_index]['total_10']
						* persediaan_parse_angka($purchase_insert_rows[$new_index]['hpp']);
				}
			} else {
				$nama = isset($purchase->uraian) ? trim((string) $purchase->uraian) : '';
				$satuan = isset($purchase->satuan) ? trim((string) $purchase->satuan) : '';
				if ($nama === '' || $satuan === '') {
					throw new Exception('Pembelian id=' . (int) $purchase->id . ' tidak dapat dibuat sebagai record baru karena uraian atau satuan kosong.');
				}
				$uuid_stock = $uuid_purchase !== '' ? $uuid_purchase : bin2hex(random_bytes(16));
				$hpp = persediaan_parse_angka(isset($purchase->harga_satuan) ? $purchase->harga_satuan : 0);
				$data = array(
					'tanggal_beli' => $meta['tanggal_target'],
					'tgl_persediaan' => $meta['tanggal_akhir'],
					'tanggal' => isset($purchase->tgl_po) ? substr((string) $purchase->tgl_po, 0, 10) : $meta['tanggal_target'],
					'namabarang' => $nama,
					'satuan' => $satuan,
					'hpp' => $hpp,
					'sa' => 0,
					'beli' => $jumlah,
					'penjualan' => 0,
					'total_10' => $jumlah,
					'nilai_persediaan' => $jumlah * $hpp,
					'tuj' => $jumlah,
					'uuid_persediaan' => $uuid_stock,
				);
				foreach ($unit_fields as $movement_field) {
					$data[$movement_field] = 0;
				}
				if (in_array('tgl_keluar', $fields, true)) {
					$data['tgl_keluar'] = '';
				}
				foreach (array('pecah_satuan', 'bahan_produksi') as $movement_field) {
					if (in_array($movement_field, $fields, true)) {
						$data[$movement_field] = 0;
					}
				}
				foreach ($fields as $field) {
					if (stripos($field, 'nominal') !== false) {
						$data[$field] = 0;
					}
				}
				$optional_map = array(
					'uuid_barang' => 'uuid_barang',
					'uuid_spop' => 'uuid_spop',
					'spop' => 'spop',
					'kode_barang' => 'kode_barang',
					'kategori' => null,
				);
				foreach ($optional_map as $field => $source_field) {
					if (!in_array($field, $fields, true)) {
						continue;
					}
					if ($field === 'kategori') {
						$data[$field] = 'barang';
					} elseif ($source_field !== null && isset($purchase->{$source_field})) {
						$data[$field] = $purchase->{$source_field};
					}
				}
				$new_index = count($purchase_insert_rows);
				$purchase_insert_rows[] = array_intersect_key($data, array_flip($insert_fields));
				$purchase_insert_uuid_by_index[$new_index] = $uuid_stock;
				$stock_ref = 'new:' . $new_index;
				if ($uuid_purchase !== '') {
					$uuid_to_stock_id[$uuid_purchase] = $stock_ref;
				}
			}

			$purchase_rows[] = array(
				'id' => isset($purchase->id) ? (int) $purchase->id : 0,
				'uuid_pembelian' => isset($purchase->uuid_pembelian) ? (string) $purchase->uuid_pembelian : '',
				'uuid_persediaan' => isset($purchase->uuid_persediaan) ? (string) $purchase->uuid_persediaan : '',
				'uraian' => isset($purchase->uraian) ? (string) $purchase->uraian : '',
				'satuan' => isset($purchase->satuan) ? (string) $purchase->satuan : '',
				'harga_satuan' => isset($purchase->harga_satuan) ? $purchase->harga_satuan : 0,
				'jumlah' => isset($purchase->jumlah) ? $purchase->jumlah : 0,
				'tgl_po' => isset($purchase->tgl_po) ? (string) $purchase->tgl_po : '',
				'id_stock' => $stock_ref,
				'aksi' => $action,
				'kategori' => $baseline_match ? 'cocok' : 'baru',
			);
			if (($purchase_index + 1) % 10 === 0 || $purchase_index + 1 === $purchase_total) {
				$purchase_name = isset($purchase->uraian) ? trim((string) $purchase->uraian) : '';
				$report_progress(
					'purchase',
					'Memeriksa record pembelian.',
					$purchase_index + 1,
					$purchase_total,
					($purchase_total > 0 ? floor(80 * ($purchase_index + 1) / $purchase_total) : 80),
					'ID ' . (int) $purchase->id . ($purchase_name !== '' ? ' — ' . $purchase_name : '')
				);
			}
		}

		if ($purchase_total === 0) {
			$report_progress('purchase', 'Tidak ada pembelian pada bulan target.', 0, 0, 80);
		} else {
			$report_progress(
				'purchase',
				'Pemeriksaan pembelian selesai; menyimpan perubahan ke stock.',
				$purchase_total,
				$purchase_total,
				80
			);
		}

		if (!empty($purchase_insert_rows)) {
			$purchase_insert_groups = array();
			foreach ($purchase_insert_rows as $row_index => $purchase_insert_row) {
				$group_key = implode("\0", array_keys($purchase_insert_row));
				if (!isset($purchase_insert_groups[$group_key])) {
					$purchase_insert_groups[$group_key] = array();
				}
				$purchase_insert_groups[$group_key][] = array(
					'index' => $row_index,
					'data' => $purchase_insert_row,
				);
			}

			$insert_total = count($purchase_insert_rows);
			$inserted_count = 0;
			foreach ($purchase_insert_groups as $purchase_group) {
				$group_rows = array_column($purchase_group, 'data');
				$group_count = count($group_rows);
				for ($offset = 0; $offset < $group_count; $offset += 100) {
					$purchase_batch = array_slice($group_rows, $offset, 100);
					$insert_result = $CI->db->insert_batch('persediaan_stock_bulanan', $purchase_batch, null, 100);
					if ($insert_result === false) {
						$error = $CI->db->error();
						throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal membuat record persediaan dari pembelian.');
					}
					$inserted_count += count($purchase_batch);
					$report_progress(
						'purchase',
						'Menyimpan pembelian baru ke persediaan_stock_bulanan.',
						$purchase_total,
						$purchase_total,
						80 + floor(10 * $inserted_count / $insert_total),
						'Record terakhir: ' . $purchase_batch[count($purchase_batch) - 1]['namabarang']
					);
				}
			}
		}

		$target_stock_rows = $CI->db->query(
			"SELECT * FROM `persediaan_stock_bulanan`
			WHERE `tanggal_beli` >= ? AND `tanggal_beli` < DATE_ADD(?, INTERVAL 1 MONTH)
			ORDER BY `id` ASC",
			array($meta['tanggal_target'], $meta['tanggal_target'])
		)->result();
		$stock_by_id = array();
		$stock_id_by_uuid = array();
		foreach ($target_stock_rows as $stock_row) {
			$stock_id = (int) $stock_row->id;
			$stock_by_id[$stock_id] = $stock_row;
			$uuid = trim((string) $stock_row->uuid_persediaan);
			if ($uuid !== '' && !isset($stock_id_by_uuid[$uuid])) {
				$stock_id_by_uuid[$uuid] = $stock_id;
			}
		}

		foreach ($purchase_rows as &$purchase_row) {
			if (is_string($purchase_row['id_stock']) && strpos($purchase_row['id_stock'], 'new:') === 0) {
				$new_index = (int) substr($purchase_row['id_stock'], 4);
				$new_uuid = isset($purchase_insert_uuid_by_index[$new_index]) ? $purchase_insert_uuid_by_index[$new_index] : '';
				if ($new_uuid === '' || !isset($stock_id_by_uuid[$new_uuid])) {
					throw new Exception('Record stock dari pembelian tidak ditemukan setelah insert batch.');
				}
				$purchase_row['id_stock'] = $stock_id_by_uuid[$new_uuid];
			} elseif (!is_int($purchase_row['id_stock'])) {
				throw new Exception('Referensi record stock pembelian tidak valid.');
			}
			if ($purchase_row['kategori'] === 'cocok') {
				$purchase_matched[] = $purchase_row;
			} else {
				$purchase_new[] = $purchase_row;
			}
		}
		unset($purchase_row);

		$updates = array();
		foreach ($stock_deltas as $stock_id => $delta) {
			if (!isset($stock_by_id[$stock_id])) {
				throw new Exception('Record stock bulanan id=' . (int) $stock_id . ' tidak ditemukan saat menerapkan pembelian.');
			}
			$stock = $stock_by_id[$stock_id];
			$beli_baru = persediaan_parse_angka(isset($stock->beli) ? $stock->beli : 0) + $delta;
			$total_baru = persediaan_parse_angka(isset($stock->total_10) ? $stock->total_10 : 0) + $delta;
			$update = array(
				'id' => (int) $stock_id,
				'beli' => $beli_baru,
				'total_10' => $total_baru,
			);
			if (in_array('tuj', $fields, true)) {
				$update['tuj'] = $total_baru;
			}
			if (in_array('nilai_persediaan', $fields, true)) {
				$update['nilai_persediaan'] = $total_baru * persediaan_parse_angka(isset($stock->hpp) ? $stock->hpp : 0);
			}
			$updates[] = $update;
		}
		if (!empty($updates)) {
			$update_total = count($updates);
			for ($offset = 0; $offset < $update_total; $offset += 100) {
				$update_batch = array_slice($updates, $offset, 100);
				$update_result = $CI->db->update_batch('persediaan_stock_bulanan', $update_batch, 'id', 100);
				if ($update_result === false) {
					$error = $CI->db->error();
					throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal meng-update stock dari pembelian.');
				}
				$report_progress(
					'purchase',
					'Menerapkan jumlah pembelian ke stock.',
					$purchase_total,
					$purchase_total,
					90 + floor(9 * min($offset + count($update_batch), $update_total) / $update_total),
					'ID stock terakhir: ' . (int) $update_batch[count($update_batch) - 1]['id']
				);
			}
		}

		if ($CI->db->trans_status() === false) {
			throw new Exception('Transaksi database gagal. Tidak ada perubahan yang disimpan.');
		}
		$CI->db->trans_commit();
		$report_progress(
			'purchase',
			'Semua data pembelian sudah tersimpan.',
			$purchase_total,
			$purchase_total,
			100
		);

		$meta['source_rows'] = array_slice($target_stock_rows, 0, count($copy_data_rows));
		$meta['purchase_rows'] = $purchase_rows;
		$meta['purchase_matched'] = $purchase_matched;
		$meta['purchase_new'] = $purchase_new;
		$meta['count_deleted'] = $count_deleted;
		$meta['count_copied'] = count($copied_rows);
		$meta['count_purchases'] = count($purchase_rows);
		$meta['count_purchase_updated'] = count(array_filter($purchase_rows, function ($row) {
			return $row['aksi'] === 'UPDATE';
		}));
		$meta['count_purchase_inserted'] = count(array_filter($purchase_rows, function ($row) {
			return $row['aksi'] === 'INSERT';
		}));
		$meta['sumber_fallback'] = ($tabel_sumber === 'persediaan');
		return $meta;
	} catch (Throwable $e) {
		$CI->db->trans_rollback();
		throw $e;
	}
}
