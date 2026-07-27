<?php
$path = dirname(__DIR__) . '/application/helpers/bukubank_compare_helper.php';
$content = file_get_contents($path);

$replacements = array(
	"if (\$logical_key === 'kode_akun') {\n\t\t\$candidates = array('kode_akun', 'kode_rekening', 'uraian_kode_rekening');" =>
		"if (\$logical_key === 'kredit') {\n\t\t\$candidates = array('kredit');",
);

foreach ($replacements as $from => $to) {
	$content = str_replace($from, $to, $content);
}

$content = preg_replace(
	'/function bukubank_compare_bukubank_target_column_map\(\$CI\)\s*\{.*?\n\}/s',
	"function bukubank_compare_bukubank_target_column_map(\$CI)\n{\n\t\$fields = \$CI->db->list_fields('bukubank');\n\n\treturn array(\n\t\t'tanggal' => bukubank_compare_pick_bukubank_db_column(\$fields, 'tanggal'),\n\t\t'bank' => bukubank_compare_pick_bukubank_db_column(\$fields, 'bank'),\n\t\t'norek' => bukubank_compare_pick_bukubank_db_column(\$fields, 'norek'),\n\t\t'keterangan' => bukubank_compare_pick_bukubank_db_column(\$fields, 'keterangan'),\n\t\t'kode' => bukubank_compare_pick_bukubank_db_column(\$fields, 'kode'),\n\t\t'debet' => bukubank_compare_pick_bukubank_db_column(\$fields, 'debet'),\n\t\t'kredit' => bukubank_compare_pick_bukubank_db_column(\$fields, 'kredit'),\n\t\t'saldo' => bukubank_compare_pick_bukubank_db_column(\$fields, 'saldo'),\n\t\t'_fields' => \$fields,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_row_get_kode_akun\(\$row\)\s*\{.*?\n\}/s',
	"function bukubank_compare_row_get_kredit_raw(\$row)\n{\n\tif (isset(\$row->kredit)) {\n\t\treturn \$row->kredit;\n\t}\n\treturn '';\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_build_bukubank_insert_row\(\$CI, \$item, \$tanggal_db\)\s*\{.*?\n\}/s',
	"function bukubank_compare_build_bukubank_insert_row(\$CI, \$item, \$tanggal_db)\n{\n\t\$target = bukubank_compare_bukubank_target_column_map(\$CI);\n\t\$missing = array();\n\tif (empty(\$target['debet'])) {\n\t\t\$missing[] = 'debet atau debit';\n\t}\n\tif (empty(\$target['kredit'])) {\n\t\t\$missing[] = 'kredit';\n\t}\n\tif (count(\$missing) > 0) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => 'Kolom wajib tidak ditemukan di tabel bukubank: ' . implode(', ', \$missing) . '.',\n\t\t);\n\t}\n\n\t\$data = array();\n\tif (!empty(\$target['tanggal'])) {\n\t\t\$data[\$target['tanggal']] = \$tanggal_db;\n\t}\n\tif (!empty(\$target['bank'])) {\n\t\t\$data[\$target['bank']] = \$item['bank'] !== '' ? \$item['bank'] : '';\n\t}\n\tif (!empty(\$target['norek'])) {\n\t\t\$data[\$target['norek']] = \$item['norek'] !== '' ? \$item['norek'] : '';\n\t}\n\tif (!empty(\$target['keterangan'])) {\n\t\t\$data[\$target['keterangan']] = \$item['keterangan'] !== '' ? \$item['keterangan'] : '';\n\t}\n\tif (!empty(\$target['kode'])) {\n\t\t\$data[\$target['kode']] = \$item['kode'] !== '' ? \$item['kode'] : '';\n\t}\n\tif (!empty(\$target['saldo'])) {\n\t\t\$data[\$target['saldo']] = \$item['saldo'] !== '' ? \$item['saldo'] : '';\n\t}\n\n\t\$data[\$target['debet']] = \$item['debet'] > 0 ? \$item['debet'] : 0;\n\t\$kredit_val = \$item['kredit'] > 0 ? bukubank_compare_format_kredit_for_db(\$item['kredit']) : '';\n\t\$data[\$target['kredit']] = \$kredit_val;\n\n\treturn array('ok' => true, 'data' => \$data, 'target_columns' => \$target);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_field_definitions\(\)\s*\{.*?\n\}/s',
	"function bukubank_compare_field_definitions()\n{\n\treturn array(\n\t\t'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),\n\t\t'bank' => array('label' => 'bank', 'required' => false, 'aliases' => array('bank', 'nama_bank', 'nama bank')),\n\t\t'norek' => array('label' => 'norek', 'required' => false, 'aliases' => array('norek', 'no_rek', 'no rekening', 'nomor rekening', 'rekening')),\n\t\t'keterangan' => array('label' => 'keterangan', 'required' => false, 'aliases' => array('keterangan', 'ket', 'uraian transaksi', 'deskripsi')),\n\t\t'kode' => array('label' => 'kode', 'required' => false, 'aliases' => array('kode', 'no_kode', 'kode transaksi')),\n\t\t'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),\n\t\t'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),\n\t\t'saldo' => array('label' => 'saldo', 'required' => false, 'aliases' => array('saldo', 'balance')),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_analyze_column_map\(\$fields\)\s*\{.*?\n\}/s',
	"function bukubank_compare_analyze_column_map(\$fields)\n{\n\tif (!is_array(\$fields) || count(\$fields) === 0) {\n\t\treturn array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');\n\t}\n\n\t\$normalized = array();\n\tforeach (\$fields as \$field) {\n\t\t\$normalized[] = trim((string) \$field);\n\t}\n\n\t\$defs = bukubank_compare_field_definitions();\n\t\$map = array(\n\t\t'tanggal' => penjualan_jurnal_compare_pick_tanggal_column(\$normalized),\n\t\t'bank' => persediaan_compare_pick_column(\$normalized, \$defs['bank']['aliases']),\n\t\t'norek' => persediaan_compare_pick_column(\$normalized, \$defs['norek']['aliases']),\n\t\t'keterangan' => persediaan_compare_pick_column(\$normalized, \$defs['keterangan']['aliases']),\n\t\t'kode' => persediaan_compare_pick_column(\$normalized, \$defs['kode']['aliases']),\n\t\t'debet' => persediaan_compare_pick_column(\$normalized, \$defs['debet']['aliases']),\n\t\t'kredit' => persediaan_compare_pick_column(\$normalized, \$defs['kredit']['aliases']),\n\t\t'saldo' => persediaan_compare_pick_column(\$normalized, \$defs['saldo']['aliases']),\n\t);\n\n\t\$missing_required = array();\n\tforeach (\$defs as \$key => \$def) {\n\t\tif (empty(\$map[\$key]) && !empty(\$def['required'])) {\n\t\t\t\$missing_required[] = \$def['label'];\n\t\t}\n\t}\n\n\tif (empty(\$map['debet']) && empty(\$map['kredit'])) {\n\t\t\$missing_required[] = 'debet atau kredit';\n\t}\n\n\t\$ok = count(\$missing_required) === 0;\n\t\$message = '';\n\tif (!\$ok) {\n\t\t\$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', \$missing_required)\n\t\t\t. '. Kolom compare: tanggal, debet atau kredit.';\n\t}\n\n\t\$mapped = array();\n\tforeach (\$map as \$key => \$col) {\n\t\tif (\$col !== null && \$col !== '') {\n\t\t\t\$mapped[\$key] = \$col;\n\t\t}\n\t}\n\n\treturn array(\n\t\t'ok' => \$ok,\n\t\t'map' => \$map,\n\t\t'mapped' => \$mapped,\n\t\t'missing_required' => \$missing_required,\n\t\t'fields' => \$normalized,\n\t\t'message' => \$message,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_validate_online_table_detail\(\$CI\)\s*\{.*?\n\}/s',
	"function bukubank_compare_validate_online_table_detail(\$CI)\n{\n\tif (!\$CI->db->table_exists('bukubank')) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => 'Tabel online `bukubank` tidak ditemukan di database.',\n\t\t\t'missing_fields' => array('bukubank (tabel)'),\n\t\t);\n\t}\n\n\t\$fields = \$CI->db->list_fields('bukubank');\n\t\$map = array(\n\t\t'tanggal' => bukubank_compare_pick_bukubank_db_column(\$fields, 'tanggal'),\n\t\t'bank' => bukubank_compare_pick_bukubank_db_column(\$fields, 'bank'),\n\t\t'norek' => bukubank_compare_pick_bukubank_db_column(\$fields, 'norek'),\n\t\t'keterangan' => bukubank_compare_pick_bukubank_db_column(\$fields, 'keterangan'),\n\t\t'kode' => bukubank_compare_pick_bukubank_db_column(\$fields, 'kode'),\n\t\t'debet' => bukubank_compare_pick_bukubank_db_column(\$fields, 'debet'),\n\t\t'kredit' => bukubank_compare_pick_bukubank_db_column(\$fields, 'kredit'),\n\t\t'saldo' => bukubank_compare_pick_bukubank_db_column(\$fields, 'saldo'),\n\t);\n\n\t\$critical_missing = array();\n\tif (empty(\$map['tanggal'])) {\n\t\t\$critical_missing[] = 'tanggal';\n\t}\n\tif (empty(\$map['debet']) && empty(\$map['kredit'])) {\n\t\t\$critical_missing[] = 'debet/debit atau kredit';\n\t}\n\n\t\$soft_missing = array();\n\tforeach (array('bank', 'norek', 'keterangan', 'kode', 'saldo') as \$k) {\n\t\tif (empty(\$map[\$k])) {\n\t\t\t\$soft_missing[] = \$k;\n\t\t}\n\t}\n\n\t\$ok = count(\$critical_missing) === 0;\n\t\$message = '';\n\tif (!\$ok) {\n\t\t\$message = 'Tabel online `bukubank` tidak memiliki kolom wajib: ' . implode(', ', \$critical_missing) . '.';\n\t} elseif (count(\$soft_missing) > 0) {\n\t\t\$message = 'Kolom compare online tidak ditemukan (diisi kosong): ' . implode(', ', \$soft_missing) . '.';\n\t}\n\n\t\$mapped = array();\n\tforeach (\$map as \$key => \$col) {\n\t\tif (\$col !== null && \$col !== '') {\n\t\t\t\$mapped[\$key] = \$col;\n\t\t}\n\t}\n\n\treturn array(\n\t\t'ok' => \$ok,\n\t\t'table' => 'bukubank',\n\t\t'map' => \$map,\n\t\t'mapped' => \$mapped,\n\t\t'missing_fields' => array_merge(\$critical_missing, \$soft_missing),\n\t\t'critical_missing' => \$critical_missing,\n\t\t'soft_missing' => \$soft_missing,\n\t\t'fields' => \$fields,\n\t\t'message' => \$message,\n\t);\n}",
	$content,
	1
);

