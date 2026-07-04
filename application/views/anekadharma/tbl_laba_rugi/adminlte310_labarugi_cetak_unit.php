<head>
	<style>
		@page { margin: 10mm; size: A4 portrait; }
		body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; font-size: 11pt; }
		.unit-page {
			margin: 0;
			padding: 0;
		}
		.unit-page-break {
			page-break-before: always;
		}
		.unit-page table {
			page-break-inside: avoid;
		}
		@media screen {
			.unit-page-break {
				border-top: 2px dashed #bbb;
				margin-top: 24px;
				padding-top: 24px;
			}
		}
		#customers { font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed; }
		#customers td, #customers th { border: 0; padding: 5px 4px; font-size: 11pt; }
		#customers th { padding-top: 3px; padding-bottom: 3px; background-color: white; color: black; font-weight: normal; }
		#customers .judul-row th { text-align: center; font-size: 14pt; font-weight: bold; border: none; }
		#customers .col-label { text-align: left; padding-left: 8px; width: 72%; }
		#customers .col-rp { text-align: left; width: 8%; white-space: nowrap; }
		#customers .col-nominal { text-align: right; padding-right: 8px; width: 20%; white-space: nowrap; }
		#customers .col-label.label-bold { font-weight: bold; }
		#customers tr.row-highlight-nominal th.col-rp,
		#customers tr.row-highlight-nominal th.col-nominal {
			border-top: 1px solid black !important;
			border-bottom: 3px double black !important;
		}
		.unit-badge { text-align: center; font-size: 12pt; font-weight: bold; margin: 6px 0 10px; }
	</style>
</head>
<?php
$this->load->helper(array('laba_rugi_detail', 'laba_rugi_unit_publish'));

if (!function_exists('labarugi_cetak_unit_bulan_teks')) {
    function labarugi_cetak_unit_bulan_teks($angka_bulan) {
        $map = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');
        return isset($map[(int)$angka_bulan]) ? $map[(int)$angka_bulan] : '';
    }
}

$judul_jenis = ($jenis_tab === 'sederhana') ? 'SEDERHANA' : 'RINCI';
?>
<body>
<?php $unit_page_index = 0; foreach ($published_units as $unit_row) {
    $unit_page_index++;
    $unit_key = labarugi_detail_unit_key($unit_row);
    $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
    $vals = array();
    foreach ($keterangan_rows as $ket_row) {
        $vals[$ket_row['key']] = labarugi_unit_publish_detail_nominal($detail_map, $ket_row['key'], $unit_key);
    }

    $penjualan = isset($vals['penjualan']) ? $vals['penjualan'] : 0;
    $hpp = isset($vals['beban_pokok_penjualan']) ? $vals['beban_pokok_penjualan'] : 0;
    $pajak = isset($vals['pajak']) ? $vals['pajak'] : 0;
    $bruto = $penjualan - $hpp;

    if ($jenis_tab === 'sederhana') {
        $total_beban_ops = isset($vals['total_beban_operasional']) ? $vals['total_beban_operasional'] : 0;
        $laba_ops = $bruto - $total_beban_ops;
        $sebelum_pajak = $laba_ops;
    } else {
        $total_beban_ops = 0;
        $beban_keys = array(
            'beban_depresiasi_dan_amortisasi', 'beban_operasional_karyawan', 'beban_operasional_promosi',
            'beban_perjalanan_dinas', 'beban_transportasi', 'beban_pemeliharaan', 'total_beban_operasional_umum'
        );
        foreach ($beban_keys as $bk) {
            $total_beban_ops += isset($vals[$bk]) ? $vals[$bk] : 0;
        }
        $laba_ops = $bruto - $total_beban_ops;
        $pendapatan_lain = (isset($vals['pendapatan_bunga_bank']) ? $vals['pendapatan_bunga_bank'] : 0)
            + (isset($vals['pendapatan_rupa_rupa']) ? $vals['pendapatan_rupa_rupa'] : 0);
        $beban_lain = (isset($vals['beban_bunga_dan_adm_bank']) ? $vals['beban_bunga_dan_adm_bank'] : 0)
            + (isset($vals['beban_rupa_rupa']) ? $vals['beban_rupa_rupa'] : 0);
        $sebelum_pajak = $laba_ops + $pendapatan_lain - $beban_lain;
    }
    $setelah_pajak = $sebelum_pajak - $pajak;
?>
<div class="unit-page<?php echo ($unit_page_index > 1) ? ' unit-page-break' : ''; ?>">
	<table id="customers" width="100%">
		<colgroup>
			<col style="width:72%">
			<col style="width:8%">
			<col style="width:20%">
		</colgroup>
		<tr class="judul-row"><th colspan="3"><strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong></th></tr>
		<tr class="judul-row"><th colspan="3"><strong>LAPORAN LABA - RUGI PER UNIT (<?php echo $judul_jenis; ?>)</strong></th></tr>
		<tr class="judul-row"><th colspan="3"><strong>Per <?php echo labarugi_cetak_unit_bulan_teks($bulan_laba_rugi); ?> Tahun <?php echo (int) $tahun_laba_rugi; ?></strong></th></tr>
		<tr class="judul-row"><th colspan="3" class="unit-badge">UNIT: <?php echo htmlspecialchars(strtoupper($unit_label), ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>)</th></tr>

		<?php foreach ($keterangan_rows as $ket_row) {
            $nom = isset($vals[$ket_row['key']]) ? $vals[$ket_row['key']] : 0;
        ?>
		<tr>
			<th class="col-label"><?php echo htmlspecialchars($ket_row['label'], ENT_QUOTES, 'UTF-8'); ?></th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($nom, 2, ',', '.'); ?></th>
		</tr>
		<?php } ?>

		<tr class="row-highlight-nominal">
			<th class="col-label label-bold">LABA RUGI BRUTO</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($bruto, 2, ',', '.'); ?></th>
		</tr>
		<tr class="row-highlight-nominal">
			<th class="col-label label-bold">LABA / RUGI OPERASIONAL</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($laba_ops, 2, ',', '.'); ?></th>
		</tr>
		<tr class="row-highlight-nominal">
			<th class="col-label label-bold">LABA RUGI SEBELUM PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($sebelum_pajak, 2, ',', '.'); ?></th>
		</tr>
		<tr>
			<th class="col-label label-bold">PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($pajak, 2, ',', '.'); ?></th>
		</tr>
		<tr class="row-highlight-nominal">
			<th class="col-label label-bold">LABA RUGI SETELAH PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($setelah_pajak, 2, ',', '.'); ?></th>
		</tr>
	</table>
</div>
<?php } ?>
</body>
