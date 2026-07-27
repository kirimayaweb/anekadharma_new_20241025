<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Neraca - PERUMDA ANEKA DHARMA</title>
	<style>
		@page {
			margin: 6mm 8mm;
			size: landscape;
		}

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;
			font-size: 9.5pt;
			line-height: 1.35;
			color: #1a1a1a;
			background: #ffffff;
		}

		#neraca-wrap {
			page-break-inside: avoid;
			max-width: 100%;
		}

		/* ===== HEADER — netral, tanpa warna mencolok ===== */
		#neraca-header {
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 8px;
		}

		#neraca-header th {
			border: none;
			text-align: center;
			padding: 2px 0;
			background: transparent;
			color: #1a1a1a;
		}

		#neraca-header .company-name {
			font-size: 12pt;
			font-weight: 700;
			letter-spacing: 0.3px;
		}

		#neraca-header .report-title {
			font-size: 11pt;
			font-weight: 600;
			margin-top: 2px;
			text-transform: uppercase;
			letter-spacing: 0.8px;
		}

		#neraca-header .report-period {
			font-size: 9.5pt;
			font-weight: 400;
			margin-top: 2px;
			color: #444;
		}

		#neraca-header-spacer {
			height: 4px;
		}

		/* ===== MAIN TABLE ===== */
		#neraca {
			border-collapse: collapse;
			width: 100%;
			table-layout: fixed;
			border: 1px solid #d1d5db;
		}

		#neraca td,
		#neraca th {
			border: 0;
			padding: 3px 6px;
			font-size: 9.5pt;
			font-weight: normal;
			vertical-align: middle;
			line-height: 1.3;
			color: #1a1a1a;
		}

		#neraca .col-gap {
			width: 1%;
			padding: 0;
			background-color: #ffffff !important;
			border-left: 1px solid #e5e7eb !important;
			border-right: none !important;
		}

		#neraca .col-label {
			width: 33%;
			text-align: left;
			padding-left: 8px;
		}

		#neraca .col-label.indent {
			padding-left: 18px;
		}

		#neraca .col-label.indent-mid {
			padding-left: 32px;
			font-weight: bold;
		}

		#neraca .col-label.label-bold {
			font-weight: bold;
		}

		#neraca .col-rp {
			width: 4%;
			text-align: left;
			white-space: nowrap;
			color: #666;
			font-size: 8.5pt;
		}

		#neraca .col-nominal {
			width: 12%;
			text-align: right;
			padding-right: 8px;
			white-space: nowrap;
			font-variant-numeric: tabular-nums;
		}

		#neraca .data-row.bold .col-label,
		#neraca .data-row.bold .col-nominal,
		#neraca .data-row.bold .col-rp {
			font-weight: bold;
		}

		#neraca .blank-row th {
			height: 4px;
			padding: 0;
			line-height: 0;
			font-size: 0;
			background-color: #ffffff !important;
		}

		#neraca tr.row-double-top th {
			border-top: 1px solid #d1d5db;
			padding: 0;
			height: 0;
			line-height: 0;
			font-size: 0;
			background: #ffffff;
		}

		#neraca tr.row-table-last th {
			border-bottom: 1px solid #d1d5db;
		}

		/* Section — netral, fokus ke struktur */
		#neraca .row-section-main th {
			background-color: #ffffff !important;
			color: #1a1a1a;
			font-weight: 700;
			font-size: 10pt;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			padding-top: 5px;
			padding-bottom: 5px;
			border-bottom: 1px solid #e5e7eb;
		}

		#neraca .row-section-sub th {
			background-color: #ffffff !important;
			color: #1a1a1a;
			font-weight: 700;
			font-size: 9.5pt;
			padding-top: 4px;
			padding-bottom: 4px;
			border-bottom: 1px solid #eef0f2;
		}

		/* Baris data — garis lembut antar baris */
		#neraca .row-data-item th {
			border-bottom: 1px solid #eef0f2;
		}

		/* Total — garis hitam tegas */
		#neraca .row-total-highlight th {
			font-weight: 700;
			background-color: #ffffff !important;
			border-top: 2px solid #000000 !important;
			border-bottom: 3px double #000000 !important;
		}

		#neraca .row-grand-total th {
			font-weight: 700;
			font-size: 10pt;
			background-color: #ffffff !important;
			color: #000000 !important;
			text-transform: uppercase;
			letter-spacing: 0.3px;
			padding-top: 6px;
			padding-bottom: 6px;
			border-top: 2px solid #000000 !important;
			border-bottom: 3px double #000000 !important;
		}

		#neraca .highlight-box {
			display: inline;
			font-weight: bold;
			color: #000000;
		}

		/* ===== FOOTER / TTD ===== */
		#ttd-footer {
			width: 100%;
			border-collapse: collapse;
			table-layout: fixed;
			margin-top: 12px;
			page-break-inside: avoid;
		}

		#ttd-footer th {
			border: none;
			font-weight: normal;
			font-size: 9.5pt;
			padding: 2px 0;
			line-height: 1.4;
			color: #333;
			background: transparent;
		}

		#ttd-footer .ttd-spacer-left {
			width: 32%;
		}

		#ttd-footer .ttd-spacer-mid {
			width: 18%;
		}

		#ttd-footer .ttd-col-right {
			width: 50%;
			text-align: center;
		}

		#ttd-footer .ttd-direktur-nama {
			font-weight: bold;
			text-decoration: underline;
			color: #1a1a1a;
			font-size: 10pt;
		}

		#ttd-footer .ttd-sign-space {
			height: 32px;
			padding: 0;
			line-height: 32px;
			font-size: 0;
		}

		.print-toolbar {
			display: none;
		}

		@media screen {
			body {
				padding: 16px;
				background: #f9fafb;
			}

			#neraca-wrap {
				background: #fff;
				padding: 16px;
				border: 1px solid #e5e7eb;
				border-radius: 4px;
			}

			.print-toolbar {
				display: flex;
				gap: 8px;
				justify-content: flex-end;
				margin-bottom: 12px;
			}

			.print-toolbar button,
			.print-toolbar a {
				background: #374151;
				color: #fff;
				border: none;
				border-radius: 4px;
				padding: 7px 14px;
				font-size: 13px;
				font-weight: 500;
				cursor: pointer;
				text-decoration: none;
			}

			.print-toolbar button:hover,
			.print-toolbar a:hover {
				background: #1f2937;
			}
		}

		@media print {
			body {
				background: #fff;
				padding: 0;
			}

			.print-toolbar {
				display: none !important;
			}

			#neraca-wrap {
				border: none;
				padding: 0;
			}
		}
	</style>
