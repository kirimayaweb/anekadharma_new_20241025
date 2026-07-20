<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($labarugi_view_mode)) { $labarugi_view_mode = 'utama'; }
if (!isset($labarugi_tab_key)) { $labarugi_tab_key = 'utama'; }
if (!isset($data_tbl_laba_rugi) || empty($data_tbl_laba_rugi)) {
    $data_tbl_laba_rugi = (object) array(
        'penjualan' => 0, 'beban_pokok_penjualan' => 0, 'beban_depresiasi_dan_amortisasi' => 0,
        'beban_operasional_karyawan' => 0, 'beban_operasional_promosi' => 0, 'beban_perjalanan_dinas' => 0,
        'beban_transportasi' => 0, 'beban_pemeliharaan' => 0, 'total_beban_operasional_umum' => 0,
        'pendapatan_bunga_bank' => 0, 'pendapatan_rupa_rupa' => 0, 'beban_bunga_dan_adm_bank' => 0,
        'beban_rupa_rupa' => 0, 'pajak' => 0,
    );
}
if (!function_exists('labarugi_bulan_teks')) {
    function labarugi_bulan_teks($angka_bulan) {
        $map = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');
        return isset($map[(int)$angka_bulan]) ? $map[(int)$angka_bulan] : '';
    }
}
$labarugi_judul = 'LAPORAN LABA - RUGI';
if ($labarugi_view_mode === 'rinci') {
    $labarugi_judul = 'LAPORAN LABA - RUGI PER UNIT (RINCI)';
} elseif ($labarugi_view_mode === 'sederhana') {
    $labarugi_judul = 'LAPORAN LABA - RUGI PER UNIT (SEDERHANA)';
}
$labarugi_readonly = ($labarugi_view_mode !== 'utama');
$list_unit = isset($list_unit) ? $list_unit : array();
$labarugi_utama_ctx = array();
if ($labarugi_view_mode === 'utama') {
    include_once APPPATH . 'views/anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_utama_field.php';
    $labarugi_utama_ctx = array(
        'data_tbl_laba_rugi' => $data_tbl_laba_rugi,
        'action' => isset($action) ? $action : '',
        'labarugi_utama_ka_map' => isset($labarugi_utama_ka_map) ? $labarugi_utama_ka_map : array(),
        'labarugi_utama_bb_rows' => isset($labarugi_utama_bb_rows) ? $labarugi_utama_bb_rows : array(),
        'labarugi_utama_sync_map' => isset($labarugi_utama_sync_map) ? $labarugi_utama_sync_map : array(),
        'labarugi_utama_save_url' => isset($labarugi_utama_save_url) ? $labarugi_utama_save_url : '',
        'labarugi_utama_sync_url' => isset($labarugi_utama_sync_url) ? $labarugi_utama_sync_url : '',
        'labarugi_utama_record_id' => isset($labarugi_utama_record_id) ? (int) $labarugi_utama_record_id : 0,
        'uuid_data_laba_rugi' => isset($uuid_data_laba_rugi) ? $uuid_data_laba_rugi : '',
        'tahun_neraca' => isset($tahun_neraca) ? $tahun_neraca : 0,
        'bulan_transaksi' => isset($bulan_transaksi) ? $bulan_transaksi : 0,
    );
}
?>
		<div class="card-header labarugi-panel-<?php echo htmlspecialchars($labarugi_tab_key, ENT_QUOTES, 'UTF-8'); ?>">

			<div class="row">
				<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




				<div class="form-group">

					<div class="row">
						<?php if ($labarugi_view_mode === 'utama') { ?>
						<div class="col-1">
							<!-- spacer -->
						</div>
						<div class="col-8">
						<?php } else { ?>
						<div class="col-12">
						<?php } ?>


							<table id="customers-<?php echo htmlspecialchars($labarugi_tab_key, ENT_QUOTES, 'UTF-8'); ?>" class="customers-labarugi-table<?php echo ($labarugi_view_mode !== 'utama') ? ' customers-labarugi-table-grid-mode' : ''; ?>">




								<!-- BARIS KE 1 -->
								<tr>
									<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
										<strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong>
									</th>
								</tr>

								<tr>
									<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
										<strong><?php echo htmlspecialchars($labarugi_judul, ENT_QUOTES, 'UTF-8'); ?></strong>
									</th>
								</tr>

								<tr>
									<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">

										<strong>Per
											<?php
											if ($bulan_transaksi > 0) {
												echo ' ' . labarugi_bulan_teks($bulan_transaksi);
											}
											?>
											Tahun <?php echo $tahun_neraca; ?></strong>


									</th>

								</tr>


								<?php if ($labarugi_view_mode !== 'utama') { ?>
							</table>

							<div class="labarugi-grid-host-wrap">
								<?php
								if (empty($list_unit)) {
									echo '<div class="alert alert-light border mt-2 mb-0">Belum ada data unit. Tambahkan unit di menu master unit.</div>';
								} else {
									$this->load->view('anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_unit_grid', array(
										'labarugi_view_mode' => $labarugi_view_mode,
										'labarugi_tab_key' => $labarugi_tab_key,
										'list_unit' => $list_unit,
										'labarugi_detail_maps' => isset($labarugi_detail_maps) ? $labarugi_detail_maps : array(),
										'labarugi_unit_publish_maps' => isset($labarugi_unit_publish_maps) ? $labarugi_unit_publish_maps : array(),
										'labarugi_is_published' => isset($labarugi_is_published) ? (bool) $labarugi_is_published : false,
										'uuid_data_laba_rugi' => isset($uuid_data_laba_rugi) ? $uuid_data_laba_rugi : '',
										'tahun_neraca' => $tahun_neraca,
										'bulan_transaksi' => $bulan_transaksi,
									));
								}
								?>
							</div>

							<table class="customers-labarugi-table labarugi-footer-table">
								<?php } ?>

								<?php if ($labarugi_view_mode === 'utama') { ?>

								<!-- Untuk setting form pertama agar form kedua/penjualan bisa terbaca di controller -->
								<form action="<?php echo $action . '/penjualanX'; ?>" method="post">

									<!-- <input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" /> -->

									<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
								</form>
								<!-- //Untuk setting form pertama agar form kedua/penjualan bisa terbaca di controller -->

								<tbody class="labarugi-utama-wrap"
									data-tahun="<?php echo (int) $tahun_neraca; ?>"
									data-bulan="<?php echo (int) $bulan_transaksi; ?>"
									data-jenis-tab="utama">

								<?php
								$this->load->helper('laba_rugi_keterangan');
								$labarugi_utama_structure = labarugi_keterangan_rows_by_tab($this, 'utama');
								foreach ($labarugi_utama_structure as $ket_row) {
									if (labarugi_keterangan_is_title_row($ket_row)) {
										labarugi_utama_render_title_row($ket_row['label'], $labarugi_utama_ctx, $ket_row['key']);
									} elseif (labarugi_keterangan_is_calculated_key_for_tab($ket_row['key'], 'utama')) {
										labarugi_utama_render_calculated_row($ket_row['key'], $ket_row['label'], $labarugi_utama_ctx);
									} else {
										labarugi_utama_render_editable_row($ket_row['key'], $ket_row['label'], $labarugi_utama_ctx);
									}
								}
								?>

								</tbody>

								<?php } ?>




								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Bantul,
										<?php

										if ($bulan_transaksi > 0) {
											echo " " . labarugi_bulan_teks($bulan_transaksi);
										} else {
										}

										echo " " . $tahun_neraca;
										?></strong>


									</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Perusahaan Umum Daerah Aneka Dharma</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Kabupaten Bantul</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>




								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Direktur</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Yuli Budi Sasangka,ST</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>





							</table>


						</div>
						<?php if ($labarugi_view_mode === 'utama') { ?>
						<div class="col-1"></div>
						<?php } ?>
					</div>

				</div>


			</div>

		</div>
