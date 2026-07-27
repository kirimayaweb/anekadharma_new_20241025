<?php
/**
 * Generator Blueprint Aplikasi Aneka Dharma → DOCX
 * Jalankan: php tools/generate_blueprint_docx.php
 */

require_once __DIR__ . '/docx_writer.php';
require_once __DIR__ . '/database_schema_helper.php';

// ─── BUILD DOCUMENT ───────────────────────────────────────────────────────────

$doc = new SimpleDocxWriter();

// SAMPUL
$doc->title('BLUEPRINT APLIKASI', 1);
$doc->title('ANEKA DHARMA', 1);
$doc->paragraph('Sistem Informasi Manajemen Bisnis Terintegrasi', ['italic' => true, 'size' => 24]);
$doc->paragraph('');
$doc->paragraph('Versi Aplikasi UI: 1.0.1 beta');
$doc->paragraph('Framework: CodeIgniter 3.1.5');
$doc->paragraph('Database: MySQL/MariaDB (anekadharma_db)');
$doc->paragraph('Tanggal Dokumen: ' . date('d F Y'));
$doc->paragraph('');
$doc->paragraph('Dokumen ini menjelaskan arsitektur, alur bisnis, skema database, modul-modul, dan diagram sistem aplikasi Aneka Dharma — mulai dari proses login hingga modul akuntansi dan cetak laporan.');
$doc->pageBreak();

// DAFTAR ISI
$doc->title('DAFTAR ISI', 2);
$toc = [
    '1. Pendahuluan',
    '2. Gambaran Umum Sistem',
    '3. Arsitektur Teknis',
    '4. Alur Login dan Keamanan',
    '5. Struktur Navigasi dan Hak Akses',
    '6. Modul-Modul Utama',
    '7. Alur Bisnis (Pembelian → Persediaan → Penjualan → Akuntansi)',
    '8. Skema Database (Lengkap per Tabel + Query)',
    '9. Diagram Sistem',
    '10. Integrasi dan Teknologi Pendukung',
    '11. Catatan Arsitektur dan Rekomendasi',
];
foreach ($toc as $item) {
    $doc->bullet($item);
}
$doc->pageBreak();

// 1. PENDAHULUAN
$doc->title('1. Pendahuluan', 2);
$doc->paragraph('Aplikasi Aneka Dharma adalah sistem ERP (Enterprise Resource Planning) berbasis web yang dikembangkan untuk mengelola operasional bisnis multi-unit. Sistem ini mencakup manajemen pembelian barang dan jasa, penjualan, persediaan multi-divisi, pembayaran, jurnal akuntansi, buku besar, neraca saldo, laporan laba rugi, neraca keuangan, serta fitur cetak dokumen bisnis (SPOP, surat jalan, pengajuan pembayaran, laporan persediaan).');
$doc->paragraph('Aplikasi dirancang untuk mendukung berbagai unit bisnis seperti Sekretariat, Cetak, Grafikita, PU-ATK, PU-Medis, PU-Sembako, dan unit outsourcing lainnya. Setiap transaksi pembelian dan penjualan terhubung ke modul persediaan dan akuntansi sehingga data keuangan dan stok selalu tersinkronisasi.');
$doc->paragraph('Dokumen blueprint ini ditujukan sebagai referensi teknis dan bisnis bagi pengembang, administrator sistem, auditor, dan pengguna tingkat manajemen yang membutuhkan pemahaman menyeluruh tentang cara kerja sistem.');

$doc->title('1.1 Ruang Lingkup Dokumen', 3);
$doc->bullet('Arsitektur perangkat lunak dan struktur folder proyek');
$doc->bullet('Alur autentikasi pengguna (login, MFA WhatsApp, reset password)');
$doc->bullet('Manajemen hak akses berbasis level user dan menu');
$doc->bullet('Seluruh modul operasional: pembelian, penjualan, persediaan, pembayaran');
$doc->bullet('Modul akuntansi: jurnal, buku besar, neraca, laba rugi');
$doc->bullet('Skema database dan relasi antar tabel');
$doc->bullet('Diagram alur sistem dan entitas-relasi');
$doc->pageBreak();

// 2. GAMBARAN UMUM
$doc->title('2. Gambaran Umum Sistem', 2);
$doc->table(
    ['Aspek', 'Keterangan'],
    [
        ['Nama Aplikasi', 'ANEKA DHARMA'],
        ['Versi UI', '1.0.1 beta'],
        ['Framework', 'CodeIgniter 3.1.5 (PHP MVC)'],
        ['Database', 'MySQL/MariaDB — anekadharma_db (lokal)'],
        ['Frontend', 'AdminLTE 3.1.0 + Bootstrap + jQuery + DataTables'],
        ['Entry Point', 'Index.php → default_controller: Anekadharmamasuk'],
        ['Timezone', 'Asia/Jakarta'],
        ['Base URL Lokal', 'http://localhost/anekadharma_new_20241025/'],
        ['Jumlah Controller', '86 controller'],
        ['Jumlah Model', '76 model'],
        ['Jumlah View', '327+ view (folder anekadharma/)'],
    ]
);

