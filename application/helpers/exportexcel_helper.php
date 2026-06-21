<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Export Excel — menghasilkan file .xlsx (OpenXML) asli agar dikenali Excel tanpa peringatan format.
 */

function excel_prepare_download($filename = 'export.xlsx')
{
	while (ob_get_level() > 0) {
		ob_end_clean();
	}

	$filename = basename(preg_replace('/[^\w.\-]+/u', '_', $filename));
	if ($filename === '' || $filename === '.' || $filename === '..') {
		$filename = 'export.xlsx';
	}
	if (stripos($filename, '.') === false) {
		$filename .= '.xlsx';
	}
	// .xls lama otomatis diganti .xlsx agar cocok dengan isi file OpenXML
	if (preg_match('/\.xls$/i', $filename) && !preg_match('/\.xlsx$/i', $filename)) {
		$filename = preg_replace('/\.xls$/i', '.xlsx', $filename);
	}

	$GLOBALS['_excel_download_filename'] = $filename;

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Cache-Control: max-age=0, must-revalidate');
	header('Pragma: public');
	header('Expires: 0');
}

function excel_xml_escape($value)
{
	return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function excel_column_letter($index)
{
	$index = (int) $index;
	$letters = '';
	++$index;
	while ($index > 0) {
		--$index;
		$letters = chr(65 + ($index % 26)) . $letters;
		$index = (int) ($index / 26);
	}
	return $letters;
}

function xlsBOF()
{
	$GLOBALS['_excel_sheet_rows'] = array();
	$GLOBALS['_excel_merge_cells'] = array();
	$GLOBALS['_excel_col_widths'] = array();
	$GLOBALS['_excel_multi_mode'] = false;
}

function xlsSetColumnWidths($widths)
{
	$GLOBALS['_excel_col_widths'] = is_array($widths) ? $widths : array();
}

function excel_build_cols_xml($widths)
{
	if (!is_array($widths) || count($widths) === 0) {
		return '';
	}

	$xml = '<cols>';
	foreach ($widths as $i => $width) {
		$colNum = (int) $i + 1;
		$w = max(1, (float) $width);
		$xml .= '<col min="' . $colNum . '" max="' . $colNum . '" width="' . $w . '" customWidth="1"/>';
	}
	$xml .= '</cols>';

	return $xml;
}

function xlsMultiBOF()
{
	$GLOBALS['_excel_multi_sheets'] = array();
	$GLOBALS['_excel_multi_mode'] = true;
	$GLOBALS['_excel_active_sheet'] = -1;
}

function excel_sheet_safe_name($name)
{
	$name = preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', ' ', (string) $name);
	$name = trim($name);
	if (function_exists('mb_substr')) {
		$name = mb_substr($name, 0, 31, 'UTF-8');
	} else {
		$name = substr($name, 0, 31);
	}
	return ($name !== '') ? $name : 'Sheet';
}

function xlsAddSheet($sheetName)
{
	if (empty($GLOBALS['_excel_multi_mode'])) {
		xlsMultiBOF();
	}

	$idx = count($GLOBALS['_excel_multi_sheets']);
	$GLOBALS['_excel_multi_sheets'][$idx] = array(
		'name' => excel_sheet_safe_name($sheetName),
		'rows' => array(),
	);
	$GLOBALS['_excel_active_sheet'] = $idx;
}

function xls_write_cell($Row, $Col, $cell)
{
	if (!empty($GLOBALS['_excel_multi_mode'])) {
		$idx = isset($GLOBALS['_excel_active_sheet']) ? (int) $GLOBALS['_excel_active_sheet'] : 0;
		if (!isset($GLOBALS['_excel_multi_sheets'][$idx])) {
			xlsAddSheet('Sheet1');
			$idx = (int) $GLOBALS['_excel_active_sheet'];
		}
		$GLOBALS['_excel_multi_sheets'][$idx]['rows'][(int) $Row][(int) $Col] = $cell;
		return;
	}

	$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = $cell;
}

function xlsWriteNumber($Row, $Col, $Value, $align = 'right')
{
	$type = 'Number';
	$out = $Value;
	if ($out === null || $out === '') {
		$type = 'String';
		$out = '';
	} elseif (!is_numeric($out)) {
		$type = 'String';
	} else {
		$out = (float) $out;
	}

	$cell = array(
		'type' => $type,
		'value' => $out,
	);
	if ($align === 'right' && $type === 'Number') {
		$cell['style'] = 1;
	}
	xls_write_cell($Row, $Col, $cell);
}

function xlsWriteLabel($Row, $Col, $Value, $align = null)
{
	$cell = array(
		'type' => 'String',
		'value' => (string) $Value,
	);
	if ($align === 'right') {
		$cell['style'] = 1;
	}
	xls_write_cell($Row, $Col, $cell);
}

/** Tulis sel string dengan style index (border, group title, dll.) */
function xlsWriteCellStyle($Row, $Col, $Value, $styleIndex)
{
	xls_write_cell($Row, $Col, array(
		'type' => 'String',
		'value' => (string) $Value,
		'style' => (int) $styleIndex,
	));
}

/** Merge sel (indeks baris/kolom 0-based, sama seperti xlsWriteLabel) */
function xlsAddMerge($rowStart, $colStart, $rowEnd, $colEnd)
{
	if (!isset($GLOBALS['_excel_merge_cells']) || !is_array($GLOBALS['_excel_merge_cells'])) {
		$GLOBALS['_excel_merge_cells'] = array();
	}
	$GLOBALS['_excel_merge_cells'][] = array(
		'r1' => (int) $rowStart,
		'c1' => (int) $colStart,
		'r2' => (int) $rowEnd,
		'c2' => (int) $colEnd,
	);
}

/** Pastikan sel ada dengan style tertentu (untuk border kotak kelompok) */
function xlsEnsureCellStyle($Row, $Col, $styleIndex, $value = '')
{
	if (!empty($GLOBALS['_excel_multi_mode'])) {
		$idx = isset($GLOBALS['_excel_active_sheet']) ? (int) $GLOBALS['_excel_active_sheet'] : 0;
		if (!isset($GLOBALS['_excel_multi_sheets'][$idx]['rows'][(int) $Row][(int) $Col])) {
			$GLOBALS['_excel_multi_sheets'][$idx]['rows'][(int) $Row][(int) $Col] = array(
				'type' => 'String',
				'value' => (string) $value,
				'style' => (int) $styleIndex,
			);
		}
		return;
	}

	if (!isset($GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col])) {
		$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = array(
			'type' => 'String',
			'value' => (string) $value,
			'style' => (int) $styleIndex,
		);
	}
}

