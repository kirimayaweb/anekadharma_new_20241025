<?php
/**
 * Blueprint Pengembangan Aneka Dharma (3 Mei – 7 Juni 2026) via Cursor AI
 * Jalankan: php tools/generate_blueprint_update_3mei.php
 */

require_once __DIR__ . '/docx_writer.php';

$doc = new SimpleDocxWriter();
$today = date('d F Y');

// ═══ SAMPUL ═══════════════════════════════════════════════════════════════════
$doc->title('BLUEPRINT PENGEMBANGAN', 1);
$doc->title('APLIKASI ANEKA DHARMA', 1);
$doc->paragraph('Dokumentasi Proses Pengembangan dengan Cursor AI', ['italic' => true, 'size' => 24]);
$doc->paragraph('');
$doc->paragraph('Periode: 3 Mei 2026 – 7 Juni 2026');
$doc->paragraph('Platform: Cursor IDE + AI Agent + Git + CodeIgniter 3.1.5');
$doc->paragraph('Proyek: anekadharma_new_20241025');
$doc->paragraph('Tanggal Dokumen: ' . $today);
$doc->paragraph('');
$doc->paragraph('Dokumen ini mencatat seluruh proses pengembangan aplikasi Aneka Dharma menggunakan bantuan AI Cursor — meliputi permintaan user di chat, implementasi kode, commit Git, diagram alur, dan cuplikan kode yang diperbarui.');
$doc->pageBreak();

// ═══ DAFTAR ISI ═══════════════════════════════════════════════════════════════
$doc->title('DAFTAR ISI', 2);
foreach ([
    '1. Pendahuluan & Metodologi Cursor AI',
    '2. Ringkasan Eksekutif Pengembangan',
    '3. Diagram Alur Pengembangan',
    '4. Timeline Kronologis (Mingguan)',
    '5. Modul Persediaan — Konsep Baru & Recalculate',
    '6. Modul Pembelian',
    '7. Modul Penjualan & Cetak Faktur',
    '8. Keamanan Login, MFA & Auth Guard',
    '9. Master Data (Konsumen, Nama Barang)',
    '10. Dokumentasi & Blueprint Sistem',
    '11. Sesi Chat Cursor AI',
    '12. Daftar Commit Git',
    '13. File yang Diubah (Ringkasan)',
    '14. Cuplikan Kode Penting',
    '15. Diagram Integrasi Penjualan–Persediaan',
    '16. Catatan Programming & Rekomendasi',
] as $item) {
    $doc->bullet($item);
}
$doc->pageBreak();

// ═══ 1. PENDAHULUAN ═══════════════════════════════════════════════════════════
$doc->title('1. Pendahuluan & Metodologi Cursor AI', 2);
$doc->paragraph('Pengembangan aplikasi Aneka Dharma pada periode 3 Mei – 7 Juni 2026 dilakukan menggunakan Cursor IDE dengan fitur AI Agent. Metodologi kerja mengikuti pola iteratif: user mengajukan permintaan dalam bahasa natural → AI menganalisis codebase → AI mengimplementasikan perubahan kode → user menguji → commit Git.');
$doc->title('1.1 Alur Kerja Cursor AI', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['1. User Request', 'Permintaan fitur/bugfix via chat Cursor', '1F4E79'],
        ['2. AI Explore Codebase', 'Semantic search, baca controller/model/view', '2E75B6'],
        ['3. AI Implementasi', 'Edit file PHP, view, helper, config', '4472C4'],
        ['4. User Testing', 'Uji di browser localhost/xampp', '548235'],
        ['5. Git Commit', 'Commit ke repository dengan pesan deskriptif', '7030A0'],
        ['6. Iterasi', 'Perbaikan lanjutan berdasarkan feedback user', 'ED7D31'],
    ], 1.2, 4.0, 0.58, 0.24);
});
$doc->title('1.2 Stack Teknologi', 3);
$doc->table(['Komponen', 'Detail'], [
    ['IDE', 'Cursor (AI Agent Mode)'],
    ['Backend', 'PHP 7.4 + CodeIgniter 3.1.5'],
    ['Database', 'MySQL/MariaDB — anekadharma_db'],
    ['Frontend', 'AdminLTE 3.1.0 + jQuery + DataTables + Select2'],
    ['Version Control', 'Git (GitHub — anekadharma)'],
    ['Server Lokal', 'XAMPP 7.4 — localhost/anekadharma_new_20241025'],
    ['Dokumentasi', 'Word DOCX via PHP generator + Audit Keamanan Login'],
]);
$doc->pageBreak();

