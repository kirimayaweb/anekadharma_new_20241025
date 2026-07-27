<?php
$base = dirname(__DIR__);
$src = file_get_contents($base . '/application/views/anekadharma/buku_besar/adminlte310_buku_besar_list.php');

$c = $src;
$c = str_replace('buku_besar_list', 'bukubank_list', $c);
$c = str_replace('buku_besar_bulan_teks', 'bukubank_bulan_teks', $c);
$c = str_replace('buku_besar_format_rupiah', 'bukubank_format_rupiah', $c);
$c = str_replace('Buku_besar/', 'Bukubank/', $c);
$c = str_replace('Buku Besar', 'Buku Bank', $c);
$c = str_replace('BUKU BESAR', 'BUKU BANK', $c);
$c = str_replace('buku_besar', 'bukubank', $c);
$c = str_replace('bb-', 'bk-', $c);
$c = str_replace('compare-bb-', 'compare-bk-', $c);
$c = str_replace('compare_bb_', 'compare_bk_', $c);
$c = str_replace('table-compare-bb-', 'table-compare-bk-', $c);
$c = str_replace('tab-bb-', 'tab-bk-', $c);
$c = str_replace('panel-bb-', 'panel-bk-', $c);
$c = str_replace('buku-besar-', 'buku-bank-', $c);
$c = str_replace('buku-besar-tabs', 'buku-bank-tabs', $c);
$c = str_replace('data_Buku_besar', 'data_buku_bank', $c);
$c = str_replace('table-buku-besar-data', 'table-buku-bank-data', $c);
$c = str_replace('btn-buku-besar-excel', 'btn-buku-bank-excel', $c);
$c = str_replace('url_buku_besar_excel', 'url_bukubank_excel', $c);
$c = str_replace('bb_kode_akun', 'bk_bulan_ns', $c);
$c = str_replace('bb_bulan_ns', 'bk_bulan_ns', $c);
$c = str_replace('bb_active_tab', 'bk_active_tab', $c);
$c = str_replace('bbMainDt', 'bkMainDt', $c);
$c = str_replace('bbInitializing', 'bkInitializing', $c);
$c = str_replace('loadBukuBesarData', 'loadBukuBankData', $c);
$c = str_replace('refreshBukuBesarFromFilters', 'refreshBukuBankFromFilters', $c);
$c = str_replace('submitCariBukuBesarForm', 'submitCariBukuBankForm', $c);
$c = str_replace('reloadBukuBesarAfterImport', 'reloadBukuBankAfterImport', $c);
$c = str_replace('saveBbLocalStorage', 'saveBkLocalStorage', $c);
$c = str_replace('restoreBbLocalStorage', 'restoreBkLocalStorage', $c);
$c = str_replace('bbEscapeHtml', 'bkEscapeHtml', $c);
$c = str_replace('bbBuildAjaxErrorMessage', 'bkBuildAjaxErrorMessage', $c);
$c = str_replace('bbShowSaveError', 'bkShowSaveError', $c);
$c = str_replace('buildMainDtRows', 'buildMainDtRowsBk', $c);