/** Label bold font 14pt (style index 2) */
function xlsWriteLabelBold14($Row, $Col, $Value)
{
	xls_write_cell($Row, $Col, array(
		'type' => 'String',
		'value' => (string) $Value,
		'style' => 2,
	));
}

/**
 * Nominal Indonesia: 1.234.567.890 (tanpa Rp dan tanpa desimal ,00), rata kanan
 */
function xlsWriteRupiah($Row, $Col, $Value)
{
	if ($Value === null || $Value === '' || !is_numeric($Value)) {
		xlsWriteLabel($Row, $Col, $Value === null || $Value === '' ? '' : $Value, 'right');
		return;
	}

	xls_write_cell($Row, $Col, array(
		'type' => 'String',
		'value' => number_format((float) $Value, 0, ',', '.'),
		'style' => 1,
	));
}

function excel_build_sheet_xml($rows, $mergeCells = null)
{
	if ($mergeCells === null) {
		$mergeCells = isset($GLOBALS['_excel_merge_cells']) ? $GLOBALS['_excel_merge_cells'] : array();
	}

	ksort($rows);
	$sheetData = '';

	foreach ($rows as $rowIdx => $rowCells) {
		$excelRow = (int) $rowIdx + 1;
		ksort($rowCells);
		$sheetData .= '<row r="' . $excelRow . '">';
		foreach ($rowCells as $colIdx => $cell) {
			$ref = excel_column_letter((int) $colIdx) . $excelRow;
			$type = isset($cell['type']) ? $cell['type'] : 'String';
			$value = isset($cell['value']) ? $cell['value'] : '';
			$styleAttr = isset($cell['style']) ? ' s="' . (int) $cell['style'] . '"' : '';

			if ($type === 'Rupiah' && is_numeric($value)) {
				$sheetData .= '<c r="' . $ref . '"' . $styleAttr . '><v>' . excel_xml_escape($value) . '</v></c>';
			} elseif ($type === 'Number' && is_numeric($value)) {
				$sheetData .= '<c r="' . $ref . '"' . $styleAttr . '><v>' . excel_xml_escape($value) . '</v></c>';
			} else {
				$sheetData .= '<c r="' . $ref . '"' . $styleAttr . ' t="inlineStr"><is><t>' . excel_xml_escape($value) . '</t></is></c>';
			}
		}
		$sheetData .= '</row>';
	}

	$mergeXml = '';
	if (!empty($mergeCells) && is_array($mergeCells)) {
		$mergeXml = '<mergeCells count="' . count($mergeCells) . '">';
		foreach ($mergeCells as $m) {
			$r1 = (int) $m['r1'] + 1;
			$c1 = (int) $m['c1'];
			$r2 = (int) $m['r2'] + 1;
			$c2 = (int) $m['c2'];
			$ref = excel_column_letter($c1) . $r1 . ':' . excel_column_letter($c2) . $r2;
			$mergeXml .= '<mergeCell ref="' . $ref . '"/>';
		}
		$mergeXml .= '</mergeCells>';
	}

	$colsXml = '';
	if (!empty($GLOBALS['_excel_col_widths']) && is_array($GLOBALS['_excel_col_widths'])) {
		$colsXml = excel_build_cols_xml($GLOBALS['_excel_col_widths']);
	}

	return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
		. $colsXml
		. '<sheetData>' . $sheetData . '</sheetData>'
		. $mergeXml
		. '</worksheet>';
}