$content = str_replace(
	"function bukubank_compare_normalize_kode_akun(\$value)\n{\n\treturn trim((string) \$value);\n}\n\nfunction bukubank_compare_normalize_keterangan",
	"function bukubank_compare_normalize_bank(\$value)\n{\n\treturn strtoupper(trim((string) \$value));\n}\n\nfunction bukubank_compare_normalize_norek(\$value)\n{\n\treturn trim((string) \$value);\n}\n\nfunction bukubank_compare_normalize_kode(\$value)\n{\n\treturn trim((string) \$value);\n}\n\nfunction bukubank_compare_format_kredit_for_db(\$value)\n{\n\t\$n = bukubank_compare_normalize_jumlah(\$value);\n\tif (\$n <= 0) {\n\t\treturn '';\n\t}\n\tif (fmod(\$n, 1.0) == 0.0) {\n\t\treturn (string) (int) \$n;\n\t}\n\treturn (string) \$n;\n}\n\nfunction bukubank_compare_normalize_keterangan",
	$content
);

$content = str_replace(
	"return array('No', 'Tanggal', 'PL', 'Kode', 'Kode Akun', 'Nama Akun', 'Keterangan', 'Debet', 'Kredit', 'Catatan');",
	"return array('No', 'Tanggal', 'Bank', 'Norek', 'Keterangan', 'Kode', 'Debet', 'Kredit', 'Saldo', 'Catatan');",
	$content
);