$doc->title('2.1 Unit Bisnis yang Didukung', 3);
$doc->paragraph('Sistem mendukung multi-unit bisnis yang didefinisikan di tabel sys_unit:');
$doc->table(
    ['Kode Unit', 'Nama Unit'],
    [
        ['PU-ATK', 'Pengadaan Umum ATK'],
        ['PU-KBS', 'Pengadaan Umum KBS'],
        ['PU-SEMBAKO', 'Pengadaan Umum Sembako'],
        ['PU-MEDIS', 'Pengadaan Umum Medis'],
        ['PU-PERSAMPAHAN', 'Pengadaan Umum Persampahan'],
        ['CETAK', 'Divisi Cetak'],
        ['GRAFIKITA', 'Divisi Grafikita'],
        ['Sekretariat', 'Sekretariat'],
        ['PU-FC Parasamya', 'Food Court Parasamya'],
        ['PU-FC Manding', 'Food Court Manding'],
        ['PU-FC Gose', 'Food Court Gose'],
        ['PU-BUKU', 'Pengadaan Umum Buku'],
        ['PU-Outsourcing', 'Pengadaan Umum Outsourcing'],
        ['PU - PPBMP', 'Pengadaan Umum PPBMP'],
    ]
);
$doc->pageBreak();

// 3. ARSITEKTUR TEKNIS
$doc->title('3. Arsitektur Teknis', 2);
$doc->title('3.1 Pola MVC CodeIgniter', 3);
$doc->paragraph('Aplikasi menggunakan pola Model-View-Controller standar CodeIgniter 3:');
$doc->bullet('Controller — menangani request HTTP, validasi, orchestration logika bisnis');
$doc->bullet('Model — interaksi database (CRUD, query kompleks)');
$doc->bullet('View — tampilan HTML dengan template AdminLTE');
$doc->bullet('Helper — fungsi utilitas (racode, login_security, pembelian_persediaan, exportexcel)');
$doc->bullet('Library — Template, REST_Controller, DomPDF, Pdf, DataTables');

$doc->title('3.2 Struktur Folder Proyek', 3);
$doc->codeBlock(
    "anekadharma_new_20241025/\n"
    . "├── Index.php                    # Entry point aplikasi\n"
    . "├── application/\n"
    . "│   ├── config/                  # routes, database, login_security\n"
    . "│   ├── controllers/             # 86 controller modul\n"
    . "│   ├── models/                  # 76 model database\n"
    . "│   ├── views/\n"
    . "│   │   ├── anekadharma/         # View utama AdminLTE (327+ file)\n"
    . "│   │   ├── masukgo/             # Halaman login & MFA\n"
    . "│   │   └── template/            # Layout template\n"
    . "│   ├── helpers/                 # Helper bisnis & keamanan\n"
    . "│   └── libraries/               # Template, PDF, REST\n"
    . "├── system/                      # Core CodeIgniter 3.1.5\n"
    . "├── assets/AdminLTE310/          # Asset UI AdminLTE\n"
    . "├── database/sql/                # Migration SQL manual\n"
    . "├── DB_LOKAL/ & DB_SERVER_*/     # Dump database referensi\n"
    . "└── docs/                        # Dokumentasi audit keamanan"
);

$doc->title('3.3 Autoload & Konfigurasi', 3);
$doc->table(
    ['Komponen', 'Nilai'],
    [
        ['Library Autoload', 'Template, database, session'],
        ['Helper Autoload', 'url, form, racode, xss'],
        ['Default Controller', 'Anekadharmamasuk (halaman login)'],
        ['Routing', 'Standar CI3 — index.php/Controller/method/id'],
        ['DB Driver', 'mysqli, charset utf8'],
    ]
);
$doc->pageBreak();

// 4. LOGIN
$doc->title('4. Alur Login dan Keamanan', 2);
$doc->paragraph('Controller utama autentikasi: Anekadharmamasuk (application/controllers/Anekadharmamasuk.php). Sistem keamanan dikelola oleh login_security_helper.php dan login_security.php.');

$doc->title('4.1 Diagram Alur Login', 3);
$doc->diagram(function ($d) {
    $d->loginFlow();
});

