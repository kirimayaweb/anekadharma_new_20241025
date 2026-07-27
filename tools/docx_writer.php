<?php
/**
 * SimpleDocxWriter — generator DOCX dengan dukungan diagram shape
 */

require_once __DIR__ . '/diagram_engine.php';

class SimpleDocxWriter
{
    private $paragraphs = [];

    public function title($text, $level = 1)
    {
        $sizes = [1 => 32, 2 => 28, 3 => 24, 4 => 22];
        $size = isset($sizes[$level]) ? $sizes[$level] : 20;
        $this->paragraphs[] = $this->p($text, ['bold' => true, 'size' => $size, 'spaceAfter' => $level <= 2 ? 240 : 120]);
    }

    public function paragraph($text, $opts = [])
    {
        $this->paragraphs[] = $this->p($text, array_merge(['size' => 22], $opts));
    }

    public function bullet($text)
    {
        $this->paragraphs[] = $this->p('• ' . $text, ['size' => 22, 'indent' => 360]);
    }

    public function codeBlock($text)
    {
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $this->paragraphs[] = $this->p($line === '' ? ' ' : $line, [
                'size' => 18,
                'font' => 'Consolas',
                'shading' => 'F2F2F2',
                'spaceAfter' => 0,
            ]);
        }
        $this->paragraphs[] = $this->p('', ['size' => 12, 'spaceAfter' => 120]);
    }

    public function table($headers, $rows)
    {
        $this->paragraphs[] = $this->buildTable($headers, $rows);
    }

    public function pageBreak()
    {
        $this->paragraphs[] = '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
    }

    public function diagram(callable $builder)
    {
        $engine = new DiagramEngine();
        $builder($engine);
        $xml = $engine->toParagraph();
        if ($xml !== '') {
            $this->paragraphs[] = $xml;
        }
    }

    private function esc($text)
    {
        return htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function run($text, $opts = [])
    {
        $rPr = '';
        if (!empty($opts['bold'])) {
            $rPr .= '<w:b/>';
        }
        if (!empty($opts['italic'])) {
            $rPr .= '<w:i/>';
        }
        if (!empty($opts['size'])) {
            $rPr .= '<w:sz w:val="' . (int)$opts['size'] . '"/><w:szCs w:val="' . (int)$opts['size'] . '"/>';
        }
        if (!empty($opts['font'])) {
            $rPr .= '<w:rFonts w:ascii="' . $this->esc($opts['font']) . '" w:hAnsi="' . $this->esc($opts['font']) . '"/>';
        }
        if (!empty($opts['color'])) {
            $rPr .= '<w:color w:val="' . $this->esc($opts['color']) . '"/>';
        }
        if (!empty($opts['shading'])) {
            $rPr .= '<w:shd w:val="clear" w:color="auto" w:fill="' . $this->esc($opts['shading']) . '"/>';
        }
        $rPrXml = $rPr !== '' ? '<w:rPr>' . $rPr . '</w:rPr>' : '';
        return '<w:r>' . $rPrXml . '<w:t xml:space="preserve">' . $this->esc($text) . '</w:t></w:r>';
    }

    private function p($text, $opts = [])
    {
        $pPr = '';
        if (!empty($opts['indent'])) {
            $pPr .= '<w:ind w:left="' . (int)$opts['indent'] . '"/>';
        }
        if (isset($opts['spaceAfter'])) {
            $pPr .= '<w:spacing w:after="' . (int)$opts['spaceAfter'] . '"/>';
        }
        $pPrXml = $pPr !== '' ? '<w:pPr>' . $pPr . '</w:pPr>' : '';
        return '<w:p>' . $pPrXml . $this->run($text, $opts) . '</w:p>';
    }

    private function buildTable($headers, $rows)
    {
        $cols = count($headers);
        $width = (int)(9000 / max(1, $cols));
        $tbl = '<w:tbl><w:tblPr><w:tblW w:w="9000" w:type="dxa"/><w:tblBorders>'
            . '<w:top w:val="single" w:sz="4" w:space="0" w:color="999999"/>'
            . '<w:left w:val="single" w:sz="4" w:space="0" w:color="999999"/>'
            . '<w:bottom w:val="single" w:sz="4" w:space="0" w:color="999999"/>'
            . '<w:right w:val="single" w:sz="4" w:space="0" w:color="999999"/>'
            . '<w:insideH w:val="single" w:sz="4" w:space="0" w:color="CCCCCC"/>'
            . '<w:insideV w:val="single" w:sz="4" w:space="0" w:color="CCCCCC"/>'
            . '</w:tblBorders></w:tblPr><w:tblGrid>';
        for ($i = 0; $i < $cols; $i++) {
            $tbl .= '<w:gridCol w:w="' . $width . '"/>';
        }
        $tbl .= '</w:tblGrid><w:tr>';
        foreach ($headers as $h) {
            $tbl .= '<w:tc><w:tcPr><w:shd w:val="clear" w:color="auto" w:fill="1F4E79"/></w:tcPr><w:p>'
                . $this->run($h, ['bold' => true, 'size' => 20, 'color' => 'FFFFFF']) . '</w:p></w:tc>';
        }
        $tbl .= '</w:tr>';
        foreach ($rows as $row) {
            $tbl .= '<w:tr>';
            foreach ($row as $cell) {
                $tbl .= '<w:tc><w:p>' . $this->run((string)$cell, ['size' => 20]) . '</w:p></w:tc>';
            }
            $tbl .= '</w:tr>';
        }
        $tbl .= '</w:tbl>';
        return $tbl;
    }

    public function save($filepath)
    {
        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"'
            . ' xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"'
            . ' xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"'
            . ' xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<w:body>' . implode('', $this->paragraphs)
            . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/>'
            . '<w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440"/>'
            . '</w:sectPr></w:body></w:document>';

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
            . '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>'
            . '</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
            . '</Relationships>';

        $docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            . '<w:docDefaults><w:rPrDefault><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/>'
            . '<w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:rPrDefault></w:docDefaults>'
            . '</w:styles>';

        @unlink($filepath);
        $tempPath = $filepath . '.tmp_' . getmypid();
        $zip = new ZipArchive();
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Gagal membuat file DOCX temp: ' . $tempPath);
        }
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('word/document.xml', $documentXml);
        $zip->addFromString('word/_rels/document.xml.rels', $docRels);
        $zip->addFromString('word/styles.xml', $styles);
        $zip->close();

        if (!@rename($tempPath, $filepath)) {
            $fallback = preg_replace('/\.docx$/i', '_copy.docx', $filepath);
            if (@rename($tempPath, $fallback)) {
                echo "PERINGATAN: {$filepath} sedang dibuka. Disimpan sebagai: {$fallback}\n";
                return $fallback;
            }
            throw new RuntimeException('Gagal menulis DOCX. Tutup file Word lalu jalankan ulang.');
        }
        return $filepath;
    }
}