$content = preg_replace(
	'/function bukubank_compare_is_row_analyzable\(\$row\)\s*\{.*?\n\}/s',
	"function bukubank_compare_is_row_analyzable(\$row)\n{\n\t\$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset(\$row['tanggal']) ? \$row['tanggal'] : '');\n\tif (\$tanggal === '' || \$tanggal === '0000-00-00') {\n\t\treturn false;\n\t}\n\t\$deb = bukubank_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0);\n\t\$kre = bukubank_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n\treturn (\$deb > 0 || \$kre > 0);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_row_unprocessed_reasons\(\$row\)\s*\{.*?\n\}/s',
	"function bukubank_compare_row_unprocessed_reasons(\$row)\n{\n\t\$reasons = array();\n\tif (pembelian_jurnal_compare_normalize_tanggal(isset(\$row['tanggal']) ? \$row['tanggal'] : '') === '') {\n\t\t\$reasons[] = 'tanggal kosong/tidak valid';\n\t}\n\tif (bukubank_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0) <= 0\n\t\t&& bukubank_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0) <= 0) {\n\t\t\$reasons[] = 'debet dan kredit kosong';\n\t}\n\treturn \$reasons;\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_make_hard_key\(\$tanggal, \$pl, \$kode, \$kode_akun\)\s*\{.*?\n\}/s',
	"function bukubank_compare_make_hard_key(\$tanggal, \$bank, \$norek, \$kode)\n{\n\treturn pembelian_jurnal_compare_normalize_tanggal(\$tanggal)\n\t\t. '|' . bukubank_compare_normalize_bank(\$bank)\n\t\t. '|' . bukubank_compare_normalize_norek(\$norek)\n\t\t. '|' . bukubank_compare_normalize_kode(\$kode);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_make_full_key\(\$row\)\s*\{.*?\n\}/s',
	"function bukubank_compare_make_full_key(\$row)\n{\n\treturn bukubank_compare_make_hard_key(\n\t\tisset(\$row['tanggal']) ? \$row['tanggal'] : '',\n\t\tisset(\$row['bank']) ? \$row['bank'] : '',\n\t\tisset(\$row['norek']) ? \$row['norek'] : '',\n\t\tisset(\$row['kode']) ? \$row['kode'] : ''\n\t)\n\t\t. '|' . bukubank_compare_normalize_keterangan(isset(\$row['keterangan']) ? \$row['keterangan'] : '')\n\t\t. '|' . bukubank_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0)\n\t\t. '|' . bukubank_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_row_to_display\(\$row, \$catatan = \'\'\)\s*\{.*?\n\}/s',
	"function bukubank_compare_row_to_display(\$row, \$catatan = '')\n{\n\t\$debet = bukubank_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0);\n\t\$kredit = bukubank_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n\t\$saldo = bukubank_compare_normalize_jumlah(isset(\$row['saldo']) ? \$row['saldo'] : 0);\n\n\treturn array(\n\t\t'tanggal' => isset(\$row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display(\$row['tanggal']) : '',\n\t\t'bank' => isset(\$row['bank']) ? \$row['bank'] : '',\n\t\t'norek' => isset(\$row['norek']) ? \$row['norek'] : '',\n\t\t'keterangan' => isset(\$row['keterangan']) ? \$row['keterangan'] : '',\n\t\t'kode' => isset(\$row['kode']) ? \$row['kode'] : '',\n\t\t'debet' => \$debet > 0 ? bukubank_compare_format_jumlah_display(\$debet) : '',\n\t\t'kredit' => \$kredit > 0 ? bukubank_compare_format_jumlah_display(\$kredit) : '',\n\t\t'saldo' => \$saldo != 0 ? bukubank_compare_format_jumlah_display(\$saldo) : (isset(\$row['saldo']) ? trim((string) \$row['saldo']) : ''),\n\t\t'catatan' => \$catatan,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_build_diff_catatan\(\$row, \$other, \$other_label\)\s*\{.*?\n\}/s',
	"function bukubank_compare_build_diff_catatan(\$row, \$other, \$other_label)\n{\n\tif (!is_array(\$row) || !is_array(\$other)) {\n\t\treturn 'Tidak ditemukan pasangan di data ' . \$other_label . '.';\n\t}\n\n\t\$similar = array('Tanggal', 'Bank', 'Norek', 'Kode');\n\t\$diff_parts = array();\n\n\tif (bukubank_compare_normalize_keterangan(\$row['keterangan']) === bukubank_compare_normalize_keterangan(\$other['keterangan'])) {\n\t\t\$similar[] = 'Keterangan';\n\t} else {\n\t\t\$diff_parts[] = 'Keterangan berbeda (Manual: ' . \$row['keterangan'] . ', ' . ucfirst(\$other_label) . ': ' . \$other['keterangan'] . ')';\n\t}\n\n\t\$deb_r = bukubank_compare_normalize_jumlah(\$row['debet']);\n\t\$deb_o = bukubank_compare_normalize_jumlah(\$other['debet']);\n\tif (\$deb_r === \$deb_o) {\n\t\t\$similar[] = 'Debet';\n\t} else {\n\t\t\$diff_parts[] = 'Debet berbeda (Manual: ' . bukubank_compare_format_jumlah_display(\$deb_r)\n\t\t\t. ', ' . ucfirst(\$other_label) . ': ' . bukubank_compare_format_jumlah_display(\$deb_o) . ')';\n\t}\n\n\t\$kre_r = bukubank_compare_normalize_jumlah(\$row['kredit']);\n\t\$kre_o = bukubank_compare_normalize_jumlah(\$other['kredit']);\n\tif (\$kre_r === \$kre_o) {\n\t\t\$similar[] = 'Kredit';\n\t} else {\n\t\t\$diff_parts[] = 'Kredit berbeda (Manual: ' . bukubank_compare_format_jumlah_display(\$kre_r)\n\t\t\t. ', ' . ucfirst(\$other_label) . ': ' . bukubank_compare_format_jumlah_display(\$kre_o) . ')';\n\t}\n\n\t\$parts = array();\n\tif (count(\$similar) > 0) {\n\t\t\$parts[] = 'Field sama: ' . implode(', ', \$similar);\n\t}\n\tif (count(\$diff_parts) > 0) {\n\t\t\$parts[] = 'Field berbeda: ' . implode('; ', \$diff_parts);\n\t}\n\n\tif (count(\$parts) === 0) {\n\t\treturn 'Tidak ditemukan pasangan lengkap di data ' . \$other_label . '.';\n\t}\n\n\treturn implode('; ', \$parts);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_manual_row_from_db\(\$row, \$map, \$default_tanggal = \'\'\)\s*\{.*?\n\}/s',
	"function bukubank_compare_manual_row_from_db(\$row, \$map, \$default_tanggal = '')\n{\n\t\$tanggal_raw = !empty(\$map['tanggal']) ? persediaan_compare_row_get(\$row, \$map['tanggal']) : \$default_tanggal;\n\t\$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal(\$tanggal_raw);\n\tif ((\$tanggal_norm === '' || \$tanggal_norm === '0000-00-00') && \$default_tanggal !== '') {\n\t\t\$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal(\$default_tanggal);\n\t}\n\n\treturn array(\n\t\t'tanggal' => \$tanggal_norm,\n\t\t'bank' => trim((string) (!empty(\$map['bank']) ? persediaan_compare_row_get(\$row, \$map['bank']) : '')),\n\t\t'norek' => trim((string) (!empty(\$map['norek']) ? persediaan_compare_row_get(\$row, \$map['norek']) : '')),\n\t\t'keterangan' => trim((string) (!empty(\$map['keterangan']) ? persediaan_compare_row_get(\$row, \$map['keterangan']) : '')),\n\t\t'kode' => trim((string) (!empty(\$map['kode']) ? persediaan_compare_row_get(\$row, \$map['kode']) : '')),\n\t\t'debet' => bukubank_compare_normalize_jumlah(!empty(\$map['debet']) ? persediaan_compare_row_get(\$row, \$map['debet']) : 0),\n\t\t'kredit' => bukubank_compare_normalize_jumlah(!empty(\$map['kredit']) ? persediaan_compare_row_get(\$row, \$map['kredit']) : 0),\n\t\t'saldo' => bukubank_compare_normalize_jumlah(!empty(\$map['saldo']) ? persediaan_compare_row_get(\$row, \$map['saldo']) : 0),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_online_row_from_db\(\$row\)\s*\{.*?\n\}/s',
	"function bukubank_compare_online_row_from_db(\$row)\n{\n\treturn array(\n\t\t'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset(\$row->tanggal) ? \$row->tanggal : ''),\n\t\t'bank' => isset(\$row->bank) ? trim((string) \$row->bank) : '',\n\t\t'norek' => isset(\$row->norek) ? trim((string) \$row->norek) : '',\n\t\t'keterangan' => isset(\$row->keterangan) ? trim((string) \$row->keterangan) : '',\n\t\t'kode' => isset(\$row->kode) ? trim((string) \$row->kode) : '',\n\t\t'debet' => bukubank_compare_normalize_jumlah(bukubank_compare_row_get_debet_raw(\$row)),\n\t\t'kredit' => bukubank_compare_normalize_jumlah(bukubank_compare_row_get_kredit_raw(\$row)),\n\t\t'saldo' => bukubank_compare_normalize_jumlah(isset(\$row->saldo) ? \$row->saldo : 0),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_load_online_all\(\$CI, \$bulan\)\s*\{.*?\n\treturn array\(\s*\n\t\t\'ok\' => true,/s',
	"function bukubank_compare_load_online_all(\$CI, \$bulan)\n{\n\t\$range = persediaan_compare_bulan_to_date_range(\$bulan);\n\tif (\$range === null) {\n\t\treturn array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');\n\t}\n\n\t\$online_fields = bukubank_compare_validate_online_table_detail(\$CI);\n\tif (empty(\$online_fields['ok'])) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => isset(\$online_fields['message']) ? \$online_fields['message'] : 'Struktur tabel online bukubank tidak valid.',\n\t\t\t'field_validation' => array('online' => \$online_fields),\n\t\t);\n\t}\n\n\t\$month = (int) substr(\$bulan, 5, 2);\n\t\$year = (int) substr(\$bulan, 0, 4);\n\n\t\$sql = \"SELECT DATE(tanggal) AS tanggal,\n\t\tCOALESCE(NULLIF(TRIM(bank), ''), '') AS bank,\n\t\tCOALESCE(NULLIF(TRIM(norek), ''), '') AS norek,\n\t\tCOALESCE(NULLIF(TRIM(keterangan), ''), '') AS keterangan,\n\t\tCOALESCE(NULLIF(TRIM(kode), ''), '') AS kode,\n\t\tCOALESCE(debet, 0) AS debet,\n\t\tCOALESCE(kredit, '') AS kredit,\n\t\tCOALESCE(saldo, '') AS saldo\n\t\tFROM bukubank\n\t\tWHERE tanggal IS NOT NULL AND tanggal <> '0000-00-00'\n\t\tAND MONTH(tanggal) = ? AND YEAR(tanggal) = ?\n\t\tORDER BY tanggal, id\";\n\n\t\$list = array();\n\t\$by_full = array();\n\t\$by_hard = array();\n\t\$unprocessed = array();\n\t\$display_all = array();\n\n\tforeach (\$CI->db->query(\$sql, array(\$month, \$year))->result() as \$row) {\n\t\t\$item = bukubank_compare_online_row_from_db(\$row);\n\t\t\$reasons = bukubank_compare_row_unprocessed_reasons(\$item);\n\t\t\$display_all[] = bukubank_compare_row_to_display(\n\t\t\t\$item,\n\t\t\tcount(\$reasons) > 0 ? ('Info: ' . implode(', ', \$reasons)) : ''\n\t\t);\n\n\t\tif (!bukubank_compare_is_row_analyzable(\$item)) {\n\t\t\t\$unprocessed[] = bukubank_compare_row_to_display(\$item, 'Online tidak terproses: ' . implode(', ', \$reasons));\n\t\t\tcontinue;\n\t\t}\n\n\t\t\$full_key = bukubank_compare_make_full_key(\$item);\n\t\t\$hard_key = bukubank_compare_make_hard_key(\$item['tanggal'], \$item['bank'], \$item['norek'], \$item['kode']);\n\t\t\$item['_full_key'] = \$full_key;\n\t\t\$item['_hard_key'] = \$hard_key;\n\n\t\t\$list[] = \$item;\n\t\tif (!isset(\$by_full[\$full_key])) {\n\t\t\t\$by_full[\$full_key] = array();\n\t\t}\n\t\t\$by_full[\$full_key][] = \$item;\n\t\tif (!isset(\$by_hard[\$hard_key])) {\n\t\t\t\$by_hard[\$hard_key] = array();\n\t\t}\n\t\t\$by_hard[\$hard_key][] = \$item;\n\t}\n\n\treturn array(\n\t\t'ok' => true,",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_sort_display_rows\(\$a, \$b\)\s*\{.*?\n\}/s',
	"function bukubank_compare_sort_display_rows(\$a, \$b)\n{\n\t\$cmp = strcmp((string) \$a['tanggal'], (string) \$b['tanggal']);\n\tif (\$cmp !== 0) {\n\t\treturn \$cmp;\n\t}\n\t\$cmp = strcmp((string) \$a['bank'], (string) \$b['bank']);\n\tif (\$cmp !== 0) {\n\t\treturn \$cmp;\n\t}\n\treturn strcmp((string) \$a['norek'], (string) \$b['norek']);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_pick_best_candidate\(\$row, \$candidates\)\s*\{.*?\n\}/s',
	"function bukubank_compare_pick_best_candidate(\$row, \$candidates)\n{\n\tif (!is_array(\$candidates) || count(\$candidates) === 0) {\n\t\treturn null;\n\t}\n\n\t\$best = null;\n\t\$best_score = -1;\n\tforeach (\$candidates as \$candidate) {\n\t\t\$score = 0;\n\t\tif (bukubank_compare_normalize_keterangan(\$row['keterangan']) === bukubank_compare_normalize_keterangan(\$candidate['keterangan'])) {\n\t\t\t\$score += 3;\n\t\t}\n\t\tif (bukubank_compare_normalize_jumlah(\$row['debet']) === bukubank_compare_normalize_jumlah(\$candidate['debet'])) {\n\t\t\t\$score += 2;\n\t\t}\n\t\tif (bukubank_compare_normalize_jumlah(\$row['kredit']) === bukubank_compare_normalize_jumlah(\$candidate['kredit'])) {\n\t\t\t\$score += 2;\n\t\t}\n\t\tif (\$score > \$best_score) {\n\t\t\t\$best_score = \$score;\n\t\t\t\$best = \$candidate;\n\t\t}\n\t}\n\n\treturn \$best;\n}",
	$content,
	1
);

