<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Query and format the sales item picker using only rows from persediaan.
 */
if (!function_exists('penjualan_modal_datatable_persediaan')) {
	function penjualan_modal_datatable_persediaan($CI, $request)
	{
		if (!$CI->db->table_exists('persediaan')) {
			throw new Exception('Tabel persediaan tidak ditemukan.');
		}
		if (!$CI->db->field_exists('total_10', 'persediaan')) {
			throw new Exception('Kolom persediaan.total_10 tidak ditemukan.');
		}

		$all_records = isset($request['all_records']) && (string) $request['all_records'] === '1';
		$bulan_persediaan = isset($request['bulan_persediaan']) && is_scalar($request['bulan_persediaan'])
			? trim((string) $request['bulan_persediaan'])
			: '';
		if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan_persediaan)) {
			throw new Exception('Bulan persediaan tidak valid.');
		}
		if ($bulan_persediaan < '2026-01') {
			throw new Exception('Tidak ada persediaan sebelum 1 Januari 2026. Silakan pilih bulan mulai Januari 2026.');
		}

		$tgl_jual_input = isset($request['tgl_jual']) && is_scalar($request['tgl_jual'])
			? trim((string) $request['tgl_jual'])
			: '';
		if (!preg_match('/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/', $tgl_jual_input, $tanggal_match)) {
			throw new Exception('Tanggal input penjualan tidak valid.');
		}
		$hari_jual = (int) $tanggal_match[1];
		$bulan_jual = (int) $tanggal_match[2];
		$tahun_jual = (int) $tanggal_match[3];
		if (!checkdate($bulan_jual, $hari_jual, $tahun_jual)) {
			throw new Exception('Tanggal input penjualan tidak valid.');
		}
		$tgl_jual = sprintf('%04d-%02d-%02d', $tahun_jual, $bulan_jual, $hari_jual);
		$tgl_akhir_bulan_pilihan = date('Y-m-t', strtotime($bulan_persediaan . '-01'));
		$tgl_akhir_filter = min($tgl_jual, $tgl_akhir_bulan_pilihan);
		if ($tgl_akhir_filter < '2026-01-01') {
			throw new Exception('Tidak ada persediaan sebelum 1 Januari 2026. Silakan pilih bulan mulai Januari 2026.');
		}

		$tanggal_expr = penjualan_sql_tanggal_persediaan_expr('p');
		$kategori_sql = $CI->db->field_exists('kategori', 'persediaan') ? 'p.kategori' : "''";
		if ($all_records) {
			$CI->load->helper('persediaan_display');
			$stok_mentah_expr = "CAST(NULLIF(REPLACE(TRIM(COALESCE(p.total_10, '')), ',', '.'), '') AS DECIMAL(20,4))";
			$stock_fields = array();
			foreach (array('sa', 'beli', 'penjualan', 'pecah_satuan', 'bahan_produksi') as $field) {
				$stock_fields[] = $CI->db->field_exists($field, 'persediaan')
					? 'p.' . $CI->db->escape_identifiers($field)
					: '0 AS ' . $CI->db->escape_identifiers($field);
			}
			$sql = "SELECT p.id, p.uuid_persediaan, p.uuid_barang,
					p.kode_barang, p.spop, {$tanggal_expr} AS tanggal_urut,
					{$tanggal_expr} AS tanggal_beli,
					{$kategori_sql} AS kategori, p.namabarang, p.satuan,
					p.hpp, p.total_10, " . implode(', ', $stock_fields) . "
				FROM persediaan p
				WHERE COALESCE({$stok_mentah_expr}, 0) > 0
				AND {$tanggal_expr} >= '2026-01-01'
				AND {$tanggal_expr} <= '{$tgl_akhir_filter}'
				ORDER BY p.namabarang ASC, tanggal_urut ASC, p.id ASC";
			$query = $CI->db->query($sql);
			if ($query === false) {
				$error = $CI->db->error();
				throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal mengambil data persediaan.');
			}

			$rows = array();
			$seen = array();
			foreach ($query->result() as $row) {
				$stock = persediaan_hitung_sisa_stock($row);
				if ($stock <= 0) {
					continue;
				}

				$uuid = trim((string) (isset($row->uuid_persediaan) ? $row->uuid_persediaan : ''));
				if ($uuid !== '') {
					$key = strtolower($uuid) . "\x1f" . strtolower(trim((string) $row->namabarang));
					if (isset($seen[$key])) {
						continue;
					}
					$seen[$key] = true;
				}

				$row->stok_tersedia = $stock;
				$rows[] = $row;
			}

			return array(
				'recordsTotal' => count($rows),
				'recordsFiltered' => count($rows),
				'start' => 0,
				'tanggalAkhir' => $tgl_akhir_filter,
				'rows' => $rows,
			);
		}

		$tanggal_group_expr = penjualan_sql_tanggal_persediaan_expr('kelompok');
		$tanggal_oldest_expr = penjualan_sql_tanggal_persediaan_expr('terpilih');
		$stok_expr = penjualan_modal_sql_sisa_stock_expr($CI, 'p');
		$stok_group_expr = penjualan_modal_sql_sisa_stock_expr($CI, 'kelompok');
		$stok_oldest_expr = penjualan_modal_sql_sisa_stock_expr($CI, 'terpilih');
		$oldest_ids_sql = "
			SELECT MIN(terpilih.id)
			FROM persediaan terpilih
			INNER JOIN (
				SELECT
					TRIM(COALESCE(kelompok.uuid_persediaan, '')) AS uuid_key,
					LOWER(TRIM(COALESCE(kelompok.namabarang, ''))) AS nama_key,
					MIN({$tanggal_group_expr}) AS tanggal_terlama
				FROM persediaan kelompok
				WHERE COALESCE({$stok_group_expr}, 0) > 0
				AND {$tanggal_group_expr} >= '2026-01-01'
				AND {$tanggal_group_expr} <= '{$tgl_akhir_filter}'
				AND TRIM(COALESCE(kelompok.uuid_persediaan, '')) <> ''
				GROUP BY TRIM(COALESCE(kelompok.uuid_persediaan, '')), LOWER(TRIM(COALESCE(kelompok.namabarang, '')))
			) tanggal_minimum
				ON TRIM(COALESCE(terpilih.uuid_persediaan, '')) = tanggal_minimum.uuid_key
				AND LOWER(TRIM(COALESCE(terpilih.namabarang, ''))) = tanggal_minimum.nama_key
				AND {$tanggal_oldest_expr} = tanggal_minimum.tanggal_terlama
			WHERE COALESCE({$stok_oldest_expr}, 0) > 0
			AND {$tanggal_oldest_expr} >= '2026-01-01'
			AND {$tanggal_oldest_expr} <= '{$tgl_akhir_filter}'
			GROUP BY TRIM(COALESCE(terpilih.uuid_persediaan, '')), LOWER(TRIM(COALESCE(terpilih.namabarang, '')))
		";
		$where = "WHERE COALESCE({$stok_expr}, 0) > 0
			AND {$tanggal_expr} >= '2026-01-01'
			AND {$tanggal_expr} <= '{$tgl_akhir_filter}'
			AND (
				TRIM(COALESCE(p.uuid_persediaan, '')) = ''
				OR p.id IN ({$oldest_ids_sql})
			)";
		$base_where = $where;
		$search_value = isset($request['search']['value']) ? $request['search']['value'] : '';
		$search = $all_records || !is_scalar($search_value) ? '' : trim((string) $search_value);
		if ($search !== '') {
			$like = $CI->db->escape('%' . $CI->db->escape_like_str($search) . '%');
			$where .= " AND (
				CAST(p.id AS CHAR) LIKE {$like} ESCAPE '!'
				OR CAST({$tanggal_expr} AS CHAR) LIKE {$like} ESCAPE '!'
				OR DATE_FORMAT({$tanggal_expr}, '%d/%m/%Y') LIKE {$like} ESCAPE '!'
				OR COALESCE(p.spop, '') LIKE {$like} ESCAPE '!'
				OR COALESCE({$kategori_sql}, '') LIKE {$like} ESCAPE '!'
				OR COALESCE(p.namabarang, '') LIKE {$like} ESCAPE '!'
				OR COALESCE(p.hpp, '') LIKE {$like} ESCAPE '!'
				OR REPLACE(FORMAT(CAST(NULLIF(REPLACE(TRIM(COALESCE(p.hpp, '')), ',', '.'), '') AS DECIMAL(20,4)), 0), ',', '.') LIKE {$like} ESCAPE '!'
				OR COALESCE(p.satuan, '') LIKE {$like} ESCAPE '!'
				OR CAST({$stok_expr} AS CHAR) LIKE {$like} ESCAPE '!'
			)";
		}

		$records_total = 0;
		$records_filtered = 0;
		if (!$all_records) {
			$total_query = $CI->db->query("SELECT COUNT(*) AS jumlah FROM persediaan p {$base_where}");
			if ($total_query === false) {
				$error = $CI->db->error();
				throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal menghitung data persediaan.');
			}
			$records_total = (int) $total_query->row()->jumlah;
			$records_filtered = $records_total;
			if ($search !== '') {
				$filtered_query = $CI->db->query("SELECT COUNT(*) AS jumlah FROM persediaan p {$where}");
				if ($filtered_query === false) {
					$error = $CI->db->error();
					throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal memfilter data persediaan.');
				}
				$records_filtered = (int) $filtered_query->row()->jumlah;
			}
		}

		$order_columns = array(
			2 => $tanggal_expr,
			3 => 'p.spop',
			4 => $kategori_sql,
			5 => 'p.namabarang',
			6 => "CAST(NULLIF(REPLACE(TRIM(COALESCE(p.hpp, '')), ',', '.'), '') AS DECIMAL(20,4))",
			7 => 'p.satuan',
			8 => $stok_expr,
		);
		$order_parts = array();
		if (isset($request['order']) && is_array($request['order'])) {
			foreach ($request['order'] as $order) {
				if (!isset($order['column']) || !is_scalar($order['column'])) {
					continue;
				}
				$order_index = (int) $order['column'];
				if (isset($order_columns[$order_index])) {
					$direction = isset($order['dir']) && strtolower($order['dir']) === 'desc' ? 'DESC' : 'ASC';
					$order_parts[] = $order_columns[$order_index] . ' ' . $direction;
				}
			}
		}
		if (empty($order_parts)) {
			$order_parts[] = 'p.namabarang ASC';
			$order_parts[] = $tanggal_expr . ' ASC';
		}
		$order_parts[] = 'p.id ASC';
		$order_sql = ' ORDER BY ' . implode(', ', $order_parts);

		$start_value = isset($request['start']) ? $request['start'] : 0;
		$length_value = isset($request['length']) ? $request['length'] : 10;
		$start = is_scalar($start_value) ? max(0, (int) $start_value) : 0;
		$length = is_scalar($length_value) ? (int) $length_value : 10;
		if ($length < 1) {
			$length = 10;
		}

		$sql = "SELECT p.id, p.uuid_persediaan, p.uuid_barang,
				p.kode_barang, p.spop, {$tanggal_expr} AS tanggal_urut,
				{$tanggal_expr} AS tanggal_beli,
				{$kategori_sql} AS kategori, p.namabarang, p.satuan,
				p.hpp, p.total_10, {$stok_expr} AS stok_tersedia
			FROM persediaan p {$where}{$order_sql}";
		if (!$all_records) {
			$length = min($length, 100);
			$sql .= " LIMIT {$start}, {$length}";
		}
		$query = $CI->db->query($sql);
		if ($query === false) {
			$error = $CI->db->error();
			throw new Exception(isset($error['message']) ? $error['message'] : 'Gagal mengambil data persediaan.');
		}
		if ($all_records) {
			$records_total = $query->num_rows();
			$records_filtered = $records_total;
		}

		return array(
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'start' => $all_records ? 0 : $start,
			'tanggalAkhir' => $tgl_akhir_filter,
			'rows' => $query->result(),
		);
	}
}