$doc->title('4.2 Fitur Keamanan Login', 3);
$doc->table(
    ['Fitur', 'Implementasi'],
    [
        ['CSRF Protection', 'Token CSRF pada setiap form POST login'],
        ['Rate Limiting', 'Pembatasan percobaan login & forgot password'],
        ['MFA WhatsApp', 'OTP 6 digit, expire 5 menit, wajib level Admin (1, 99)'],
        ['Password Hash', 'Verifikasi password terenkripsi'],
        ['HTTPS Force', 'Dipaksa di environment production'],
        ['Security Headers', 'HTTP security headers via helper'],
        ['Reset Password', 'Via nomor WhatsApp terdaftar (forgotpassword)'],
    ]
);

$doc->title('4.3 Level User (Role)', 3);
$doc->table(
    ['ID Level', 'Peran', 'Redirect Setelah Login'],
    [
        ['1', 'Admin', 'Dashboard'],
        ['99', 'Administrator', 'Dashboard'],
        ['2', 'Manager', 'Dashboard'],
        ['7', 'Kasir', 'Dashboard'],
        ['3', 'Sales', 'Dashboard'],
        ['4', 'Customer', 'Dashboard'],
        ['Lainnya', 'Tidak diizinkan', 'Kembali ke login'],
    ]
);

$doc->title('4.4 Session Data', 3);
$doc->paragraph('Setelah login sukses, data berikut disimpan di session PHP:');
$doc->bullet('sess_username, sess_iduser, sess_id_user_level');
$doc->bullet('sess_email_user, sess_no_hp');
$doc->bullet('Seluruh row tbl_user disimpan ke session');
$doc->bullet('listcover_selected (opsional, untuk laporan cover)');
$doc->pageBreak();

// 5. HAK AKSES
$doc->title('5. Struktur Navigasi dan Hak Akses', 2);
$doc->paragraph('Fungsi is_login() di racode_helper.php menjadi guard utama setiap modul yang membutuhkan autentikasi.');

$doc->title('5.1 Mekanisme Guard Akses', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['1. Cek session id_users', 'Kosong → redirect Anekadharmamasuk', 'C00000'],
        ['2. Ambil controller URI', 'Segment 1 = nama modul', '2E75B6'],
        ['3. Cari tbl_menu', 'WHERE url = controller', '4472C4'],
        ['4. Cek tbl_hak_akses', 'id_menu + id_user_level', '548235'],
        ['5. Akses ditolak', 'Redirect → Blokir (blokir_akses)', '7030A0'],
    ], 1.35, 3.8, 0.58, 0.26);
});

$doc->title('5.2 Menu Navigasi', 3);
$doc->paragraph('Menu utama dibangun dinamis dari database:');
$doc->bullet('Tabel menu — menu parent aktif (is_active=1, is_parent=0)');
$doc->bullet('Filter per user via tbl_hak_akses (id_user + main_menu)');
$doc->bullet('Submenu: tbl_hak_akses.id_menu → menu.link (contoh: /Tbl_pembelian)');
$doc->bullet('Layout: adminlte310_anekadharma_topnav_aside.php (top navbar + sidebar minimal)');
$doc->bullet('Item tetap: Dashboard, LOGOUT');

$doc->title('5.3 Tabel Akses', 3);
$doc->table(
    ['Tabel', 'Fungsi'],
    [
        ['menu', 'Definisi menu navigasi (link, parent, aktif)'],
        ['tbl_menu', 'Mapping URL controller ke id_menu (guard akses)'],
        ['tbl_hak_akses', 'Hak akses per user level atau per user'],
        ['tbl_user_level', 'Definisi level/role user'],
        ['tbl_user', 'Data akun pengguna'],
    ]
);
$doc->pageBreak();

// 6. MODUL
$doc->title('6. Modul-Modul Utama', 2);

$doc->title('6.1 Modul Pembelian (Tbl_pembelian)', 3);
$doc->paragraph('Controller: Tbl_pembelian.php (~5700 baris) — modul pembelian barang via SPOP (Surat Permintaan Order Pembelian).');
$doc->bullet('Input PO/SPOP barang, link ke sys_nama_barang dan sys_supplier');
$doc->bullet('Pengajuan & cetak pembayaran ke supplier');
$doc->bullet('Cetak belanja per SPOP (PDF)');
$doc->bullet('Setting kode akun per pembelian → posting ke buku_besar');
$doc->bullet('Jurnal pembelian (jurnal_pembelian, jurnal_pembelian2)');
$doc->bullet('Pecah satuan barang, rollback transaksi');
$doc->bullet('Integrasi persediaan (uuid_persediaan, id_persediaan_barang)');

