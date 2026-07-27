<?php
$path = __DIR__ . '/../application/helpers/buku_besar_compare_helper.php';
$content = file_get_contents($path);

$replacements = array(
	"if (\$logical_key === 'kode_rekening') {\n\t\t\$candidates = array('uraian_kode_rekening', 'kode_rekening');" =>
		"if (\$logical_key === 'kode_akun') {\n\t\t\$candidates = array('kode_akun', 'kode_rekening', 'uraian_kode_rekening');",
);

foreach ($replacements as $from => $to) {
	$content = str_replace($from, $to, $content);
}

$content = preg_replace(
	'/function buku_besar_compare_buku_besar_target_column_map\(\$CI\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_buku_besar_target_column_map(\$CI)\n{\n\t\$fields = \$CI->db->list_fields('buku_besar');\n\n\treturn array(\n\t\t'tanggal' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'tanggal'),\n\t\t'pl' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'pl'),\n\t\t'kode' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kode'),\n\t\t'kode_akun' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kode_akun'),\n\t\t'nama_akun' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'nama_akun'),\n\t\t'keterangan' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'keterangan'),\n\t\t'debet' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'debet'),\n\t\t'kredit' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kredit'),\n\t\t'source' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'source'),\n\t\t'_fields' => \$fields,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_row_get_kode_akun\(\$row\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_row_get_kode_akun(\$row)\n{\n\tif (isset(\$row->kode_akun)) {\n\t\treturn trim((string) \$row->kode_akun);\n\t}\n\tif (isset(\$row->kode_rekening)) {\n\t\treturn trim((string) \$row->kode_rekening);\n\t}\n\tif (isset(\$row->uraian_kode_rekening)) {\n\t\treturn trim((string) \$row->uraian_kode_rekening);\n\t}\n\n\treturn '';\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_build_buku_besar_insert_row\(\$CI, \$item, \$tanggal_db\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_build_buku_besar_insert_row(\$CI, \$item, \$tanggal_db)\n{\n\t\$target = buku_besar_compare_buku_besar_target_column_map(\$CI);\n\t\$missing = array();\n\tif (empty(\$target['debet'])) {\n\t\t\$missing[] = 'debet atau debit';\n\t}\n\tif (empty(\$target['kredit'])) {\n\t\t\$missing[] = 'kredit';\n\t}\n\tif (count(\$missing) > 0) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => 'Kolom wajib tidak ditemukan di tabel buku_besar: ' . implode(', ', \$missing) . '.',\n\t\t);\n\t}\n\n\t\$data = array();\n\tif (!empty(\$target['tanggal'])) {\n\t\t\$data[\$target['tanggal']] = \$tanggal_db;\n\t}\n\tif (!empty(\$target['pl'])) {\n\t\t\$data[\$target['pl']] = \$item['pl'] !== '' ? \$item['pl'] : '';\n\t}\n\tif (!empty(\$target['kode'])) {\n\t\t\$data[\$target['kode']] = \$item['kode'] !== '' ? \$item['kode'] : '';\n\t}\n\tif (!empty(\$target['kode_akun'])) {\n\t\t\$data[\$target['kode_akun']] = \$item['kode_akun'] !== '' ? \$item['kode_akun'] : '';\n\t}\n\tif (!empty(\$target['nama_akun'])) {\n\t\t\$data[\$target['nama_akun']] = \$item['nama_akun'] !== '' ? \$item['nama_akun'] : '';\n\t}\n\tif (!empty(\$target['keterangan'])) {\n\t\t\$data[\$target['keterangan']] = \$item['keterangan'] !== '' ? \$item['keterangan'] : '';\n\t}\n\tif (!empty(\$target['source'])) {\n\t\t\$data[\$target['source']] = 'import_manual';\n\t}\n\n\t\$data[\$target['debet']] = \$item['debet'] > 0 ? \$item['debet'] : 0;\n\t\$data[\$target['kredit']] = \$item['kredit'] > 0 ? \$item['kredit'] : 0;\n\n\treturn array('ok' => true, 'data' => \$data, 'target_columns' => \$target);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_field_definitions\(\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_field_definitions()\n{\n\treturn array(\n\t\t'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),\n\t\t'pl' => array('label' => 'pl', 'required' => false, 'aliases' => array('pl', 'kode_pl', 'profit center')),\n\t\t'kode' => array('label' => 'kode', 'required' => false, 'aliases' => array('kode', 'no_kode', 'kode transaksi')),\n\t\t'kode_akun' => array('label' => 'kode_akun', 'required' => true, 'aliases' => array('kode_akun', 'kode akun', 'kode_rekening', 'kode_rek', 'uraian_kode_rekening')),\n\t\t'nama_akun' => array('label' => 'nama_akun', 'required' => false, 'aliases' => array('nama_akun', 'nama akun', 'rekening', 'nama_rekening', 'uraian')),\n\t\t'keterangan' => array('label' => 'keterangan', 'required' => false, 'aliases' => array('keterangan', 'ket', 'uraian transaksi', 'deskripsi')),\n\t\t'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),\n\t\t'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_analyze_column_map\(\$fields\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_analyze_column_map(\$fields)\n{\n\tif (!is_array(\$fields) || count(\$fields) === 0) {\n\t\treturn array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');\n\t}\n\n\t\$normalized = array();\n\tforeach (\$fields as \$field) {\n\t\t\$normalized[] = trim((string) \$field);\n\t}\n\n\t\$defs = buku_besar_compare_field_definitions();\n\t\$map = array(\n\t\t'tanggal' => penjualan_jurnal_compare_pick_tanggal_column(\$normalized),\n\t\t'pl' => persediaan_compare_pick_column(\$normalized, \$defs['pl']['aliases']),\n\t\t'kode' => persediaan_compare_pick_column(\$normalized, \$defs['kode']['aliases']),\n\t\t'kode_akun' => persediaan_compare_pick_column(\$normalized, \$defs['kode_akun']['aliases']),\n\t\t'nama_akun' => persediaan_compare_pick_column(\$normalized, \$defs['nama_akun']['aliases']),\n\t\t'keterangan' => persediaan_compare_pick_column(\$normalized, \$defs['keterangan']['aliases']),\n\t\t'debet' => persediaan_compare_pick_column(\$normalized, \$defs['debet']['aliases']),\n\t\t'kredit' => persediaan_compare_pick_column(\$normalized, \$defs['kredit']['aliases']),\n\t);\n\n\t\$missing_required = array();\n\tforeach (\$defs as \$key => \$def) {\n\t\tif (empty(\$map[\$key]) && !empty(\$def['required'])) {\n\t\t\t\$missing_required[] = \$def['label'];\n\t\t}\n\t}\n\n\tif (empty(\$map['debet']) && empty(\$map['kredit'])) {\n\t\t\$missing_required[] = 'debet atau kredit';\n\t}\n\n\t\$ok = count(\$missing_required) === 0;\n\t\$message = '';\n\tif (!\$ok) {\n\t\t\$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', \$missing_required)\n\t\t\t. '. Kolom compare: tanggal, kode_akun, debet atau kredit.';\n\t}\n\n\t\$mapped = array();\n\tforeach (\$map as \$key => \$col) {\n\t\tif (\$col !== null && \$col !== '') {\n\t\t\t\$mapped[\$key] = \$col;\n\t\t}\n\t}\n\n\treturn array(\n\t\t'ok' => \$ok,\n\t\t'map' => \$map,\n\t\t'mapped' => \$mapped,\n\t\t'missing_required' => \$missing_required,\n\t\t'fields' => \$normalized,\n\t\t'message' => \$message,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_validate_online_table_detail\(\$CI\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_validate_online_table_detail(\$CI)\n{\n\tif (!\$CI->db->table_exists('buku_besar')) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => 'Tabel online `buku_besar` tidak ditemukan di database.',\n\t\t\t'missing_fields' => array('buku_besar (tabel)'),\n\t\t);\n\t}\n\n\t\$fields = \$CI->db->list_fields('buku_besar');\n\t\$map = array(\n\t\t'tanggal' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'tanggal'),\n\t\t'pl' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'pl'),\n\t\t'kode' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kode'),\n\t\t'kode_akun' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kode_akun'),\n\t\t'nama_akun' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'nama_akun'),\n\t\t'keterangan' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'keterangan'),\n\t\t'debet' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'debet'),\n\t\t'kredit' => buku_besar_compare_pick_buku_besar_db_column(\$fields, 'kredit'),\n\t);\n\n\t\$critical_missing = array();\n\tif (empty(\$map['tanggal'])) {\n\t\t\$critical_missing[] = 'tanggal';\n\t}\n\tif (empty(\$map['debet']) && empty(\$map['kredit'])) {\n\t\t\$critical_missing[] = 'debet/debit atau kredit';\n\t}\n\n\t\$soft_missing = array();\n\tforeach (array('pl', 'kode', 'kode_akun', 'nama_akun', 'keterangan') as \$k) {\n\t\tif (empty(\$map[\$k])) {\n\t\t\t\$soft_missing[] = \$k;\n\t\t}\n\t}\n\n\t\$ok = count(\$critical_missing) === 0;\n\t\$message = '';\n\tif (!\$ok) {\n\t\t\$message = 'Tabel online `buku_besar` tidak memiliki kolom wajib: ' . implode(', ', \$critical_missing) . '.';\n\t} elseif (count(\$soft_missing) > 0) {\n\t\t\$message = 'Kolom compare online tidak ditemukan (diisi kosong): ' . implode(', ', \$soft_missing) . '.';\n\t}\n\n\t\$mapped = array();\n\tforeach (\$map as \$key => \$col) {\n\t\tif (\$col !== null && \$col !== '') {\n\t\t\t\$mapped[\$key] = \$col;\n\t\t}\n\t}\n\n\treturn array(\n\t\t'ok' => \$ok,\n\t\t'table' => 'buku_besar',\n\t\t'map' => \$map,\n\t\t'mapped' => \$mapped,\n\t\t'missing_fields' => array_merge(\$critical_missing, \$soft_missing),\n\t\t'critical_missing' => \$critical_missing,\n\t\t'soft_missing' => \$soft_missing,\n\t\t'fields' => \$fields,\n\t\t'message' => \$message,\n\t);\n}",
	$content,
	1
);

$content = str_replace(
	"function buku_besar_compare_normalize_bukti(\$bukti)\n{\n\treturn strtoupper(trim((string) \$bukti));\n}\n\nfunction buku_besar_compare_normalize_text",
	"function buku_besar_compare_normalize_kode_akun(\$value)\n{\n\treturn trim((string) \$value);\n}\n\nfunction buku_besar_compare_normalize_keterangan(\$value)\n{\n\treturn trim((string) \$value);\n}\n\nfunction buku_besar_compare_normalize_text",
	$content
);

$content = str_replace(
	"return array('No', 'Tanggal', 'Bukti', 'PL', 'Ref', 'Kode Rek.', 'Rek.', 'Debet', 'Kredit', 'Catatan');",
	"return array('No', 'Tanggal', 'PL', 'Kode', 'Kode Akun', 'Nama Akun', 'Keterangan', 'Debet', 'Kredit', 'Catatan');",
	$content
);

$content = preg_replace(
	'/function buku_besar_compare_is_row_analyzable\(\$row\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_is_row_analyzable(\$row)\n{\n\t\$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset(\$row['tanggal']) ? \$row['tanggal'] : '');\n\tif (\$tanggal === '' || \$tanggal === '0000-00-00') {\n\t\treturn false;\n\t}\n\tif (buku_besar_compare_normalize_kode_akun(isset(\$row['kode_akun']) ? \$row['kode_akun'] : '') === '') {\n\t\treturn false;\n\t}\n\t\$deb = buku_besar_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0);\n\t\$kre = buku_besar_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n\treturn (\$deb > 0 || \$kre > 0);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_row_unprocessed_reasons\(\$row\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_row_unprocessed_reasons(\$row)\n{\n\t\$reasons = array();\n\tif (pembelian_jurnal_compare_normalize_tanggal(isset(\$row['tanggal']) ? \$row['tanggal'] : '') === '') {\n\t\t\$reasons[] = 'tanggal kosong/tidak valid';\n\t}\n\tif (buku_besar_compare_normalize_kode_akun(isset(\$row['kode_akun']) ? \$row['kode_akun'] : '') === '') {\n\t\t\$reasons[] = 'kode_akun kosong';\n\t}\n\tif (buku_besar_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0) <= 0\n\t\t&& buku_besar_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0) <= 0) {\n\t\t\$reasons[] = 'debet dan kredit kosong';\n\t}\n\treturn \$reasons;\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_make_hard_key\(\$tanggal, \$bukti, \$pl, \$ref, \$kode_rekening\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_make_hard_key(\$tanggal, \$pl, \$kode, \$kode_akun)\n{\n\treturn pembelian_jurnal_compare_normalize_tanggal(\$tanggal)\n\t\t. '|' . buku_besar_compare_normalize_text(\$pl)\n\t\t. '|' . buku_besar_compare_normalize_text(\$kode)\n\t\t. '|' . buku_besar_compare_normalize_kode_akun(\$kode_akun);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_make_full_key\(\$row\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_make_full_key(\$row)\n{\n\treturn buku_besar_compare_make_hard_key(\n\t\tisset(\$row['tanggal']) ? \$row['tanggal'] : '',\n\t\tisset(\$row['pl']) ? \$row['pl'] : '',\n\t\tisset(\$row['kode']) ? \$row['kode'] : '',\n\t\tisset(\$row['kode_akun']) ? \$row['kode_akun'] : ''\n\t)\n\t\t. '|' . buku_besar_compare_normalize_keterangan(isset(\$row['keterangan']) ? \$row['keterangan'] : '')\n\t\t. '|' . buku_besar_compare_normalize_text(isset(\$row['nama_akun']) ? \$row['nama_akun'] : '')\n\t\t. '|' . buku_besar_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0)\n\t\t. '|' . buku_besar_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_row_to_display\(\$row, \$catatan = \'\'\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_row_to_display(\$row, \$catatan = '')\n{\n\t\$debet = buku_besar_compare_normalize_jumlah(isset(\$row['debet']) ? \$row['debet'] : 0);\n\t\$kredit = buku_besar_compare_normalize_jumlah(isset(\$row['kredit']) ? \$row['kredit'] : 0);\n\n\treturn array(\n\t\t'tanggal' => isset(\$row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display(\$row['tanggal']) : '',\n\t\t'pl' => isset(\$row['pl']) ? \$row['pl'] : '',\n\t\t'kode' => isset(\$row['kode']) ? \$row['kode'] : '',\n\t\t'kode_akun' => isset(\$row['kode_akun']) ? \$row['kode_akun'] : '',\n\t\t'nama_akun' => isset(\$row['nama_akun']) ? \$row['nama_akun'] : '',\n\t\t'keterangan' => isset(\$row['keterangan']) ? \$row['keterangan'] : '',\n\t\t'debet' => \$debet > 0 ? buku_besar_compare_format_jumlah_display(\$debet) : '',\n\t\t'kredit' => \$kredit > 0 ? buku_besar_compare_format_jumlah_display(\$kredit) : '',\n\t\t'catatan' => \$catatan,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_build_diff_catatan\(\$row, \$other, \$other_label\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_build_diff_catatan(\$row, \$other, \$other_label)\n{\n\tif (!is_array(\$row) || !is_array(\$other)) {\n\t\treturn 'Tidak ditemukan pasangan di data ' . \$other_label . '.';\n\t}\n\n\t\$similar = array('Tanggal', 'PL', 'Kode', 'Kode Akun');\n\t\$diff_parts = array();\n\n\tif (buku_besar_compare_normalize_keterangan(\$row['keterangan']) === buku_besar_compare_normalize_keterangan(\$other['keterangan'])) {\n\t\t\$similar[] = 'Keterangan';\n\t} else {\n\t\t\$diff_parts[] = 'Keterangan berbeda (Manual: ' . \$row['keterangan'] . ', ' . ucfirst(\$other_label) . ': ' . \$other['keterangan'] . ')';\n\t}\n\n\tif (buku_besar_compare_normalize_text(\$row['nama_akun']) === buku_besar_compare_normalize_text(\$other['nama_akun'])) {\n\t\t\$similar[] = 'Nama Akun';\n\t} else {\n\t\t\$diff_parts[] = 'Nama Akun berbeda (Manual: ' . \$row['nama_akun'] . ', ' . ucfirst(\$other_label) . ': ' . \$other['nama_akun'] . ')';\n\t}\n\n\t\$deb_r = buku_besar_compare_normalize_jumlah(\$row['debet']);\n\t\$deb_o = buku_besar_compare_normalize_jumlah(\$other['debet']);\n\tif (\$deb_r === \$deb_o) {\n\t\t\$similar[] = 'Debet';\n\t} else {\n\t\t\$diff_parts[] = 'Debet berbeda (Manual: ' . buku_besar_compare_format_jumlah_display(\$deb_r)\n\t\t\t. ', ' . ucfirst(\$other_label) . ': ' . buku_besar_compare_format_jumlah_display(\$deb_o) . ')';\n\t}\n\n\t\$kre_r = buku_besar_compare_normalize_jumlah(\$row['kredit']);\n\t\$kre_o = buku_besar_compare_normalize_jumlah(\$other['kredit']);\n\tif (\$kre_r === \$kre_o) {\n\t\t\$similar[] = 'Kredit';\n\t} else {\n\t\t\$diff_parts[] = 'Kredit berbeda (Manual: ' . buku_besar_compare_format_jumlah_display(\$kre_r)\n\t\t\t. ', ' . ucfirst(\$other_label) . ': ' . buku_besar_compare_format_jumlah_display(\$kre_o) . ')';\n\t}\n\n\t\$parts = array();\n\tif (count(\$similar) > 0) {\n\t\t\$parts[] = 'Field sama: ' . implode(', ', \$similar);\n\t}\n\tif (count(\$diff_parts) > 0) {\n\t\t\$parts[] = 'Field berbeda: ' . implode('; ', \$diff_parts);\n\t}\n\n\tif (count(\$parts) === 0) {\n\t\treturn 'Tidak ditemukan pasangan lengkap di data ' . \$other_label . ' (semua field compare harus sama).';\n\t}\n\n\treturn implode('; ', \$parts);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_manual_row_from_db\(\$row, \$map, \$default_tanggal = \'\'\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_manual_row_from_db(\$row, \$map, \$default_tanggal = '')\n{\n\t\$tanggal_raw = !empty(\$map['tanggal']) ? persediaan_compare_row_get(\$row, \$map['tanggal']) : \$default_tanggal;\n\t\$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal(\$tanggal_raw);\n\tif ((\$tanggal_norm === '' || \$tanggal_norm === '0000-00-00') && \$default_tanggal !== '') {\n\t\t\$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal(\$default_tanggal);\n\t}\n\n\treturn array(\n\t\t'tanggal' => \$tanggal_norm,\n\t\t'pl' => trim((string) (!empty(\$map['pl']) ? persediaan_compare_row_get(\$row, \$map['pl']) : '')),\n\t\t'kode' => trim((string) (!empty(\$map['kode']) ? persediaan_compare_row_get(\$row, \$map['kode']) : '')),\n\t\t'kode_akun' => trim((string) (!empty(\$map['kode_akun']) ? persediaan_compare_row_get(\$row, \$map['kode_akun']) : '')),\n\t\t'nama_akun' => trim((string) (!empty(\$map['nama_akun']) ? persediaan_compare_row_get(\$row, \$map['nama_akun']) : '')),\n\t\t'keterangan' => trim((string) (!empty(\$map['keterangan']) ? persediaan_compare_row_get(\$row, \$map['keterangan']) : '')),\n\t\t'debet' => buku_besar_compare_normalize_jumlah(!empty(\$map['debet']) ? persediaan_compare_row_get(\$row, \$map['debet']) : 0),\n\t\t'kredit' => buku_besar_compare_normalize_jumlah(!empty(\$map['kredit']) ? persediaan_compare_row_get(\$row, \$map['kredit']) : 0),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_online_row_from_db\(\$row\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_online_row_from_db(\$row)\n{\n\treturn array(\n\t\t'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset(\$row->tanggal) ? \$row->tanggal : ''),\n\t\t'pl' => isset(\$row->pl) ? trim((string) \$row->pl) : '',\n\t\t'kode' => isset(\$row->kode) ? trim((string) \$row->kode) : '',\n\t\t'kode_akun' => buku_besar_compare_row_get_kode_akun(\$row),\n\t\t'nama_akun' => isset(\$row->nama_akun) ? trim((string) \$row->nama_akun) : '',\n\t\t'keterangan' => isset(\$row->keterangan) ? trim((string) \$row->keterangan) : '',\n\t\t'debet' => buku_besar_compare_normalize_jumlah(buku_besar_compare_row_get_debet_raw(\$row)),\n\t\t'kredit' => buku_besar_compare_normalize_jumlah(isset(\$row->kredit) ? \$row->kredit : 0),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_pick_best_candidate\(\$row, \$candidates\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_pick_best_candidate(\$row, \$candidates)\n{\n\tif (!is_array(\$candidates) || count(\$candidates) === 0) {\n\t\treturn null;\n\t}\n\n\t\$best = null;\n\t\$best_score = -1;\n\tforeach (\$candidates as \$candidate) {\n\t\t\$score = 0;\n\t\tif (buku_besar_compare_normalize_text(\$row['nama_akun']) === buku_besar_compare_normalize_text(\$candidate['nama_akun'])) {\n\t\t\t\$score += 3;\n\t\t}\n\t\tif (buku_besar_compare_normalize_keterangan(\$row['keterangan']) === buku_besar_compare_normalize_keterangan(\$candidate['keterangan'])) {\n\t\t\t\$score += 3;\n\t\t}\n\t\tif (buku_besar_compare_normalize_jumlah(\$row['debet']) === buku_besar_compare_normalize_jumlah(\$candidate['debet'])) {\n\t\t\t\$score += 2;\n\t\t}\n\t\tif (buku_besar_compare_normalize_jumlah(\$row['kredit']) === buku_besar_compare_normalize_jumlah(\$candidate['kredit'])) {\n\t\t\t\$score += 2;\n\t\t}\n\t\tif (\$score > \$best_score) {\n\t\t\t\$best_score = \$score;\n\t\t\t\$best = \$candidate;\n\t\t}\n\t}\n\n\treturn \$best;\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_sort_display_rows\(\$a, \$b\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_sort_display_rows(\$a, \$b)\n{\n\t\$cmp = strcmp((string) \$a['tanggal'], (string) \$b['tanggal']);\n\tif (\$cmp !== 0) {\n\t\treturn \$cmp;\n\t}\n\t\$cmp = strcmp((string) \$a['kode_akun'], (string) \$b['kode_akun']);\n\tif (\$cmp !== 0) {\n\t\treturn \$cmp;\n\t}\n\treturn strcmp((string) \$a['kode'], (string) \$b['kode']);\n}",
	$content,
	1
);

$content = str_replace(
	"\$hard_key = buku_besar_compare_make_hard_key(\$item['tanggal'], \$item['bukti'], \$item['pl'], \$item['ref'], \$item['kode_rekening']);",
	"\$hard_key = buku_besar_compare_make_hard_key(\$item['tanggal'], \$item['pl'], \$item['kode'], \$item['kode_akun']);",
	$content
);

$content = preg_replace(
	'/function buku_besar_compare_item_to_row_cells\(\$item, \$no\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_item_to_row_cells(\$item, \$no)\n{\n\treturn array(\n\t\t\$no,\n\t\tisset(\$item['tanggal']) ? \$item['tanggal'] : '',\n\t\tisset(\$item['pl']) ? \$item['pl'] : '',\n\t\tisset(\$item['kode']) ? \$item['kode'] : '',\n\t\tisset(\$item['kode_akun']) ? \$item['kode_akun'] : '',\n\t\tisset(\$item['nama_akun']) ? \$item['nama_akun'] : '',\n\t\tisset(\$item['keterangan']) ? \$item['keterangan'] : '',\n\t\tisset(\$item['debet']) ? \$item['debet'] : '',\n\t\tisset(\$item['kredit']) ? \$item['kredit'] : '',\n\t\tisset(\$item['catatan']) ? \$item['catatan'] : '',\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_import_field_definitions\(\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_import_field_definitions()\n{\n\treturn array(\n\t\t'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),\n\t\t'pl' => array('label' => 'pl', 'required' => false, 'aliases' => array('pl', 'kode_pl', 'profit center')),\n\t\t'kode' => array('label' => 'kode', 'required' => false, 'aliases' => array('kode', 'no_kode', 'kode transaksi')),\n\t\t'kode_akun' => array('label' => 'kode_akun', 'required' => false, 'aliases' => array('kode_akun', 'kode akun', 'kode_rekening', 'kode_rek', 'uraian_kode_rekening')),\n\t\t'nama_akun' => array('label' => 'nama_akun', 'required' => false, 'aliases' => array('nama_akun', 'nama akun', 'rekening', 'nama_rekening', 'uraian')),\n\t\t'keterangan' => array('label' => 'keterangan', 'required' => false, 'aliases' => array('keterangan', 'ket', 'uraian transaksi', 'deskripsi')),\n\t\t'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),\n\t\t'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_analyze_import_column_map\(\$fields\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_analyze_import_column_map(\$fields)\n{\n\tif (!is_array(\$fields) || count(\$fields) === 0) {\n\t\treturn array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');\n\t}\n\n\t\$normalized = array();\n\tforeach (\$fields as \$field) {\n\t\t\$normalized[] = trim((string) \$field);\n\t}\n\n\t\$defs = buku_besar_compare_import_field_definitions();\n\t\$map = array(\n\t\t'tanggal' => penjualan_jurnal_compare_pick_tanggal_column(\$normalized),\n\t\t'pl' => persediaan_compare_pick_column(\$normalized, \$defs['pl']['aliases']),\n\t\t'kode' => persediaan_compare_pick_column(\$normalized, \$defs['kode']['aliases']),\n\t\t'kode_akun' => persediaan_compare_pick_column(\$normalized, \$defs['kode_akun']['aliases']),\n\t\t'nama_akun' => persediaan_compare_pick_column(\$normalized, \$defs['nama_akun']['aliases']),\n\t\t'keterangan' => persediaan_compare_pick_column(\$normalized, \$defs['keterangan']['aliases']),\n\t\t'debet' => persediaan_compare_pick_column(\$normalized, \$defs['debet']['aliases']),\n\t\t'kredit' => persediaan_compare_pick_column(\$normalized, \$defs['kredit']['aliases']),\n\t);\n\n\t\$missing_required = array();\n\t\$mapped = array();\n\tforeach (\$defs as \$key => \$def) {\n\t\tif (!empty(\$map[\$key])) {\n\t\t\t\$mapped[\$key] = \$map[\$key];\n\t\t} elseif (!empty(\$def['required'])) {\n\t\t\t\$missing_required[] = \$def['label'];\n\t\t}\n\t}\n\n\tif (empty(\$map['debet']) && empty(\$map['kredit'])) {\n\t\t\$missing_required[] = 'debet atau kredit';\n\t}\n\n\t\$ok = count(\$missing_required) === 0;\n\t\$message = '';\n\tif (!\$ok) {\n\t\t\$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', \$missing_required)\n\t\t\t. '. Kolom import minimal: tanggal, debet atau kredit.';\n\t}\n\n\treturn array(\n\t\t'ok' => \$ok,\n\t\t'map' => \$map,\n\t\t'mapped' => \$mapped,\n\t\t'missing_required' => \$missing_required,\n\t\t'fields' => \$normalized,\n\t\t'message' => \$message,\n\t);\n}",
	$content,
	1
);

$content = preg_replace(
	'/function buku_besar_compare_import_row_from_db\(\$row, \$map, \$ref_month = 0, \$ref_year = 0\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_import_row_from_db(\$row, \$map, \$ref_month = 0, \$ref_year = 0)\n{\n\t\$tanggal_raw = !empty(\$map['tanggal']) ? persediaan_compare_row_get(\$row, \$map['tanggal']) : '';\n\t\$tanggal_norm = buku_besar_compare_normalize_tanggal_for_db(\$tanggal_raw, \$ref_month, \$ref_year);\n\n\treturn array(\n\t\t'tanggal' => \$tanggal_norm,\n\t\t'pl' => trim((string) (!empty(\$map['pl']) ? persediaan_compare_row_get(\$row, \$map['pl']) : '')),\n\t\t'kode' => trim((string) (!empty(\$map['kode']) ? persediaan_compare_row_get(\$row, \$map['kode']) : '')),\n\t\t'kode_akun' => trim((string) (!empty(\$map['kode_akun']) ? persediaan_compare_row_get(\$row, \$map['kode_akun']) : '')),\n\t\t'nama_akun' => trim((string) (!empty(\$map['nama_akun']) ? persediaan_compare_row_get(\$row, \$map['nama_akun']) : '')),\n\t\t'keterangan' => trim((string) (!empty(\$map['keterangan']) ? persediaan_compare_row_get(\$row, \$map['keterangan']) : '')),\n\t\t'debet' => buku_besar_compare_normalize_jumlah(!empty(\$map['debet']) ? persediaan_compare_row_get(\$row, \$map['debet']) : 0),\n\t\t'kredit' => buku_besar_compare_normalize_jumlah(!empty(\$map['kredit']) ? persediaan_compare_row_get(\$row, \$map['kredit']) : 0),\n\t);\n}",
	$content,
	1
);

$content = str_replace('function buku_besar_compare_count_jurnal_umum_rows_for_bulan', 'function buku_besar_compare_count_buku_besar_rows_for_bulan', $content);
$content = str_replace('jurnal_umum_bulan_conflict', 'buku_besar_bulan_conflict', $content);
$content = str_replace('jurnal_umum_existing_count', 'buku_besar_existing_count', $content);
$content = str_replace('import jurnal umum', 'import buku besar', $content);
$content = str_replace('import jurnal_umum', 'import buku_besar', $content);
$content = str_replace('syarat kolom import jurnal umum', 'syarat kolom import buku besar', $content);
$content = str_replace('buku_besar_compare_count_jurnal_umum_rows_for_bulan', 'buku_besar_compare_count_buku_besar_rows_for_bulan', $content);

$content = preg_replace(
	'/function buku_besar_compare_load_online_all\(\$CI, \$bulan\)\s*\{.*?\n\treturn array\(/s',
	"function buku_besar_compare_load_online_all(\$CI, \$bulan)\n{\n\t\$range = persediaan_compare_bulan_to_date_range(\$bulan);\n\tif (\$range === null) {\n\t\treturn array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');\n\t}\n\n\t\$online_fields = buku_besar_compare_validate_online_table_detail(\$CI);\n\tif (empty(\$online_fields['ok'])) {\n\t\treturn array(\n\t\t\t'ok' => false,\n\t\t\t'message' => isset(\$online_fields['message']) ? \$online_fields['message'] : 'Struktur tabel online buku_besar tidak valid.',\n\t\t\t'field_validation' => array('online' => \$online_fields),\n\t\t);\n\t}\n\n\t\$month = (int) substr(\$bulan, 5, 2);\n\t\$year = (int) substr(\$bulan, 0, 4);\n\t\$col_db = \$online_fields['map']['debet'];\n\t\$col_db_sql = '`' . str_replace('`', '``', \$col_db) . '`';\n\n\t\$sql = \"SELECT DATE(tanggal) AS tanggal,\n\t\tCOALESCE(NULLIF(TRIM(pl), ''), '') AS pl,\n\t\tCOALESCE(NULLIF(TRIM(kode), ''), '') AS kode,\n\t\tCOALESCE(NULLIF(TRIM(kode_akun), ''), '') AS kode_akun,\n\t\tCOALESCE(NULLIF(TRIM(nama_akun), ''), '') AS nama_akun,\n\t\tCOALESCE(NULLIF(TRIM(keterangan), ''), '') AS keterangan,\n\t\tCOALESCE({\$col_db_sql}, 0) AS debet,\n\t\tCOALESCE(kredit, 0) AS kredit\n\t\tFROM buku_besar\n\t\tWHERE tanggal IS NOT NULL AND tanggal <> '0000-00-00'\n\t\tAND MONTH(tanggal) = ? AND YEAR(tanggal) = ?\n\t\tAND kode_akun IS NOT NULL AND TRIM(kode_akun) <> ''\n\t\tORDER BY tanggal, id\";\n\n\t\$list = array();\n\t\$by_full = array();\n\t\$by_hard = array();\n\t\$unprocessed = array();\n\t\$display_all = array();\n\n\tforeach (\$CI->db->query(\$sql, array(\$month, \$year))->result() as \$row) {\n\t\t\$item = buku_besar_compare_online_row_from_db(\$row);\n\t\t\$reasons = buku_besar_compare_row_unprocessed_reasons(\$item);\n\t\t\$display_all[] = buku_besar_compare_row_to_display(\n\t\t\t\$item,\n\t\t\tcount(\$reasons) > 0 ? ('Info: ' . implode(', ', \$reasons)) : ''\n\t\t);\n\n\t\tif (!buku_besar_compare_is_row_analyzable(\$item)) {\n\t\t\t\$unprocessed[] = buku_besar_compare_row_to_display(\$item, 'Online tidak terproses: ' . implode(', ', \$reasons));\n\t\t\tcontinue;\n\t\t}\n\n\t\t\$full_key = buku_besar_compare_make_full_key(\$item);\n\t\t\$hard_key = buku_besar_compare_make_hard_key(\$item['tanggal'], \$item['pl'], \$item['kode'], \$item['kode_akun']);\n\t\t\$item['_full_key'] = \$full_key;\n\t\t\$item['_hard_key'] = \$hard_key;\n\n\t\t\$list[] = \$item;\n\t\tif (!isset(\$by_full[\$full_key])) {\n\t\t\t\$by_full[\$full_key] = array();\n\t\t}\n\t\t\$by_full[\$full_key][] = \$item;\n\t\tif (!isset(\$by_hard[\$hard_key])) {\n\t\t\t\$by_hard[\$hard_key] = array();\n\t\t}\n\t\t\$by_hard[\$hard_key][] = \$item;\n\t}\n\n\treturn array(",
	$content,
	1
);

// CSV import column variables
$content = preg_replace(
	"/\\\$col_bukti = .*?\\\$col_kredit = .*?;/s",
	"\$col_pl = !empty(\$column_map['pl']) ? \$column_map['pl'] : 'pl';\n\t\$col_kode = !empty(\$column_map['kode']) ? \$column_map['kode'] : 'kode';\n\t\$col_kode_akun = !empty(\$column_map['kode_akun']) ? \$column_map['kode_akun'] : 'kode_akun';\n\t\$col_nama_akun = !empty(\$column_map['nama_akun']) ? \$column_map['nama_akun'] : 'nama_akun';\n\t\$col_keterangan = !empty(\$column_map['keterangan']) ? \$column_map['keterangan'] : 'keterangan';\n\t\$col_debet = !empty(\$column_map['debet']) ? \$column_map['debet'] : 'debet';\n\t\$col_kredit = !empty(\$column_map['kredit']) ? \$column_map['kredit'] : 'kredit';",
	$content,
	1
);

$content = str_replace(
	"foreach (array(\$col_tanggal, \$col_bukti, \$col_pl, \$col_ref, \$col_kode_rekening, \$col_rekening, \$col_debet, \$col_kredit) as \$required_col)",
	"foreach (array(\$col_tanggal, \$col_pl, \$col_kode, \$col_kode_akun, \$col_nama_akun, \$col_keterangan, \$col_debet, \$col_kredit) as \$required_col)",
	$content
);

$content = str_replace(
	"'message' => 'Header CSV tidak memenuhi syarat Buku Besar. Kolom wajib: tanggal, bukti, kode_rekening, debet atau kredit.',",
	"'message' => 'Header CSV tidak memenuhi syarat Buku Besar. Kolom wajib: tanggal, kode_akun, debet atau kredit.',",
	$content
);

$content = preg_replace(
	'/function buku_besar_compare_load_table_detail_for_bulan\(\$CI, \$table, \$bulan\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_load_table_detail_for_bulan(\$CI, \$table, \$bulan)\n{\n\tif (!preg_match('/^\\d{4}-\\d{2}\$/', (string) \$bulan)) {\n\t\treturn array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');\n\t}\n\n\t\$valid = buku_besar_compare_validate_import_table(\$CI, \$table);\n\tif (empty(\$valid['ok'])) {\n\t\treturn \$valid;\n\t}\n\n\t\$ref_year = (int) substr(\$bulan, 0, 4);\n\t\$ref_month = (int) substr(\$bulan, 5, 2);\n\t\$map = \$valid['map'];\n\t\$range = persediaan_compare_bulan_to_date_range(\$bulan);\n\t\$all_rows = \$CI->db->query('SELECT * FROM `' . \$table . '` ORDER BY id ASC')->result();\n\t\$items = array();\n\t\$no = 0;\n\n\tforeach ((array) \$all_rows as \$row) {\n\t\t\$item = buku_besar_compare_import_row_from_db(\$row, \$map, \$ref_month, \$ref_year);\n\t\tif (!buku_besar_compare_row_matches_bulan(\$item['tanggal'], \$bulan)) {\n\t\t\tcontinue;\n\t\t}\n\t\t\$no++;\n\t\t\$debet = (float) \$item['debet'];\n\t\t\$kredit = (float) \$item['kredit'];\n\t\t\$items[] = array(\n\t\t\t'no' => \$no,\n\t\t\t'tanggal' => pembelian_jurnal_compare_format_tanggal_display(\$item['tanggal']),\n\t\t\t'pl' => \$item['pl'],\n\t\t\t'kode' => \$item['kode'],\n\t\t\t'kode_akun' => \$item['kode_akun'],\n\t\t\t'nama_akun' => \$item['nama_akun'],\n\t\t\t'keterangan' => \$item['keterangan'],\n\t\t\t'debet' => \$debet > 0 ? buku_besar_compare_format_jumlah_display(\$debet) : '',\n\t\t\t'kredit' => \$kredit > 0 ? buku_besar_compare_format_jumlah_display(\$kredit) : '',\n\t\t\t'debet_raw' => \$debet,\n\t\t\t'kredit_raw' => \$kredit,\n\t\t);\n\t}\n\n\treturn array(\n\t\t'ok' => true,\n\t\t'headers' => array('No', 'Tanggal', 'PL', 'Kode', 'Kode Akun', 'Nama Akun', 'Keterangan', 'Debet', 'Kredit'),\n\t\t'rows' => \$items,\n\t\t'table' => \$table,\n\t\t'bulan' => \$bulan,\n\t\t'bulan_label' => \$range ? \$range['bulan_label'] : \$bulan,\n\t\t'total' => count(\$items),\n\t);\n}",
	$content,
	1
);