if (!function_exists('penjualan_modal_sql_sisa_stock_expr')) {
	function penjualan_modal_sql_sisa_stock_expr($CI, $alias)
	{
		$total = "CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.total_10, '')), ',', '.'), '') AS DECIMAL(20,4))";
		$penjualan = $CI->db->field_exists('penjualan', 'persediaan')
			? "COALESCE(CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.penjualan, '')), ',', '.'), '') AS DECIMAL(20,4)), 0)"
			: '0';
		$pecah_satuan = $CI->db->field_exists('pecah_satuan', 'persediaan')
			? "COALESCE(CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.pecah_satuan, '')), ',', '.'), '') AS DECIMAL(20,4)), 0)"
			: '0';
		$bahan_produksi = $CI->db->field_exists('bahan_produksi', 'persediaan')
			? "COALESCE(CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.bahan_produksi, '')), ',', '.'), '') AS DECIMAL(20,4)), 0)"
			: '0';
		$sa = $CI->db->field_exists('sa', 'persediaan')
			? "COALESCE(CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.sa, '')), ',', '.'), '') AS DECIMAL(20,4)), 0)"
			: '0';
		$beli = $CI->db->field_exists('beli', 'persediaan')
			? "COALESCE(CAST(NULLIF(REPLACE(TRIM(COALESCE({$alias}.beli, '')), ',', '.'), '') AS DECIMAL(20,4)), 0)"
			: '0';
		$deductions = "({$penjualan} + {$pecah_satuan} + {$bahan_produksi})";
		$gross = "({$sa} + {$beli})";
		$total_value = "COALESCE({$total}, 0)";

		return "(CASE
			WHEN {$deductions} <= 0 THEN GREATEST(0, FLOOR({$total_value}))
			WHEN {$gross} > 0 AND ABS({$total_value} - {$gross}) < 0.01
				THEN GREATEST(0, FLOOR({$total_value} - {$deductions}))
			WHEN ABS({$total_value} - GREATEST(0, FLOOR({$gross} - {$deductions}))) < 0.01
				THEN GREATEST(0, FLOOR({$total_value}))
			ELSE GREATEST(0, FLOOR({$total_value} - {$deductions}))
		END)";
	}
}