// ═══ 2. RINGKASAN EKSEKUTIF ═══════════════════════════════════════════════════
$doc->title('2. Ringkasan Eksekutif Pengembangan', 2);
$doc->table(['Metrik', 'Nilai'], [
    ['Periode pengembangan', '3 Mei 2026 – 7 Juni 2026 (36 hari)'],
    ['Total commit Git', '53 commit'],
    ['File diubah (kumulatif)', '87 file'],
    ['Baris ditambahkan', '+53.095 baris'],
    ['Baris dihapus', '-2.654 baris'],
    ['Sesi chat Cursor terdokumentasi', '10+ sesi transcript'],
    ['Modul utama diupdate', 'Persediaan, Pembelian, Penjualan, Login, Master Data'],
    ['Fitur keamanan baru', 'CSRF, XSS, Rate Limit, MFA WhatsApp, Auth Hook'],
    ['Dokumen audit keamanan', 'Audit_Keamanan_Login_AnekaDharma.doc'],
]);
$doc->title('2.1 Pencapaian Utama', 3);
$doc->bullet('Redesign total modul Persediaan — konsep data bulanan + generate + recalculate otomatis');
$doc->bullet('Integrasi Penjualan → Persediaan — setiap transaksi penjualan memperbarui stok multi-unit');
$doc->bullet('Modal input barang pembelian — UX tanpa redirect halaman terpisah');
$doc->bullet('Cetak faktur penjualan dot matrix — layout PERUMDAM TIRTA, 8 baris, 2 faktur/folio');
$doc->bullet('Keamanan login enterprise — MFA WhatsApp untuk admin, rate limiting, CSRF');
$doc->bullet('Export Excel pembelian dengan filter tanggal dan format rupiah');
$doc->bullet('Blueprint dokumentasi sistem lengkap dalam format Word');
$doc->pageBreak();

// ═══ 3. DIAGRAM ALUR PENGEMBANGAN ═════════════════════════════════════════════
$doc->title('3. Diagram Alur Pengembangan per Fase', 2);
$doc->diagram(function ($d) {
    $cx = DiagramEngine::in(2.8);
    $w = DiagramEngine::in(2.5);
    $h = DiagramEngine::in(0.55);
    $y = DiagramEngine::in(0.05);
    $d->roundRect($cx - $w / 2, $y, $w, $h, ['FASE 1: Mei 7–12', 'Persediaan, Pembelian, Cetak PDF'], '2E75B6');
    $y += $h + DiagramEngine::in(0.25);
    $d->arrowDown($cx, $y - DiagramEngine::in(0.25), DiagramEngine::in(0.22));
    $d->roundRect($cx - $w / 2, $y, $w, $h, ['FASE 2: Mei 13–21', 'Kategori, Excel, Kode Akun, Konsep Persediaan'], '548235');
    $y += $h + DiagramEngine::in(0.25);
    $d->arrowDown($cx, $y - DiagramEngine::in(0.25), DiagramEngine::in(0.22));
    $d->roundRect($cx - $w / 2, $y, $w, $h, ['FASE 3: Mei 23–26', 'Login, Penjualan↔Persediaan, Recalculate'], '7030A0');
    $y += $h + DiagramEngine::in(0.25);
    $d->arrowDown($cx, $y - DiagramEngine::in(0.25), DiagramEngine::in(0.22));
    $d->roundRect($cx - $w / 2, $y, $w, $h, ['FASE 4: Mei 29 – Jun 7', 'Konsumen, Keamanan, Blueprint Word'], 'C00000');
});
$doc->pageBreak();

// ═══ 4. TIMELINE ══════════════════════════════════════════════════════════════
$doc->title('4. Timeline Kronologis (Mingguan)', 2);

