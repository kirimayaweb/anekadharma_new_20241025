<?php
$base = dirname(__DIR__);
$src = file_get_contents($base . '/application/helpers/buku_besar_compare_helper.php');

$c = $src;
$c = str_replace('buku_besar_compare', 'bukubank_compare', $c);
$c = str_replace('buku_besar_format_rupiah', 'bukubank_format_rupiah', $c);
$c = str_replace('buku_besar_list', 'bukubank_list', $c);
$c = str_replace('Buku_besar_model', 'Bukubank_model', $c);
$c = str_replace('buku_besar', 'bukubank', $c);
$c = str_replace('Buku Besar', 'Buku Bank', $c);
$c = str_replace('BUKU BESAR', 'BUKU BANK', $c);

file_put_contents($base . '/application/helpers/bukubank_compare_helper.php', $c);
echo "Base helper written: " . strlen($c) . " bytes\n";
