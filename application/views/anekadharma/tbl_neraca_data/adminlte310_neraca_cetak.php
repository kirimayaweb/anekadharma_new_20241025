<head>
	<style>
		@page {
			margin: 5mm 8mm;
			size: landscape;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10pt;
			line-height: 1.15;
		}

		#neraca-header {
			border-collapse: collapse;
			width: 100%;
			border: none;
		}

		#neraca-header th {
			border: none;
			text-align: center;
			font-size: 12pt;
			font-weight: bold;
			padding: 1px 0;
			line-height: 1.2;
		}

		#neraca-header-spacer {
			height: 4px;
			border: none;
			font-size: 0;
			line-height: 0;
		}

		#neraca {
			border-collapse: collapse;
			width: 100%;
			table-layout: fixed;
			border-left: 1px solid #000;
			border-right: 1px solid #000;
			border-bottom: 1px solid #000;
		}

		#neraca td,
		#neraca th {
			border: 0;
			padding: 1px 3px;
			font-size: 10pt;
			font-weight: normal;
			vertical-align: top;
			line-height: 1.15;
		}

		#neraca tr.row-double-top th {
			border-top: 3px double #000;
			border-left: 1px solid #000;
			border-right: 1px solid #000;
			padding: 0;
			height: 0;
			line-height: 0;
			font-size: 0;
		}

		#neraca tr.row-table-last th {
			border-bottom: 1px solid #000;
		}

		#neraca tr th:first-child {
			border-left: 1px solid #000;
		}

		#neraca tr th:last-child {
			border-right: 1px solid #000;
		}

		#neraca tr.row-highlight-aktiva th:first-child,
		#neraca tr.row-highlight-pasiva th:first-child {
			border-left: 1px solid #000;
		}

		#neraca tr.row-highlight-aktiva th:last-child,
		#neraca tr.row-highlight-pasiva th:last-child {
			border-right: 1px solid #000;
		}

		#neraca .col-gap {
			width: 1%;
			padding: 0;
			border-top: none !important;
			border-bottom: none !important;
			border-right: none !important;
			border-left: 1px solid #000 !important;
		}

		#neraca .col-label {
			width: 33%;
			text-align: left;
			padding-left: 4px;
		}

		#neraca .col-label.indent {
			padding-left: 12px;
		}

		#neraca .col-label.indent-mid {
			padding-left: 28px;
			font-weight: bold;
		}

		#neraca .col-label.label-bold {
			font-weight: bold;
		}

		#neraca .col-rp {
			width: 4%;
			text-align: left;
			white-space: nowrap;
		}

		#neraca .col-nominal {
			width: 12%;
			text-align: right;
			padding-right: 4px;
			white-space: nowrap;
		}

		#neraca .section-row th,
		#neraca .data-row th {
			font-size: 10pt;
			white-space: nowrap;
			overflow: hidden;
		}

		#neraca .data-row.bold .col-label {
			font-weight: bold;
		}

		#neraca .data-row.bold .col-nominal,
		#neraca .data-row.bold .col-rp {
			font-weight: bold;
		}

		#neraca tr.row-highlight-aktiva th.col-rp-aktiva,
		#neraca tr.row-highlight-aktiva th.col-nominal-aktiva,
		#neraca tr.row-highlight-pasiva th.col-rp-pasiva,
		#neraca tr.row-highlight-pasiva th.col-nominal-pasiva {
			padding: 0;
			border-top: none !important;
			border-bottom: none !important;
			border-left: none !important;
			border-right: none !important;
		}

		#neraca tr.row-highlight-aktiva th.col-gap,
		#neraca tr.row-highlight-pasiva th.col-gap,
		#neraca tr.row-highlight-aktiva th.col-label,
		#neraca tr.row-highlight-pasiva th.col-label {
			border-top: none !important;
			border-bottom: none !important;
		}

		#neraca tr.row-highlight-aktiva th.col-gap,
		#neraca tr.row-highlight-pasiva th.col-gap {
			border-left: 1px solid #000 !important;
		}

		#neraca .highlight-box {
			display: block;
			border-top: 1px solid #000;
			border-bottom: 3px double #000;
			padding: 1px 2px;
			font-weight: bold;
		}

		#neraca .highlight-box-aktiva-rp {
			margin-left: 0;
			margin-right: 0;
			text-align: left;
		}

		#neraca .highlight-box-aktiva-nominal {
			margin-left: 0;
			margin-right: 1mm;
			text-align: right;
		}

		#neraca .highlight-box-pasiva-rp {
			margin-left: 1mm;
			margin-right: 0;
			text-align: left;
		}

		#neraca .highlight-box-pasiva-nominal {
			margin-left: 0;
			margin-right: 1mm;
			text-align: right;
		}

		#neraca .blank-row th {
			height: 3px;
			padding: 0;
			line-height: 0;
			font-size: 0;
		}

		#neraca-wrap {
			page-break-inside: avoid;
		}

		#ttd-footer {
			width: 100%;
			border-collapse: collapse;
			table-layout: fixed;
			margin-top: 4px;
			page-break-inside: avoid;
		}

		#ttd-footer th {
			border: none;
			font-weight: normal;
			font-size: 10pt;
			padding: 1px 0;
			line-height: 1.2;
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
			white-space: nowrap;
		}

		#ttd-footer .ttd-direktur-nama {
			font-weight: bold;
			text-decoration: underline;
		}

		#ttd-footer .ttd-sign-space {
			height: 28px;
			padding: 0;
			line-height: 28px;
			font-size: 0;
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