$doc->title('4.1 Minggu 1: 7–13 Mei 2026', 3);
$doc->table(['Tanggal', 'Commit / Chat', 'Perubahan'], [
    ['7 Mei', 'Git: Persediaan combobox, cetak PDF, Stock', 'Modul persediaan dasar + export PDF'],
    ['7 Mei', 'Git: test github dari server', 'Setup repository GitHub'],
    ['8 Mei', 'Chat 6daa2960 + Git', 'Modal input barang pembelian, auto satuan/harga'],
    ['9–11 Mei', 'Git + Chat 506879fa', 'Cetak PDF penjualan, layout faktur dot matrix'],
    ['12–13 Mei', 'Git', 'Setting data barang penjualan, cetak penjualan'],
]);

$doc->title('4.2 Minggu 2: 14–21 Mei 2026', 3);
$doc->table(['Tanggal', 'Commit / Chat', 'Perubahan'], [
    ['17 Mei', 'Chat 6bd4509a', 'Min baris cetak 8, font Courier, 2 faktur/folio'],
    ['18 Mei', 'Git + Chat c98c618c', 'Kategori barang, export Excel pembelian, pembelian jasa'],
    ['19 Mei', 'Git + Chat 68faf093', 'Setting kode akun — optimasi performa 300 record'],
    ['20 Mei', 'Git + Chat d426eb11', 'KONSEP PERSEDIAAN + tombol Recalculate UUID'],
    ['21 Mei', 'Git (6 commit)', 'Pembelian barang/jasa, sys_nama_barang, konsep persediaan'],
]);

$doc->title('4.3 Minggu 3: 22–28 Mei 2026', 3);
$doc->table(['Tanggal', 'Commit / Chat', 'Perubahan'], [
    ['23 Mei', 'Git + Chat febe9afb', 'Login masuk, UPDATE PENJUALAN KE PERSEDIAAN'],
    ['26 Mei', 'Git (8 commit)', 'Konsep persediaan final, display helper, date picker jual'],
    ['26 Mei', 'Chat 5b57155a', 'Perbaikan filter bulan April 2026 kosong'],
    ['26–28 Mei', 'Chat febe9afb', 'Implementasi keamanan login: CSRF, MFA, rate limit'],
]);

$doc->title('4.4 Minggu 4–5: 29 Mei – 7 Juni 2026', 3);
$doc->table(['Tanggal', 'Commit / Chat', 'Perubahan'], [
    ['29 Mei', 'Git + Chat 4e5eec0a', 'Update konsumen CRUD modal, cetak penjualan'],
    ['3 Juni', 'Git (2 commit)', 'Update pembelian dan penjualan'],
    ['6 Juni', 'Git', 'Update persediaan + pembelian_persediaan_helper (+1573 baris)'],
    ['7 Juni', 'Chat 7e65ed6f', 'Blueprint Word lengkap + diagram shape + dokumen ini'],
]);
$doc->pageBreak();