function excel_output_xlsx($rows)
{
	if (!class_exists('ZipArchive')) {
		excel_output_csv_fallback($rows);
		return;
	}

	$sheetXml = excel_build_sheet_xml($rows);

	$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
		. '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
		. '<Default Extension="xml" ContentType="application/xml"/>'
		. '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
		. '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
		. '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
		. '</Types>';

	$relsRoot = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
		. '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
		. '</Relationships>';

	$workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
		. 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
		. '<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets>'
		. '</workbook>';

	$workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
		. '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
		. '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
		. '</Relationships>';

	$styles = excel_get_styles_xml();

	$tmpFile = tempnam(sys_get_temp_dir(), 'xlsx');
	if ($tmpFile === false) {
		excel_output_csv_fallback($rows);
		return;
	}

	$zip = new ZipArchive();
	if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
		@unlink($tmpFile);
		excel_output_csv_fallback($rows);
		return;
	}

	$zip->addFromString('[Content_Types].xml', $contentTypes);
	$zip->addFromString('_rels/.rels', $relsRoot);
	$zip->addFromString('xl/workbook.xml', $workbook);
	$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
	$zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
	$zip->addFromString('xl/styles.xml', $styles);
	$zip->close();

	$size = filesize($tmpFile);
	if ($size !== false) {
		header('Content-Length: ' . $size);
	}
	readfile($tmpFile);
	@unlink($tmpFile);
}