// Fix hard_key calls in compare_run
$content = str_replace(
	"\$hard_key = bukubank_compare_make_hard_key(\$item['tanggal'], \$item['pl'], \$item['kode'], \$item['kode_akun']);",
	"\$hard_key = bukubank_compare_make_hard_key(\$item['tanggal'], \$item['bank'], \$item['norek'], \$item['kode']);",
	$content
);
$content = str_replace(
	"\$hard_key = bukubank_compare_make_hard_key(\$manual_row['tanggal'], \$manual_row['pl'], \$manual_row['kode'], \$manual_row['kode_akun']);",
	"\$hard_key = bukubank_compare_make_hard_key(\$manual_row['tanggal'], \$manual_row['bank'], \$manual_row['norek'], \$manual_row['kode']);",
	$content
);

$content = preg_replace(
	'/function bukubank_compare_item_to_row_cells\(\$item, \$no\)\s*\{.*?\n\}/s',
	"function bukubank_compare_item_to_row_cells(\$item, \$no)\n{\n\treturn array(\n\t\t\$no,\n\t\tisset(\$item['tanggal']) ? \$item['tanggal'] : '',\n\t\tisset(\$item['bank']) ? \$item['bank'] : '',\n\t\tisset(\$item['norek']) ? \$item['norek'] : '',\n\t\tisset(\$item['keterangan']) ? \$item['keterangan'] : '',\n\t\tisset(\$item['kode']) ? \$item['kode'] : '',\n\t\tisset(\$item['debet']) ? \$item['debet'] : '',\n\t\tisset(\$item['kredit']) ? \$item['kredit'] : '',\n\t\tisset(\$item['saldo']) ? \$item['saldo'] : '',\n\t\tisset(\$item['catatan']) ? \$item['catatan'] : '',\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_import_field_definitions\(\)\s*\{.*?\n\}/s',
	"function bukubank_compare_import_field_definitions()\n{\n\treturn bukubank_compare_field_definitions();\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_analyze_import_column_map\(\$fields\)\s*\{.*?\n\}/s',
	"function bukubank_compare_analyze_import_column_map(\$fields)\n{\n\treturn bukubank_compare_analyze_column_map(\$fields);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_import_row_from_db\(\$row, \$map, \$ref_month = 0, \$ref_year = 0\)\s*\{.*?\n\}/s',
	"function bukubank_compare_import_row_from_db(\$row, \$map, \$ref_month = 0, \$ref_year = 0)\n{\n\treturn bukubank_compare_manual_row_from_db(\$row, \$map, bukubank_compare_normalize_tanggal_for_db('', \$ref_month, \$ref_year));\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_load_table_detail_for_bulan\(\$CI, \$table, \$bulan\)\s*\{.*?\n\}/s',
	"function bukubank_compare_load_table_detail_for_bulan(\$CI, \$table, \$bulan)\n{\n\tif (!preg_match('/^\\d{4}-\\d{2}\$/', (string) \$bulan)) {\n\t\treturn array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');\n\t}\n\n\t\$valid = bukubank_compare_validate_import_table(\$CI, \$table);\n\tif (empty(\$valid['ok'])) {\n\t\treturn \$valid;\n\t}\n\n\t\$ref_year = (int) substr(\$bulan, 0, 4);\n\t\$ref_month = (int) substr(\$bulan, 5, 2);\n\t\$map = \$valid['map'];\n\t\$range = persediaan_compare_bulan_to_date_range(\$bulan);\n\t\$all_rows = \$CI->db->query('SELECT * FROM `' . \$table . '` ORDER BY id ASC')->result();\n\t\$items = array();\n\t\$no = 0;\n\n\tforeach ((array) \$all_rows as \$row) {\n\t\t\$item = bukubank_compare_import_row_from_db(\$row, \$map, \$ref_month, \$ref_year);\n\t\tif (!bukubank_compare_row_matches_bulan(\$item['tanggal'], \$bulan)) {\n\t\t\tcontinue;\n\t\t}\n\t\t\$no++;\n\t\t\$debet = (float) \$item['debet'];\n\t\t\$kredit = (float) \$item['kredit'];\n\t\t\$saldo = (float) \$item['saldo'];\n\t\t\$items[] = array(\n\t\t\t'no' => \$no,\n\t\t\t'tanggal' => pembelian_jurnal_compare_format_tanggal_display(\$item['tanggal']),\n\t\t\t'bank' => \$item['bank'],\n\t\t\t'norek' => \$item['norek'],\n\t\t\t'keterangan' => \$item['keterangan'],\n\t\t\t'kode' => \$item['kode'],\n\t\t\t'debet' => \$debet > 0 ? bukubank_compare_format_jumlah_display(\$debet) : '',\n\t\t\t'kredit' => \$kredit > 0 ? bukubank_compare_format_jumlah_display(\$kredit) : '',\n\t\t\t'saldo' => \$saldo != 0 ? bukubank_compare_format_jumlah_display(\$saldo) : '',\n\t\t\t'debet_raw' => \$debet,\n\t\t\t'kredit_raw' => \$kredit,\n\t\t\t'saldo_raw' => \$saldo,\n\t\t);\n\t}\n\n\treturn array(\n\t\t'ok' => true,\n\t\t'headers' => array('No', 'Tanggal', 'Bank', 'Norek', 'Keterangan', 'Kode', 'Debet', 'Kredit', 'Saldo'),\n\t\t'rows' => \$items,\n\t\t'table' => \$table,\n\t\t'bulan' => \$bulan,\n\t\t'bulan_label' => \$range ? \$range['bulan_label'] : \$bulan,\n\t\t'total' => count(\$items),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function bukubank_compare_export_table_detail_excel\(\$CI, \$table, \$bulan\)\s*\{.*?\n\txlsEOF\(\);\n\}/s',
	"function bukubank_compare_export_table_detail_excel(\$CI, \$table, \$bulan)\n{\n\t@set_time_limit(600);\n\t@ini_set('memory_limit', '512M');\n\t\$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'bukubank_list'));\n\n\t\$result = bukubank_compare_load_table_detail_for_bulan(\$CI, \$table, \$bulan);\n\tif (empty(\$result['ok'])) {\n\t\txlsBOF();\n\t\txlsWriteLabel(0, 0, isset(\$result['message']) ? \$result['message'] : 'Export Excel gagal.');\n\t\txlsEOF();\n\t\treturn;\n\t}\n\n\t\$headers = isset(\$result['headers']) ? \$result['headers'] : array();\n\t\$rows = isset(\$result['rows']) ? \$result['rows'] : array();\n\t\$bulan_label = isset(\$result['bulan_label']) ? \$result['bulan_label'] : \$bulan;\n\t\$styleHeader = 4;\n\t\$styleBorder = 3;\n\t\$styleRight = 8;\n\t\$styleLeft = 7;\n\n\txlsBOF();\n\txlsWriteLabelBold14(0, 0, 'Detail Tabel ' . \$table . ' — Bulan ' . \$bulan_label);\n\txlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count(\$rows));\n\n\t\$headerRow = 3;\n\tforeach (\$headers as \$col => \$label) {\n\t\txlsWriteCellStyle(\$headerRow, \$col, \$label, \$styleHeader);\n\t}\n\n\t\$rowNum = 4;\n\tforeach (\$rows as \$item) {\n\t\txlsWriteCellStyle(\$rowNum, 0, isset(\$item['no']) ? (int) \$item['no'] : 0, \$styleBorder);\n\t\txlsWriteCellStyle(\$rowNum, 1, isset(\$item['tanggal']) ? \$item['tanggal'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 2, isset(\$item['bank']) ? \$item['bank'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 3, isset(\$item['norek']) ? \$item['norek'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 4, isset(\$item['keterangan']) ? \$item['keterangan'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 5, isset(\$item['kode']) ? \$item['kode'] : '', \$styleLeft);\n\t\t\$deb = isset(\$item['debet_raw']) ? (float) \$item['debet_raw'] : 0;\n\t\t\$kre = isset(\$item['kredit_raw']) ? (float) \$item['kredit_raw'] : 0;\n\t\t\$sal = isset(\$item['saldo_raw']) ? (float) \$item['saldo_raw'] : 0;\n\t\tif (\$deb > 0) {\n\t\t\txlsWriteCellStyle(\$rowNum, 6, bukubank_format_rupiah(\$deb), \$styleRight);\n\t\t} else {\n\t\t\txlsWriteCellStyle(\$rowNum, 6, '', \$styleBorder);\n\t\t}\n\t\tif (\$kre > 0) {\n\t\t\txlsWriteCellStyle(\$rowNum, 7, bukubank_format_rupiah(\$kre), \$styleRight);\n\t\t} else {\n\t\t\txlsWriteCellStyle(\$rowNum, 7, '', \$styleBorder);\n\t\t}\n\t\tif (\$sal != 0) {\n\t\t\txlsWriteCellStyle(\$rowNum, 8, bukubank_format_rupiah(\$sal), \$styleRight);\n\t\t} else {\n\t\t\txlsWriteCellStyle(\$rowNum, 8, '', \$styleBorder);\n\t\t}\n\t\t\$rowNum++;\n\t}\n\n\txlsEOF();\n}",
	$content,
	1
);