// ═══ 5. PERSEDIAAN ════════════════════════════════════════════════════════════
$doc->title('5. Modul Persediaan — Konsep Baru & Recalculate', 2);
$doc->paragraph('Perubahan terbesar dalam periode ini adalah redesign total modul Persediaan. Konsep baru menggunakan data persediaan per bulan (tanggal_beli) dengan kolom multi-unit (Sekretariat, CETAK, GRAFIKITA, medis, pu_outsor, dll.).');
$doc->title('5.1 Catatan Programming (Konsep Bisnis)', 3);
$doc->codeBlock(
    "CATATAN BESAR UPDATE SISTEM PERSEDIAAN:\n"
    . "1. Model data tabel persediaan konsep baru (laporan bulanan Aneka Dharma).\n"
    . "   Jika ada perubahan pembelian/penjualan di bulan lampau → WAJIB rekalkulasi\n"
    . "   dari bulan yang diupdate sampai bulan aktif (berantai).\n"
    . "2. Nama unit harus diseragamkan antara kolom persediaan dan penjualan\n"
    . "   agar proses recalculate tidak error."
);
$doc->title('5.2 Diagram Alur Recalculate Persediaan', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Perubahan Pembelian/Penjualan', 'Di bulan lampau (retroaktif)', 'C00000'],
        ['Trigger Recalculate', 'Tombol / otomatis setelah simpan transaksi', 'ED7D31'],
        ['GENERATE_PERSEDIAN_BULAN', 'Salin SA, hitung beli dari tbl_pembelian', '2E75B6'],
        ['Sync Penjualan', 'Kurangi stok unit terkait dari tbl_penjualan', '4472C4'],
        ['Update Bulan Berikutnya', 'Rantai rekalkulasi bulan+1 s/d bulan aktif', '548235'],
        ['Log History', 'persediaan_gen_recalc_log / _item', '7030A0'],
    ], 1.0, 4.2, 0.56, 0.22);
});
$doc->title('5.3 File Utama yang Diubah', 3);
$doc->table(['File', 'Perubahan'], [
    ['Persediaan.php (~3400 baris)', 'Generate bulan, recalculate, export Excel/PDF, batch'],
    ['pembelian_persediaan_helper.php (~7000+ baris)', 'Logika bisnis inti persediaan-pembelian-penjualan'],
    ['persediaan_display_helper.php', 'Label kolom unit, mapping sys_unit → kolom persediaan'],
    ['Persediaan_model.php', 'Query filter rentang tanggal bulan (fix April 2026)'],
    ['adminlte310_persediaan_list.php', 'UI tabs Generate/Recalculate, DataTables, export'],
    ['generate_persediaan_bulan_process.php', 'Proses generate persediaan bulan'],
    ['recalculate_persediaan_penjualan_process.php', 'Proses recalculate dari penjualan'],
    ['database/sql/persediaan_gen_recalc_history.sql', 'Tabel log recalculate'],
]);
$doc->pageBreak();

// ═══ 6. PEMBELIAN ═════════════════════════════════════════════════════════════
$doc->title('6. Modul Pembelian', 2);
$doc->title('6.1 Diagram Alur Modal Input Barang (Chat 6daa2960)', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Form SPOP Pembelian', 'Tbl_pembelian/create', '2E75B6'],
        ['Klik "Input Barang Baru"', 'Buka modal nested (bukan redirect)', '4472C4'],
        ['Isi Data Barang', 'Nama, kategori, satuan, harga — format ribuan', '548235'],
        ['AJAX Simpan', 'Sys_nama_barang → insert sys_nama_barang', 'ED7D31'],
        ['Auto-fill Form SPOP', 'Satuan & harga otomatis terisi', '7030A0'],
        ['Link ke Persediaan', 'uuid_persediaan setelah SPOP disimpan', '1F4E79'],
    ], 1.2, 3.8, 0.56, 0.22);
});
$doc->title('6.2 Fitur Pembelian yang Diimplementasikan', 3);
$doc->bullet('Modal input barang baru tanpa leave page (8 Mei — Chat 6daa2960)');
$doc->bullet('Export Excel pembelian dengan filter tanggal awal/akhir (18 Mei)');
$doc->bullet('Setting kode akun pembelian — optimasi batch query (19 Mei — Chat 68faf093)');
$doc->bullet('UI pembelian jasa — border kuning list, border biru form (Chat 6bd4509a)');
$doc->bullet('Kolom kategori barang di halaman Stock (18 Mei — Chat c98c618c)');
$doc->title('6.3 File Diubah', 3);
$doc->table(['File', 'Fungsi'], [
    ['Tbl_pembelian.php', 'Controller utama SPOP, jurnal, cetak, export'],
    ['adminlte310_tbl_pembelian_form.php', 'Form input SPOP + modal barang'],
    ['sys_nama_barang_form_pembelian_modal.php', 'Modal tambah barang via AJAX'],
    ['adminlte310_tbl_pembelian_setting_kode_akun.php', 'Setting kode akun per SPOP'],
    ['exportexcel_helper.php', 'Export Excel dengan format rupiah'],
    ['Tbl_pembelian_jasa.php + views', 'Modul pembelian jasa mirror pembelian barang'],
]);
$doc->pageBreak();