function excel_output_csv_fallback($rows)
{
	$filename = isset($GLOBALS['_excel_download_filename'])
		? preg_replace('/\.xlsx$/i', '.csv', $GLOBALS['_excel_download_filename'])
		: 'export.csv';

	header('Content-Type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	echo "\xEF\xBB\xBF";

	ksort($rows);
	foreach ($rows as $rowCells) {
		ksort($rowCells);
		$line = array();
		foreach ($rowCells as $cell) {
			$line[] = isset($cell['value']) ? (string) $cell['value'] : '';
		}
		echo excel_csv_line($line);
	}
}

function excel_csv_line($fields)
{
	$out = array();
	foreach ($fields as $field) {
		$field = str_replace('"', '""', (string) $field);
		$out[] = '"' . $field . '"';
	}
	return implode(';', $out) . "\r\n";
}

function excel_get_styles_xml()
{
	return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
		. '<fonts count="4">'
		. '<font><sz val="11"/><name val="Calibri"/></font>'
		. '<font><b/><sz val="14"/><name val="Calibri"/></font>'
		. '<font><b/><sz val="11"/><name val="Calibri"/></font>'
		. '<font><i/><sz val="11"/><name val="Calibri"/></font>'
		. '</fonts>'
		. '<fills count="5">'
		. '<fill><patternFill patternType="none"/></fill>'
		. '<fill><patternFill patternType="gray125"/></fill>'
		. '<fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/><bgColor indexed="64"/></patternFill></fill>'
		. '<fill><patternFill patternType="solid"><fgColor rgb="FFF5E6D3"/><bgColor indexed="64"/></patternFill></fill>'
		. '<fill><patternFill patternType="solid"><fgColor rgb="FFC6EFCE"/><bgColor indexed="64"/></patternFill></fill>'
		. '</fills>'
		. '<borders count="2">'
		. '<border><left/><right/><top/><bottom/></border>'
		. '<border>'
		. '<left style="thin"><color auto="1"/></left>'
		. '<right style="thin"><color auto="1"/></right>'
		. '<top style="thin"><color auto="1"/></top>'
		. '<bottom style="thin"><color auto="1"/></bottom>'
		. '</border>'
		. '</borders>'
		. '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellStyleXfs>'
		. '<cellXfs count="17">'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf>'
		. '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"/>'
		. '<xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1"/>'
		. '<xf numFmtId="0" fontId="2" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="2" fillId="4" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
		. '</cellXfs>'
		. '</styleSheet>';
}

function excel_output_xlsx_multi($sheets)
{
	if (!is_array($sheets) || count($sheets) === 0) {
		excel_output_xlsx(array());
		return;
	}

	if (!class_exists('ZipArchive')) {
		excel_output_csv_fallback(isset($sheets[0]['rows']) ? $sheets[0]['rows'] : array());
		return;
	}

	$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
		. '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
		. '<Default Extension="xml" ContentType="application/xml"/>'
		. '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
		. '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';

	$workbookSheets = '';
	$workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';

	$usedNames = array();
	foreach ($sheets as $i => $sheet) {
		$num = $i + 1;
		$name = isset($sheet['name']) ? excel_sheet_safe_name($sheet['name']) : ('Sheet' . $num);
		$base = $name;
		$suffix = 2;
		while (isset($usedNames[$name])) {
			$tail = ' (' . $suffix . ')';
			$maxLen = 31 - strlen($tail);
			$name = excel_sheet_safe_name(substr($base, 0, max(1, $maxLen)) . $tail);
			$suffix++;
		}
		$usedNames[$name] = true;

		$contentTypes .= '<Override PartName="/xl/worksheets/sheet' . $num . '.xml" '
			. 'ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
		$workbookSheets .= '<sheet name="' . excel_xml_escape($name) . '" sheetId="' . $num . '" r:id="rId' . $num . '"/>';
		$workbookRels .= '<Relationship Id="rId' . $num . '" '
			. 'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" '
			. 'Target="worksheets/sheet' . $num . '.xml"/>';
	}

	$contentTypes .= '</Types>';
	$stylesRid = count($sheets) + 1;
	$workbookRels .= '<Relationship Id="rId' . $stylesRid . '" '
		. 'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
		. '</Relationships>';

	$workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
		. 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
		. '<sheets>' . $workbookSheets . '</sheets>'
		. '</workbook>';

	$relsRoot = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
		. '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
		. '</Relationships>';

	$tmpFile = tempnam(sys_get_temp_dir(), 'xlsx');
	if ($tmpFile === false) {
		excel_output_csv_fallback(isset($sheets[0]['rows']) ? $sheets[0]['rows'] : array());
		return;
	}

	$zip = new ZipArchive();
	if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
		@unlink($tmpFile);
		excel_output_csv_fallback(isset($sheets[0]['rows']) ? $sheets[0]['rows'] : array());
		return;
	}

	$zip->addFromString('[Content_Types].xml', $contentTypes);
	$zip->addFromString('_rels/.rels', $relsRoot);
	$zip->addFromString('xl/workbook.xml', $workbook);
	$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
	foreach ($sheets as $i => $sheet) {
		$num = $i + 1;
		$rows = isset($sheet['rows']) ? $sheet['rows'] : array();
		$zip->addFromString('xl/worksheets/sheet' . $num . '.xml', excel_build_sheet_xml($rows));
	}
	$zip->addFromString('xl/styles.xml', excel_get_styles_xml());
	$zip->close();

	$size = filesize($tmpFile);
	if ($size !== false) {
		header('Content-Length: ' . $size);
	}
	readfile($tmpFile);
	@unlink($tmpFile);
}

function xlsMultiEOF()
{
	$sheets = isset($GLOBALS['_excel_multi_sheets']) ? $GLOBALS['_excel_multi_sheets'] : array();
	excel_output_xlsx_multi($sheets);
	unset(
		$GLOBALS['_excel_multi_sheets'],
		$GLOBALS['_excel_multi_mode'],
		$GLOBALS['_excel_active_sheet'],
		$GLOBALS['_excel_sheet_rows'],
		$GLOBALS['_excel_download_filename']
	);
}

function xlsEOF()
{
	$rows = isset($GLOBALS['_excel_sheet_rows']) ? $GLOBALS['_excel_sheet_rows'] : array();
	excel_output_xlsx($rows);
	unset(
		$GLOBALS['_excel_sheet_rows'],
		$GLOBALS['_excel_merge_cells'],
		$GLOBALS['_excel_col_widths'],
		$GLOBALS['_excel_download_filename']
	);
}