// Remove kode akun filter block from header
$c = preg_replace(
    '/<form id="form-cari-buku-besar".*?<\/form>/s',
    '<div class="row justify-content-end align-items-center">
                                    <div class="col-auto mb-2 mb-md-0">
                                        <label for="bulan_ns" class="small mb-1">Pilih Bulan</label>
                                        <input type="month" class="form-control form-control-sm" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, \'UTF-8\'); ?>">
                                    </div>
                                    <div class="col-auto mb-2 mb-md-0">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm btn-flat" id="btn-cari-bk">
                                            <i class="fa fa-search" aria-hidden="true"></i> Cari
                                        </button>
                                    </div>
                                    <div class="col-auto mb-2 mb-md-0">
                                        <?php echo anchor(site_url(\'Bukubank/create\'), \'Input Buku Bank\', \'class="btn btn-success btn-sm"\'); ?>
                                    </div>
                                </div>',
    $c,
    1
);

// Tab 1 table headers
$c = preg_replace(
    '/<thead>\s*<tr>\s*<th>No<\/th>\s*<th>Tanggal<\/th>\s*<th>PL<\/th>.*?<\/tr>\s*<\/thead>/s',
    '<thead>
                                            <tr>
                                                <th rowspan="2" style="text-align:center">No</th>
                                                <th rowspan="2" style="text-align:center">Action</th>
                                                <th rowspan="2">Tanggal</th>
                                                <th colspan="2" style="text-align:center">Rekening</th>
                                                <th rowspan="2">Keterangan</th>
                                                <th rowspan="2">Kode</th>
                                                <th rowspan="2">Debet</th>
                                                <th rowspan="2">Kredit</th>
                                                <th rowspan="2">Saldo</th>
                                            </tr>
                                            <tr>
                                                <th>Bank</th>
                                                <th>Nomor rekening</th>
                                            </tr>
                                        </thead>',
    $c,
    1
);

// Tab 1 tbody PHP loop
$c = preg_replace(
    '/<tbody>\s*<\?php foreach \(\$data_buku_bank as \$list_data\) \{ \?>.*?<\/tbody>/s',
    '<tbody>
                                            <?php foreach ($data_buku_bank as $list_data) { ?>
                                            <tr>
                                                <td><?php echo (int) $list_data[\'no\']; ?></td>
                                                <td style="text-align:left">
                                                    <?php
                                                    echo anchor(site_url(\'Bukubank/update/\' . $list_data[\'id\']), \'<i class="fa fa-pencil-square-o">Ubah</i>\', array(\'title\' => \'edit\', \'class\' => \'btn btn-warning btn-sm\'));
                                                    echo \' \';
                                                    echo anchor(site_url(\'Bukubank/delete/\' . $list_data[\'id\']), \'<i class="fa fa-trash-o">Hapus</i>\', \'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\\\'Anda Yakin Akan Menghapus Data ini ?\\\')"\');
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($list_data[\'tanggal\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data[\'bank\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data[\'norek\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data[\'keterangan\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($list_data[\'kode\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data[\'debet_display\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data[\'kredit_display\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data[\'saldo_display\'], ENT_QUOTES, \'UTF-8\'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>',
    $c,
    1
);

// Tab 1 tfoot
$c = preg_replace(
    '/<tfoot>\s*<tr class="bb-total-row">.*?<\/tfoot>/s',
    '<tfoot>
                                            <tr class="bk-total-row">
                                                <th colspan="7" class="text-right">JUMLAH DEBET / KREDIT / SALDO</th>
                                                <th class="text-right bk-total-debet"><?php echo bukubank_format_rupiah(isset($total_debet) ? $total_debet : 0, true); ?></th>
                                                <th class="text-right bk-total-kredit"><?php echo bukubank_format_rupiah(isset($total_kredit) ? $total_kredit : 0, true); ?></th>
                                                <th class="text-right bk-total-saldo"><?php echo bukubank_format_rupiah(isset($total_saldo) ? $total_saldo : 0, true); ?></th>
                                            </tr>
                                        </tfoot>',
    $c,
    1
);

// Compare tab description
$c = str_replace(
    'Bandingkan data buku besar online (<strong>bukubank</strong>) dengan tabel manual hasil upload CSV.
                                    Kolom compare: <strong>tanggal, PL, kode, kode_akun, nama_akun, keterangan, debet, kredit</strong>.',
    'Bandingkan data buku bank online (<strong>bukubank</strong>) dengan tabel manual hasil upload CSV.
                                    Kolom compare: <strong>tanggal, bank, norek, keterangan, kode, debet, kredit</strong>.',
    $c
);

$c = str_replace('Proses Simpan Data ke Tabel Buku_besar', 'Proses Simpan Data ke Tabel Utama : Buku Bank', $c);
$c = str_replace('bukubank.', 'bukubank (tabel utama).', $c);

// Compare datatable headers
$c = preg_replace(
    '/<th>No<\/th><th>Tanggal<\/th><th>PL<\/th><th>Kode<\/th>\s*<th>Kode Akun<\/th><th>Nama Akun<\/th><th>Keterangan<\/th>\s*<th>Debet<\/th><th>Kredit<\/th><th>Catatan<\/th>/',
    '<th>No</th><th>Tanggal</th><th>Bank</th><th>Norek</th><th>Keterangan</th><th>Kode</th><th>Debet</th><th>Kredit</th><th>Saldo</th><th>Catatan</th>',
    $c
);

$c = preg_replace(
    '/<th>No<\/th><th>Tanggal<\/th><th>PL<\/th><th>Kode<\/th>\s*<th>Kode Akun<\/th><th>Nama Akun<\/th><th>Keterangan<\/th>\s*<th>Debet<\/th><th>Kredit<\/th>/',
    '<th>No</th><th>Tanggal</th><th>Bank</th><th>Norek</th><th>Keterangan</th><th>Kode</th><th>Debet</th><th>Kredit</th><th>Saldo</th>',
    $c
);

// Compare sections subtitles
$c = str_replace('Data bukubank bulan terpilih', 'Data bukubank (online) bulan terpilih', $c);
$c = str_replace('Tanggal, PL, kode, kode_akun, nama_akun, keterangan, debet, kredit sama', 'Tanggal, bank, norek, kode, keterangan, debet, kredit sama', $c);

// JS buildMainDtRows
$c = preg_replace(
    '/function buildMainDtRows\(items\) \{.*?\}/s',
    "function buildMainDtRowsBk(items) {\n        return (items || []).map(function(it) {\n            var action = '<a href=\"' + <?php echo json_encode(site_url('Bukubank/update/')); ?> + it.id + '\" class=\"btn btn-warning btn-sm\"><i class=\"fa fa-pencil-square-o\">Ubah</i></a> '\n                + '<a href=\"' + <?php echo json_encode(site_url('Bukubank/delete/')); ?> + it.id + '\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm(\\'Anda Yakin Akan Menghapus Data ini ?\\')\"><i class=\"fa fa-trash-o\">Hapus</i></a>';\n            return [\n                it.no || '',\n                action,\n                it.tanggal || '',\n                it.bank || '',\n                it.norek || '',\n                it.keterangan || '',\n                it.kode || '',\n                it.debet_display || '',\n                it.kredit_display || '',\n                it.saldo_display || ''\n            ];\n        });\n    }",
    $c,
    1
);

$c = str_replace('buildMainDtRows(res.rows)', 'buildMainDtRowsBk(res.rows)', $c);
$c = str_replace('$t.find(\'tfoot .bb-total-debet\')', '$t.find(\'tfoot .bk-total-debet\')', $c);
$c = str_replace('$t.find(\'tfoot .bb-total-kredit\')', '$t.find(\'tfoot .bk-total-kredit\')', $c);
$c = str_replace('jQuery(\'#bb-bulan-label\')', 'jQuery(\'#bk-bulan-label\')', $c);

// Remove kode_akun from ajax and select2
$c = preg_replace('/if \(jQuery\.fn\.select2 && jQuery\(\'#kode_akun\'\)\.length\) \{.*?\}\s*\n\s*restoreBkLocalStorage/s', 'restoreBkLocalStorage', $c, 1);
$c = preg_replace('/if \(jQuery\.fn\.select2 && jQuery\(\'#kode_akun\'\)\.hasClass\(\'select2-hidden-accessible\'\)\) \{.*?\}\s*\n\s*syncCompareFromBulanNs/s', 'syncCompareFromBulanNs', $c, 1);
$c = str_replace("kode_akun: jQuery('#kode_akun').val() || ''", '', $c);
$c = str_replace("jQuery('#kode_akun').on('change', function() {\n            if (bkInitializing) return;\n            refreshBukuBankFromFilters();\n        });", '', $c);

$c = str_replace('jQuery(\'#btn-cari-bb\')', 'jQuery(\'#btn-cari-bk\')', $c);
$c = str_replace('form-cari-buku-besar', 'form-cari-buku-bank', $c);

// buildRows for compare
$c = preg_replace(
    '/function buildRows\(items\) \{.*?\}/s',
    "function buildRows(items) {\n            return (items || []).map(function(it, i) {\n                return [\n                    i + 1,\n                    it.tanggal || '',\n                    it.bank || '',\n                    it.norek || '',\n                    it.keterangan || '',\n                    it.kode || '',\n                    fmtAmtCell(it.debet, 'debet'),\n                    fmtAmtCell(it.kredit, 'kredit'),\n                    fmtAmtCell(it.saldo, 'saldo'),\n                    it.catatan ? '<span class=\"text-catatan\">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''\n                ];\n            });\n        }",
    $c,
    1
);

$c = preg_replace(
    '/function buildDetailRows\(items\) \{.*?\}/s',
    "function buildDetailRows(items) {\n            return (items || []).map(function(it) {\n                return [\n                    it.no || '',\n                    it.tanggal || '',\n                    it.bank || '',\n                    it.norek || '',\n                    it.keterangan || '',\n                    it.kode || '',\n                    fmtAmtCell(it.debet, 'debet'),\n                    fmtAmtCell(it.kredit, 'kredit'),\n                    fmtAmtCell(it.saldo, 'saldo')\n                ];\n            });\n        }",
    $c,
    1
);

// buildTabelInfoHtml mapping keys
$c = str_replace("['tanggal', 'pl', 'kode', 'kode_akun', 'nama_akun', 'keterangan', 'debet', 'kredit']", "['tanggal', 'bank', 'norek', 'keterangan', 'kode', 'debet', 'kredit', 'saldo']", $c);
$c = str_replace('Siap Diproses ke Buku Besar', 'Siap Diproses ke Buku Bank', $c);
$c = str_replace('res.bukubank_bulan_conflict', 'res.bukubank_bulan_conflict', $c);

// CSV success auto close 2 sec
$c = str_replace(
    "Swal.fire({ icon: 'success', title: 'Import CSV Berhasil', html: 'Tabel <strong>' + (res.table || '') + '</strong> — ' + (res.rows || 0) + ' baris.' });",
    "Swal.fire({ icon: 'success', title: 'Upload CSV Sukses', html: 'Tabel <strong>' + (res.table || '') + '</strong> — ' + (res.rows || 0) + ' baris.', timer: 2000, timerProgressBar: true, showConfirmButton: true });",
    $c
);

// Import button text updates in JS
$c = str_replace('Proses Simpan Data ke Tabel Buku_besar', 'Proses Simpan Data ke Tabel Utama : Buku Bank', $c);
$c = str_replace('Menyimpan data ke tabel <strong>bukubank</strong>', 'Menyimpan data ke tabel <strong>bukubank</strong> (utama)', $c);

// drawCallback totals - add saldo column colspan fix
$c = str_replace('colspan="7"', 'colspan="6"', $c);

// CSS class renames
$c = str_replace('.bb-dt-wrap', '.bk-dt-wrap', $c);
$c = str_replace('.bb-main-dt', '.bk-main-dt', $c);
$c = str_replace('.bb-total-row', '.bk-total-row', $c);

// Remove uuid/kode akun php vars at top
$c = preg_replace('/if \(!isset\(\$uuid_kode_akun\)\).*?if \(!isset\(\$list_kode_akun\)\).*?\}\s*\n/s', '', $c, 1);

// Fix header label id
$c = str_replace('id="bk-bulan-label"', 'id="bk-bulan-label"', $c);
$c = str_replace('id="bb-bulan-label"', 'id="bk-bulan-label"', $c);

// Remove url_form_action and url_cari unused
$c = preg_replace('/\$url_cari = .*?\n/', '', $c, 1);
$c = preg_replace('/\$url_form_action = .*?\n/', '', $c, 1);

file_put_contents($base . '/application/views/anekadharma/buku_bank/adminlte310_buku_bank_list.php', $c);
echo "View generated\n";