// ═══ 7. PENJUALAN ═════════════════════════════════════════════════════════════
$doc->title('7. Modul Penjualan & Cetak Faktur', 2);
$doc->title('7.1 Spesifikasi Cetak Faktur Dot Matrix', 3);
$doc->paragraph('Berdasarkan sesi chat Cursor (506879fa, 6bd4509a, c98c618c), layout cetak penjualan disesuaikan untuk printer dot matrix:');
$doc->bullet('Margin 0.5 cm, font Courier New seragam');
$doc->bullet('Box header PERUMDAM TIRTA MALANG');
$doc->bullet('Minimum 8 baris item (padding baris kosong)');
$doc->bullet('2 faktur per folio (dot matrix)');
$doc->bullet('Auto-shrink teks nama konsumen panjang');
$doc->bullet('Total penjualan ditampilkan italic');
$doc->title('7.2 Integrasi Penjualan → Persediaan', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Buat Order Penjualan', 'Tbl_penjualan/create', '4472C4'],
        ['Modal Pilih Barang', 'list_persediaan_penjualan_ajax', '2E75B6'],
        ['Kurangi Stok Unit', 'Kolom CETAK/GRAFIKITA/Sekretariat di persediaan', '548235'],
        ['Simpan tbl_penjualan', 'nmrkirim, konsumen, kode_akun', '7030A0'],
        ['Trigger Recalculate', 'Jika bulan lampau → rantai recalculate', 'C00000'],
        ['Cetak Surat Jalan PDF', 'adminlte310_cetak_penjualan.php', 'ED7D31'],
    ], 1.2, 3.8, 0.56, 0.22);
});
$doc->title('7.3 File Diubah', 3);
$doc->table(['File', 'Perubahan'], [
    ['Tbl_penjualan.php', 'CRUD, rekap, export Excel, integrasi persediaan'],
    ['adminlte310_cetak_penjualan.php', 'Layout cetak faktur dot matrix'],
    ['adminlte310_tbl_penjualan_form.php', 'Date picker jual, input barang modal'],
    ['adminlte310_tbl_penjualan_form_input_barang.php', 'Modal pilih barang dari persediaan'],
    ['adminlte310_tbl_penjualan_list_rekap_data.php', 'Rekap data penjualan per unit/konsumen'],
    ['_modal_pilih_barang_penjualan_fragment.php', 'Fragment AJAX modal barang'],
]);
$doc->pageBreak();

// ═══ 8. LOGIN ═════════════════════════════════════════════════════════════════
$doc->title('8. Keamanan Login, MFA & Auth Guard', 2);
$doc->paragraph('Sesi chat febe9afb (23–28 Mei – 7 Juni) melakukan audit dan implementasi keamanan login enterprise-grade.');
$doc->title('8.1 Diagram Keamanan Login', 3);
$doc->diagram(function ($d) {
    $d->loginFlow();
});
$doc->title('8.2 Fitur Keamanan Diimplementasikan', 3);
$doc->table(['Fitur', 'File', 'Detail'], [
    ['CSRF Token', 'login_security_helper.php', 'Token unik per form POST login'],
    ['XSS Escape', 'login_security_helper.php', 'Sanitasi input email, password, OTP'],
    ['Rate Limiting', 'login_security.php', 'Max 10 percobaan, lockout 15 menit'],
    ['MFA WhatsApp', 'Anekadharmamasuk.php', 'OTP 6 digit, level admin 1 & 99'],
    ['HTTPS Force', 'login_security.php', 'Redirect HTTPS di production'],
    ['Auth Hook Global', 'auth_hook.php, auth_public.php', 'Guard otomatis semua controller'],
    ['Pesan Generik', 'login_security.php', 'Anti user enumeration'],
    ['Reset Password WA', 'Anekadharmamasuk.php', 'Forgot password via WhatsApp'],
]);
$doc->title('8.3 Konfigurasi login_security.php', 3);
$doc->codeBlock(
    "\$config['login_max_attempts'] = 10;\n"
    . "\$config['login_lockout_minutes'] = 15;\n"
    . "\$config['login_mfa_levels'] = array('1', '99');\n"
    . "\$config['login_mfa_otp_length'] = 6;\n"
    . "\$config['login_mfa_otp_expire'] = 300;\n"
    . "\$config['login_generic_error'] = 'Kredensial tidak valid...';"
);
$doc->title('8.4 File Keamanan', 3);
$doc->bullet('application/helpers/login_security_helper.php — fungsi CSRF, MFA, rate limit');
$doc->bullet('application/config/login_security.php — konfigurasi parameter keamanan');
$doc->bullet('application/controllers/Anekadharmamasuk.php — controller login utama');
$doc->bullet('application/views/masukgo/masukgo.php — form login');
$doc->bullet('application/views/masukgo/verifymfa.php — form verifikasi OTP');
$doc->bullet('docs/Audit_Keamanan_Login_AnekaDharma.doc — dokumen audit lengkap');
$doc->pageBreak();

