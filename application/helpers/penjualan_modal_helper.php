<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper modal Pilih Barang (penjualan).
 * File: application/helpers/penjualan_modal_helper.php
 *
 * Load: $this->load->helper('penjualan_modal');
 *
 * Function utama:
 * - penjualan_modal_load_stock_rows($CI, $tahun_bulan)
 * - penjualan_modal_render_tbody($Data_stock, $extra = array())
 */

if (!function_exists('penjualan_modal_load_stock_rows')) {
	/**
	 * Ambil daftar stok untuk modal Pilih Barang dari 3 sumber (tbl_pembelian, persediaan, tbl_pembelian_pecah_satuan).
	 * sys_unit_produk tidak ditampilkan karena datanya sudah masuk ke persediaan.
	 *
	 * @param object $CI instance CodeIgniter
	 * @param string $tahun_bulan format YYYY-MM
	 * @return array list of stdClass (properti seragam)
	 */
	function penjualan_modal_load_stock_rows($CI, $tahun_bulan)
	{
		$Data_stock = array();
		$tahun_bulan = trim((string) $tahun_bulan);
		if ($tahun_bulan === '' || !preg_match('/^\d{4}-\d{2}$/', $tahun_bulan)) {
			return $Data_stock;
		}

		// ----------------------------------------------------------
		// A. tbl_pembelian (filter tgl_po)
		//    sisa = jumlah - SUM(tbl_penjualan where id_persediaan_barang = id)
		// ----------------------------------------------------------
		if ($CI->db->table_exists('tbl_pembelian')) {
			$sql_beli = "
				SELECT id, uuid_pembelian, uuid_barang, kode_barang, spop, tgl_po,
					uraian AS nama_barang_beli, satuan, harga_satuan, jumlah, uuid_persediaan
				FROM tbl_pembelian
				WHERE DATE_FORMAT(tgl_po, '%Y-%m') = ?
				ORDER BY tgl_po ASC, id ASC
			";
			$list_pembelian = $CI->db->query($sql_beli, array($tahun_bulan))->result();

			$map_terjual_by_id = array();
			if (!empty($list_pembelian)) {
				$ids = array();
				foreach ($list_pembelian as $b) {
					$ids[] = (int) $b->id;
				}
				$ids = array_values(array_unique(array_filter($ids)));
				if (!empty($ids)) {
					$sql_jual = "
						SELECT id_persediaan_barang, COALESCE(SUM(jumlah), 0) AS total_terjual
						FROM tbl_penjualan
						WHERE id_persediaan_barang IN (" . implode(',', $ids) . ")
						AND (barang_jasa != 'jasa' OR barang_jasa IS NULL)
						GROUP BY id_persediaan_barang
					";
					foreach ($CI->db->query($sql_jual)->result() as $j) {
						$map_terjual_by_id[(int) $j->id_persediaan_barang] = (float) $j->total_terjual;
					}
				}
			}

			foreach ($list_pembelian as $beli) {
				$id_beli = (int) $beli->id;
				$jumlah_beli = (float) $beli->jumlah;
				$total_terjual = isset($map_terjual_by_id[$id_beli]) ? $map_terjual_by_id[$id_beli] : 0;
				$sisa_stok = $jumlah_beli - $total_terjual;
				if ($sisa_stok <= 0) {
					continue;
				}

				$spop_val = trim((string) (isset($beli->spop) ? $beli->spop : ''));
				$obj = new stdClass();
				$obj->id_persediaan_barang = $id_beli;
				$obj->uuid_persediaan = !empty($beli->uuid_pembelian) ? $beli->uuid_pembelian : (isset($beli->uuid_persediaan) ? $beli->uuid_persediaan : '');
				$obj->uuid_barang = isset($beli->uuid_barang) ? $beli->uuid_barang : '';
				$obj->kode_barang = isset($beli->kode_barang) ? $beli->kode_barang : '';
				$obj->tgl_po = isset($beli->tgl_po) ? $beli->tgl_po : null;
				$obj->spop = $spop_val;
				$obj->spop_label = 'pembelian';
				$obj->sumber_tabel = 'tbl_pembelian';
				$obj->kategori = '';
				$obj->namabarang = isset($beli->nama_barang_beli) ? $beli->nama_barang_beli : '';
				$obj->satuan = isset($beli->satuan) ? $beli->satuan : '';
				$obj->hpp = isset($beli->harga_satuan) ? $beli->harga_satuan : 0;
				$obj->sisa_stok = $sisa_stok;
				$obj->total_10 = $sisa_stok;
				$obj->tabel_source_referensi = 'tbl_pembelian';
				$Data_stock[] = $obj;
			}
		}

		// ----------------------------------------------------------
		// B. persediaan (filter tanggal_beli)
		//    tgl po = tanggal_beli, spop + label "persediaan",
		//    nama = namabarang, harga = hpp, satuan = satuan, sisa = total_10
		// ----------------------------------------------------------
		if ($CI->db->table_exists('persediaan')) {
			$sql_pers = "
				SELECT id, uuid_persediaan, uuid_barang, kode_barang, spop,
					tanggal_beli, tanggal, tgl_persediaan,
					kategori, namabarang, satuan, hpp, total_10
				FROM persediaan
				WHERE DATE_FORMAT(tanggal_beli, '%Y-%m') = ?
				AND COALESCE(total_10, 0) > 0
				AND (kategori IS NULL OR LOWER(TRIM(kategori)) != 'jasa')
				ORDER BY tanggal_beli ASC, id ASC
			";
			foreach ($CI->db->query($sql_pers, array($tahun_bulan))->result() as $pers) {
				$sisa_stok = (float) (isset($pers->total_10) ? $pers->total_10 : 0);
				if ($sisa_stok <= 0) {
					continue;
				}

				$spop_val = trim((string) (isset($pers->spop) ? $pers->spop : ''));
				$tgl_po_val = null;
				if (!empty($pers->tanggal_beli) && strpos((string) $pers->tanggal_beli, '0000-00-00') === false) {
					$tgl_po_val = $pers->tanggal_beli;
				} elseif (!empty($pers->tanggal) && strpos((string) $pers->tanggal, '0000-00-00') === false) {
					$tgl_po_val = $pers->tanggal;
				} elseif (!empty($pers->tgl_persediaan)) {
					$tgl_po_val = $pers->tgl_persediaan;
				}

				$obj = new stdClass();
				$obj->id_persediaan_barang = (int) $pers->id;
				$obj->uuid_persediaan = isset($pers->uuid_persediaan) ? $pers->uuid_persediaan : '';
				$obj->uuid_barang = isset($pers->uuid_barang) ? $pers->uuid_barang : '';
				$obj->kode_barang = isset($pers->kode_barang) ? $pers->kode_barang : '';
				$obj->tgl_po = $tgl_po_val;
				$obj->spop = $spop_val;
				$obj->spop_label = 'persediaan';
				$obj->sumber_tabel = 'persediaan';
				$obj->kategori = isset($pers->kategori) ? trim((string) $pers->kategori) : '';
				$obj->namabarang = isset($pers->namabarang) ? $pers->namabarang : '';
				$obj->satuan = isset($pers->satuan) ? $pers->satuan : '';
				$obj->hpp = isset($pers->hpp) ? $pers->hpp : 0;
				$obj->sisa_stok = $sisa_stok;
				$obj->total_10 = $sisa_stok;
				$obj->tabel_source_referensi = 'persediaan';
				$Data_stock[] = $obj;
			}
		}

		// ----------------------------------------------------------
		// C. sys_unit_produk — TIDAK ditampilkan
		//    Data produksi sudah masuk ke tabel persediaan.
		// ----------------------------------------------------------

		// ----------------------------------------------------------
		// D. tbl_pembelian_pecah_satuan (filter tgl_po) — hasil pecah
		// ----------------------------------------------------------
		if ($CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
			$sql_pecah = "
				SELECT id, uuid_pecah_satuan, uuid_pembelian, uuid_barang, uuid_persediaan,
					tgl_po, spop, kode_barang, uraian, jumlah, satuan, harga_satuan,
					nama_barang_baru, jumlah_barang_baru, satuan_barang_baru,
					harga_satuan_barang_baru, kode_barang_baru,
					uuid_persediaan_baru, uuid_barang_baru, id_persediaan_baru
				FROM tbl_pembelian_pecah_satuan
				WHERE DATE_FORMAT(tgl_po, '%Y-%m') = ?
				ORDER BY tgl_po ASC, id ASC
			";
			$list_pecah = $CI->db->query($sql_pecah, array($tahun_bulan))->result();

			$map_terjual_pecah = array();
			if (!empty($list_pecah)) {
				$ids_pecah = array();
				foreach ($list_pecah as $p) {
					$ids_pecah[] = (int) $p->id;
					if (!empty($p->id_persediaan_baru)) {
						$ids_pecah[] = (int) $p->id_persediaan_baru;
					}
				}
				$ids_pecah = array_values(array_unique(array_filter($ids_pecah)));
				if (!empty($ids_pecah)) {
					$sql_jual_p = "
						SELECT id_persediaan_barang, COALESCE(SUM(jumlah), 0) AS total_terjual
						FROM tbl_penjualan
						WHERE id_persediaan_barang IN (" . implode(',', $ids_pecah) . ")
						AND (barang_jasa != 'jasa' OR barang_jasa IS NULL)
						GROUP BY id_persediaan_barang
					";
					foreach ($CI->db->query($sql_jual_p)->result() as $j) {
						$map_terjual_pecah[(int) $j->id_persediaan_barang] = (float) $j->total_terjual;
					}
				}
			}

			foreach ($list_pecah as $pecah) {
				$nama = trim((string) (isset($pecah->nama_barang_baru) ? $pecah->nama_barang_baru : ''));
				if ($nama === '') {
					$nama = isset($pecah->uraian) ? $pecah->uraian : '';
				}
				$satuan = trim((string) (isset($pecah->satuan_barang_baru) ? $pecah->satuan_barang_baru : ''));
				if ($satuan === '') {
					$satuan = isset($pecah->satuan) ? $pecah->satuan : '';
				}
				$harga = isset($pecah->harga_satuan_barang_baru) ? $pecah->harga_satuan_barang_baru : (isset($pecah->harga_satuan) ? $pecah->harga_satuan : 0);
				$jumlah_asal = (float) (isset($pecah->jumlah_barang_baru) ? $pecah->jumlah_barang_baru : (isset($pecah->jumlah) ? $pecah->jumlah : 0));

				$id_ref = (int) $pecah->id;
				$total_terjual = isset($map_terjual_pecah[$id_ref]) ? $map_terjual_pecah[$id_ref] : 0;
				if (!empty($pecah->id_persediaan_baru) && isset($map_terjual_pecah[(int) $pecah->id_persediaan_baru])) {
					$total_terjual += $map_terjual_pecah[(int) $pecah->id_persediaan_baru];
				}
				$sisa_stok = $jumlah_asal - $total_terjual;
				if ($sisa_stok <= 0) {
					continue;
				}

				$spop_val = trim((string) (isset($pecah->spop) ? $pecah->spop : ''));
				$kode_brg = trim((string) (isset($pecah->kode_barang_baru) ? $pecah->kode_barang_baru : (isset($pecah->kode_barang) ? $pecah->kode_barang : '')));

				$obj = new stdClass();
				$obj->id_persediaan_barang = $id_ref;
				$obj->uuid_persediaan = !empty($pecah->uuid_persediaan_baru) ? $pecah->uuid_persediaan_baru : (isset($pecah->uuid_persediaan) ? $pecah->uuid_persediaan : (isset($pecah->uuid_pecah_satuan) ? $pecah->uuid_pecah_satuan : ''));
				$obj->uuid_barang = !empty($pecah->uuid_barang_baru) ? $pecah->uuid_barang_baru : (isset($pecah->uuid_barang) ? $pecah->uuid_barang : '');
				$obj->kode_barang = $kode_brg;
				$obj->tgl_po = isset($pecah->tgl_po) ? $pecah->tgl_po : null;
				$obj->spop = $spop_val;
				$obj->spop_label = 'pecah satuan';
				$obj->sumber_tabel = 'tbl_pembelian_pecah_satuan';
				$obj->kategori = '';
				$obj->namabarang = $nama;
				$obj->satuan = $satuan;
				$obj->hpp = $harga;
				$obj->sisa_stok = $sisa_stok;
				$obj->total_10 = $sisa_stok;
				$obj->tabel_source_referensi = 'tbl_pembelian_pecah_satuan';
				$Data_stock[] = $obj;
			}
		}

		return $Data_stock;
	}
}

