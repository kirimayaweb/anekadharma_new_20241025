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
}

function xlsWriteNumber($Row, $Col, $Value)
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

	$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = array(
		'type' => $type,
		'value' => $out,
	);
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
	$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = $cell;
}

/** Label bold font 14pt (style index 2) */
function xlsWriteLabelBold14($Row, $Col, $Value)
{
	$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = array(
		'type' => 'String',
		'value' => (string) $Value,
		'style' => 2,
	);
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

	$GLOBALS['_excel_sheet_rows'][(int) $Row][(int) $Col] = array(
		'type' => 'String',
		'value' => number_format((float) $Value, 0, ',', '.'),
		'style' => 1,
	);
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

function xlsEOF()
{
	$rows = isset($GLOBALS['_excel_sheet_rows']) ? $GLOBALS['_excel_sheet_rows'] : array();
	excel_output_xlsx($rows);
	unset($GLOBALS['_excel_sheet_rows'], $GLOBALS['_excel_download_filename']);
}