// ═══ 9. MASTER DATA ═══════════════════════════════════════════════════════════
$doc->title('9. Master Data (Konsumen, Nama Barang)', 2);
$doc->table(['Modul', 'Chat ID', 'Perubahan', 'File'], [
    ['Sys_konsumen', '4e5eec0a', 'CRUD modal AJAX, SweetAlert, rename label gudang→konsumen', 'Sys_konsumen.php, adminlte310_sys_konsumen_list.php'],
    ['Sys_nama_barang', 'c98c618c', 'Kolom kategori + combobox/modal tambah kategori', 'Sys_nama_barang.php, views'],
    ['Sys_nama_barang', '6daa2960', 'Modal pembelian — AJAX insert dari form SPOP', 'sys_nama_barang_form_pembelian_modal.php'],
]);
$doc->pageBreak();

// ═══ 10. DOKUMENTASI ══════════════════════════════════════════════════════════
$doc->title('10. Dokumentasi & Blueprint Sistem', 2);
$doc->paragraph('Pada fase akhir (5–7 Juni 2026), dibuat dokumentasi blueprint sistem lengkap:');
$doc->bullet('Blueprint.docx — 11 bab arsitektur sistem (login → akuntansi)');
$doc->bullet('Blueprint_update_3mei.docx — dokumen ini (changelog pengembangan Cursor AI)');
$doc->bullet('tools/generate_blueprint_docx.php — generator blueprint sistem');
$doc->bullet('tools/generate_blueprint_update_3mei.php — generator changelog pengembangan');
$doc->bullet('tools/diagram_engine.php — engine diagram shape Word (DrawingML)');
$doc->bullet('tools/docx_writer.php — library writer DOCX');
$doc->pageBreak();

// ═══ 11. SESI CHAT CURSOR ═════════════════════════════════════════════════════
$doc->title('11. Sesi Chat Cursor AI (Transcript)', 2);
$doc->paragraph('Berikut sesi chat Cursor AI yang terdokumentasi untuk proyek Aneka Dharma:');
$doc->table(['Transcript ID', 'Periode', 'Permintaan User', 'Hasil Implementasi'], [
    ['6daa2960', '8 Mei', 'Modal input barang pembelian', 'Modal nested + AJAX + auto-fill satuan/harga'],
    ['506879fa', '10–12 Mei', 'Layout cetak dot matrix PERUMDAM TIRTA', 'Redesign adminlte310_cetak_penjualan.php'],
    ['6bd4509a', '12–17 Mei', 'Min 8 baris, 2 faktur/folio, UI jasa', 'CSS cetak + border pembelian jasa'],
    ['c98c618c', '17–18 Mei', 'Kategori barang, italic total', 'Kolom kategori stock + master barang'],
    ['68faf093', '19–21 Mei', 'Performa kode akun, export Excel', 'Batch query + datepicker + export'],
    ['d426eb11', '20 Mei', 'Recalculate UUID, fix error Persediaan', 'Tombol recalculate + bugfix'],
    ['5b57155a', 'Mei akhir', 'Filter April 2026 kosong', 'Fix query rentang tanggal model'],
    ['febe9afb', '23 Mei–7 Jun', 'Audit & implementasi keamanan login', 'CSRF, MFA, rate limit, auth hook'],
    ['4e5eec0a', '29 Mei–3 Jun', 'CRUD modal konsumen', 'AJAX modal + SweetAlert'],
    ['7e65ed6f', '5–7 Juni', 'Blueprint Word + diagram shape + changelog', 'Generator DOCX + diagram engine'],
]);
$doc->pageBreak();

