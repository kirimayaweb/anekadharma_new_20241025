<?php
$base = dirname(__DIR__);
$srcFile = $base . '/application/views/anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list__jurnal_pembelian.php';
$destFile = $base . '/application/views/anekadharma/buku_besar/partials/modal_jurnal_pembelian.php';

$src = file($srcFile);
$css = implode('', array_slice($src, 4, 438));
$css = preg_replace('/^\\s*<style[^>]*>/i', '', $css);
$css = preg_replace('/<\\/style>\\s*$/i', '', $css);
$panel = implode('', array_slice($src, 634, 199));
$modal = implode('', array_slice($src, 847, 61));
$js = implode('', array_slice($src, 914, 651));

$map = array(
    '#tglSPOPFreeze' => '#bbJpTglSPOPFreeze',
    'tglSPOPFreeze' => 'bbJpTglSPOPFreeze',
    '#modalJurnalPembelianKodeAkun' => '#bbJpModalJurnalPembelianKodeAkun',
    'modalJurnalPembelianKodeAkun' => 'bbJpModalJurnalPembelianKodeAkun',
    '#modalJurnalKodeAkunSelect' => '#bbJpModalKodeAkunSelect',
    'modalJurnalKodeAkunSelect' => 'bbJpModalKodeAkunSelect',
    '#modalJurnalKodeAkunAlert' => '#bbJpModalKodeAkunAlert',
    'modalJurnalKodeAkunAlert' => 'bbJpModalKodeAkunAlert',
    '#modalJurnalSpop' => '#bbJpModalJurnalSpop',
    'modalJurnalSpop' => 'bbJpModalJurnalSpop',
    '#modalJurnalSupplier' => '#bbJpModalJurnalSupplier',
    'modalJurnalSupplier' => 'bbJpModalJurnalSupplier',
    '#modalJurnalDetailContent' => '#bbJpModalJurnalDetailContent',
    'modalJurnalDetailContent' => 'bbJpModalJurnalDetailContent',
    '#modalJurnalHintSpop' => '#bbJpModalJurnalHintSpop',
    'modalJurnalHintSpop' => 'bbJpModalJurnalHintSpop',
    '#modalJurnalHintSupplier' => '#bbJpModalJurnalHintSupplier',
    'modalJurnalHintSupplier' => 'bbJpModalJurnalHintSupplier',
    '#jurnalSearchField' => '#bbJpJurnalSearchField',
    'jurnalSearchField' => 'bbJpJurnalSearchField',
    '#jurnalSearchText' => '#bbJpJurnalSearchText',
    'jurnalSearchText' => 'bbJpJurnalSearchText',
    '#jurnalSpopStatsSudah' => '#bbJpJurnalSpopStatsSudah',
    'jurnalSpopStatsSudah' => 'bbJpJurnalSpopStatsSudah',
    '#jurnalSpopStatsBelum' => '#bbJpJurnalSpopStatsBelum',
    'jurnalSpopStatsBelum' => 'bbJpJurnalSpopStatsBelum',
    '#jurnalSpopStatsTotal' => '#bbJpJurnalSpopStatsTotal',
    'jurnalSpopStatsTotal' => 'bbJpJurnalSpopStatsTotal',
    '#jurnalSpopStatsFilterNote' => '#bbJpJurnalSpopStatsFilterNote',
    'jurnalSpopStatsFilterNote' => 'bbJpJurnalSpopStatsFilterNote',
    '#btnCetakExcelJurnalPembelian' => '#bbJpBtnCetakExcelJurnalPembelian',
    'btnCetakExcelJurnalPembelian' => 'bbJpBtnCetakExcelJurnalPembelian',
    '#footerTotalDebet' => '#bbJpFooterTotalDebet',
    '#footerTotalKredit' => '#bbJpFooterTotalKredit',
    'id="footerTotalDebet"' => 'id="bbJpFooterTotalDebet"',
    'id="footerTotalKredit"' => 'id="bbJpFooterTotalKredit"',
    'jurnal_pembelian2_search_' => 'bb_jp_jurnal_pembelian2_search_',
    'jurnal_pembelian2_sort_' => 'bb_jp_jurnal_pembelian2_sort_',
    '__jurnalPembelianFieldFilterRegistered' => '__bbJpJurnalPembelianFieldFilterRegistered',
    'window.location.reload();' => 'if (window.bbJpRefreshCallback) { window.bbJpRefreshCallback(); } else { window.location.reload(); }',
);

foreach ($map as $k => $v) {
    $css = str_replace($k, $v, $css);
    $panel = str_replace($k, $v, $panel);
    $modal = str_replace($k, $v, $modal);
    $js = str_replace($k, $v, $js);
}

$js = preg_replace('/<script[^>]*>/', '', $js);
$js = preg_replace('/<\/script>/', '', $js);
$js = preg_replace('/\$\(document\)\.ready\(function\(\) \{/', 'function bbJpInitJurnalPembelianEmbed() {', $js, 1);
$js = preg_replace('/\}\);\s*$/', '}', trim($js));
$js = str_replace("$(document).on('click.bbJpKodeAkun', '.bb-jp-embed .btn-jurnal-kode-akun'", "$('.bb-jp-embed').off('click.bbJpKodeAkun').on('click.bbJpKodeAkun', '.btn-jurnal-kode-akun'", $js);
$js = str_replace('"scrollY": 900', '"scrollY": "min(62vh, 520px)"', $js);
$js = preg_replace("/\$\('#bulan_ns'\)\.on\('change'[\s\S]*$/", '', $js);

$out = '<?php' . "\n";
$out .= '$jurnal_spop_stats = isset($jurnal_pembelian2_spop_stats) ? $jurnal_pembelian2_spop_stats : array(\'total\' => 0, \'sudah\' => 0, \'belum\' => 0);' . "\n";
$out .= '$bulan_ns_selected = isset($bulan_ns_selected) ? $bulan_ns_selected : date(\'Y-m\');' . "\n";
$out .= '$bulan_label = isset($bulan_label) ? $bulan_label : \'\';' . "\n";
$out .= '?>' . "\n";
$out .= '<div class="bb-jp-embed" data-bb-jp-embed="1">' . "\n";
$out .= '<style type="text/css">' . "\n" . $css . "\n";
$out .= '.bb-jp-embed-header{background:linear-gradient(135deg,#1b5e20 0%,#43a047 55%,#66bb6a 100%);color:#fff;border-radius:12px;padding:14px 18px;margin-bottom:12px;box-shadow:0 4px 14px rgba(27,94,32,.25);}' . "\n";
$out .= '.bb-jp-embed-header h4{margin:0;font-weight:700;font-size:1.15rem;}.bb-jp-embed-header .sub{opacity:.92;font-size:.9rem;margin-top:4px;}' . "\n";
$out .= '#bbJpModalJurnalPembelianKodeAkun{z-index:1065!important;}' . "\n";
$out .= '</style>' . "\n";
$out .= '<div class="bb-jp-embed-header"><h4><i class="fa fa-shopping-cart"></i> Jurnal Pembelian</h4><div class="sub"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, \'UTF-8\'); ?> &mdash; DataTables lengkap dengan tombol ubah kode akun</div></div>' . "\n";
$out .= $panel . "\n" . $modal . "\n";
$out .= '<script>' . "\n" . $js . "\nwindow.bbJpInitJurnalPembelianEmbed = bbJpInitJurnalPembelianEmbed;\n</script>\n";
$out .= '</div>' . "\n";

file_put_contents($destFile, $out);
echo 'Written ' . strlen($out) . " bytes\n";
