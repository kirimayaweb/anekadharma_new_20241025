<?php
$base = dirname(__DIR__);
$srcFile = $base . '/application/views/anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list__jurnal_penjualan.php';
$destFile = $base . '/application/views/anekadharma/buku_besar/partials/modal_jurnal_penjualan.php';

$src = file($srcFile);
$css = implode('', array_slice($src, 2, 428));
$css = preg_replace('/^\\s*<style[^>]*>/i', '', $css);
$css = preg_replace('/<\\/style>\\s*$/i', '', $css);
$body = implode('', array_slice($src, 567, 627));
$js = implode('', array_slice($src, 1206, 394));

$map = array(
    '#tblJurnalPenjualanBaris' => '#bbJnTblJurnalPenjualanBaris',
    'tblJurnalPenjualanBaris' => 'bbJnTblJurnalPenjualanBaris',
    '#tglSPOPFreeze' => '#bbJnTglSPOPFreeze',
    'tglSPOPFreeze' => 'bbJnTglSPOPFreeze',
    '#tabJurnalPenjualanBaris' => '#bbJnTabJurnalPenjualanBaris',
    'tabJurnalPenjualanBaris' => 'bbJnTabJurnalPenjualanBaris',
    '#tabJurnalPenjualanBarisTab' => '#bbJnTabJurnalPenjualanBarisTab',
    'tabJurnalPenjualanBarisTab' => 'bbJnTabJurnalPenjualanBarisTab',
    '#tabJurnalPenjualanKolom' => '#bbJnTabJurnalPenjualanKolom',
    'tabJurnalPenjualanKolom' => 'bbJnTabJurnalPenjualanKolom',
    '#tabJurnalPenjualanKolomTab' => '#bbJnTabJurnalPenjualanKolomTab',
    'tabJurnalPenjualanKolomTab' => 'bbJnTabJurnalPenjualanKolomTab',
    '#tabJurnalPenjualanPerUnit' => '#bbJnTabJurnalPenjualanPerUnit',
    'tabJurnalPenjualanPerUnit' => 'bbJnTabJurnalPenjualanPerUnit',
    '#tabJurnalPenjualanPerUnitTab' => '#bbJnTabJurnalPenjualanPerUnitTab',
    'tabJurnalPenjualanPerUnitTab' => 'bbJnTabJurnalPenjualanPerUnitTab',
    'tblJurnalPenjualanPerUnit' => 'bbJnTblJurnalPenjualanPerUnit',
    '#tblJurnalPenjualanPerUnit' => '#bbJnTblJurnalPenjualanPerUnit',
    'tableJurnalPenjualanBaris' => 'bbJnTableJurnalPenjualanBaris',
    'tableJurnalPenjualan' => 'bbJnTableJurnalPenjualan',
);

foreach ($map as $k => $v) {
    $css = str_replace($k, $v, $css);
    $body = str_replace($k, $v, $body);
    $js = str_replace($k, $v, $js);
}

$js = preg_replace('/<script[^>]*>/', '', $js);
$js = preg_replace('/<\/script>/', '', $js);
$js = preg_replace('/\$\(document\)\.ready\(function\(\) \{/', 'function bbJnInitJurnalPenjualanEmbed() {', $js, 1);
$js = preg_replace('/\}\);\s*$/', '}', trim($js));
$js = str_replace('"scrollY": \'400px\'', '"scrollY": "min(52vh, 420px)"', $js);
$js = str_replace('scrollY: \'400px\'', 'scrollY: "min(52vh, 420px)"', $js);
$js = preg_replace("/\\$\\('#bulan_ns'\\)\\.on\\('change'[\\s\\S]*$/", '', $js);

$out = '<?php' . "\n";
$out .= '$bulan_ns_selected = isset($bulan_ns_selected) ? $bulan_ns_selected : date(\'Y-m\');' . "\n";
$out .= '$bulan_label = isset($bulan_label) ? $bulan_label : \'\';' . "\n";
$out .= '?>' . "\n";
$out .= '<div class="bb-jn-embed" data-bb-jn-embed="1">' . "\n";
$out .= '<style type="text/css">' . "\n" . $css . "\n";
$out .= '.bb-jn-embed-header{background:linear-gradient(135deg,#e65100 0%,#fb8c00 55%,#ffb74d 100%);color:#fff;border-radius:12px;padding:14px 18px;margin-bottom:12px;box-shadow:0 4px 14px rgba(230,81,0,.25);}' . "\n";
$out .= '.bb-jn-embed-header h4{margin:0;font-weight:700;font-size:1.15rem;}.bb-jn-embed-header .sub{opacity:.92;font-size:.9rem;margin-top:4px;}' . "\n";
$out .= '</style>' . "\n";
$out .= '<div class="bb-jn-embed-header"><h4><i class="fa fa-line-chart"></i> Jurnal Penjualan</h4><div class="sub"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, \'UTF-8\'); ?> &mdash; Baris, kolom, dan per unit (sama seperti halaman jurnal penjualan)</div></div>' . "\n";
$out .= $body . "\n";
$out .= '<script>' . "\n" . $js . "\nwindow.bbJnInitJurnalPenjualanEmbed = bbJnInitJurnalPenjualanEmbed;\n</script>\n";
$out .= '</div>' . "\n";

file_put_contents($destFile, $out);
echo 'Written ' . strlen($out) . " bytes\n";