// ═══ 12. COMMIT GIT ═══════════════════════════════════════════════════════════
$doc->title('12. Daftar Commit Git (3 Mei – 7 Juni 2026)', 2);
$commits = [
    ['2026-05-07', 'Persediaan combobox, cetak PDF, Stock'],
    ['2026-05-08', 'Pembelian input barang beli (×3)'],
    ['2026-05-09', 'Penjualan cetak format PDF'],
    ['2026-05-11', 'Penjualan cetak format PDF'],
    ['2026-05-12', 'Penjualan setting data barang'],
    ['2026-05-13', 'Penjualan cetak penjualan (×2)'],
    ['2026-05-18', 'Cetak penjualan format, Excel pembelian, kategori barang, pembelian jasa'],
    ['2026-05-19', 'Setting kode akun pembelian, update data pembelian'],
    ['2026-05-20', 'UPDATE KONSEP PERSEDIAAN (×3)'],
    ['2026-05-21', 'Pembelian barang/jasa, sys_nama_barang, konsep persediaan (×6)'],
    ['2026-05-23', 'Penjualan, login masuk, PENJUALAN KE PERSEDIAAN (×4)'],
    ['2026-05-26', 'Konsep persediaan, penjualan, persediaan (×8)'],
    ['2026-05-29', 'Data konsumen, cetak penjualan'],
    ['2026-06-03', 'Update pembelian dan penjualan (×2)'],
    ['2026-06-06', 'Update persediaan (+1573 baris helper)'],
];
$doc->table(['Tanggal', 'Ringkasan Commit'], $commits);
$doc->pageBreak();

// ═══ 13. FILE DIUBAH ══════════════════════════════════════════════════════════
$doc->title('13. File yang Paling Banyak Diubah', 2);
$doc->table(['File', 'Modul', 'Estimasi Perubahan'], [
    ['pembelian_persediaan_helper.php', 'Persediaan', '+7000 baris (logika bisnis inti)'],
    ['Persediaan.php', 'Persediaan', 'Controller ~3400 baris, generate/recalculate'],
    ['Tbl_penjualan.php', 'Penjualan', 'Integrasi persediaan, rekap, export'],
    ['Tbl_pembelian.php', 'Pembelian', 'Modal barang, export Excel, kode akun'],
    ['adminlte310_persediaan_list.php', 'Persediaan View', 'UI tabs, DataTables, export'],
    ['adminlte310_cetak_penjualan.php', 'Cetak', 'Layout dot matrix faktur'],
    ['adminlte310_tbl_penjualan_form_input_barang.php', 'Penjualan View', 'Modal pilih barang persediaan'],
    ['persediaan_display_helper.php', 'Helper', 'Mapping label kolom unit'],
    ['login_security_helper.php', 'Keamanan', 'CSRF, MFA, rate limit (baru)'],
    ['Anekadharmamasuk.php', 'Login', 'Login + MFA + forgot password'],
    ['exportexcel_helper.php', 'Export', 'Excel pembelian/penjualan format rupiah'],
    ['Sys_konsumen.php', 'Master', 'CRUD modal AJAX'],
]);
$doc->pageBreak();

// ═══ 14. CUPLIKAN KODE ════════════════════════════════════════════════════════
$doc->title('14. Cuplikan Kode Penting', 2);

$doc->title('14.1 Guard Login — is_login() (racode_helper.php)', 3);
$doc->codeBlock(
    "function is_login()\n"
    . "{\n"
    . "    \$ci = get_instance();\n"
    . "    if (!\$ci->session->userdata('id_users')) {\n"
    . "        redirect('Anekadharmamasuk');\n"
    . "    } else {\n"
    . "        \$modul = \$ci->uri->segment(1);\n"
    . "        \$menu = \$ci->db->get_where('tbl_menu', array('url' => \$modul))->row_array();\n"
    . "        if (\$menu) {\n"
    . "            \$hak_akses = \$ci->db->get_where('tbl_hak_akses',\n"
    . "                array('id_menu' => \$menu['id_menu'],\n"
    . "                      'id_user_level' => \$ci->session->userdata('id_user_level')));\n"
    . "            if (\$hak_akses->num_rows() < 1) {\n"
    . "                redirect('blokir');\n"
    . "            }\n"
    . "        }\n"
    . "    }\n"
    . "}"
);