$doc->title('6.2 Modul Pembelian Jasa (Tbl_pembelian_jasa)', 3);
$doc->paragraph('Mirror modul pembelian barang, khusus untuk pembelian jasa. Tabel: tbl_pembelian_jasa, tbl_jasa.');

$doc->title('6.3 Modul Penjualan (Tbl_penjualan)', 3);
$doc->paragraph('Controller: Tbl_penjualan.php — manajemen penjualan barang.');
$doc->bullet('CRUD penjualan per nmrpesan / nmrkirim');
$doc->bullet('Pilih barang dari persediaan via AJAX modal');
$doc->bullet('Rekap per barang, konsumen, unit bisnis');
$doc->bullet('Cetak surat jalan/penjualan PDF');
$doc->bullet('Export Excel (rekap unit, konsumen, barang)');
$doc->bullet('Pembayaran penjualan (Tbl_penjualan/bayar)');
$doc->bullet('Jurnal penjualan → buku_besar');
$doc->bullet('Versi accounting: Tbl_penjualan_accounting (piutang, PPN, dll.)');

$doc->title('6.4 Modul Persediaan (Persediaan)', 3);
$doc->paragraph('Controller: Persediaan.php (~3400 baris) — modul paling kompleks. Helper utama: pembelian_persediaan_helper.php (~7000+ baris).');
$doc->bullet('Daftar persediaan per bulan (tanggal_beli)');
$doc->bullet('Kolom multi-unit: Sekretariat, CETAK, GRAFIKITA, medis, pu_outsor, dll.');
$doc->bullet('Generate persediaan bulan — salin SA dari bulan sumber, hitung beli dari pembelian');
$doc->bullet('Recalculate — sinkronisasi dari pembelian & penjualan');
$doc->bullet('Rekap nilai persediaan (Sediaan Awal + Pembelian per unit)');
$doc->bullet('Export Excel multi-sheet, PDF, compare tabel');
$doc->bullet('History: persediaan_gen_recalc_log / persediaan_gen_recalc_item');
$doc->bullet('Akses generate: level 1, 2, 99 atau admin/administrator');

$doc->title('6.5 Modul Akuntansi', 3);
$doc->paragraph('Modul akuntansi tersebar di beberapa controller, tidak ada controller tunggal bernama Akuntansi:');
$doc->table(
    ['Area', 'Controller', 'Tabel Utama'],
    [
        ['Chart of Accounts', 'Sys_kode_akun', 'sys_kode_akun'],
        ['Grup Akuntansi', 'Tbl_accounting_group', 'tbl_accounting_group'],
        ['Detail Transaksi', 'Tbl_accounting_detail', 'tbl_accounting_detail'],
        ['Jurnal Umum', 'Jurnal_umum', 'jurnal_umum'],
        ['Jurnal Kas', 'Jurnal_kas', 'jurnal_kas'],
        ['Jurnal Pembelian', 'Jurnal_pembelian', 'jurnal_pembelian'],
        ['Jurnal Penjualan', 'Jurnal_penjualan', 'jurnal_penjualan'],
        ['Penerimaan Kas', 'Jurnal_penerimaan_kas', 'jurnal_penerimaan_kas'],
        ['Pengeluaran Kas', 'Jurnal_pengeluaran_kas', 'jurnal_pengeluaran_kas'],
        ['Penyesuaian', 'Jurnal_penyesuaian', 'jurnal_penyesuaian'],
        ['Saldo Akhir Kas', 'Jurnal_kas_saldo_akhir_bulan', 'jurnal_kas_saldo_akhir_bulan'],
        ['Buku Besar', 'Buku_besar', 'buku_besar'],
        ['Buku Bank', 'Bukubank', 'bukubank'],
        ['Neraca Saldo', 'Neraca_saldo', 'neraca_saldo'],
        ['Laba Rugi', 'Tbl_laba_rugi', 'tbl_laba_rugi'],
        ['Neraca', 'Tbl_neraca_data', 'tbl_neraca_data'],
        ['Kas Kecil', 'Tbl_kas_kecil', 'tbl_kas_kecil'],
        ['Penyusutan', 'Tbl_penyusutan', 'tbl_penyusutan'],
        ['Rekening Koran', 'Tbl_rekening_koran', 'tbl_rekening_koran'],
    ]
);

$doc->title('6.6 Modul Cetak Dokumen', 3);
$doc->paragraph('Fitur cetak (print/export) terintegrasi di modul transaksi:');
$doc->table(
    ['Dokumen', 'Controller/Method', 'Format'],
    [
        ['Surat Jalan Penjualan', 'Tbl_penjualan/cetak_penjualan_per_uuid_penjualan', 'PDF (DomPDF)'],
        ['SPOP Pembelian', 'Tbl_pembelian/cetak_belanja_per_spop', 'PDF'],
        ['Pengajuan Bayar Supplier', 'Tbl_pembelian/cetak_pengajuan_bayar_per_spop', 'PDF'],
        ['Laporan Persediaan', 'Persediaan/cetak_pdf', 'PDF'],
        ['Rekap Penjualan/Pembelian', 'Export Excel di modul terkait', 'XLS/XLSX'],
        ['Laporan Laba Rugi/Neraca', 'Tbl_laba_rugi, Tbl_neraca_data', 'PDF'],
    ]
);