$content = str_replace(
	"\$CI->db->insert('buku_besar', \$built['data']);",
	"\$CI->load->model('Buku_besar_model');\n\t\t\$CI->Buku_besar_model->insert(\$built['data']);",
	$content
);

$content = preg_replace(
	'/function buku_besar_compare_export_table_detail_excel\(\$CI, \$table, \$bulan\)\s*\{.*?\n\}/s',
	"function buku_besar_compare_export_table_detail_excel(\$CI, \$table, \$bulan)\n{\n\t@set_time_limit(600);\n\t@ini_set('memory_limit', '512M');\n\t\$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'buku_besar_list'));\n\n\t\$result = buku_besar_compare_load_table_detail_for_bulan(\$CI, \$table, \$bulan);\n\tif (empty(\$result['ok'])) {\n\t\txlsBOF();\n\t\txlsWriteLabel(0, 0, isset(\$result['message']) ? \$result['message'] : 'Export Excel gagal.');\n\t\txlsEOF();\n\t\treturn;\n\t}\n\n\t\$headers = isset(\$result['headers']) ? \$result['headers'] : array();\n\t\$rows = isset(\$result['rows']) ? \$result['rows'] : array();\n\t\$bulan_label = isset(\$result['bulan_label']) ? \$result['bulan_label'] : \$bulan;\n\t\$styleHeader = 4;\n\t\$styleBorder = 3;\n\t\$styleRight = 8;\n\t\$styleLeft = 7;\n\n\txlsBOF();\n\txlsWriteLabelBold14(0, 0, 'Detail Tabel ' . \$table . ' — Bulan ' . \$bulan_label);\n\txlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count(\$rows));\n\n\t\$headerRow = 3;\n\tforeach (\$headers as \$col => \$label) {\n\t\txlsWriteCellStyle(\$headerRow, \$col, \$label, \$styleHeader);\n\t}\n\n\t\$rowNum = 4;\n\tforeach (\$rows as \$item) {\n\t\txlsWriteCellStyle(\$rowNum, 0, isset(\$item['no']) ? (int) \$item['no'] : 0, \$styleBorder);\n\t\txlsWriteCellStyle(\$rowNum, 1, isset(\$item['tanggal']) ? \$item['tanggal'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 2, isset(\$item['pl']) ? \$item['pl'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 3, isset(\$item['kode']) ? \$item['kode'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 4, isset(\$item['kode_akun']) ? \$item['kode_akun'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 5, isset(\$item['nama_akun']) ? \$item['nama_akun'] : '', \$styleLeft);\n\t\txlsWriteCellStyle(\$rowNum, 6, isset(\$item['keterangan']) ? \$item['keterangan'] : '', \$styleLeft);\n\t\t\$deb = isset(\$item['debet_raw']) ? (float) \$item['debet_raw'] : 0;\n\t\t\$kre = isset(\$item['kredit_raw']) ? (float) \$item['kredit_raw'] : 0;\n\t\tif (\$deb > 0) {\n\t\t\txlsWriteCellStyle(\$rowNum, 7, buku_besar_format_rupiah(\$deb), \$styleRight);\n\t\t} else {\n\t\t\txlsWriteCellStyle(\$rowNum, 7, '', \$styleBorder);\n\t\t}\n\t\tif (\$kre > 0) {\n\t\t\txlsWriteCellStyle(\$rowNum, 8, buku_besar_format_rupiah(\$kre), \$styleRight);\n\t\t} else {\n\t\t\txlsWriteCellStyle(\$rowNum, 8, '', \$styleBorder);\n\t\t}\n\t\t\$rowNum++;\n\t}\n\n\txlsEOF();\n}",
	$content,
	1
);

$content = str_replace("buku_besar_compare_row_get_kode_rekening", "buku_besar_compare_row_get_kode_akun", $content);
$content = str_replace('$item[\'kode_rekening\']', '$item[\'kode_akun\']', $content);
$content = str_replace('$row[\'kode_rekening\']', '$row[\'kode_akun\']', $content);
$content = str_replace('$item[\'rekening\']', '$item[\'nama_akun\']', $content);
$content = str_replace('$row[\'rekening\']', '$row[\'nama_akun\']', $content);
$content = str_replace('$item[\'bukti\']', '$item[\'kode\']', $content);
$content = str_replace('$row[\'bukti\']', '$row[\'kode\']', $content);

file_put_contents($path, $content);
echo "full patch done\n";