</head>

<?php
$this->load->helper('tbl_neraca_data_cetak');

$bulan_neraca = isset($bulan_neraca) ? (int) $bulan_neraca : 0;
$tahun_neraca = isset($tahun_neraca) ? (int) $tahun_neraca : 0;

if ($bulan_neraca > 0) {
	$hari_akhir = (int) date('t', mktime(0, 0, 0, $bulan_neraca, 1, $tahun_neraca));
	$teks_periode = 'Per Tanggal ' . $hari_akhir . ' ' . tbl_neraca_cetak_bulan_teks($bulan_neraca) . ' ' . $tahun_neraca;
} else {
	$teks_periode = 'Per Tanggal 31 Desember ' . $tahun_neraca;
}

$tableRows = tbl_neraca_data_compute_cetak_excel_rows($data_tbl_neraca_data);

$boldLabels = array(
	'AKTIVA',
	'PASIVA',
	'Aktiva Lancar',
	'Aktiva Tetap',
	'Aktiva Lain-Lain',
	'Utang Jangka Panjang',
	'Modal dan Laba ditahan',
	'Total Aktiva Tetap Bersih',
	'Total aktiva tetap bersih',
	'TOTAL AKTIVA',
	'TOTAL PASIVA',
);

$highlightAktivaLabels = array(
	'Total Aktiva Lancar',
	'Total Aktiva Tetap Bersih',
	'Total aktiva tetap bersih',
	'TOTAL AKTIVA',
);

$highlightPasivaLabels = array(
	'Total Utang',
	'TOTAL PASIVA',
);

/* Warna lembut hanya untuk baris data detail (bukan section/total) */
$aktivaDataBg = array('#f8fafc', '#ffffff');
$pasivaDataBg = array('#fafafa', '#ffffff');

$format_amount = function ($value) {
	if ($value === null || $value === '') {
		return '';
	}
	return tbl_neraca_cetak_format_amount($value);
};

$isLabelBold = function ($label) use ($boldLabels) {
	return in_array($label, $boldLabels, true);
};

$isHighlightAktiva = function ($label) use ($highlightAktivaLabels) {
	return in_array($label, $highlightAktivaLabels, true);
};