$doc->title('6.7 Master Data (Sys_*)', 3);
$doc->table(
    ['Controller', 'Tabel', 'Fungsi'],
    [
        ['Sys_nama_barang', 'sys_nama_barang', 'Master barang/produk'],
        ['Sys_konsumen', 'sys_konsumen', 'Master pelanggan/konsumen'],
        ['Sys_supplier', 'sys_supplier', 'Master supplier/pemasok'],
        ['Sys_unit', 'sys_unit', 'Unit bisnis'],
        ['Sys_kode_akun', 'sys_kode_akun', 'Chart of accounts'],
        ['Sys_bank', 'sys_bank', 'Data bank'],
        ['Sys_gudang', 'sys_gudang', 'Data gudang'],
        ['Sys_pajak', 'sys_pajak', 'Konfigurasi pajak'],
        ['Sys_kas_nominal', 'sys_kas_nominal', 'Nominal kas'],
    ]
);
$doc->pageBreak();

// 7. ALUR BISNIS
$doc->title('7. Alur Bisnis', 2);

$doc->title('7.1 Alur Pembelian → Persediaan → Akuntansi', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['1. Input SPOP (Pembelian)', 'Tbl_pembelian/create → tbl_pembelian', '2E75B6'],
        ['2. Update Persediaan', 'uuid_persediaan → kolom unit (cetak/grafikita/sekret)', '548235'],
        ['3. Pengajuan Bayar Supplier', 'tbl_pembelian_pengajuan_bayar → Cetak PDF', 'ED7D31'],
        ['4. Setting Kode Akun', 'Assign sys_kode_akun → setting_kode_akun_pembelian2', '7030A0'],
        ['5. Posting Jurnal', 'jurnal_pembelian2 → buku_besar → jurnal_pembelian', '1F4E79'],
    ]);
});

$doc->title('7.2 Alur Penjualan → Persediaan → Akuntansi', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['1. Buat Order Penjualan', 'Tbl_penjualan/create → uuid_penjualan', '4472C4'],
        ['2. Pilih Barang dari Stok', 'list_persediaan_penjualan_ajax → kurangi stok unit', '548235'],
        ['3. Simpan Penjualan', 'tbl_penjualan (nmrkirim, konsumen, kode_akun)', '2E75B6'],
        ['4. Pembayaran', 'Tbl_penjualan/bayar → tbl_penjualan_pembayaran', 'ED7D31'],
        ['5. Cetak Surat Jalan', 'cetak_penjualan_per_uuid_penjualan (PDF)', 'BF8F00'],
        ['6. Jurnal Penjualan', 'jurnal_penjualan2 → buku_besar / tbl_penjualan_accounting', '7030A0'],
    ], 1.35, 3.8, 0.58, 0.24);
});

$doc->title('7.3 Alur Persediaan Bulanan', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Persediaan/index', 'Tampil data bulan YYYY-MM', '1F4E79'],
        ['Tab Generate', 'Cek data bulan target', '2E75B6'],
        ['GENERATE_PERSEDIAN_BULAN', 'Salin SA, hitung beli dari pembelian', '548235'],
        ['Recalculate', 'Sync penjualan & pembelian', 'ED7D31'],
        ['Log History', 'persediaan_gen_recalc_log / item', '7030A0'],
        ['Rekap & Export', 'persediaan_rekap_view → Excel / PDF', '4472C4'],
    ], 1.35, 3.8, 0.56, 0.22);
});

$doc->title('7.4 Alur Jurnal Kas & Laporan Keuangan', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Jurnal_kas/index', 'Transaksi kas bulan berjalan', '1F4E79'],
        ['Penerimaan Kas', 'Jurnal_penerimaan_kas', '548235'],
        ['Pengeluaran Kas', 'Jurnal_pengeluaran_kas', 'C00000'],
        ['Saldo Akhir Bulan', 'Jurnal_kas_saldo_akhir_bulan', '2E75B6'],
        ['Agregasi Keuangan', 'buku_besar & neraca_saldo', '7030A0'],
        ['Laporan Laba Rugi & Neraca', 'Tbl_laba_rugi / Tbl_neraca_data → Cetak PDF', 'ED7D31'],
    ], 1.2, 4.0, 0.56, 0.22);
});
$doc->pageBreak();

