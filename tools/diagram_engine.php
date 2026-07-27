<?php
/**
 * DiagramEngine — Word DrawingML shapes untuk flowchart / arsitektur diagram
 */

class DiagramEngine
{
    private $shapeId = 2;
    private $drawings = [];
    private $totalHeight = 0;

    const EMU = 914400;

    public static function in($inch)
    {
        return (int) round($inch * self::EMU);
    }

    private function esc($t)
    {
        return htmlspecialchars($t, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function nid()
    {
        return $this->shapeId++;
    }

    private function track($y, $h)
    {
        $b = $y + $h;
        if ($b > $this->totalHeight) {
            $this->totalHeight = $b;
        }
    }

    private function txbx($lines, $textColor = 'FFFFFF', $subColor = 'D6E4F0')
    {
        $x = '';
        foreach ((array) $lines as $i => $line) {
            if ($line === '') {
                continue;
            }
            $bold = $i === 0 ? '<w:b/>' : '';
            $sz = $i === 0 ? 18 : 15;
            $col = $i === 0 ? $textColor : $subColor;
            $x .= '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:before="40" w:after="40"/></w:pPr>'
                . '<w:r><w:rPr>' . $bold . '<w:color w:val="' . $col . '"/>'
                . '<w:sz w:val="' . $sz . '"/><w:szCs w:val="' . $sz . '"/>'
                . '<w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/></w:rPr>'
                . '<w:t xml:space="preserve">' . $this->esc($line) . '</w:t></w:r></w:p>';
        }
        return $x;
    }

    public function roundRect($x, $y, $w, $h, $lines, $fill = '1F4E79', $textColor = 'FFFFFF', $border = null)
    {
        $id = $this->nid();
        $border = $border ?: $fill;
        $this->drawings[] =
            '<wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="' . ($id * 1000)
            . '" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">'
            . '<wp:simplePos x="0" y="0"/>'
            . '<wp:positionH relativeFrom="column"><wp:posOffset>' . (int) $x . '</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>' . (int) $y . '</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="' . (int) $w . '" cy="' . (int) $h . '"/>'
            . '<wp:effectExtent l="0" t="0" r="0" b="0"/><wp:wrapNone/>'
            . '<wp:docPr id="' . $id . '" name="Box' . $id . '"/><wp:cNvGraphicFramePr/>'
            . '<a:graphic><a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<wps:wsp><wps:cNvSpPr/>'
            . '<wps:spPr>'
            . '<a:xfrm><a:off x="0" y="0"/><a:ext cx="' . (int) $w . '" cy="' . (int) $h . '"/></a:xfrm>'
            . '<a:prstGeom prst="roundRect"><a:avLst/></a:prstGeom>'
            . '<a:solidFill><a:srgbClr val="' . $fill . '"/></a:solidFill>'
            . '<a:ln w="12700"><a:solidFill><a:srgbClr val="' . $border . '"/></a:solidFill></a:ln>'
            . '</wps:spPr>'
            . '<wps:txbx><w:txbxContent>' . $this->txbx((array) $lines, $textColor) . '</w:txbxContent></wps:txbx>'
            . '<wps:bodyPr rot="0" vert="horz" wrap="square" anchor="ctr" lIns="54000" tIns="27000" rIns="54000" bIns="27000"/>'
            . '</wps:wsp></a:graphicData></a:graphic></wp:anchor>';
        $this->track($y, $h);
    }

    public function diamond($x, $y, $size, $lines, $fill = 'ED7D31', $textColor = 'FFFFFF')
    {
        $id = $this->nid();
        $this->drawings[] =
            '<wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="' . ($id * 1000)
            . '" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">'
            . '<wp:simplePos x="0" y="0"/>'
            . '<wp:positionH relativeFrom="column"><wp:posOffset>' . (int) $x . '</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>' . (int) $y . '</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="' . (int) $size . '" cy="' . (int) $size . '"/>'
            . '<wp:effectExtent l="0" t="0" r="0" b="0"/><wp:wrapNone/>'
            . '<wp:docPr id="' . $id . '" name="Diamond' . $id . '"/><wp:cNvGraphicFramePr/>'
            . '<a:graphic><a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<wps:wsp><wps:cNvSpPr/>'
            . '<wps:spPr>'
            . '<a:xfrm><a:off x="0" y="0"/><a:ext cx="' . (int) $size . '" cy="' . (int) $size . '"/></a:xfrm>'
            . '<a:prstGeom prst="diamond"><a:avLst/></a:prstGeom>'
            . '<a:solidFill><a:srgbClr val="' . $fill . '"/></a:solidFill>'
            . '<a:ln w="12700"><a:solidFill><a:srgbClr val="C55A11"/></a:solidFill></a:ln>'
            . '</wps:spPr>'
            . '<wps:txbx><w:txbxContent>' . $this->txbx((array) $lines, $textColor, 'FFF2E8') . '</w:txbxContent></wps:txbx>'
            . '<wps:bodyPr rot="0" vert="horz" wrap="square" anchor="ctr" lIns="36000" tIns="18000" rIns="36000" bIns="18000"/>'
            . '</wps:wsp></a:graphicData></a:graphic></wp:anchor>';
        $this->track($y, $size);
    }

    public function arrowDown($cx, $y, $len, $color = '2E75B6')
    {
        $id = $this->nid();
        $w = self::in(0.18);
        $this->drawings[] =
            '<wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="' . ($id * 1000)
            . '" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">'
            . '<wp:simplePos x="0" y="0"/>'
            . '<wp:positionH relativeFrom="column"><wp:posOffset>' . (int) ($cx - $w / 2) . '</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>' . (int) $y . '</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="' . (int) $w . '" cy="' . (int) $len . '"/>'
            . '<wp:effectExtent l="0" t="0" r="0" b="0"/><wp:wrapNone/>'
            . '<wp:docPr id="' . $id . '" name="Arrow' . $id . '"/><wp:cNvGraphicFramePr/>'
            . '<a:graphic><a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<wps:wsp><wps:cNvSpPr/>'
            . '<wps:spPr>'
            . '<a:xfrm><a:off x="0" y="0"/><a:ext cx="' . (int) $w . '" cy="' . (int) $len . '"/></a:xfrm>'
            . '<a:prstGeom prst="downArrow"><a:avLst/></a:prstGeom>'
            . '<a:solidFill><a:srgbClr val="' . $color . '"/></a:solidFill>'
            . '<a:ln w="9525"><a:noFill/></a:ln>'
            . '</wps:spPr>'
            . '<wps:bodyPr rot="0" vert="horz" wrap="none" anchor="ctr"/>'
            . '</wps:wsp></a:graphicData></a:graphic></wp:anchor>';
        $this->track($y, $len);
    }

    public function connector($x1, $y1, $x2, $y2, $color = '2E75B6')
    {
        $id = $this->nid();
        $w = max(abs($x2 - $x1), self::in(0.05));
        $h = max(abs($y2 - $y1), self::in(0.05));
        $x = min($x1, $x2);
        $y = min($y1, $y2);
        $flipH = $x2 < $x1 ? ' flipH="1"' : '';
        $flipV = $y2 < $y1 ? ' flipV="1"' : '';
        $this->drawings[] =
            '<wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="' . ($id * 1000)
            . '" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">'
            . '<wp:simplePos x="0" y="0"/>'
            . '<wp:positionH relativeFrom="column"><wp:posOffset>' . (int) $x . '</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>' . (int) $y . '</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="' . (int) $w . '" cy="' . (int) $h . '"/>'
            . '<wp:effectExtent l="0" t="0" r="0" b="0"/><wp:wrapNone/>'
            . '<wp:docPr id="' . $id . '" name="Line' . $id . '"/><wp:cNvGraphicFramePr/>'
            . '<a:graphic><a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<wps:wsp><wps:cNvSpPr/>'
            . '<wps:spPr>'
            . '<a:xfrm' . $flipH . $flipV . '><a:off x="0" y="0"/><a:ext cx="' . (int) $w . '" cy="' . (int) $h . '"/></a:xfrm>'
            . '<a:prstGeom prst="straightConnector1"><a:avLst/></a:prstGeom>'
            . '<a:ln w="19050"><a:solidFill><a:srgbClr val="' . $color . '"/></a:solidFill>'
            . '<a:tailEnd type="triangle" w="med" len="med"/></a:ln>'
            . '</wps:spPr><wps:bodyPr rot="0" vert="horz" wrap="none" anchor="ctr"/>'
            . '</wps:wsp></a:graphicData></a:graphic></wp:anchor>';
        $this->track($y, $h);
    }

    public function label($x, $y, $text, $color = '595959')
    {
        $id = $this->nid();
        $w = self::in(1.2);
        $h = self::in(0.22);
        $this->drawings[] =
            '<wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="' . ($id * 1000)
            . '" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">'
            . '<wp:simplePos x="0" y="0"/>'
            . '<wp:positionH relativeFrom="column"><wp:posOffset>' . (int) $x . '</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>' . (int) $y . '</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="' . (int) $w . '" cy="' . (int) $h . '"/>'
            . '<wp:effectExtent l="0" t="0" r="0" b="0"/><wp:wrapNone/>'
            . '<wp:docPr id="' . $id . '" name="Lbl' . $id . '"/><wp:cNvGraphicFramePr/>'
            . '<a:graphic><a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">'
            . '<wps:wsp><wps:cNvSpPr/>'
            . '<wps:spPr>'
            . '<a:xfrm><a:off x="0" y="0"/><a:ext cx="' . (int) $w . '" cy="' . (int) $h . '"/></a:xfrm>'
            . '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:noFill/><a:ln><a:noFill/></a:ln>'
            . '</wps:spPr>'
            . '<wps:txbx><w:txbxContent><w:p><w:pPr><w:jc w:val="center"/></w:pPr>'
            . '<w:r><w:rPr><w:i/><w:color w:val="' . $color . '"/><w:sz w:val="16"/><w:szCs w:val="16"/>'
            . '<w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/></w:rPr>'
            . '<w:t>' . $this->esc($text) . '</w:t></w:r></w:p></w:txbxContent></wps:txbx>'
            . '<wps:bodyPr rot="0" vert="horz" wrap="none" anchor="ctr"/>'
            . '</wps:wsp></a:graphicData></a:graphic></wp:anchor>';
        $this->track($y, $h);
    }

    /** Flowchart vertikal berurutan */
    public function verticalFlow($steps, $cx = 1.35, $boxW = 3.8, $boxH = 0.62, $gap = 0.28)
    {
        $x = self::in($cx);
        $w = self::in($boxW);
        $h = self::in($boxH);
        $g = self::in($gap);
        $y = self::in(0.05);
        $colors = ['1F4E79', '2E75B6', '4472C4', '5B9BD5', '548235', '7030A0', 'C00000'];

        foreach ($steps as $i => $step) {
            $title = is_array($step) ? $step[0] : $step;
            $sub = is_array($step) && isset($step[1]) ? $step[1] : '';
            $fill = is_array($step) && isset($step[2]) ? $step[2] : $colors[$i % count($colors)];
            $lines = $sub !== '' ? [$title, $sub] : [$title];
            $this->roundRect($x, $y, $w, $h, $lines, $fill);
            $y += $h;
            if ($i < count($steps) - 1) {
                $this->arrowDown($x + $w / 2, $y, $g);
                $y += $g;
            }
        }
    }

    /** Diagram arsitektur berlapis */
    public function layeredArchitecture()
    {
        $w = self::in(5.5);
        $x = self::in(0.55);
        $y = self::in(0.05);
        $layers = [
            ['CLIENT (Web Browser)', 'AdminLTE 3 + jQuery + DataTables', 'D6E4F0', '1F4E79'],
            ['CODEIGNITER 3.1.5 (PHP MVC)', 'Controller (86) | Model (76) | Helper & Library', '2E75B6', 'FFFFFF'],
            ['MySQL anekadharma_db', 'Session PHP | WhatsApp API (MFA)', '7030A0', 'FFFFFF'],
        ];
        $bh = self::in(0.72);
        $g = self::in(0.32);
        foreach ($layers as $i => $L) {
            $this->roundRect($x, $y, $w, $bh, [$L[0], $L[1]], $L[2], $L[3], '1F4E79');
            if ($i === 0) {
                $this->label($x + $w / 2 - self::in(0.6), $y + $bh, 'HTTP / HTTPS', '2E75B6');
            }
            $y += $bh;
            if ($i < count($layers) - 1) {
                $this->arrowDown($x + $w / 2, $y, $g, '1F4E79');
                $y += $g;
            }
        }
        $y += self::in(0.05);
        $dw = self::in(1.65);
        $dx = $x + self::in(0.1);
        $this->roundRect($dx, $y, $dw, self::in(0.55), ['Database', 'MySQL/MariaDB'], 'E8F4E8', '548235', '548235');
        $this->roundRect($dx + self::in(1.85), $y, $dw, self::in(0.55), ['Session', 'PHP State'], 'FFF8E8', 'BF8F00', 'BF8F00');
        $this->roundRect($dx + self::in(3.6), $y, $dw, self::in(0.55), ['WhatsApp', 'OTP / MFA'], 'FCE8FF', '7030A0', '7030A0');
    }

    /** Diagram modul tree */
    public function moduleTree()
    {
        $cx = self::in(2.8);
        $w = self::in(2.4);
        $h = self::in(0.52);
        $y = self::in(0.05);
        $this->roundRect($cx - $w / 2, $y, $w, $h, ['LOGIN / MFA', 'Anekadharmamasuk'], 'C00000');
        $y += $h + self::in(0.22);
        $this->arrowDown($cx, $y - self::in(0.22), self::in(0.22));
        $this->roundRect($cx - $w / 2, $y, $w, $h, ['DASHBOARD'], '1F4E79');
        $y += $h + self::in(0.22);
        $this->arrowDown($cx, $y - self::in(0.22), self::in(0.22));
        $mods = [
            ['PEMBELIAN', 'SPOP, Bayar, Cetak', '2E75B6'],
            ['PENJUALAN', 'Order, Bayar, Cetak', '4472C4'],
            ['PERSEDIAAN', 'Generate, Recalc', '548235'],
            ['AKUNTANSI', 'Jurnal, Neraca', '7030A0'],
            ['MASTER DATA', 'Barang, Unit, CoA', 'ED7D31'],
        ];
        $mw = self::in(1.05);
        $mh = self::in(0.72);
        $startX = self::in(0.35);
        $gapX = self::in(1.12);
        $rowY = $y;
        foreach ($mods as $i => $m) {
            $mx = $startX + $i * $gapX;
            $this->connector($cx, $y - self::in(0.02), $mx + $mw / 2, $rowY, '5B9BD5');
            $this->roundRect($mx, $rowY, $mw, $mh, [$m[0], $m[1]], $m[2]);
        }
    }

    /** ER diagram inti */
    public function entityDiagram()
    {
        $y = self::in(0.05);
        $ew = self::in(1.55);
        $eh = self::in(0.55);
        $this->roundRect(self::in(0.4), $y, $ew, $eh, ['sys_supplier'], 'D6E4F0', '1F4E79', '2E75B6');
        $this->roundRect(self::in(4.5), $y, $ew, $eh, ['sys_konsumen'], 'D6E4F0', '1F4E79', '2E75B6');
        $y2 = $y + $eh + self::in(0.35);
        $this->connector(self::in(1.17), $y + $eh, self::in(1.6), $y2, '2E75B6');
        $this->connector(self::in(5.27), $y + $eh, self::in(4.35), $y2, '2E75B6');
        $this->roundRect(self::in(0.35), $y2, self::in(1.65), $eh, ['tbl_pembelian', '(SPOP)'], '2E75B6');
        $this->roundRect(self::in(4.25), $y2, self::in(1.65), $eh, ['tbl_penjualan', '(Order)'], '4472C4');
        $y3 = $y2 + $eh + self::in(0.35);
        $this->connector(self::in(1.17), $y2 + $eh, self::in(2.8), $y3, '2E75B6');
        $this->connector(self::in(5.07), $y2 + $eh, self::in(3.2), $y3, '2E75B6');
        $pw = self::in(2.6);
        $this->roundRect(self::in(1.7), $y3, $pw, self::in(0.62), ['persediaan', '(multi-unit stok)'], '548235');
        $this->roundRect(self::in(4.55), $y3 + self::in(0.08), $ew, self::in(0.48), ['sys_nama_barang'], 'FFF2CC', '7F6000', 'BF8F00');
        $this->connector(self::in(4.3), $y3 + self::in(0.3), self::in(4.55), $y3 + self::in(0.3), 'BF8F00');
        $y4 = $y3 + self::in(0.72) + self::in(0.3);
        $this->arrowDown(self::in(3.0), $y3 + self::in(0.62), self::in(0.28));
        $jw = self::in(1.45);
        $this->roundRect(self::in(0.35), $y4, $jw, $eh, ['jurnal_*'], '7030A0');
        $this->roundRect(self::in(2.05), $y4, $jw, $eh, ['buku_besar'], '7030A0');
        $this->roundRect(self::in(3.75), $y4, $jw, $eh, ['laba rugi', 'neraca'], '7030A0');
        $y5 = $y4 + $eh + self::in(0.3);
        $this->arrowDown(self::in(3.0), $y4 + $eh, self::in(0.25));
        $this->roundRect(self::in(1.85), $y5, self::in(2.3), $eh, ['sys_kode_akun', '(Chart of Accounts)'], '1F4E79');
    }

    /** Login flow diagram */
    public function loginFlow()
    {
        $y = self::in(0.05);
        $bw = self::in(1.55);
        $bh = self::in(0.55);
        $gap = self::in(0.18);
        $row1y = $y;
        $boxes = [
            [self::in(0.2), 'Pengguna', 'Browser', '5B9BD5'],
            [self::in(2.05), 'Form Login', 'masukgo.php', '2E75B6'],
            [self::in(3.9), 'cheklogin()', 'Validasi CSRF', '1F4E79'],
        ];
        foreach ($boxes as $i => $b) {
            $this->roundRect($b[0], $row1y, $bw, $bh, [$b[1], $b[2]], $b[3]);
            if ($i < 2) {
                $this->connector($b[0] + $bw, $row1y + $bh / 2, $boxes[$i + 1][0], $row1y + $bh / 2, '2E75B6');
            }
        }
        $y2 = $row1y + $bh + self::in(0.35);
        $this->arrowDown(self::in(4.67), $row1y + $bh, self::in(0.28));
        $row2 = [
            [self::in(0.15), 'Rate Limit', 'Blokir sementara', 'C00000'],
            [self::in(2.0), 'Autentikasi', 'email + password', '548235'],
            [self::in(3.85), 'MFA?', 'Level 1 & 99', 'ED7D31'],
        ];
        foreach ($row2 as $i => $b) {
            $this->roundRect($b[0], $y2, $bw, $bh, [$b[1], $b[2]], $b[3]);
        }
        $this->connector(self::in(4.67), $y2 - self::in(0.05), self::in(4.67), $y2, '2E75B6');
        $y3 = $y2 + $bh + self::in(0.25);
        $this->roundRect(self::in(3.55), $y3, self::in(2.0), self::in(0.58), ['OTP WhatsApp', 'verifymfa.php — 6 digit'], '25B050');
        $this->connector(self::in(4.67), $y2 + $bh, self::in(4.55), $y3, '25B050');
        $y4 = $y3 + self::in(0.72);
        $this->roundRect(self::in(1.2), $y4, self::in(3.8), self::in(0.62), ['Session Login Sukses', 'sess_iduser, id_user_level, email'], '1F4E79');
        $this->connector(self::in(4.55), $y3 + self::in(0.58), self::in(3.1), $y4, '1F4E79');
        $this->connector(self::in(2.75), $y2 + $bh, self::in(3.1), $y4, '548235');
        $y5 = $y4 + self::in(0.72);
        $this->arrowDown(self::in(3.1), $y4 + self::in(0.62), self::in(0.25));
        $this->roundRect(self::in(1.0), $y5, self::in(4.2), self::in(0.55), ['Redirect → Dashboard', 'Level 1, 2, 3, 4, 7, 99'], '4472C4');
    }

    /** Sequence diagram sederhana */
    public function sequenceLogin()
    {
        $actors = ['User', 'Browser', 'Controller', 'Database', 'WhatsApp'];
        $ax = [self::in(0.15), self::in(1.15), self::in(2.35), self::in(3.55), self::in(4.75)];
        $aw = self::in(0.85);
        $ah = self::in(0.42);
        $y = self::in(0.05);
        foreach ($actors as $i => $a) {
            $this->roundRect($ax[$i], $y, $aw, $ah, [$a], 'D6E4F0', '1F4E79', '2E75B6');
            $this->connector($ax[$i] + $aw / 2, $y + $ah, $ax[$i] + $aw / 2, $y + self::in(3.8), 'CCCCCC');
        }
        $msgs = [
            [0, 1, 'buka URL'],
            [1, 2, 'GET index()'],
            [0, 1, 'email + password'],
            [1, 2, 'POST cheklogin()'],
            [2, 3, 'query tbl_user'],
            [2, 4, 'kirim OTP'],
            [0, 1, 'input OTP'],
            [1, 2, 'POST chekmfa()'],
            [2, 3, 'set session'],
            [1, 2, 'GET Dashboard'],
        ];
        $my = $y + $ah + self::in(0.12);
        $step = self::in(0.28);
        foreach ($msgs as $m) {
            $x1 = $ax[$m[0]] + $aw / 2;
            $x2 = $ax[$m[1]] + $aw / 2;
            $this->connector(min($x1, $x2), $my, max($x1, $x2), $my, '2E75B6');
            $this->label(min($x1, $x2), $my - self::in(0.18), $m[2], '404040');
            $my += $step;
        }
    }

    public function toParagraph($padIn = 0.12)
    {
        if (empty($this->drawings)) {
            return '';
        }
        $lineTwips = (int) (($this->totalHeight + self::in($padIn)) / 635);
        $xml = '<w:p><w:pPr><w:spacing w:before="60" w:after="160" w:line="' . max($lineTwips, 400) . '" w:lineRule="exact"/></w:pPr>';
        foreach ($this->drawings as $d) {
            $xml .= '<w:r><w:drawing>' . $d . '</w:drawing></w:r>';
        }
        $xml .= '</w:p>';
        return $xml;
    }
}