$isHighlightPasiva = function ($label) use ($highlightPasivaLabels) {
	return in_array($label, $highlightPasivaLabels, true);
};

$cellStyle = function ($bg, $extra = '') {
	$s = 'background-color:' . $bg . ';';
	if ($extra !== '') {
		$s .= $extra;
	}
	return $s;
};

$isFirstDataRow = true;
$rowCount = count($tableRows);
$rowIndex = 0;
$aktivaDataIdx = 0;
$pasivaDataIdx = 0;
?>

<body>

<div class="print-toolbar">
	<button type="button" onclick="window.print();">Cetak / Print</button>
	<a href="?format=pdf" target="_blank">Download PDF</a>
	<a href="?format=excel">Download Excel</a>
</div>

<div id="neraca-wrap">

	<table id="neraca-header" width="100%">
		<tr>
			<th>
				<div class="company-name">PERUMDA ANEKA DHARMA KABUPATEN BANTUL</div>
				<div class="report-title">Neraca</div>
				<div class="report-period"><?php echo htmlspecialchars($teks_periode); ?></div>
			</th>
		</tr>
	</table>

	<div id="neraca-header-spacer">&nbsp;</div>

	<table id="neraca" width="100%">
		<colgroup>
			<col class="col-label">
			<col class="col-rp">
			<col class="col-nominal">
			<col class="col-gap">
			<col class="col-label">
			<col class="col-rp">
			<col class="col-nominal">
		</colgroup>

		<?php foreach ($tableRows as $item) :
			$rowIndex++;
			$type = isset($item['type']) ? $item['type'] : 'data';

			if ($type === 'blank') : ?>
				<tr class="blank-row">
					<th class="col-label">&nbsp;</th>
					<th class="col-rp">&nbsp;</th>
					<th class="col-nominal">&nbsp;</th>
					<th class="col-gap">&nbsp;</th>
					<th class="col-label">&nbsp;</th>
					<th class="col-rp">&nbsp;</th>
					<th class="col-nominal">&nbsp;</th>
				</tr>
			<?php continue;
			endif;

			$aktivaLabel = isset($item['aktiva_label']) ? $item['aktiva_label'] : '';
			$pasivaLabel = isset($item['pasiva_label']) ? $item['pasiva_label'] : '';
			$aktivaAmount = array_key_exists('aktiva_amount', $item) ? $item['aktiva_amount'] : null;
			$pasivaAmount = array_key_exists('pasiva_amount', $item) ? $item['pasiva_amount'] : null;
			$bold = !empty($item['bold']);
			$indentMid = !empty($item['indent_mid']);
			$highlightAktiva = $isHighlightAktiva($aktivaLabel);
			$highlightPasiva = $isHighlightPasiva($pasivaLabel);
			$isGrandTotal = ($aktivaLabel === 'TOTAL AKTIVA' || $pasivaLabel === 'TOTAL PASIVA');
			$isDataDetail = ($type === 'data' && !$bold && !$highlightAktiva && !$highlightPasiva && !$isGrandTotal);

			if ($isFirstDataRow) : ?>
				<tr class="row-double-top">
					<th colspan="7">&nbsp;</th>
				</tr>
			<?php
				$isFirstDataRow = false;
			endif;

			$rowClass = ($type === 'section' || $type === 'subsection') ? 'section-row' : 'data-row';
			if ($bold) {
				$rowClass .= ' bold';
			}
			if ($rowIndex === $rowCount) {
				$rowClass .= ' row-table-last';
			}
			if ($isGrandTotal) {
				$rowClass .= ' row-grand-total';
			} elseif ($highlightAktiva || $highlightPasiva) {
				$rowClass .= ' row-total-highlight';
			}
			if ($type === 'section') {
				$rowClass .= ' row-section-main';
			} elseif ($type === 'subsection') {
				$rowClass .= ' row-section-sub';
			}
			if ($isDataDetail) {
				$rowClass .= ' row-data-item';
			}

			/* Background: putih/netral untuk section & total; lembut hanya baris data */
			$aktivaBg = '#ffffff';
			$pasivaBg = '#ffffff';
			$aktivaExtra = '';
			$pasivaExtra = '';

			if ($isDataDetail) {
				if ($aktivaLabel !== '' || ($aktivaAmount !== null && $aktivaAmount !== '')) {
					$aktivaBg = $aktivaDataBg[$aktivaDataIdx % 2];
					$aktivaDataIdx++;
				}
				if ($pasivaLabel !== '' || ($pasivaAmount !== null && $pasivaAmount !== '')) {
					$pasivaBg = $pasivaDataBg[$pasivaDataIdx % 2];
					$pasivaDataIdx++;
				}
			}

			$aktivaLabelClass = 'col-label';
			if ($indentMid) {
				$aktivaLabelClass .= ' indent-mid';
			} elseif ($type === 'data' && $aktivaLabel !== '' && !$isLabelBold($aktivaLabel) && !$bold) {
				$aktivaLabelClass .= ' indent';
			}
			if ($isLabelBold($aktivaLabel) || $type === 'section' || $type === 'subsection') {
				if ($aktivaLabel !== '') {
					$aktivaLabelClass .= ' label-bold';
				}
			}

			$pasivaLabelClass = 'col-label';
			if ($type === 'data' && $pasivaLabel !== '' && !$isLabelBold($pasivaLabel) && !$bold) {
				$pasivaLabelClass .= ' indent';
			}
			if ($isLabelBold($pasivaLabel) || ($type === 'section' && $pasivaLabel !== '')) {
				$pasivaLabelClass .= ' label-bold';
			}

			$showAktivaRp = ($aktivaAmount !== null && $aktivaAmount !== '');
			$showPasivaRp = ($pasivaAmount !== null && $pasivaAmount !== '');

			$renderAktivaRp = $showAktivaRp ? 'Rp.' : '';
			$renderAktivaNominal = $showAktivaRp ? $format_amount($aktivaAmount) : '';
			$renderPasivaRp = $showPasivaRp ? 'Rp.' : '';
			$renderPasivaNominal = $showPasivaRp ? $format_amount($pasivaAmount) : '';

			if ($highlightAktiva && $showAktivaRp) {
				$renderAktivaRp = '<span class="highlight-box">Rp.</span>';
				$renderAktivaNominal = '<span class="highlight-box">' . $format_amount($aktivaAmount) . '</span>';
			}
			if ($highlightPasiva && $showPasivaRp) {
				$renderPasivaRp = '<span class="highlight-box">Rp.</span>';
				$renderPasivaNominal = '<span class="highlight-box">' . $format_amount($pasivaAmount) . '</span>';
			}
		?>
			<tr class="<?php echo $rowClass; ?>">
				<th class="<?php echo $aktivaLabelClass; ?>" style="<?php echo $cellStyle($aktivaBg, $aktivaExtra); ?>"><?php echo htmlspecialchars($aktivaLabel); ?></th>
				<th class="col-rp col-rp-aktiva" style="<?php echo $cellStyle($aktivaBg, $aktivaExtra); ?>"><?php echo $renderAktivaRp; ?></th>
				<th class="col-nominal col-nominal-aktiva" style="<?php echo $cellStyle($aktivaBg, $aktivaExtra); ?>"><?php echo $renderAktivaNominal; ?></th>
				<th class="col-gap">&nbsp;</th>
				<th class="<?php echo $pasivaLabelClass; ?>" style="<?php echo $cellStyle($pasivaBg, $pasivaExtra); ?>"><?php echo htmlspecialchars($pasivaLabel); ?></th>
				<th class="col-rp col-rp-pasiva" style="<?php echo $cellStyle($pasivaBg, $pasivaExtra); ?>"><?php echo $renderPasivaRp; ?></th>
				<th class="col-nominal col-nominal-pasiva" style="<?php echo $cellStyle($pasivaBg, $pasivaExtra); ?>"><?php echo $renderPasivaNominal; ?></th>
			</tr>
		<?php endforeach; ?>
	</table>

	<?php
	$ttd_tanggal = 'Bantul, ';
	if ($bulan_neraca > 0) {
		$ttd_tanggal .= tbl_neraca_cetak_bulan_teks($bulan_neraca) . ' ' . $tahun_neraca;
	} else {
		$ttd_tanggal .= 'Tahun ' . $tahun_neraca;
	}
	?>
	<table id="ttd-footer" width="100%">
		<colgroup>
			<col style="width:32%">
			<col style="width:18%">
			<col style="width:50%">
		</colgroup>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right"><?php echo htmlspecialchars($ttd_tanggal); ?></th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Perusahaan Umum Daerah Aneka Dharma</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Kabupaten Bantul</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Direktur</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right ttd-sign-space">&nbsp;</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right ttd-direktur-nama">Yuli Budi Sasangka, ST</th>
		</tr>
	</table>

</div>

</body>
</html>