// 8. DATABASE — LENGKAP PER TABEL
$doc->title('8. Skema Database', 2);
$doc->paragraph('Database: anekadharma_db (MySQL/MariaDB, localhost). Dokumen ini memuat blueprint database lengkap: diagram relasi, ringkasan kategori, struktur kolom per tabel (diambil live dari INFORMATION_SCHEMA), penjelasan fungsi, relasi antar tabel, dan query SQL contoh untuk setiap tabel inti.');
$doc->paragraph('Catatan: Tabel backup/arsip (suffix _bu, _x, xx, persediaan_lama, dll.) tidak dicantumkan — hanya tabel produksi yang aktif dipakai aplikasi.');

$doc->title('8.1 Diagram Entity-Relationship Database', 3);
$doc->diagram(function ($d) {
    $d->entityDiagram();
});

$doc->title('8.2 Diagram Alur Data Transaksi → Akuntansi', 3);
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['sys_supplier / sys_konsumen', 'Master data pihak ketiga', 'D6E4F0'],
        ['tbl_pembelian / tbl_penjualan', 'Transaksi SPOP & Order', '2E75B6'],
        ['persediaan', 'Stok multi-unit per bulan', '548235'],
        ['buku_besar', 'Posting debet/kredit', '7030A0'],
        ['jurnal_kas / jurnal_*', 'Jurnal operasional', '4472C4'],
        ['tbl_laba_rugi / tbl_neraca_data', 'Laporan keuangan', '1F4E79'],
    ], 1.0, 4.2, 0.56, 0.22);
});

$doc->title('8.3 Ringkasan Kategori Tabel', 3);
$doc->table(['Kategori', 'Jumlah Tabel Inti', 'Contoh'], [
    ['A. Transaksi Operasional', '13', 'persediaan, tbl_pembelian, tbl_penjualan, persediaan_gen_recalc_log'],
    ['B. Akuntansi & Jurnal', '18', 'buku_besar, jurnal_kas, tbl_laba_rugi, neraca_saldo'],
    ['C. Master Data (sys_*)', '12', 'sys_konsumen, sys_supplier, sys_unit, sys_kode_akun'],
    ['D. User & Akses', '6', 'tbl_user, tbl_hak_akses, menu, tbl_menu'],
]);

$doc->title('8.4 Relasi Utama Antar Tabel', 3);
$doc->table(['Dari Tabel', 'Kolom', 'Ke Tabel', 'Keterangan'], [
    ['tbl_pembelian', 'uuid_persediaan', 'persediaan', 'Link pembelian ke baris persediaan'],
    ['tbl_pembelian', 'uuid_supplier', 'sys_supplier', 'Supplier pembelian'],
    ['tbl_pembelian', 'uuid_barang', 'sys_nama_barang_x', 'Master barang'],
    ['tbl_pembelian', 'id_buku_besar', 'buku_besar', 'Posting akuntansi'],
    ['tbl_penjualan', 'uuid_persediaan', 'persediaan', 'Kurangi stok saat jual'],
    ['tbl_penjualan', 'uuid_konsumen', 'sys_konsumen', 'Pelanggan'],
    ['tbl_penjualan', 'uuid_unit', 'sys_unit', 'Unit bisnis penjualan'],
    ['persediaan_gen_recalc_item', 'id_log', 'persediaan_gen_recalc_log', 'FK CASCADE'],
    ['tbl_user', 'id_user_level', 'tbl_user_level', 'Role user'],
    ['tbl_hak_akses', 'id_menu', 'tbl_menu', 'Guard is_login()'],
    ['buku_besar', 'kode_akun', 'sys_kode_akun', 'Chart of accounts'],
]);

