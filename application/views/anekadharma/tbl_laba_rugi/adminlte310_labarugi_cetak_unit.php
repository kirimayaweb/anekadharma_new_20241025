<head>
	<style>
		@page { margin: 8mm 10mm; size: A4 portrait; }
		body { margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 11pt; color: #000; }
		.unit-page {
			margin: 0;
			padding: 0;
			page-break-inside: avoid;
		}
		.unit-page.unit-page-after-break {
			page-break-after: always;
			border-top: none !important;
		}
		@media screen {
			.unit-page + .unit-page {
				margin-top: 24px;
				padding-top: 0;
				border-top: none;
			}
		}
		.cetak-unit-header {
			text-align: center;
			font-weight: bold;
			font-style: normal;
			border: none;
			margin: 0;
			padding: 0;
			line-height: 1.25;
		}
		.cetak-unit-header p {
			margin: 0.15em 0;
			padding: 0;
			border: none !important;
			font-size: 1.09em;
			font-weight: bold;
			color: #000;
		}
		#customers {
			font-family: Arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
			table-layout: fixed;
			color: #000;
			border: none !important;
			border-top: none !important;
		}
		#customers td, #customers th {
			border: 0;
			padding: 0.2em 0.3em;
			font-family: Arial, sans-serif;
			font-size: 1em;
			font-weight: normal;
			vertical-align: middle;
			color: #000;
			line-height: 1.2;
		}
		#customers th { background-color: transparent; color: #000; }
		#customers > tr:first-child th {
			border-top: none !important;
		}
		#customers tr.row-title th {
			background: transparent !important;
			border-top: none !important;
			border-bottom: none !important;
		}
		#customers .col-label { text-align: left; padding-left: 0.6em; width: 72%; }
		#customers .col-rp { text-align: left; width: 8%; white-space: nowrap; }
		#customers .col-nominal { text-align: right; padding-right: 0.5em; width: 20%; white-space: nowrap; }
		#customers .col-empty { text-align: right; color: #000; }

		#customers .cetak-label-title {
			font-size: 1.09em;
			font-weight: 800;
			padding-left: 0.6em;
			background: transparent;
			border-top: none !important;
		}
		#customers .cetak-label-indent { padding-left: 1.2em; font-weight: 600; }
		#customers .cetak-label-deep-indent { padding-left: 2.4em; font-weight: 600; }
		#customers .cetak-label-sub-muted {
			padding-left: 2.4em;
			font-size: 0.82em;
			font-style: italic;
			font-weight: 400;
			color: #000;
		}
		#customers .cetak-label-summary {
			font-size: 1.09em;
			font-weight: 800;
			padding-left: 0.6em;
			background: transparent;
		}
		#customers tr.row-summary th { background: transparent !important; }
		#customers .label-bold { font-weight: 600; }

		#customers .cetak-nominal-calc { font-weight: bold; color: #000; }
		#customers .cetak-nominal-muted { font-style: italic; font-weight: normal; color: #000; }

		#customers tr.nominal-border-double th.col-rp,
		#customers tr.nominal-border-double th.col-nominal {
			border-top: 1px solid black !important;
			border-bottom: 3px double black !important;
		}

		#customers tr.nominal-border-single th.col-rp,
		#customers tr.nominal-border-single th.col-nominal {
			border-top: 1px solid black !important;
			border-bottom: 1px solid black !important;
		}

		#customers tr.nominal-border-top th.col-rp,
		#customers tr.nominal-border-top th.col-nominal {
			border-top: 1px solid black !important;
			border-bottom: none !important;
		}

		#ttd-footer {
			width: 100%;
			border-collapse: collapse;
			table-layout: fixed;
			margin-top: 0.4em;
			font-family: Arial, sans-serif;
		}

		#ttd-footer th {
			border: none;
			font-weight: normal;
			font-size: 1em;
			font-family: Arial, sans-serif;
			padding: 0.12em 0;
			background: transparent;
			color: #000;
			line-height: 1.2;
		}

		#ttd-footer .ttd-col-nominal {
			text-align: center;
			padding-right: 0.5em;
			white-space: nowrap;
		}

		#ttd-footer .ttd-signature-space {
			height: 2.2em;
			padding: 0;
		}

		#ttd-footer .ttd-direktur-nama {
			font-weight: bold;
			text-decoration: underline;
		}

	</style>
</head>
<?php
$this->load->helper(array('laba_rugi_detail', 'laba_rugi_keterangan', 'laba_rugi_unit_publish'));

if (!function_exists('labarugi_cetak_unit_bulan_teks')) {
    function labarugi_cetak_unit_bulan_teks($angka_bulan) {
        $map = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');
        return isset($map[(int)$angka_bulan]) ? $map[(int)$angka_bulan] : '';
    }
}

if (!function_exists('labarugi_cetak_unit_format_nominal')) {
    function labarugi_cetak_unit_format_nominal($nom) {
        return number_format((float) $nom, 2, ',', '.');
    }
}

if (!function_exists('labarugi_cetak_unit_page_font_pt')) {
    function labarugi_cetak_unit_page_font_pt($jenis_tab, $keterangan_row_count) {
        // Perkiraan baris visual: header + data + pajak + ttd
        $visual_rows = (int) $keterangan_row_count + 3 + 2 + 6;
        $target_rows = ($jenis_tab === 'rinci') ? 38 : 28;
        $base_pt = 11.0;
        if ($visual_rows > $target_rows) {
            $base_pt = $base_pt * ($target_rows / $visual_rows);
        } else {
            $base_pt = $base_pt * 0.95;
        }
        $min_pt = ($jenis_tab === 'rinci') ? 7.0 : 8.2;
        if ($base_pt < $min_pt) {
            $base_pt = $min_pt;
        }
        return round($base_pt, 2);
    }
}