$isFirstDataRow = true;
$rowCount = count($tableRows);
$rowIndex = 0;
?>

<body>

<div id="neraca-wrap">

	<table id="neraca-header" width="100%">
		<tr>
			<th>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</th>
		</tr>
		<tr>
			<th>NERACA</th>
		</tr>
		<tr>
			<th><?php echo $teks_periode; ?></th>
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
			if ($highlightAktiva) {
				$rowClass .= ' row-highlight-aktiva';
			}
			if ($highlightPasiva) {
				$rowClass .= ' row-highlight-pasiva';
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

			$nominalAktivaClass = 'col-nominal col-nominal-aktiva';

			$showAktivaRp = ($aktivaAmount !== null && $aktivaAmount !== '');
			$showPasivaRp = ($pasivaAmount !== null && $pasivaAmount !== '');

			$renderAktivaRp = '';
			if ($showAktivaRp) {
				$renderAktivaRp = $highlightAktiva
					? '<span class="highlight-box highlight-box-aktiva-rp">Rp.</span>'
					: 'Rp.';
			}

			$renderAktivaNominal = '';
			if ($showAktivaRp) {
				$renderAktivaNominal = $highlightAktiva
					? '<span class="highlight-box highlight-box-aktiva-nominal">' . $format_amount($aktivaAmount) . '</span>'
					: $format_amount($aktivaAmount);
			}

			$renderPasivaRp = '';
			if ($showPasivaRp) {
				$renderPasivaRp = $highlightPasiva
					? '<span class="highlight-box highlight-box-pasiva-rp">Rp.</span>'
					: 'Rp.';
			}

			$renderPasivaNominal = '';
			if ($showPasivaRp) {
				$renderPasivaNominal = $highlightPasiva
					? '<span class="highlight-box highlight-box-pasiva-nominal">' . $format_amount($pasivaAmount) . '</span>'
					: $format_amount($pasivaAmount);
			}
		?>
			<tr class="<?php echo $rowClass; ?>">
				<th class="<?php echo $aktivaLabelClass; ?>"><?php echo htmlspecialchars($aktivaLabel); ?></th>
				<th class="col-rp col-rp-aktiva"><?php echo $renderAktivaRp; ?></th>
				<th class="<?php echo $nominalAktivaClass; ?>"><?php echo $renderAktivaNominal; ?></th>
				<th class="col-gap">&nbsp;</th>
				<th class="<?php echo $pasivaLabelClass; ?>"><?php echo htmlspecialchars($pasivaLabel); ?></th>
				<th class="col-rp col-rp-pasiva"><?php echo $renderPasivaRp; ?></th>
				<th class="col-nominal col-nominal-pasiva"><?php echo $renderPasivaNominal; ?></th>
			</tr>
		<?php endforeach; ?>
	</table>

	<?php
	$ttd_tanggal = 'Bantul, ';
	if ($bulan_neraca > 0) {
		$ttd_tanggal .= 'Bulan ' . tbl_neraca_cetak_bulan_teks($bulan_neraca) . ' Tahun ' . $tahun_neraca;
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
			<th class="ttd-col-right"><?php echo $ttd_tanggal; ?></th>
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
			<th class="ttd-col-right ttd-direktur-nama">Yuli Budi Sasangka,ST</th>
		</tr>
	</table>

</div>

</body>