$doc->title('8.5 Query SQL Penting (Ilustrasi)', 3);
$doc->paragraph('Query berikut sering dipakai di controller dan helper aplikasi:');
$doc->codeBlock(
    "-- [Q1] Persediaan per bulan\n"
    . "SELECT namabarang, sa, beli, CETAK, GRAFIKITA, nilai_persediaan\n"
    . "FROM persediaan\n"
    . "WHERE tanggal_beli BETWEEN '2026-01-01' AND '2026-01-31';\n\n"
    . "-- [Q2] Pembelian per SPOP dengan supplier\n"
    . "SELECT p.spop, p.supplier_nama, p.uraian, p.jumlah, p.harga_total, p.tgl_po\n"
    . "FROM tbl_pembelian p WHERE p.spop = '893';\n\n"
    . "-- [Q3] Penjualan per surat jalan\n"
    . "SELECT nmrkirim, konsumen_nama, nama_barang, unit, jumlah, total_nominal\n"
    . "FROM tbl_penjualan WHERE nmrkirim = '01';\n\n"
    . "-- [Q4] Guard hak akses (racode_helper is_login)\n"
    . "SELECT ha.* FROM tbl_hak_akses ha\n"
    . "JOIN tbl_menu m ON m.id_menu = ha.id_menu\n"
    . "WHERE m.url = 'Persediaan' AND ha.id_user_level = 1;\n\n"
    . "-- [Q5] Log generate persediaan terakhir\n"
    . "SELECT bulan_target, generate_insert, generate_update, created_at\n"
    . "FROM persediaan_gen_recalc_log ORDER BY created_at DESC LIMIT 5;\n\n"
    . "-- [Q6] Buku besar bulan berjalan\n"
    . "SELECT tanggal, kode_akun, keterangan, debet, kredit\n"
    . "FROM buku_besar\n"
    . "WHERE MONTH(tanggal) = MONTH(CURDATE())\n"
    . "ORDER BY tanggal, id;"
);

$doc->pageBreak();
$doc->title('8.6 Detail Struktur per Tabel', 2);
$doc->paragraph('Bagian berikut memuat struktur kolom lengkap (live dari database anekadharma_db), penjelasan fungsi tabel, relasi, dan query contoh untuk setiap tabel inti.');
db_schema_build_sections($doc);
$doc->pageBreak();

// 9. DIAGRAM
$doc->title('9. Diagram Sistem', 2);

$doc->title('9.1 Diagram Arsitektur Sistem (High-Level)', 3);
$doc->diagram(function ($d) {
    $d->layeredArchitecture();
});

$doc->title('9.2 Diagram Entity-Relationship (Inti Bisnis)', 3);
$doc->diagram(function ($d) {
    $d->entityDiagram();
});

$doc->title('9.3 Diagram Modul Aplikasi', 3);
$doc->diagram(function ($d) {
    $d->moduleTree();
});

$doc->title('9.4 Diagram Sequence: Login ke Dashboard', 3);
$doc->diagram(function ($d) {
    $d->sequenceLogin();
});
$doc->pageBreak();

// 10. INTEGRASI
$doc->title('10. Integrasi dan Teknologi Pendukung', 2);
$doc->table(
    ['Teknologi', 'Fungsi', 'Lokasi'],
    [
        ['AdminLTE 3.1.0', 'UI framework responsif', 'assets/AdminLTE310/'],
        ['DataTables', 'Tabel interaktif dengan sort/filter/export', 'View modul list'],
        ['DomPDF', 'Generate PDF dokumen', 'vendor/dompdf/'],
        ['TCPDF', 'Alternatif PDF', 'vendor/tecnickcom/'],
        ['Select2', 'Dropdown searchable', 'Form input'],
        ['WhatsApp API', 'MFA OTP, notifikasi login, reset password', 'KirimWa_model'],
        ['REST_Controller', 'API endpoints', 'RestApi, Penjualan_detail_rest'],
        ['ZipArchive (PHP)', 'Export arsip', 'Modul export'],
        ['Session PHP', 'State management login', 'CI Session library'],
    ]
);

$doc->title('10.1 Helper Penting', 3);
$doc->table(
    ['Helper', 'Fungsi Utama'],
    [
        ['racode_helper.php', 'is_login(), cmb_dinamis(), alert()'],
        ['login_security_helper.php', 'CSRF, MFA, rate limit, security headers'],
        ['pembelian_persediaan_helper.php', 'Logika bisnis persediaan, pembelian, penjualan (~7000 baris)'],
        ['persediaan_display_helper.php', 'Label kolom unit persediaan'],
        ['exportexcel_helper.php', 'Export Excel legacy'],
        ['nominal_helper.php', 'Format nominal Rupiah'],
    ]
);

