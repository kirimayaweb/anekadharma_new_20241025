<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$Persediaan_rows = isset($Persediaan_rows) && is_array($Persediaan_rows) ? $Persediaan_rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-persediaan-verifikasi';
$bulan_tampil = isset($bulan_tampil) ? (string) $bulan_tampil : date('Y-m');

if (!function_exists('persediaan_parse_angka')) {
	$CI_ver = function_exists('get_instance') ? get_instance() : null;
	if ($CI_ver) {
		$CI_ver->load->helper('persediaan_display');
	}
}

$sum_hpp = 0.0;
$sum_total_10_db = 0.0;
$sum_nilai_persediaan = 0.0;
?>
<div class="persediaan-tab-dt-wrap">
<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>"
	class="table table-bordered table-striped persediaan-tab-dt persediaan-verifikasi-dt"
	style="width:100%;font-size:15px;"
	data-money-cols="<?php echo htmlspecialchars(json_encode(array(6, 9, 10)), ENT_QUOTES, 'UTF-8'); ?>">
	<thead>
		<tr>
			<th width="50px">No</th>
			<th width="70px">ID</th>
			<th width="120px">Tanggal Beli</th>
			<th>Kategori</th>
			<th>Nama Barang</th>
			<th>Satuan</th>
			<th class="text-right persediaan-col-money">HPP</th>
			<th class="text-right">Sa</th>
			<th class="text-right">Beli</th>
			<th class="text-right persediaan-col-money">Total 10 (DB)</th>
			<th class="text-right persediaan-col-money">Total Nominal Persediaan</th>
			<th class="text-right">Total 10 (Kalk)</th>
			<th class="text-right">Penjualan</th>
			<th class="text-right">Pecah Satuan</th>
			<th class="text-right">Bahan Produksi</th>
			<th>SPOP</th>
			<th>Keterangan Verifikasi</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 0;
		foreach ($Persediaan_rows as $persediaan) :
			$no++;
			$id_row = (int) persediaan_row_get($persediaan, 'id');
			$parts = persediaan_gen_recalc_total_10_formula_parts($persediaan);
			$total_10_db = persediaan_parse_angka(persediaan_row_get($persediaan, 'total_10'));
			$total_10_kalk = (int) $parts['total_10_kalkulasi'];
			$hpp_num = persediaan_parse_angka(persediaan_row_get($persediaan, 'hpp'));
			$nilai_persediaan_row = $hpp_num * $total_10_db;
			$sum_hpp += $hpp_num;
			$sum_total_10_db += $total_10_db;
			$sum_nilai_persediaan += $nilai_persediaan_row;
			$ket = isset($persediaan->verifikasi_keterangan)
				? (string) $persediaan->verifikasi_keterangan
				: persediaan_verifikasi_tanpa_sumber_keterangan($persediaan);
			$kategori = isset($persediaan->verifikasi_kategori)
				? (string) $persediaan->verifikasi_kategori
				: (persediaan_row_is_kategori_jasa($persediaan) ? 'Jasa' : 'Barang');
			$row_warn = ($total_10_db > 0 && $total_10_kalk !== (int) floor($total_10_db));
			$tgl_beli = persediaan_format_tanggal_beli_tampil($persediaan);
			$nama_barang = (string) persediaan_row_get($persediaan, 'namabarang');
		?>
			<tr class="<?php echo $row_warn ? 'table-warning' : ''; ?>" data-persediaan-id="<?php echo (int) $id_row; ?>">
				<td><?php echo (int) $no; ?></td>
				<td><strong><?php echo (int) $id_row; ?></strong></td>
				<td>
					<div><?php echo htmlspecialchars($tgl_beli, ENT_QUOTES, 'UTF-8'); ?></div>
					<?php if ($id_row > 0) : ?>
						<button type="button" class="btn btn-danger btn-xs mt-1 btn-hapus-persediaan-verifikasi"
							data-id="<?php echo (int) $id_row; ?>"
							data-namabarang="<?php echo htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8'); ?>"
							title="Pindahkan ke persediaan_hapus">
							<i class="fas fa-trash"></i> Hapus
						</button>
					<?php endif; ?>
				</td>
				<td><span class="badge badge-<?php echo ($kategori === 'Jasa') ? 'info' : 'primary'; ?>"><?php echo htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8'); ?></span></td>
				<td><?php echo htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars((string) persediaan_row_get($persediaan, 'satuan'), ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right persediaan-col-money pv-col-hpp" data-num="<?php echo htmlspecialchars((string) $hpp_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_tampil_hpp_row($persediaan); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil(persediaan_parse_angka(persediaan_row_get($persediaan, 'sa'))); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil(persediaan_parse_angka(persediaan_row_get($persediaan, 'beli'))); ?></td>
				<td class="text-right persediaan-col-money pv-col-total10" data-num="<?php echo htmlspecialchars((string) $total_10_db, ENT_QUOTES, 'UTF-8'); ?>"><strong><?php echo persediaan_format_angka_tampil($total_10_db); ?></strong></td>
				<td class="text-right persediaan-col-money pv-col-nominal" data-num="<?php echo htmlspecialchars((string) $nilai_persediaan_row, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_format_rupiah_tampil($nilai_persediaan_row, true); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil($total_10_kalk); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil($parts['penjualan']); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil($parts['pecah_satuan']); ?></td>
				<td class="text-right"><?php echo persediaan_format_angka_tampil($parts['bahan_produksi']); ?></td>
				<td><?php echo htmlspecialchars((string) persediaan_row_get($persediaan, 'spop'), ENT_QUOTES, 'UTF-8'); ?></td>
				<td><small class="text-danger"><?php echo htmlspecialchars($ket, ENT_QUOTES, 'UTF-8'); ?></small></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr class="pv-verifikasi-total-row">
			<th class="persediaan-foot-total-label">TOTAL</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th class="text-right persediaan-col-money persediaan-foot-num pv-foot-hpp"><?php echo persediaan_format_rupiah_tampil($sum_hpp, true); ?></th>
			<th></th>
			<th></th>
			<th class="text-right persediaan-col-money persediaan-foot-num pv-foot-total10"><?php echo persediaan_format_angka_tampil($sum_total_10_db); ?></th>
			<th class="text-right persediaan-col-money persediaan-foot-num pv-foot-nominal"><?php echo persediaan_format_rupiah_tampil($sum_nilai_persediaan, true); ?></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
</table>
</div>
<style type="text/css">
	table.persediaan-verifikasi-dt tfoot .pv-verifikasi-total-row th {
		background-color: #fff3cd;
		font-weight: 700;
		border-top: 2px solid #ffc107;
	}
	table.persediaan-verifikasi-dt tfoot .pv-foot-hpp,
	table.persediaan-verifikasi-dt tfoot .pv-foot-total10,
	table.persediaan-verifikasi-dt tfoot .pv-foot-nominal {
		color: #c82333;
		font-family: Consolas, Monaco, monospace;
	}
</style>
