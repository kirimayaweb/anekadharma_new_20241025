<?php
/**
 * Generate Blueprint Word Document (HTML-based .doc for print)
 * Run: php tools/generate_blueprint.php
 */
$repoPath = dirname(__DIR__);
chdir($repoPath);

function gitCount($args) {
    $cmd = 'git ' . $args . ' 2>nul';
    return trim(shell_exec($cmd));
}

$totalCommits = gitCount('rev-list --count HEAD');
$commitsUntil2025 = gitCount('rev-list --count --until="2025-12-31 23:59:59" HEAD');
$commits2026 = gitCount('rev-list --count --since="2026-01-01" HEAD');
$firstCommit = trim(shell_exec('git log --format="%ai" --reverse 2>nul | head -1'));
$lastCommit = gitCount('log --format="%ai" -1');
$now = date('d F Y, H:i');

$outputFile = $repoPath . DIRECTORY_SEPARATOR . 'BLUEPRINT_ANEKADHARMA.doc';

$html = <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<meta name="ProgId" content="Word.Document">
<meta name="Generator" content="Aneka Dharma Blueprint Generator">
<title>BLUEPRINT ANEKADHARMA</title>
<!--[if gte mso 9]><xml>
<w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom></w:WordDocument>
</xml><![endif]-->
<style>
@page { size: A4; margin: 2cm 2.5cm; }
body { font-family: 'Segoe UI', Calibri, Arial, sans-serif; font-size: 11pt; color: #2c3e50; line-height: 1.5; }
.cover { text-align: center; padding: 80px 40px; page-break-after: always; background: linear-gradient(135deg, #1a5276 0%, #2e86c1 50%, #7d3c98 100%); color: white; min-height: 900px; }
.cover h1 { font-size: 48pt; margin: 40px 0 10px; letter-spacing: 8px; font-weight: 700; }
.cover h2 { font-size: 18pt; font-weight: 300; margin: 10px 0; opacity: 0.95; }
.cover .app { font-size: 32pt; font-weight: 700; margin: 30px 0; color: #f9e79f; }
.cover .meta { font-size: 12pt; margin-top: 50px; line-height: 2; }
.cover .line { border-top: 2px solid rgba(255,255,255,0.5); width: 60%; margin: 30px auto; }
h1 { color: #1a5276; font-size: 22pt; border-bottom: 3px solid #2e86c1; padding-bottom: 8px; margin-top: 30px; page-break-before: always; }
h1.first { page-break-before: auto; }
h2 { color: #2e75b6; font-size: 16pt; margin-top: 24px; border-left: 5px solid #7d3c98; padding-left: 12px; }
h3 { color: #1f4e79; font-size: 13pt; margin-top: 18px; }
h4 { color: #566573; font-size: 11pt; margin-top: 14px; }
table { width: 100%; border-collapse: collapse; margin: 12px 0 20px; font-size: 10pt; }
th { background: #2e75b6; color: white; padding: 10px 12px; text-align: left; font-weight: 600; }
td { padding: 8px 12px; border: 1px solid #d5dbdb; vertical-align: top; }
tr:nth-child(even) td { background: #f4f6f7; }
.box { background: #eaf2f8; border-left: 5px solid #2e86c1; padding: 14px 18px; margin: 14px 0; border-radius: 0 6px 6px 0; }
.box-title { font-weight: 700; color: #1a5276; font-size: 11pt; margin-bottom: 6px; }
.box-body { font-size: 10pt; color: #34495e; }
ul { margin: 8px 0; padding-left: 24px; }
li { margin: 4px 0; font-size: 10.5pt; }
.toc { background: #f8f9fa; padding: 20px 30px; border: 1px solid #d5dbdb; margin: 20px 0; }
.toc li { margin: 8px 0; }
.phase { background: #fdfefe; border: 1px solid #aed6f1; padding: 16px; margin: 12px 0; border-radius: 6px; }
.stat-grid { display: table; width: 100%; margin: 16px 0; }
.stat-item { display: table-cell; text-align: center; padding: 16px; background: #2e75b6; color: white; border: 2px solid white; }
.stat-num { font-size: 24pt; font-weight: 700; }
.stat-label { font-size: 9pt; opacity: 0.9; }
pre { background: #f4f6f7; padding: 14px; font-family: Consolas, monospace; font-size: 9pt; border: 1px solid #d5dbdb; overflow-x: auto; }
.footer { text-align: center; color: #7f8c8d; font-size: 9pt; margin-top: 40px; padding-top: 20px; border-top: 1px solid #d5dbdb; }
.badge { display: inline-block; background: #7d3c98; color: white; padding: 2px 10px; border-radius: 12px; font-size: 9pt; }
.pagebreak { page-break-after: always; }
</style>
</head>
<body>

<!-- COVER -->
<div class="cover">
  <p style="font-size:14pt; opacity:0.8; margin-top:60px;">DOKUMEN RESMI</p>
  <h1>BLUEPRINT</h1>
  <h2>SISTEM INFORMASI AKUNTANSI &amp; MANAJEMEN</h2>
  <div class="app">ANEKA DHARMA</div>
  <div class="line"></div>
  <div class="meta">
    Dokumen Blueprint Pengembangan Aplikasi<br>
    Periode Analisis: 8 Oktober 2024 &ndash; 22 Juni 2026<br>
    Total Commit Git: <strong>{$totalCommits}</strong> commits<br>
  <span class="badge">Versi 1.0.1 [beta]</span>
  </div>
  <p style="margin-top:60px; font-size:10pt; opacity:0.7;">Dibuat otomatis dari riwayat Git Repository<br>{$now} WIB</p>
</div>

<!-- DAFTAR ISI -->
<h1 class="first">DAFTAR ISI</h1>
<div class="toc">
<ol>
<li>Ringkasan Eksekutif</li>
<li><strong>BAGIAN I</strong> &mdash; Blueprint Riwayat Git (Awal s/d 31 Desember 2025)</li>
<li><strong>BAGIAN II</strong> &mdash; Blueprint Riwayat Git (1 Januari 2026 s/d Sekarang)</li>
<li><strong>BAGIAN III</strong> &mdash; Blueprint Aplikasi Lengkap &amp; Komprehensif
  <ul>
    <li>3.1 Arsitektur Sistem</li>
    <li>3.2 Technology Stack</li>
    <li>3.3 Struktur MVC</li>
    <li>3.4 Modul Operasional Bisnis</li>
    <li>3.5 Modul Akuntansi &amp; Keuangan</li>
    <li>3.6 Modul Master Data &amp; Sistem</li>
    <li>3.7 Alur Data &amp; Integrasi Modul</li>
    <li>3.8 Keamanan &amp; Hak Akses</li>
    <li>3.9 Laporan &amp; Cetak Dokumen</li>
    <li>3.10 Peta File &amp; Struktur Direktori</li>
  </ul>
</li>
<li>Penutup</li>
</ol>
</div>

<!-- RINGKASAN -->
<h1>RINGKASAN EKSEKUTIF</h1>
<div class="box">
  <div class="box-title">Tentang Aplikasi</div>
  <div class="box-body">Aneka Dharma adalah sistem informasi terintegrasi berbasis web untuk manajemen akuntansi, persediaan, pembelian, penjualan, produksi, dan pelaporan keuangan. Aplikasi dibangun menggunakan framework CodeIgniter 3 dengan antarmuka AdminLTE 3.10, dirancang untuk mendukung operasional bisnis distribusi/perdagangan dengan pencatatan jurnal akuntansi otomatis.</div>
</div>

<table>
<tr><th>Aspek</th><th>Detail</th></tr>
<tr><td>Nama Proyek</td><td>Aneka Dharma (anekadharma_new_20241025)</td></tr>
<tr><td>Framework</td><td>CodeIgniter 3 (PHP 7.4)</td></tr>
<tr><td>UI Template</td><td>AdminLTE 3.10 + Bootstrap 5 + DataTables</td></tr>
<tr><td>Database</td><td>MySQL / MariaDB (mysqli driver)</td></tr>
<tr><td>Mulai Development</td><td>8 Oktober 2024</td></tr>
<tr><td>Commit s/d 31 Des 2025</td><td><strong>{$commitsUntil2025}</strong> commits</td></tr>
<tr><td>Commit 2026</td><td><strong>{$commits2026}</strong> commits</td></tr>
<tr><td>Total Commit</td><td><strong>{$totalCommits}</strong> commits</td></tr>
<tr><td>Commit Terakhir</td><td>{$lastCommit}</td></tr>
<tr><td>Default Controller</td><td>Anekadharmamasuk (Login)</td></tr>
<tr><td>Kontributor Utama</td><td>iwanesia.id, kirimaya, T480</td></tr>
</table>

<div class="pagebreak"></div>

<!-- BAGIAN I -->
<h1>BAGIAN I<br><span style="font-size:14pt;color:#566573;">Blueprint Riwayat Git &mdash; Awal s/d 31 Desember 2025</span></h1>
<p>Periode ini mencakup <strong>{$commitsUntil2025} commit</strong> dari inisialisasi proyek (8 Oktober 2024) hingga 31 Desember 2025. Pengembangan dibagi dalam 7 fase utama:</p>

<h2>Fase 1: Inisialisasi &amp; Setup Proyek (Okt 2024)</h2>
<table>
<tr><th>Tanggal</th><th>Milestone</th><th>Keterangan</th></tr>
<tr><td>08 Okt 2024</td><td>Inisialisasi Repository</td><td>Setup index, APPS folder, MASTER HOSTING</td></tr>
<tr><td>08-09 Okt 2024</td><td>Struktur CodeIgniter</td><td>Config, Controller, Core, Helpers, Models, Views, Libraries</td></tr>
<tr><td>09 Okt 2024</td><td>Assets &amp; Frontend</td><td>jQuery, JS, AdminLTE 3.10 dist, sistem folder</td></tr>
<tr><td>11 Okt 2024</td><td>Vendor Dependencies</td><td>Composer, TCPDF, DomPDF, PHP dependencies</td></tr>
<tr><td>12 Okt 2024</td><td>Application Layer</td><td>Struktur application/ lengkap</td></tr>
<tr><td>25 Okt 2024</td><td>Production Ready</td><td>Config production, index dev/production, update 20241025</td></tr>
</table>

<h2>Fase 2: Modul Neraca &amp; Laporan Keuangan (Okt-Nov 2024)</h2>
<ul>
<li>Neraca form, list, cetak, dan input tahunan</li>
<li>AdminLTE v2.30 integration untuk laporan neraca</li>
<li>Menu dinamis dan manajemen user</li>
<li>Rekap penjualan dan pendapatan lain-lain</li>
<li>Menu laba rugi</li>
<li>Persediaan dan kontrol lupa password</li>
</ul>

<h2>Fase 3: Sistem User, Menu &amp; Hak Akses (Nov 2024)</h2>
<ul>
<li>Menu dinamis berbasis database (tabel <code>menu</code>)</li>
<li>Tabel hak akses per user (<code>tbl_hak_akses</code>)</li>
<li>User controller, form user, user level</li>
<li>Sidebar controlling berdasarkan hak akses</li>
<li>Sistem is_login dan session management</li>
</ul>

<h2>Fase 4: Modul Jurnal Akuntansi (Des 2024)</h2>
<table>
<tr><th>Modul</th><th>Fitur Utama</th></tr>
<tr><td>Buku Bank</td><td>Pencatatan transaksi bank</td></tr>
<tr><td>Jurnal Kas</td><td>Jurnal kas masuk/keluar</td></tr>
<tr><td>Jurnal Pembelian</td><td>Auto-jurnal dari transaksi pembelian</td></tr>
<tr><td>Jurnal Penerimaan Kas</td><td>Pencatatan penerimaan kas</td></tr>
<tr><td>Jurnal Pengeluaran Kas</td><td>Pencatatan pengeluaran kas</td></tr>
<tr><td>Jurnal Penjualan</td><td>Auto-jurnal dari transaksi penjualan</td></tr>
<tr><td>Jurnal Umum</td><td>Jurnal umum manual</td></tr>
</table>

<h2>Fase 5: Pembelian, Penjualan &amp; Persediaan (Des 2024)</h2>
<ul>
<li>Form input pembelian dengan SPOP (Surat Pesanan Pembelian)</li>
<li>Integrasi pembelian ke persediaan otomatis</li>
<li>Form penjualan dengan kontrol stock real-time</li>
<li>Sys_nama_barang &mdash; master data barang</li>
<li>Kas Kecil, Pengajuan Pembayaran, Pembayaran ke Supplier</li>
<li>Pecah Satuan, Data Stock, Produksi, Uang Muka di Depan</li>
</ul>

<h2>Fase 6: Login, Dashboard &amp; Routing (Des 2024)</h2>
<ul>
<li>Anekadharmamasuk sebagai default controller (login)</li>
<li>Dashboard dengan summary data operasional</li>
<li>UUID supplier dan konsumen</li>
</ul>

<h2>Fase 7: Pengembangan Intensif 2025 (Jan-Agu 2025)</h2>
<table>
<tr><th>Bulan</th><th>Area Pengembangan</th><th>Highlight</th></tr>
<tr><td>Jan 2025</td><td>Pembelian &amp; Kas Kecil</td><td>Pecahan desimal, pengajuan termin, bea operasional</td></tr>
<tr><td>Feb 2025</td><td>Akuntansi Lengkap</td><td>Buku besar, buku bank, neraca saldo, jurnal, penyusutan</td></tr>
<tr><td>Mar 2025</td><td>Integrasi Jurnal</td><td>Setting kode akun pembelian/penjualan, buku besar model</td></tr>
<tr><td>Apr-Mei 2025</td><td>Pembayaran &amp; WA</td><td>Pembayaran supplier, WA broadcast, jurnal penyesuaian</td></tr>
<tr><td>Mei-Jun 2025</td><td>Laporan Keuangan</td><td>Neraca saldo, laba rugi, laporan neraca, form input neraca</td></tr>
<tr><td>Jun-Agu 2025</td><td>Refinement</td><td>Jurnal kas PL combo, neraca form, penjualan accounting</td></tr>
</table>

<h3>Ringkasan Statistik Periode I</h3>
<table>
<tr><th>Metrik</th><th>Nilai</th></tr>
<tr><td>Total Commit</td><td>{$commitsUntil2025}</td></tr>
<tr><td>Periode</td><td>8 Okt 2024 &mdash; 31 Des 2025</td></tr>
<tr><td>Controllers</td><td>88 file</td></tr>
<tr><td>Models</td><td>78 file</td></tr>
<tr><td>Views</td><td>350+ file</td></tr>
<tr><td>Modul Utama Selesai</td><td>Pembelian, Penjualan, Persediaan, Jurnal, Neraca, Laba Rugi</td></tr>
</table>

<div class="pagebreak"></div>

<!-- BAGIAN II -->
<h1>BAGIAN II<br><span style="font-size:14pt;color:#566573;">Blueprint Riwayat Git &mdash; 1 Januari 2026 s/d Sekarang</span></h1>
<p>Periode ini mencakup <strong>{$commits2026} commit</strong> dari 1 Januari 2026 hingga 22 Juni 2026. Fokus bergeser ke penyempurnaan modul, integrasi data, dan fitur compare/validasi.</p>

<h2>Timeline Pengembangan 2026</h2>
<table>
<tr><th>Periode</th><th>Commit</th><th>Fokus Utama</th></tr>
<tr><td>28 Jan 2026</td><td>3</td><td>Setup multi-device (laptop T450), view menu, test GitHub sync</td></tr>
<tr><td>7 Mei 2026</td><td>6</td><td>Persediaan combobox, cetak PDF, integrasi Stock</td></tr>
<tr><td>8-13 Mei 2026</td><td>8</td><td>Pembelian input barang, Penjualan cetak PDF, setting data barang</td></tr>
<tr><td>18-21 Mei 2026</td><td>15</td><td>Cetak Excel pembelian, update konsep persediaan, pembelian jasa</td></tr>
<tr><td>23-26 Mei 2026</td><td>12</td><td>Update penjualan ke persediaan, date picker, login &amp; menu form</td></tr>
<tr><td>29 Mei - 7 Jun 2026</td><td>8</td><td>Update konsumen, cetak penjualan, pembelian &amp; penjualan</td></tr>
<tr><td>9-15 Jun 2026</td><td>18</td><td>Monitoring System, input produksi, penjualan jasa, compare data</td></tr>
<tr><td>16-22 Jun 2026</td><td>40</td><td>Setting kode akun &amp; jurnal, compare jurnal, Jurnal Kas lengkap</td></tr>
</table>

<h2>Fitur Baru &amp; Peningkatan 2026</h2>
<div class="box"><div class="box-title">1. Konsep Persediaan Baru</div><div class="box-body">Revisi fundamental alur persediaan &mdash; sinkronisasi pembelian, penjualan, dan produksi ke tabel persediaan dengan UUID dan id_persediaan_barang yang lebih konsisten.</div></div>
<div class="box"><div class="box-title">2. Monitoring System</div><div class="box-body">Modul pemantauan operasional sistem. Controller: Monitoring_system.php, Model: Monitoring_system_model.php, Helper: monitoring_helper.php</div></div>
<div class="box"><div class="box-title">3. Penjualan Jasa</div><div class="box-body">Modul tbl_penjualan_jasa untuk transaksi penjualan jasa (non-barang), terpisah dari penjualan barang.</div></div>
<div class="box"><div class="box-title">4. Compare Data &amp; Validasi Jurnal</div><div class="box-body">Sistem compare helper: penjualan_jurnal_compare, jurnal_kas compare, buku_besar compare, buku_bank compare, jurnal_penyesuaian compare.</div></div>
<div class="box"><div class="box-title">5. Jurnal Kas Terpadu</div><div class="box-body">Pengembangan besar modul Jurnal Kas dengan sub-modul Penerimaan Kas dan Pengeluaran Kas, termasuk list helper dan compare helper.</div></div>
<div class="box"><div class="box-title">6. Cetak &amp; Export</div><div class="box-body">Cetak PDF persediaan, cetak PDF/Excel penjualan dan pembelian dengan filter tanggal otomatis.</div></div>

<h2>Perbandingan Periode</h2>
<table>
<tr><th>Aspek</th><th>s/d 31 Des 2025</th><th>2026 (s/d Jun)</th></tr>
<tr><td>Jumlah Commit</td><td>{$commitsUntil2025}</td><td>{$commits2026}</td></tr>
<tr><td>Fokus</td><td>Pembangunan modul dari nol</td><td>Penyempurnaan &amp; validasi data</td></tr>
<tr><td>Modul Baru</td><td>Semua modul utama</td><td>Monitoring System, Penjualan Jasa, Compare Data</td></tr>
<tr><td>Kualitas</td><td>Feature development</td><td>Data integrity &amp; jurnal validation</td></tr>
</table>

<div class="pagebreak"></div>

<!-- BAGIAN III -->
<h1>BAGIAN III<br><span style="font-size:14pt;color:#566573;">Blueprint Aplikasi Lengkap &amp; Komprehensif</span></h1>

<h2>3.1 Arsitektur Sistem</h2>
<p>Aplikasi menggunakan arsitektur <strong>MVC (Model-View-Controller)</strong> berbasis CodeIgniter 3:</p>
<table>
<tr><th>Layer</th><th>Komponen</th><th>Fungsi</th></tr>
<tr><td>Presentation</td><td>Views (AdminLTE 3.10)</td><td>Antarmuka pengguna, form, tabel, cetak</td></tr>
<tr><td>Business Logic</td><td>Controllers + Helpers</td><td>Proses bisnis, validasi, routing</td></tr>
<tr><td>Data Access</td><td>Models</td><td>Query database, CRUD operations</td></tr>
<tr><td>Database</td><td>MySQL/MariaDB</td><td>Penyimpanan data transaksi &amp; master</td></tr>
<tr><td>Assets</td><td>CSS/JS/Plugins</td><td>AdminLTE, DataTables, Select2, SweetAlert2</td></tr>
<tr><td>Vendor</td><td>Composer packages</td><td>TCPDF, DomPDF untuk generate PDF</td></tr>
</table>
<p><strong>Alur Request:</strong></p>
<ul>
<li>Browser &rarr; index.php &rarr; Router &rarr; Controller &rarr; Model &rarr; Database</li>
<li>Controller &rarr; load View dengan data &rarr; Render HTML (AdminLTE template)</li>
<li>AJAX request &rarr; Controller method &rarr; JSON response</li>
</ul>

<h2>3.2 Technology Stack</h2>
<table>
<tr><th>Kategori</th><th>Teknologi</th><th>Keterangan</th></tr>
<tr><td>Backend</td><td>PHP 7.4.33</td><td>Server-side language</td></tr>
<tr><td>Framework</td><td>CodeIgniter 3</td><td>MVC framework</td></tr>
<tr><td>Database</td><td>MySQL/MariaDB</td><td>mysqli driver</td></tr>
<tr><td>Frontend CSS</td><td>AdminLTE 3.10 + Bootstrap 5</td><td>UI template</td></tr>
<tr><td>JavaScript</td><td>jQuery + DataTables</td><td>Interaktif tabel data</td></tr>
<tr><td>UI Components</td><td>Select2, DatePicker, SweetAlert2</td><td>Form enhancement</td></tr>
<tr><td>PDF</td><td>TCPDF, DomPDF</td><td>Cetak laporan &amp; invoice</td></tr>
<tr><td>Excel</td><td>exportexcel_helper</td><td>Export data ke Excel</td></tr>
<tr><td>WA</td><td>KirimWa module</td><td>WhatsApp broadcast</td></tr>
<tr><td>Web Server</td><td>Apache (XAMPP)</td><td>mod_rewrite, .htaccess</td></tr>
</table>

<h2>3.3 Struktur MVC</h2>
<table>
<tr><th>Folder</th><th>Jumlah</th><th>Peran</th></tr>
<tr><td>application/controllers/</td><td>88 files</td><td>Handler HTTP request per modul</td></tr>
<tr><td>application/models/</td><td>78 files</td><td>Akses data &amp; business query</td></tr>
<tr><td>application/views/anekadharma/</td><td>350+ files</td><td>Template UI per modul</td></tr>
<tr><td>application/helpers/</td><td>24 files</td><td>Fungsi utilitas &amp; compare data</td></tr>
<tr><td>application/config/</td><td>15+ files</td><td>Konfigurasi app, database, routes</td></tr>
<tr><td>assets/AdminLTE310/</td><td>dist + plugins</td><td>CSS, JS, icon, template</td></tr>
<tr><td>vendor/</td><td>composer</td><td>Third-party libraries</td></tr>
<tr><td>tools/</td><td>scripts</td><td>Generator &amp; patch scripts</td></tr>
</table>

<h2>3.4 Modul Operasional Bisnis</h2>

<h3>A. Pembelian (Tbl_pembelian)</h3>
<table>
<tr><th>Komponen</th><th>File</th><th>Fungsi</th></tr>
<tr><td>Controller</td><td>Tbl_pembelian.php</td><td>CRUD pembelian, SPOP management</td></tr>
<tr><td>Model</td><td>Tbl_pembelian_model.php</td><td>Query data pembelian</td></tr>
<tr><td>Views</td><td>tbl_pembelian/*.php</td><td>Form, list, setting kode akun, jurnal</td></tr>
<tr><td>Helper</td><td>pembelian_persediaan_helper.php</td><td>Sync pembelian ke persediaan</td></tr>
<tr><td>Fitur</td><td colspan="2">Input barang, pengajuan bayar, cetak Excel/PDF, pecah satuan, pembelian jasa</td></tr>
</table>

<h3>B. Penjualan (Tbl_penjualan)</h3>
<table>
<tr><th>Komponen</th><th>File</th><th>Fungsi</th></tr>
<tr><td>Controller</td><td>Tbl_penjualan.php</td><td>CRUD penjualan, rekap, cetak</td></tr>
<tr><td>Model</td><td>Tbl_penjualan_model.php</td><td>Query data penjualan</td></tr>
<tr><td>Views</td><td>tbl_penjualan/*.php</td><td>Form input barang, list, rekap, cetak</td></tr>
<tr><td>Helper</td><td>penjualan_jurnal_compare_helper.php</td><td>Validasi jurnal penjualan</td></tr>
<tr><td>Fitur</td><td colspan="2">Input barang, kontrol stock, rekap per konsumen, cetak PDF</td></tr>
</table>

<h3>C. Penjualan Jasa (Tbl_penjualan_jasa)</h3>
<p>Modul transaksi jasa terpisah dari barang. Controller, model, dan views dedicated untuk penjualan jasa.</p>

<h3>D. Persediaan &amp; Stock</h3>
<table>
<tr><th>Modul</th><th>Controller</th><th>Fungsi</th></tr>
<tr><td>Persediaan</td><td>Persediaan.php</td><td>Manajemen data persediaan, sync pembelian/penjualan</td></tr>
<tr><td>Stock Barang</td><td>Views/stock/</td><td>Tampilan stock real-time</td></tr>
<tr><td>Sys Nama Barang</td><td>Sys_nama_barang.php</td><td>Master data nama barang &amp; kategori</td></tr>
<tr><td>Pecah Satuan</td><td>Tbl_pembelian_pecah_satuan</td><td>Konversi unit barang</td></tr>
<tr><td>Unit Produk</td><td>Sys_unit_produk.php</td><td>Master unit produk &amp; bahan produksi</td></tr>
<tr><td>Produksi</td><td>Sys_unit_produk views</td><td>Input produksi, bahan baku</td></tr>
</table>

<h3>E. Pembayaran &amp; Kas</h3>
<ul>
<li><strong>Kas Kecil</strong> (Tbl_kas_kecil) &mdash; transaksi kas kecil harian</li>
<li><strong>Pengajuan Pembayaran</strong> &mdash; approval pembayaran supplier</li>
<li><strong>Pembayaran ke Supplier</strong> &mdash; tracking lunas/hutang/termin</li>
<li><strong>Bea Operasional</strong> &mdash; biaya operasional</li>
<li><strong>Uang Muka di Depan</strong> &mdash; pencatatan uang muka</li>
</ul>

<div class="pagebreak"></div>

<h2>3.5 Modul Akuntansi &amp; Keuangan</h2>
<table>
<tr><th>Modul</th><th>Controller</th><th>Helper</th><th>Fungsi</th></tr>
<tr><td>Jurnal Kas</td><td>Jurnal_kas.php</td><td>jurnal_kas_list/compare</td><td>Jurnal kas terpadu</td></tr>
<tr><td>Penerimaan Kas</td><td>Jurnal_kas (sub)</td><td>penerimaan_kas_list/compare</td><td>Pencatatan kas masuk</td></tr>
<tr><td>Pengeluaran Kas</td><td>Jurnal_kas (sub)</td><td>pengeluaran_kas_list/compare</td><td>Pencatatan kas keluar</td></tr>
<tr><td>Jurnal Pembelian</td><td>Jurnal_pembelian.php</td><td>-</td><td>Auto-jurnal dari pembelian</td></tr>
<tr><td>Jurnal Penjualan</td><td>Jurnal_penjualan.php</td><td>penjualan_jurnal_compare</td><td>Auto-jurnal dari penjualan</td></tr>
<tr><td>Jurnal Umum</td><td>Jurnal_umum.php</td><td>jurnal_umum_list/compare</td><td>Jurnal manual</td></tr>
<tr><td>Jurnal Penyesuaian</td><td>Jurnal_penyesuaian.php</td><td>jurnal_penyesuaian_list/compare</td><td>Jurnal penyesuaian akhir periode</td></tr>
<tr><td>Buku Besar</td><td>Buku_besar.php</td><td>buku_besar_list/compare</td><td>General ledger</td></tr>
<tr><td>Buku Bank</td><td>Bukubank.php</td><td>bukubank_list/compare</td><td>Buku bank per rekening</td></tr>
<tr><td>Neraca Saldo</td><td>Neraca_saldo.php</td><td>-</td><td>Trial balance</td></tr>
<tr><td>Neraca</td><td>Tbl_neraca_data.php</td><td>-</td><td>Laporan neraca keuangan</td></tr>
<tr><td>Laba Rugi</td><td>Tbl_laba_rugi.php</td><td>-</td><td>Income statement</td></tr>
<tr><td>Penyusutan</td><td>Tbl_penyusutan.php</td><td>-</td><td>Depreciation schedule</td></tr>
<tr><td>Rekening Koran</td><td>Tbl_rekening_koran.php</td><td>-</td><td>Bank statement</td></tr>
<tr><td>Penjualan Accounting</td><td>Tbl_penjualan_accounting.php</td><td>-</td><td>Akuntansi penjualan</td></tr>
</table>

<h2>3.6 Modul Master Data &amp; Sistem</h2>
<table>
<tr><th>Kategori</th><th>Modul</th><th>Tabel/Controller</th></tr>
<tr><td>Master Barang</td><td>Nama Barang</td><td>sys_nama_barang</td></tr>
<tr><td>Master Partner</td><td>Supplier, Konsumen, Pengirim</td><td>sys_supplier, sys_konsumen, tbl_pengirim</td></tr>
<tr><td>Master Akun</td><td>Kode Akun, Accounting Group/Detail</td><td>sys_kode_akun, tbl_accounting_*</td></tr>
<tr><td>Master Organisasi</td><td>Unit, Gudang, Bank, Pajak, Kas Nominal</td><td>sys_unit, sys_gudang, sys_bank, sys_pajak</td></tr>
<tr><td>Sistem</td><td>User, Menu, Hak Akses, Login</td><td>tbl_user, menu, tbl_hak_akses, Anekadharmamasuk</td></tr>
<tr><td>Sistem</td><td>Dashboard, Monitoring, WA, REST API</td><td>Dashboard, Monitoring_system, Kirimwa, RestApi</td></tr>
</table>

<h2>3.7 Alur Data &amp; Integrasi Modul</h2>
<div class="box"><div class="box-title">Alur Pembelian &rarr; Persediaan &rarr; Jurnal</div><div class="box-body">PEMBELIAN (input barang + SPOP) &rarr; PERSEDIAAN (auto insert/update stock) &rarr; JURNAL PEMBELIAN (auto-generate) &rarr; BUKU BESAR &rarr; NERACA SALDO &rarr; NERACA</div></div>
<div class="box"><div class="box-title">Alur Penjualan &rarr; Persediaan &rarr; Jurnal</div><div class="box-body">PENJUALAN (input barang + kontrol stock) &rarr; PERSEDIAAN (kurangi stock) &rarr; JURNAL PENJUALAN &rarr; BUKU BESAR &rarr; LABA RUGI</div></div>
<div class="box"><div class="box-title">Alur Kas</div><div class="box-body">PENERIMAAN KAS / PENGELUARAN KAS &rarr; JURNAL KAS &rarr; BUKU BESAR &rarr; BUKU BANK &rarr; REKENING KORAN</div></div>
<div class="box"><div class="box-title">Alur Produksi</div><div class="box-body">INPUT PRODUKSI (sys_unit_produk) &rarr; PERSEDIAAN (kurangi bahan, tambah produk jadi) &rarr; STOCK BARANG</div></div>

<h2>3.8 Keamanan &amp; Hak Akses</h2>
<table>
<tr><th>Aspek</th><th>Implementasi</th></tr>
<tr><td>Autentikasi</td><td>Session-based login via Anekadharmamasuk</td></tr>
<tr><td>Autorisasi</td><td>tbl_hak_akses &mdash; menu per user</td></tr>
<tr><td>User Level</td><td>tbl_user_level &mdash; role-based access</td></tr>
<tr><td>Login Security</td><td>login_security_helper.php</td></tr>
<tr><td>XSS Protection</td><td>xss_helper.php</td></tr>
<tr><td>Password</td><td>Hash password, lupa password module</td></tr>
<tr><td>Session Check</td><td>is_login construct di setiap controller</td></tr>
</table>

<h2>3.9 Laporan &amp; Cetak Dokumen</h2>
<table>
<tr><th>Jenis Laporan</th><th>Format</th><th>Sumber</th></tr>
<tr><td>Cetak Penjualan</td><td>PDF</td><td>Tbl_penjualan</td></tr>
<tr><td>Cetak Pembelian</td><td>PDF/Excel</td><td>Tbl_pembelian</td></tr>
<tr><td>Cetak Persediaan</td><td>PDF</td><td>Persediaan</td></tr>
<tr><td>Cetak Pengajuan Bayar</td><td>PDF</td><td>Tbl_pembelian_pengajuan_bayar</td></tr>
<tr><td>Cetak Kas Kecil</td><td>PDF/Excel</td><td>Tbl_kas_kecil</td></tr>
<tr><td>Laporan Neraca</td><td>PDF/Print</td><td>Laporan, LaporanTcpdf, LaporanDompdf</td></tr>
<tr><td>Laba Rugi</td><td>PDF/Print</td><td>Tbl_laba_rugi</td></tr>
<tr><td>Neraca Saldo / Buku Besar / Buku Bank</td><td>Screen/Print</td><td>Masing-masing controller</td></tr>
<tr><td>Rekap Penjualan</td><td>Excel</td><td>Tbl_penjualan rekap</td></tr>
</table>

<h2>3.10 Peta File &amp; Struktur Direktori</h2>
<pre>anekadharma_new_20241025/
├── index.php                  (Front controller)
├── .htaccess                  (URL rewriting)
├── application/
│   ├── config/                (database, routes, autoload)
│   ├── controllers/           (88 controller files)
│   ├── models/                (78 model files)
│   ├── views/anekadharma/     (350+ view files per modul)
│   ├── helpers/               (24 helper files)
│   ├── libraries/             (Custom libraries)
│   └── core/                  (CI extensions)
├── assets/AdminLTE310/        (UI template &amp; plugins)
├── vendor/                    (Composer dependencies)
├── system/                    (CodeIgniter core)
└── tools/                     (Utility &amp; generator scripts)</pre>

<h3>Peta Modul View per Folder</h3>
<table>
<tr><th>Folder View</th><th>Modul</th></tr>
<tr><td>tbl_pembelian/</td><td>Pembelian (15+ files)</td></tr>
<tr><td>tbl_penjualan/</td><td>Penjualan (20+ files)</td></tr>
<tr><td>tbl_penjualan_jasa/</td><td>Penjualan Jasa (15+ files)</td></tr>
<tr><td>persediaan/, stock/</td><td>Persediaan &amp; Stock</td></tr>
<tr><td>jurnal_kas/, jurnal_umum/, jurnal_penyesuaian/</td><td>Modul Jurnal</td></tr>
<tr><td>buku_besar/, buku_bank/, neraca_saldo/</td><td>Laporan Akuntansi</td></tr>
<tr><td>sys_unit_produk/</td><td>Unit Produk / Produksi</td></tr>
<tr><td>monitoring_system/</td><td>Monitoring Sistem</td></tr>
<tr><td>Anekadharmamasuk/</td><td>Login / Auth</td></tr>
</table>

<div class="pagebreak"></div>

<!-- PENUTUP -->
<h1>PENUTUP</h1>
<div class="box">
  <div class="box-title">Kesimpulan</div>
  <div class="box-body">Aplikasi Aneka Dharma telah berkembang dari inisialisasi proyek pada Oktober 2024 menjadi sistem ERP/akuntansi terintegrasi dengan <strong>{$totalCommits} commit</strong>. Modul operasional (pembelian, penjualan, persediaan, produksi) terhubung langsung dengan modul akuntansi (jurnal, buku besar, neraca, laba rugi) melalui sistem kode akun dan auto-jurnal. Periode 2026 fokus pada validasi data (compare helpers) dan penyempurnaan modul jurnal kas.</div>
</div>

<p style="text-align:center; margin-top:40px; font-size:12pt;"><strong>&mdash; Akhir Dokumen &mdash;</strong></p>
<div class="footer">BLUEPRINT ANEKADHARMA | Dibuat {$now} WIB | anekadharma_new_20241025</div>

</body></html>
HTML;

file_put_contents($outputFile, $html);
echo "SUCCESS: $outputFile\n";
echo "Size: " . number_format(filesize($outputFile)) . " bytes\n";