// CSV import column setup
$content = preg_replace(
	"/\\\$col_tanggal = !empty\(\\\$column_map\['tanggal'\]\) \? \\\$column_map\['tanggal'\] : 'tanggal';\n\t\\\$col_pl = !empty\(\\\$column_map\['pl'\]\) \? \\\$column_map\['pl'\] : 'pl';\n\t\\\$col_kode = !empty\(\\\$column_map\['kode'\]\) \? \\\$column_map\['kode'\] : 'kode';\n\t\\\$col_kode_akun = !empty\(\\\$column_map\['kode_akun'\]\) \? \\\$column_map\['kode_akun'\] : 'kode_akun';\n\t\\\$col_nama_akun = !empty\(\\\$column_map\['nama_akun'\]\) \? \\\$column_map\['nama_akun'\] : 'nama_akun';\n\t\\\$col_keterangan = !empty\(\\\$column_map\['keterangan'\]\) \? \\\$column_map\['keterangan'\] : 'keterangan';\n\t\\\$col_debet = !empty\(\\\$column_map\['debet'\]\) \? \\\$column_map\['debet'\] : 'debet';\n\t\\\$col_kredit = !empty\(\\\$column_map\['kredit'\]\) \? \\\$column_map\['kredit'\] : 'kredit';/",
	"\$col_tanggal = !empty(\$column_map['tanggal']) ? \$column_map['tanggal'] : 'tanggal';\n\t\$col_bank = !empty(\$column_map['bank']) ? \$column_map['bank'] : 'bank';\n\t\$col_norek = !empty(\$column_map['norek']) ? \$column_map['norek'] : 'norek';\n\t\$col_keterangan = !empty(\$column_map['keterangan']) ? \$column_map['keterangan'] : 'keterangan';\n\t\$col_kode = !empty(\$column_map['kode']) ? \$column_map['kode'] : 'kode';\n\t\$col_debet = !empty(\$column_map['debet']) ? \$column_map['debet'] : 'debet';\n\t\$col_kredit = !empty(\$column_map['kredit']) ? \$column_map['kredit'] : 'kredit';\n\t\$col_saldo = !empty(\$column_map['saldo']) ? \$column_map['saldo'] : 'saldo';",
	$content,
	1
);