$doc->title('14.2 MFA Check — Anekadharmamasuk.php', 3);
$doc->codeBlock(
    "if (login_mfa_is_required(\$auth['id_user_level'])) {\n"
    . "    \$this->_start_mfa_challenge(\$auth);\n"
    . "    return;\n"
    . "}\n"
    . "\$this->_complete_successful_login(\$auth, false);"
);

$doc->title('14.3 Konfigurasi MFA — login_security.php', 3);
$doc->codeBlock(
    "\$config['login_mfa_levels'] = array('1', '99');\n"
    . "\$config['login_mfa_otp_length'] = 6;\n"
    . "\$config['login_mfa_otp_expire'] = 300;"
);

$doc->title('14.4 Struktur Tabel persediaan (kolom multi-unit)', 3);
$doc->codeBlock(
    "CREATE TABLE persediaan (\n"
    . "  uuid_persediaan varchar(255),\n"
    . "  tanggal_beli date,\n"
    . "  namabarang varchar(109),\n"
    . "  hpp varchar(50), sa varchar(50), beli varchar(4),\n"
    . "  Sekretariat varchar(1), CETAK varchar(3),\n"
    . "  GRAFIKITA varchar(3), medis varchar(10),\n"
    . "  pu_outsor varchar(3), nilai_persediaan varchar(50),\n"
    . "  penjualan int(11) DEFAULT 0\n"
    . ");"
);
$doc->pageBreak();

// ═══ 15. DIAGRAM INTEGRASI ════════════════════════════════════════════════════
$doc->title('15. Diagram Integrasi Penjualan–Persediaan–Pembelian', 2);
$doc->diagram(function ($d) {
    $d->entityDiagram();
});
$doc->pageBreak();

// ═══ 16. REKOMENDASI ══════════════════════════════════════════════════════════
$doc->title('16. Catatan Programming & Rekomendasi Lanjutan', 2);
$doc->title('16.1 Catatan Penting', 3);
$doc->bullet('Setiap perubahan transaksi di bulan lampau WAJIB trigger recalculate persediaan berantai');
$doc->bullet('Nama unit di kolom persediaan dan penjualan harus diseragamkan (konfirmasi dengan user bisnis)');
$doc->bullet('File pembelian_persediaan_helper.php sangat besar (~7000 baris) — pertimbangkan refactoring');
$doc->bullet('Dua sistem menu (menu vs tbl_menu) perlu diselaraskan');
$doc->title('16.2 Rekomendasi', 3);
$doc->bullet('Implementasi CI migrations untuk version control skema database');
$doc->bullet('Unit testing modul generate & recalculate persediaan');
$doc->bullet('Backup otomatis database terjadwal');
$doc->bullet('Dokumentasi API REST untuk integrasi eksternal');
$doc->bullet('Monitoring log recalculate via persediaan_gen_recalc_log');
$doc->title('16.3 Regenerasi Dokumen', 3);
$doc->paragraph('Jalankan ulang generator untuk memperbarui dokumen ini:');
$doc->codeBlock('D:\\xampp74\\php\\php.exe D:\\xampp74\\htdocs\\anekadharma_new_20241025\\tools\\generate_blueprint_update_3mei.php');
$doc->paragraph('');
$doc->paragraph('— End of Document —', ['italic' => true, 'size' => 20]);

// ═══ SAVE ═════════════════════════════════════════════════════════════════════
$outputPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'Blueprint_update_3mei.docx';
if (!is_dir(dirname($outputPath))) {
    mkdir(dirname($outputPath), 0755, true);
}
$savedPath = $doc->save($outputPath);
echo "Blueprint pengembangan berhasil dibuat: {$savedPath}\n";
echo "Ukuran file: " . number_format(filesize($savedPath)) . " bytes\n";