$cetak_unit_base_font_pt = labarugi_cetak_unit_page_font_pt($jenis_tab, count($keterangan_rows));

?>
<body>
<?php
$total_published_units = count($published_units);
$unit_page_index = 0;
foreach ($published_units as $unit_row) {
    $unit_page_index++;
    $is_last_unit_page = ($unit_page_index >= $total_published_units);
    $unit_key = labarugi_detail_unit_key($unit_row);
    $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
    $unit_nama_cetak = strtoupper(trim($unit_label));
    $vals = array();
    foreach ($keterangan_rows as $ket_row) {
        $vals[$ket_row['key']] = labarugi_unit_publish_detail_nominal($detail_map, $ket_row['key'], $unit_key);
    }
    $pajak = labarugi_unit_publish_detail_nominal($detail_map, 'pajak', $unit_key);
    $sebelum_pajak = isset($vals['laba_rugi_sebelum_pajak']) ? $vals['laba_rugi_sebelum_pajak'] : 0;
    $setelah_pajak = $sebelum_pajak - $pajak;
    $ttd_tanggal = 'Bantul, ';
    if ((int) $bulan_laba_rugi > 0) {
        $ttd_tanggal .= 'Bulan ' . labarugi_cetak_unit_bulan_teks($bulan_laba_rugi) . ' Tahun ' . (int) $tahun_laba_rugi;
    } else {
        $ttd_tanggal .= 'Tahun ' . (int) $tahun_laba_rugi;
    }
?>
<div class="unit-page<?php echo $is_last_unit_page ? '' : ' unit-page-after-break'; ?>" style="font-size: <?php echo $cetak_unit_base_font_pt; ?>pt;">
	<div class="cetak-unit-header">
		<p><strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong></p>
		<p><strong>LAPORAN LABA - RUGI PER UNIT (<?php echo htmlspecialchars($unit_nama_cetak, ENT_QUOTES, 'UTF-8'); ?>)</strong></p>
		<p><strong>Per <?php echo labarugi_cetak_unit_bulan_teks($bulan_laba_rugi); ?> Tahun <?php echo (int) $tahun_laba_rugi; ?></strong></p>
	</div>
	<table id="customers" width="100%">
		<colgroup>
			<col style="width:72%">
			<col style="width:8%">
			<col style="width:20%">
		</colgroup>

		<?php foreach ($keterangan_rows as $ket_row) {
            $ket_key = $ket_row['key'];
            $ket_label = $ket_row['label'];
            $is_title = labarugi_keterangan_is_title_row($ket_row);
            $is_summary = labarugi_keterangan_is_calculated_key_for_tab($ket_key, $jenis_tab)
                && labarugi_keterangan_row_style_for_key($ket_key, $jenis_tab) === 'summary';
            $label_classes = labarugi_keterangan_cetak_label_classes($ket_key, $ket_row, $jenis_tab);
            $row_class = '';
            if ($is_title) {
                $row_class = 'row-title';
            } elseif ($is_summary) {
                $row_class = 'row-summary';
            }
            $border_class = labarugi_keterangan_cetak_nominal_row_class($ket_key);
            if ($border_class !== '') {
                $row_class = trim($row_class . ' ' . $border_class);
            }
            $nom_cell_class = labarugi_keterangan_cetak_nominal_cell_class($ket_key, $jenis_tab);
            $nom = isset($vals[$ket_key]) ? $vals[$ket_key] : 0;
        ?>
		<tr class="<?php echo htmlspecialchars(trim($row_class), ENT_QUOTES, 'UTF-8'); ?>">
			<th class="<?php echo htmlspecialchars(implode(' ', $label_classes), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></th>
			<?php if ($is_title) { ?>
			<th class="col-rp col-empty"></th>
			<th class="col-nominal col-empty"></th>
			<?php } else { ?>
			<th class="col-rp <?php echo htmlspecialchars($nom_cell_class, ENT_QUOTES, 'UTF-8'); ?>">Rp.</th>
			<th class="col-nominal <?php echo htmlspecialchars($nom_cell_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo labarugi_cetak_unit_format_nominal($nom); ?></th>
			<?php } ?>
		</tr>
		<?php } ?>

		<tr>
			<th class="col-label cetak-label-indent label-bold">PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo labarugi_cetak_unit_format_nominal($pajak); ?></th>
		</tr>
		<tr class="row-summary nominal-border-top">
			<th class="col-label cetak-label-summary">LABA RUGI SETELAH PAJAK</th>
			<th class="col-rp cetak-nominal-calc">Rp.</th>
			<th class="col-nominal cetak-nominal-calc"><?php echo labarugi_cetak_unit_format_nominal($setelah_pajak); ?></th>
		</tr>
	</table>

	<table id="ttd-footer" width="100%" style="font-size: <?php echo $cetak_unit_base_font_pt; ?>pt;">
		<colgroup>
			<col style="width:72%">
			<col style="width:8%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal"><?php echo htmlspecialchars($ttd_tanggal, ENT_QUOTES, 'UTF-8'); ?></th>
		</tr>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal">Perusahaan Umum Daerah Aneka Dharma</th>
		</tr>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal">Kabupaten Bantul</th>
		</tr>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal">Direktur</th>
		</tr>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal ttd-signature-space"></th>
		</tr>
		<tr>
			<th class="col-label"></th>
			<th colspan="2" class="ttd-col-nominal ttd-direktur-nama">Yuli Budi Sasangka,ST</th>
		</tr>
	</table>
</div>
<?php } ?>
</body>