$content = preg_replace(
	"/foreach \(array\(\\\$col_tanggal, \\\$col_pl, \\\$col_kode, \\\$col_kode_akun, \\\$col_nama_akun, \\\$col_keterangan, \\\$col_debet, \\\$col_kredit\) as \\\$required_col\)/",
	"foreach (array(\$col_tanggal, \$col_bank, \$col_norek, \$col_keterangan, \$col_kode, \$col_debet, \$col_kredit, \$col_saldo) as \$required_col)",
	$content,
	1
);

$content = str_replace(
	"'message' => 'Header CSV tidak memenuhi syarat Buku Bank. Kolom wajib: tanggal, kode_akun, debet atau kredit.',",
	"'message' => 'Header CSV tidak memenuhi syarat Buku Bank. Kolom wajib: tanggal, debet atau kredit.',",
	$content
);

$content = str_replace(
	"'message' => 'Tabel `' . \$table . '` memenuhi syarat kolom import buku besar.',",
	"'message' => 'Tabel `' . \$table . '` memenuhi syarat kolom import buku bank.',",
	$content
);

$content = str_replace('bukubank_bulan_conflict', 'bukubank_bulan_conflict', $content);
$content = str_replace('buku_besar_bulan_conflict', 'bukubank_bulan_conflict', $content);
$content = str_replace('buku_besar_existing_count', 'bukubank_existing_count', $content);
$content = str_replace('count_bukubank_rows_for_bulan', 'count_bukubank_rows_for_bulan', $content);

file_put_contents($path, $content);
echo "Patched bukubank_compare_helper.php\n";