if (!function_exists('penjualan_modal_format_datatable_row')) {
	function penjualan_modal_format_datatable_row($row, $nomor, $selectable, $client_side = false)
	{
		$id = (int) $row->id;
		$spop = htmlspecialchars((string) (isset($row->spop) ? $row->spop : ''), ENT_QUOTES, 'UTF-8');
		$kategori = htmlspecialchars((string) (isset($row->kategori) ? $row->kategori : ''), ENT_QUOTES, 'UTF-8');
		$nama = htmlspecialchars((string) (isset($row->namabarang) ? $row->namabarang : ''), ENT_QUOTES, 'UTF-8');
		$satuan = htmlspecialchars((string) (isset($row->satuan) ? $row->satuan : ''), ENT_QUOTES, 'UTF-8');
		$harga = (float) (isset($row->hpp) ? $row->hpp : 0);
		$stok = isset($row->stok_tersedia) ? (float) $row->stok_tersedia : (float) (isset($row->total_10) ? $row->total_10 : 0);
		$tanggal = isset($row->tanggal_urut) ? $row->tanggal_urut : null;
		$tanggal_tampil = '';
		if (!empty($tanggal) && strpos((string) $tanggal, '0000-00-00') === false) {
			$timestamp = strtotime($tanggal);
			$tanggal_tampil = $timestamp ? date('d/m/Y', $timestamp) : htmlspecialchars((string) $tanggal, ENT_QUOTES, 'UTF-8');
		}
		$tanggal_cell = '<span data-order="' . htmlspecialchars((string) $tanggal, ENT_QUOTES, 'UTF-8') . '">' . $tanggal_tampil . '</span>';
		$button_class = $selectable ? 'btn-success' : 'btn-secondary';
		$disabled = $selectable ? '' : ' disabled';
		$button_attributes = $client_side
			? ' class="btn ' . $button_class . ' btn-xs btn-pilih-barang-penjualan" data-id="' . $id . '"'
			: ' class="btn ' . $button_class . ' btn-xs" data-toggle="modal" data-target="#modal-xl_1_' . $id . '"';
		$button = '<button type="button"' . $button_attributes . $disabled . '>PILIH BARANG</button>';

		return array(
			$nomor,
			$button,
			$tanggal_cell,
			$spop . '<br><small class="text-muted">persediaan</small>',
			$kategori,
			$nama,
			number_format($harga, 0, ',', '.'),
			$satuan,
			rtrim(rtrim(number_format($stok, 2, ',', '.'), '0'), ','),
			$button,
		);
	}
}
