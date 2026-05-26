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
	$GLOBALS['_excel_multi_mode'] = false;
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

function excel_build_sheet_xml($rows)
{
	ksort($rows);
	$sheetData = '';
	$rowNum = 1;

	foreach ($rows as $rowCells) {
		ksort($rowCells);
		$sheetData .= '<row r="' . $rowNum . '">';
		$colNum = 0;
		foreach ($rowCells as $cell) {
			$ref = excel_column_letter($colNum) . $rowNum;
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
			++$colNum;
		}
		$sheetData .= '</row>';
		++$rowNum;
	}

	return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
		. '<sheetData>' . $sheetData . '</sheetData>'
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

	$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
		. '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
		. '<fonts count="2">'
		. '<font><sz val="11"/><name val="Calibri"/></font>'
		. '<font><b/><sz val="14"/><name val="Calibri"/></font>'
		. '</fonts>'
		. '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
		. '<borders count="1"><border><left/><right/><top/><bottom/></border></borders>'
		. '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
		. '<cellXfs count="3">'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf>'
		. '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
		. '</cellXfs>'
		. '</styleSheet>';

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
		. '<fonts count="2">'
		. '<font><sz val="11"/><name val="Calibri"/></font>'
		. '<font><b/><sz val="14"/><name val="Calibri"/></font>'
		. '</fonts>'
		. '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
		. '<borders count="1"><border><left/><right/><top/><bottom/></border></borders>'
		. '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
		. '<cellXfs count="3">'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
		. '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf>'
		. '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
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
	unset($GLOBALS['_excel_sheet_rows'], $GLOBALS['_excel_download_filename']);
}
