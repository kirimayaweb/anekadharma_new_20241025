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



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PENJUALAN</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/penjualan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->penjualan);
																																														// echo "<br/>";  
																																														echo number_format($data_tbl_laba_rugi->penjualan, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN POKOK PENJUALAN</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_pokok_penjualan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_pokok_penjualan); 
																																														echo number_format($data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">LABA RUGI BRUTO</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/laba_rugi_bruto'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan); 
																																													echo number_format($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.');
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form> -->


									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

								</tr>



								<!-- buat jarak dengan baris atasnya -->
								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN OPERASIONAL</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
									<th style="font-size:0.550em; text-align:right;border-top:none;border-bottom:none;  width: 200px;" colspan="200"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Depresiasi dan Amortisasi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_depresiasi_dan_amortisasi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi); 
																																														echo number_format($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>

								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Karyawan</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_operasional_karyawan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_operasional_karyawan); 
																																														echo number_format($data_tbl_laba_rugi->beban_operasional_karyawan, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>

								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Promosi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_operasional_promosi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_operasional_promosi); 
																																														echo number_format($data_tbl_laba_rugi->beban_operasional_promosi, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>







								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Perjalanan Dinas</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_perjalanan_dinas'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_perjalanan_dinas); 
																																														echo number_format($data_tbl_laba_rugi->beban_perjalanan_dinas, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Transportasi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_transportasi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_transportasi); 
																																														echo number_format($data_tbl_laba_rugi->beban_transportasi, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Pemeliharaan</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">


										<form action="<?php echo $action . '/beban_pemeliharaan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_pemeliharaan); 
																																														echo number_format($data_tbl_laba_rugi->beban_pemeliharaan, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>








								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Total Beban Operaisonal umum</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">


										<form action="<?php echo $action . '/total_beban_operasional_umum'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->total_beban_operasional_umum); 
																																														echo number_format($data_tbl_laba_rugi->total_beban_operasional_umum, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>







								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">Total Beban Operasional</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum); 


																																													echo number_format($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum, 2, ',', '.');
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">Laba / Rugi Operasional</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																													// echo str_replace('.', ',', ($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum));



																																													echo number_format(($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum), 2, ',', '.');
																																													$GET_Labar_rugi_operasional = ($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum);
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>


								<!-- Garis double : bprder-top : 1px -->
								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pendapatan_bunga_bank'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_bunga_bank); 

																																														echo number_format(($data_tbl_laba_rugi->pendapatan_bunga_bank), 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Rupa-Rupa</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pendapatan_rupa_rupa'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_rupa_rupa); 
																																														echo number_format($data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>




								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total Pendapatan Lain-lain</strong></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa);

																																													echo number_format($data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.');
																																													$GET_Total_Pendapatan_Lain_Lain = $data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa;
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>



								<!-- Garis double : bprder-top : 1px -->
								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Beban Bunga dan ADM Bank</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_bunga_dan_adm_bank'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_bunga_dan_adm_bank); 
																																														echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Beban Rupa-Rupa</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_rupa_rupa'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_rupa_rupa); 
																																														echo number_format($data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total beban Lain-Lain</strong></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa);

																																													echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.');
																																													$GET_Total_beban_lain_lain = $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa;
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<!-- Garis double : bprder-top : 1px -->
								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">LABA RUGI SEBELUM PAJAK</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																													// echo str_replace('.', ',', $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain);

																																													echo number_format($GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain, 2, ',', '.');
																																													$GET_Laba_rugi_sebelum_pajak = $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain;
																																													?> " style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PAJAK</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pajak'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_laba_rugi->pajak); /
																																														echo number_format($data_tbl_laba_rugi->pajak, 2, ',', '.');
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">LABA RUGI SETELAH PAJAK</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																													// echo str_replace('.', ',', $GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak); 
																																													echo number_format($GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak, 2, ',', '.');
																																													?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

								</tr>

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