if (!function_exists('penjualan_modal_render_tbody')) {
	/**
	 * Bentuk HTML tbody + (opsional) modals untuk DataTable modal Pilih Barang.
	 *
	 * Kolom: No | Pilih | Tgl PO | SPOP | Kategori | Nama Barang | Harga Satuan | Satuan | Sisa Stok | Pilih
	 *
	 * @param array $Data_stock hasil penjualan_modal_load_stock_rows()
	 * @param array $extra opsional (belum dipakai untuk form tersembunyi)
	 * @return array [tbody => string, modals => string, jumlah => int]
	 */
	function penjualan_modal_render_tbody($Data_stock, $extra = array())
	{
		$tbody = '';
		$modals = '';
		$no = 0;

		if (!is_array($Data_stock) || empty($Data_stock)) {
			$tbody = '<tr><td colspan="10" class="text-center text-muted">Tidak ada barang persediaan untuk bulan ini.</td></tr>';
			return array(
				'tbody' => $tbody,
				'modals' => $modals,
				'jumlah' => 0,
			);
		}

		foreach ($Data_stock as $row) {
			$no++;
			$id_ref = (int) (isset($row->id_persediaan_barang) ? $row->id_persediaan_barang : 0);
			$uuid_pers = htmlspecialchars((string) (isset($row->uuid_persediaan) ? $row->uuid_persediaan : ''), ENT_QUOTES, 'UTF-8');
			$nama = htmlspecialchars((string) (isset($row->namabarang) ? $row->namabarang : ''), ENT_QUOTES, 'UTF-8');
			$satuan = htmlspecialchars((string) (isset($row->satuan) ? $row->satuan : ''), ENT_QUOTES, 'UTF-8');
			$kategori = htmlspecialchars((string) (isset($row->kategori) ? $row->kategori : ''), ENT_QUOTES, 'UTF-8');
			$spop = htmlspecialchars((string) (isset($row->spop) ? $row->spop : ''), ENT_QUOTES, 'UTF-8');
			$spop_label = trim((string) (isset($row->spop_label) ? $row->spop_label : ''));
			$sumber = htmlspecialchars((string) (isset($row->sumber_tabel) ? $row->sumber_tabel : ''), ENT_QUOTES, 'UTF-8');
			$hpp = (float) (isset($row->hpp) ? $row->hpp : 0);
			$sisa = (float) (isset($row->sisa_stok) ? $row->sisa_stok : 0);
			$tgl_po_raw = isset($row->tgl_po) ? $row->tgl_po : null;

			$tgl_po_disp = '';
			if (!empty($tgl_po_raw) && strpos((string) $tgl_po_raw, '0000-00-00') === false) {
				$ts = strtotime($tgl_po_raw);
				$tgl_po_disp = $ts ? date('d/m/Y', $ts) : htmlspecialchars((string) $tgl_po_raw, ENT_QUOTES, 'UTF-8');
			}

			// SPOP + label sumber di bawahnya (persediaan / sys_unit_produk / pecah_satuan)
			$spop_html = $spop;
			if ($spop_label !== '') {
				$spop_html .= '<br><small class="text-muted">' . htmlspecialchars($spop_label, ENT_QUOTES, 'UTF-8') . '</small>';
			}

			$harga_disp = number_format($hpp, 0, ',', '.');
			$sisa_disp = rtrim(rtrim(number_format($sisa, 2, ',', '.'), '0'), ',');

			$btn_pilih = '<button type="button" class="btn btn-xs btn-primary btn-pilih-barang-penjualan"'
				. ' data-id="' . $id_ref . '"'
				. ' data-uuid_persediaan="' . $uuid_pers . '"'
				. ' data-nama="' . $nama . '"'
				. ' data-satuan="' . $satuan . '"'
				. ' data-hpp="' . htmlspecialchars((string) $hpp, ENT_QUOTES, 'UTF-8') . '"'
				. ' data-sisa="' . htmlspecialchars((string) $sisa, ENT_QUOTES, 'UTF-8') . '"'
				. ' data-sumber="' . $sumber . '"'
				. ' data-spop="' . $spop . '"'
				. ' data-tgl_po="' . htmlspecialchars((string) $tgl_po_raw, ENT_QUOTES, 'UTF-8') . '"'
				. '>Pilih</button>';

			$tbody .= '<tr>'
				. '<td class="text-center">' . $no . '</td>'
				. '<td class="text-center">' . $btn_pilih . '</td>'
				. '<td>' . $tgl_po_disp . '</td>'
				. '<td>' . $spop_html . '</td>'
				. '<td>' . $kategori . '</td>'
				. '<td>' . $nama . '</td>'
				. '<td class="text-right">' . $harga_disp . '</td>'
				. '<td>' . $satuan . '</td>'
				. '<td class="text-right">' . $sisa_disp . '</td>'
				. '<td class="text-center">' . $btn_pilih . '</td>'
				. '</tr>';
		}

		return array(
			'tbody' => $tbody,
			'modals' => $modals,
			'jumlah' => $no,
		);
	}
}