$doc->title('10.2 Daftar Controller Lengkap (86 Controller)', 3);
$controllers = [
    'Anekadharmamasuk — Login, MFA, logout, forgot password',
    'Dashboard — Home, ringkasan jurnal kas, neraca, laba rugi',
    'Tbl_pembelian — Pembelian barang (SPOP), jurnal, cetak',
    'Tbl_pembelian_jasa — Pembelian jasa',
    'Tbl_Jasa — Master jasa',
    'Tbl_penjualan — Penjualan, rekap, cetak, bayar',
    'Tbl_penjualan_accounting — Penjualan versi akuntansi',
    'Tbl_penjualan_pembayaran — Pembayaran penjualan',
    'Persediaan — Persediaan bulanan, generate, recalculate',
    'Jurnal_umum — Jurnal umum',
    'Jurnal_kas — Jurnal kas harian',
    'Jurnal_pembelian — View jurnal pembelian',
    'Jurnal_penjualan — View jurnal penjualan',
    'Jurnal_penerimaan_kas — Penerimaan kas',
    'Jurnal_pengeluaran_kas — Pengeluaran kas',
    'Jurnal_penyesuaian — Jurnal penyesuaian',
    'Jurnal_kas_saldo_akhir_bulan — Saldo akhir kas',
    'Buku_besar — Buku besar',
    'Bukubank — Buku bank',
    'Neraca_saldo — Neraca saldo',
    'Tbl_laba_rugi — Laporan laba rugi',
    'Tbl_neraca_data — Data neraca',
    'Tbl_accounting_group — Grup akuntansi',
    'Tbl_accounting_detail — Detail akuntansi',
    'Tbl_kas_kecil — Kas kecil',
    'Tbl_penyusutan — Penyusutan aset',
    'Tbl_rekening_koran — Rekening koran',
    'Sys_nama_barang — Master barang',
    'Sys_konsumen — Master konsumen',
    'Sys_supplier — Master supplier',
    'Sys_unit — Unit bisnis',
    'Sys_kode_akun — Chart of accounts',
    'Sys_bank, Sys_gudang, Sys_pajak — Master data',
    'Tbl_user — Manajemen user',
    'Tbl_hak_akses — Hak akses menu',
    'Blokir — Halaman akses ditolak',
    'Laporan, LaporanDompdf, LaporanTcpdf — Laporan PDF',
    'RestApi, Kirimwa — API & WhatsApp',
    'Masuk, Masukgo — Login legacy',
];
foreach ($controllers as $c) {
    $doc->bullet($c);
}
$doc->pageBreak();

// 11. CATATAN
$doc->title('11. Catatan Arsitektur dan Rekomendasi', 2);

$doc->title('11.1 Catatan Teknis', 3);
$doc->bullet('Dua sistem menu: navigasi pakai tabel menu (filter id_user), guard akses pakai tbl_menu (filter id_user_level) — perlu diselaraskan saat maintenance.');
$doc->bullet('Duplikasi controller legacy: Tbl_pembelian_BU, Tbl_penjualan_X, Masuk/Masukgo — versi backup/eksperimental.');
$doc->bullet('Tidak ada CI migrations — perubahan skema via SQL manual.');
$doc->bullet('Modul cetak produksi (trans_cetak, trans_finishing, trans_gudang) — view/model ada, controller tidak aktif (sisa sistem lama).');
$doc->bullet('File besar inti bisnis: Tbl_pembelian.php (~5700 baris), Persediaan.php (~3400 baris), pembelian_persediaan_helper.php (~7000+ baris).');

$doc->title('11.2 Rekomendasi Pengembangan', 3);
$doc->bullet('Unifikasi sistem menu (menu vs tbl_menu) untuk konsistensi hak akses.');
$doc->bullet('Implementasi CI migrations untuk version control skema database.');
$doc->bullet('Refactoring helper pembelian_persediaan_helper.php menjadi service classes terpisah.');
$doc->bullet('Dokumentasi API REST untuk integrasi eksternal.');
$doc->bullet('Unit testing untuk modul persediaan (generate & recalculate).');
$doc->bullet('Backup otomatis database terjadwal.');

$doc->title('11.3 Penutup', 3);
$doc->paragraph('Dokumen blueprint ini merupakan gambaran menyeluruh sistem aplikasi Aneka Dharma per ' . date('d F Y') . '. Sistem ini merupakan ERP multi-unit yang mengintegrasikan pembelian, penjualan, persediaan, dan akuntansi dalam satu platform web berbasis CodeIgniter 3.');
$doc->paragraph('Untuk pembaruan dokumen ini, jalankan ulang script generator: php tools/generate_blueprint_docx.php');
$doc->paragraph('');
$doc->paragraph('— End of Document —', ['italic' => true, 'size' => 20]);

// ─── SAVE ───────────────────────────────────────────────────────────────────

$outputPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'Blueprint_updated.docx';

if (!is_dir(dirname($outputPath))) {
    mkdir(dirname($outputPath), 0755, true);
}

$savedPath = $doc->save($outputPath);

if (strpos($savedPath, '_updated') !== false) {
    for ($i = 0; $i < 8; $i++) {
        if (@copy($savedPath, $outputPath)) {
            @unlink($savedPath);
            $savedPath = $outputPath;
            echo "Blueprint.docx berhasil diperbarui (percobaan " . ($i + 1) . ").\n";
            break;
        }
        usleep(400000);
    }
}

echo "Blueprint berhasil dibuat: {$savedPath}\n";
echo "Ukuran file: " . number_format(filesize($savedPath)) . " bytes\n";
