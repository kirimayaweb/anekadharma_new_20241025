# Generate Blueprint Word Document - Aneka Dharma
$ErrorActionPreference = "Stop"
$repoPath = "D:\xampp74\htdocs\anekadharma_new_20241025"
$outputFile = Join-Path $repoPath "BLUEPRINT_ANEKADHARMA.docx"

# Git stats
Push-Location $repoPath
$totalCommits = (git rev-list --count HEAD)
$commitsUntil2025 = (git rev-list --count --until="2025-12-31 23:59:59" HEAD)
$commits2026 = (git rev-list --count --since="2026-01-01" HEAD)
$firstCommit = (git log --format="%ai" --reverse | Select-Object -First 1)
$lastCommit = (git log --format="%ai" -1)
Pop-Location

$word = New-Object -ComObject Word.Application
$word.Visible = $false
$doc = $word.Documents.Add()

function Set-Font($range, $name, $size, $bold=$false, $color=$null, $italic=$false) {
    $range.Font.Name = $name
    $range.Font.Size = $size
    $range.Font.Bold = $bold
    $range.Font.Italic = $italic
    if ($color) { $range.Font.Color = $color }
}

function Add-Heading($text, $level) {
    $p = $doc.Content.Paragraphs.Add()
    $p.Range.Text = $text
    $p.Range.set_Style("Heading $level")
    $p.Range.Font.Color = 0x7030A0  # purple accent
    $p.SpaceAfter = 6
    return $p
}

function Add-Paragraph($text, $size=11, $bold=$false, $indent=0, $spaceAfter=6) {
    $p = $doc.Content.Paragraphs.Add()
    $p.Range.Text = $text
    Set-Font $p.Range "Calibri" $size $bold
    if ($indent -gt 0) { $p.Range.ParagraphFormat.LeftIndent = $indent }
    $p.Range.ParagraphFormat.SpaceAfter = $spaceAfter
    return $p
}

function Add-Bullet($text, $size=10) {
    $p = $doc.Content.Paragraphs.Add()
    $p.Range.Text = $text
    Set-Font $p.Range "Calibri" $size
    $p.Range.ListFormat.ApplyBulletDefault()
    $p.Range.ParagraphFormat.SpaceAfter = 3
    return $p
}

function Add-ColorBox($title, $body, $bgColor=0xE8F4FD) {
    $p = $doc.Content.Paragraphs.Add()
    $p.Range.Text = $title
    Set-Font $p.Range "Calibri" 12 $true 0x1F4E79
    $p.Range.Shading.BackgroundPatternColor = $bgColor
    $p.Range.ParagraphFormat.SpaceAfter = 2
    $p2 = $doc.Content.Paragraphs.Add()
    $p2.Range.Text = $body
    Set-Font $p2.Range "Calibri" 10
    $p2.Range.Shading.BackgroundPatternColor = $bgColor
    $p2.Range.ParagraphFormat.SpaceAfter = 10
}

function Add-Table($headers, $rows) {
    $cols = $headers.Count
    $table = $doc.Tables.Add($doc.Content.Paragraphs.Add().Range, ($rows.Count + 1), $cols)
    $table.Style = "Grid Table 4 - Accent 1"
    for ($c = 0; $c -lt $cols; $c++) {
        $cell = $table.Cell(1, $c + 1)
        $cell.Range.Text = $headers[$c]
        $cell.Range.Font.Bold = $true
        $cell.Range.Font.Color = 0xFFFFFF
        $cell.Shading.BackgroundPatternColor = 0x2E75B6
    }
    for ($r = 0; $r -lt $rows.Count; $r++) {
        for ($c = 0; $c -lt $cols; $c++) {
            $table.Cell($r + 2, $c + 1).Range.Text = $rows[$r][$c]
            $table.Cell($r + 2, $c + 1).Range.Font.Size = 9
        }
    }
    $doc.Content.Paragraphs.Add() | Out-Null
}

function Add-PageBreak {
    $doc.Content.Paragraphs.Add().Range.InsertBreak(7) | Out-Null  # wdPageBreak
}

# ===== COVER PAGE =====
$cover = $doc.Content.Paragraphs.Add()
$cover.Range.Text = ""
$cover.Range.ParagraphFormat.Alignment = 1  # center
$cover.Range.InsertBreak(7) | Out-Null

$sp = $doc.Content.Paragraphs.Add()
$sp.Range.Text = ""
$sp.Range.ParagraphFormat.SpaceBefore = 120

$title = $doc.Content.Paragraphs.Add()
$title.Range.Text = "BLUEPRINT"
$title.Range.ParagraphFormat.Alignment = 1
Set-Font $title.Range "Segoe UI" 36 $true 0x1F4E79
$title.Range.ParagraphFormat.SpaceAfter = 12

$subtitle = $doc.Content.Paragraphs.Add()
$subtitle.Range.Text = "SISTEM INFORMASI AKUNTANSI & MANAJEMEN"
$subtitle.Range.ParagraphFormat.Alignment = 1
Set-Font $subtitle.Range "Segoe UI" 18 $false 0x2E75B6
$subtitle.Range.ParagraphFormat.SpaceAfter = 6

$appname = $doc.Content.Paragraphs.Add()
$appname.Range.Text = "ANEKA DHARMA"
$appname.Range.ParagraphFormat.Alignment = 1
Set-Font $appname.Range "Segoe UI" 28 $true 0x7030A0
$appname.Range.ParagraphFormat.SpaceAfter = 24

$line = $doc.Content.Paragraphs.Add()
$line.Range.Text = "_________________________________________________________"
$line.Range.ParagraphFormat.Alignment = 1
Set-Font $line.Range "Calibri" 11

$meta = $doc.Content.Paragraphs.Add()
$meta.Range.Text = "Dokumen Blueprint Pengembangan Aplikasi`nPeriode Analisis: 8 Oktober 2024 - 22 Juni 2026`nTotal Commit Git: $totalCommits commits`nVersi Aplikasi: 1.0.1 [beta]"
$meta.Range.ParagraphFormat.Alignment = 1
Set-Font $meta.Range "Calibri" 12
$meta.Range.ParagraphFormat.SpaceAfter = 36

$gen = $doc.Content.Paragraphs.Add()
$gen.Range.Text = "Dibuat otomatis dari riwayat Git Repository`n$(Get-Date -Format 'dd MMMM yyyy, HH:mm') WIB"
$gen.Range.ParagraphFormat.Alignment = 1
Set-Font $gen.Range "Calibri" 10 $false 0x666666

Add-PageBreak

# ===== DAFTAR ISI =====
Add-Heading "DAFTAR ISI" 1
Add-Bullet "Ringkasan Eksekutif"
Add-Bullet "BAGIAN I - Blueprint Riwayat Git (Awal s/d 31 Desember 2025)"
Add-Bullet "BAGIAN II - Blueprint Riwayat Git (1 Januari 2026 s/d Sekarang)"
Add-Bullet "BAGIAN III - Blueprint Aplikasi Lengkap dan Komprehensif"
Add-Bullet "   3.1 Arsitektur Sistem"
Add-Bullet "   3.2 Technology Stack"
Add-Bullet "   3.3 Struktur MVC (Model-View-Controller)"
Add-Bullet "   3.4 Modul Operasional Bisnis"
Add-Bullet "   3.5 Modul Akuntansi & Keuangan"
Add-Bullet "   3.6 Modul Master Data & Sistem"
Add-Bullet "   3.7 Alur Data & Integrasi Modul"
Add-Bullet "   3.8 Keamanan & Hak Akses"
Add-Bullet "   3.9 Laporan & Cetak Dokumen"
Add-Bullet "   3.10 Peta File & Struktur Direktori"
Add-PageBreak

# ===== RINGKASAN EKSEKUTIF =====
Add-Heading "RINGKASAN EKSEKUTIF" 1
Add-ColorBox "Tentang Aplikasi" "Aneka Dharma adalah sistem informasi terintegrasi berbasis web untuk manajemen akuntansi, persediaan, pembelian, penjualan, produksi, dan pelaporan keuangan. Aplikasi dibangun menggunakan framework CodeIgniter 3 dengan antarmuka AdminLTE 3.10, dirancang untuk mendukung operasional bisnis distribusi/perdagangan dengan pencatatan jurnal akuntansi otomatis."

Add-Table @("Aspek", "Detail") @(
    @("Nama Proyek", "Aneka Dharma (anekadharma_new_20241025)"),
    @("Framework", "CodeIgniter 3 (PHP 7.4)"),
    @("UI Template", "AdminLTE 3.10 + Bootstrap 5 + DataTables"),
    @("Database", "MySQL / MariaDB (mysqli driver)"),
    @("Mulai Development", "8 Oktober 2024"),
    @("Commit s/d 31 Des 2025", "$commitsUntil2025 commits"),
    @("Commit 2026", "$commits2026 commits"),
    @("Total Commit", "$totalCommits commits"),
    @("Commit Terakhir", $lastCommit),
    @("Default Controller", "Anekadharmamasuk (Login)"),
    @("Kontributor Utama", "iwanesia.id, kirimaya, T480")
)

Add-PageBreak

# ===== BAGIAN I =====
Add-Heading "BAGIAN I" 1
Add-Heading "Blueprint Riwayat Git - Awal s/d 31 Desember 2025" 2
Add-Paragraph "Periode ini mencakup $commitsUntil2025 commit dari inisialisasi proyek (8 Oktober 2024) hingga 31 Desember 2025. Pengembangan dibagi dalam fase-fase berikut:" 11

Add-Heading "Fase 1: Inisialisasi dan Setup Proyek (Okt 2024)" 3
Add-Table @("Tanggal", "Milestone", "Keterangan") @(
    @("08 Okt 2024", "Inisialisasi Repository", "Setup index, APPS folder, MASTER HOSTING"),
    @("08-09 Okt 2024", "Struktur CodeIgniter", "Config, Controller, Core, Helpers, Models, Views, Libraries"),
    @("09 Okt 2024", "Assets & Frontend", "jQuery, JS, AdminLTE 3.10 dist, sistem folder"),
    @("11 Okt 2024", "Vendor Dependencies", "Composer, TCPDF, DomPDF, PHP dependencies"),
    @("12 Okt 2024", "Application Layer", "Struktur application/ lengkap"),
    @("25 Okt 2024", "Production Ready", "Config production, index dev/production, update 20241025")
)

Add-Heading "Fase 2: Modul Neraca dan Laporan Keuangan (Okt-Nov 2024)" 3
Add-Bullet "Neraca form, list, cetak, dan input tahunan"
Add-Bullet "AdminLTE v2.30 integration untuk laporan neraca"
Add-Bullet "Menu dinamis dan manajemen user"
Add-Bullet "Rekap penjualan dan pendapatan lain-lain"
Add-Bullet "Menu laba rugi"
Add-Bullet "Persediaan dan kontrol lupa password"

Add-Heading "Fase 3: Sistem User, Menu dan Hak Akses (Nov 2024)" 3
Add-Bullet "Menu dinamis berbasis database (tabel menu)"
Add-Bullet "Tabel hak akses per user (tbl_hak_akses)"
Add-Bullet "User controller, form user, user level"
Add-Bullet "Sidebar controlling berdasarkan hak akses"
Add-Bullet "Sistem is_login dan session management"

Add-Heading "Fase 4: Modul Jurnal Akuntansi (Des 2024)" 3
Add-Table @("Modul", "Fitur Utama") @(
    @("Buku Bank", "Pencatatan transaksi bank"),
    @("Jurnal Kas", "Jurnal kas masuk/keluar"),
    @("Jurnal Pembelian", "Auto-jurnal dari transaksi pembelian"),
    @("Jurnal Penerimaan Kas", "Pencatatan penerimaan kas"),
    @("Jurnal Pengeluaran Kas", "Pencatatan pengeluaran kas"),
    @("Jurnal Penjualan", "Auto-jurnal dari transaksi penjualan"),
    @("Jurnal Umum", "Jurnal umum manual")
)

Add-Heading "Fase 5: Pembelian, Penjualan dan Persediaan (Des 2024)" 3
Add-Bullet "Form input pembelian dengan SPOP (Surat Pesanan Pembelian)"
Add-Bullet "Integrasi pembelian ke persediaan otomatis"
Add-Bullet "Form penjualan dengan kontrol stock real-time"
Add-Bullet "Sys_nama_barang - master data barang"
Add-Bullet "Refresh data persediaan dari pembelian/penjualan"
Add-Bullet "Kas Kecil - pencatatan transaksi kas kecil"
Add-Bullet "Pengajuan Pembayaran ke supplier"
Add-Bullet "Pembayaran ke supplier (status Lunas/Hutang)"
Add-Bullet "Pecah Satuan - konversi unit barang"
Add-Bullet "Data Stock & Stock Barang"
Add-Bullet "Produksi & Input Produk"
Add-Bullet "Uang Muka di Depan"

Add-Heading "Fase 6: Login, Dashboard dan Routing (Des 2024)" 3
Add-Bullet "Anekadharmamasuk sebagai default controller (login)"
Add-Bullet "Redirect landing page dan welcome off"
Add-Bullet "Dashboard dengan summary data"
Add-Bullet "UUID supplier dan konsumen"

Add-Heading "Fase 7: Pengembangan Intensif 2025 (Jan-Agu 2025)" 3
Add-Paragraph "Tahun 2025 merupakan periode pengembangan paling intensif dengan fokus pada:" 11

Add-Table @("Bulan", "Area Pengembangan", "Highlight") @(
    @("Jan 2025", "Pembelian dan Kas Kecil", "Pecahan desimal, pengajuan pembayaran termin, bea operasional"),
    @("Feb 2025", "Akuntansi Lengkap", "Buku besar, buku bank, neraca saldo, jurnal lengkap, penyusutan"),
    @("Mar 2025", "Integrasi Jurnal", "Setting kode akun pembelian/penjualan, buku besar model"),
    @("Apr-Mei 2025", "Pembayaran dan WA", "Pembayaran supplier, WA broadcast, jurnal penyesuaian"),
    @("Mei-Jun 2025", "Laporan Keuangan", "Neraca saldo, laba rugi, laporan neraca, form input neraca"),
    @("Jun-Agu 2025", "Refinement", "Jurnal kas PL combo, neraca form, penjualan accounting, buku besar index")
)

Add-Heading "Ringkasan Statistik Periode I" 3
Add-Table @("Metrik", "Nilai") @(
    @("Total Commit", "$commitsUntil2025"),
    @("Periode", "8 Okt 2024 - 31 Des 2025"),
    @("Controllers Dikembangkan", "80+ controller files"),
    @("Models", "75+ model files"),
    @("Views", "350+ view files"),
    @("Modul Utama Selesai", "Pembelian, Penjualan, Persediaan, Jurnal, Neraca, Laba Rugi")
)

Add-PageBreak

# ===== BAGIAN II =====
Add-Heading "BAGIAN II" 1
Add-Heading "Blueprint Riwayat Git - 1 Januari 2026 s/d Sekarang" 2
Add-Paragraph "Periode ini mencakup $commits2026 commit dari 1 Januari 2026 hingga 22 Juni 2026. Fokus pengembangan bergeser ke penyempurnaan modul, integrasi data, dan fitur compare/validasi." 11

Add-Heading "Timeline Pengembangan 2026" 3
Add-Table @("Periode", "Commit", "Fokus Utama") @(
    @("28 Jan 2026", "3", "Setup multi-device (laptop T450), view menu, test GitHub sync"),
    @("7 Mei 2026", "6", "Persediaan combobox, cetak PDF, integrasi Stock"),
    @("8-13 Mei 2026", "8", "Pembelian input barang, Penjualan cetak PDF, setting data barang"),
    @("18-21 Mei 2026", "15", "Cetak Excel pembelian, update konsep persediaan, pembelian jasa, sys nama barang"),
    @("23-26 Mei 2026", "12", "Update penjualan ke persediaan, date picker, login & menu form"),
    @("29 Mei - 7 Jun 2026", "8", "Update konsumen, cetak penjualan, pembelian & penjualan"),
    @("9-15 Jun 2026", "18", "Monitoring System, input produksi, penjualan jasa, persediaan stock, compare data"),
    @("16-22 Jun 2026", "40", "Setting kode akun & jurnal, compare jurnal, Jurnal Kas lengkap (penerimaan & pengeluaran)")
)

Add-Heading "Fitur Baru dan Peningkatan 2026" 3
Add-ColorBox "1. Konsep Persediaan Baru" "Revisi fundamental alur persediaan - sinkronisasi pembelian, penjualan, dan produksi ke tabel persediaan dengan konsep UUID dan id_persediaan_barang yang lebih konsisten."

Add-ColorBox "2. Monitoring System" "Modul monitoring_system untuk pemantauan operasional sistem secara real-time. Controller: Monitoring_system.php, Model: Monitoring_system_model.php, Helper: monitoring_helper.php"

Add-ColorBox "3. Penjualan Jasa" "Modul baru tbl_penjualan_jasa untuk transaksi penjualan jasa (non-barang). Controller, model, dan views terpisah dari penjualan barang."

Add-ColorBox "4. Compare Data & Validasi Jurnal" "Sistem compare helper untuk validasi konsistensi data antara transaksi dan jurnal akuntansi: penjualan_jurnal_compare, pembelian compare, jurnal_kas compare, buku_besar compare, neraca_saldo compare."

Add-ColorBox "5. Jurnal Kas Terpadu" "Pengembangan besar modul Jurnal Kas dengan sub-modul Penerimaan Kas dan Pengeluaran Kas, termasuk list helper dan compare helper untuk validasi data."

Add-ColorBox "6. Cetak & Export" "Peningkatan fitur cetak PDF persediaan, cetak PDF/Excel penjualan dan pembelian dengan filter tanggal otomatis."

Add-Heading "Perbandingan Periode" 3
Add-Table @("Aspek", "s/d 31 Des 2025", "2026 (s/d Jun)") @(
    @("Jumlah Commit", "$commitsUntil2025", "$commits2026"),
    @("Fokus", "Pembangunan modul dari nol", "Penyempurnaan & validasi data"),
    @("Modul Baru", "Semua modul utama", "Monitoring System, Penjualan Jasa, Compare Data"),
    @("Kualitas", "Feature development", "Data integrity dan jurnal validation"),
    @("Helper Baru", "exportexcel, nominal", "12+ compare & list helpers")
)

Add-PageBreak

# ===== BAGIAN III =====
Add-Heading "BAGIAN III" 1
Add-Heading "Blueprint Aplikasi Lengkap dan Komprehensif" 2

Add-Heading "3.1 Arsitektur Sistem" 3
Add-Paragraph "Aplikasi Aneka Dharma menggunakan arsitektur MVC (Model-View-Controller) berbasis CodeIgniter 3 dengan pola request-response standar web:" 11

Add-Table @("Layer", "Komponen", "Fungsi") @(
    @("Presentation", "Views (AdminLTE 3.10)", "Antarmuka pengguna, form input, tabel data, cetak"),
    @("Business Logic", "Controllers + Helpers", "Proses bisnis, validasi, routing request"),
    @("Data Access", "Models", "Query database, CRUD operations"),
    @("Database", "MySQL/MariaDB", "Penyimpanan data transaksi & master"),
    @("Assets", "CSS/JS/Plugins", "AdminLTE, DataTables, Select2, SweetAlert2, DatePicker"),
    @("Vendor", "Composer packages", "TCPDF, DomPDF untuk generate PDF")
)

Add-Paragraph "Alur Request:" 11 $true
Add-Bullet "Browser → index.php → Router (routes.php) → Controller → Model → Database"
Add-Bullet "Controller → load View dengan data → Render HTML (AdminLTE template)"
Add-Bullet "AJAX request → Controller method → JSON response"

Add-Heading "3.2 Technology Stack" 3
Add-Table @("Kategori", "Teknologi", "Versi/Keterangan") @(
    @("Backend", "PHP", "7.4.33"),
    @("Framework", "CodeIgniter", "3.x"),
    @("Database", "MySQL/MariaDB", "mysqli driver"),
    @("Frontend CSS", "AdminLTE + Bootstrap", "3.10 / 5.2.3"),
    @("JavaScript", "jQuery + DataTables", "Fixed columns, responsive"),
    @("UI Components", "Select2, DatePicker, SweetAlert2", "Form enhancement"),
    @("PDF Generation", "TCPDF, DomPDF", "Cetak laporan & invoice"),
    @("Excel Export", "Custom exportexcel_helper", "Export data ke Excel"),
    @("WA Integration", "KirimWa module", "WhatsApp broadcast notification"),
    @("Web Server", "Apache (XAMPP)", "mod_rewrite, .htaccess")
)

Add-Heading "3.3 Struktur MVC" 3
Add-Table @("Folder", "Jumlah File", "Peran") @(
    @("application/controllers/", "88 files", "Handler HTTP request per modul"),
    @("application/models/", "78 files", "Akses data & business query"),
    @("application/views/anekadharma/", "350+ files", "Template UI per modul"),
    @("application/helpers/", "24 files", "Fungsi utilitas & compare data"),
    @("application/config/", "15+ files", "Konfigurasi app, database, routes"),
    @("assets/AdminLTE310/", "dist + plugins", "CSS, JS, icon, template"),
    @("vendor/", "composer packages", "Third-party libraries"),
    @("tools/", "utility scripts", "Generator & patch scripts")
)

Add-Heading "3.4 Modul Operasional Bisnis" 3

Add-Heading "A. Pembelian (Tbl_pembelian)" 4
Add-Table @("Komponen", "File", "Fungsi") @(
    @("Controller", "Tbl_pembelian.php", "CRUD pembelian, SPOP management"),
    @("Model", "Tbl_pembelian_model.php", "Query data pembelian"),
    @("Views", "tbl_pembelian/*.php", "Form, list, setting kode akun, jurnal"),
    @("Helper", "pembelian_persediaan_helper.php", "Sync pembelian ke persediaan"),
    @("Fitur", "-", "Input barang, pengajuan bayar, cetak Excel/PDF, pecah satuan")
)

Add-Heading "B. Penjualan (Tbl_penjualan)" 4
Add-Table @("Komponen", "File", "Fungsi") @(
    @("Controller", "Tbl_penjualan.php", "CRUD penjualan, rekap, cetak"),
    @("Model", "Tbl_penjualan_model.php", "Query data penjualan"),
    @("Views", "tbl_penjualan/*.php", "Form input barang, list, rekap, cetak"),
    @("Helper", "penjualan_jurnal_compare_helper.php", "Validasi jurnal penjualan"),
    @("Fitur", "-", "Input barang, kontrol stock, rekap per konsumen, cetak PDF")
)

Add-Heading "C. Penjualan Jasa (Tbl_penjualan_jasa)" 4
Add-Bullet "Controller: Tbl_penjualan_jasa.php - transaksi jasa terpisah dari barang"
Add-Bullet "Views: form input jasa, list, rekap, cetak penjualan jasa"

Add-Heading "D. Persediaan dan Stock" 4
Add-Table @("Modul", "Controller", "Fungsi") @(
    @("Persediaan", "Persediaan.php", "Manajemen data persediaan, sync pembelian/penjualan"),
    @("Stock Barang", "Views/stock/", "Tampilan stock real-time"),
    @("Sys Nama Barang", "Sys_nama_barang.php", "Master data nama barang & kategori"),
    @("Pecah Satuan", "Tbl_pembelian_pecah_satuan", "Konversi unit barang"),
    @("Unit Produk", "Sys_unit_produk.php", "Master unit produk & bahan produksi"),
    @("Produksi", "Sys_unit_produk views", "Input produksi, bahan baku")
)

Add-Heading "E. Pembayaran dan Kas" 4
Add-Bullet "Kas Kecil (Tbl_kas_kecil) - transaksi kas kecil harian"
Add-Bullet "Pengajuan Pembayaran (Tbl_pembelian_pengajuan_bayar) - approval pembayaran supplier"
Add-Bullet "Pembayaran ke Supplier - tracking status lunas/hutang/termin"
Add-Bullet "Bea Operasional (Tbl_bea_operasional) - biaya operasional"
Add-Bullet "Uang Muka di Depan (Tbl_uang_muka_didepan)"

Add-PageBreak

Add-Heading "3.5 Modul Akuntansi dan Keuangan" 3
Add-Table @("Modul", "Controller", "Helper", "Fungsi Utama") @(
    @("Jurnal Kas", "Jurnal_kas.php", "jurnal_kas_list/compare", "Jurnal kas terpadu"),
    @("Penerimaan Kas", "Jurnal_kas (sub)", "penerimaan_kas_list/compare", "Pencatatan kas masuk"),
    @("Pengeluaran Kas", "Jurnal_kas (sub)", "pengeluaran_kas_list/compare", "Pencatatan kas keluar"),
    @("Jurnal Pembelian", "Jurnal_pembelian.php", "-", "Auto-jurnal dari pembelian"),
    @("Jurnal Penjualan", "Jurnal_penjualan.php", "penjualan_jurnal_compare", "Auto-jurnal dari penjualan"),
    @("Jurnal Umum", "Jurnal_umum.php", "jurnal_umum_list/compare", "Jurnal manual"),
    @("Jurnal Penyesuaian", "Jurnal_penyesuaian.php", "jurnal_penyesuaian_list/compare", "Jurnal penyesuaian akhir periode"),
    @("Buku Besar", "Buku_besar.php", "buku_besar_list/compare", "General ledger"),
    @("Buku Bank", "Bukubank.php", "bukubank_list/compare", "Buku bank per rekening"),
    @("Neraca Saldo", "Neraca_saldo.php", "-", "Trial balance"),
    @("Neraca", "Tbl_neraca_data.php", "-", "Laporan neraca keuangan"),
    @("Laba Rugi", "Tbl_laba_rugi.php", "-", "Income statement"),
    @("Penyusutan", "Tbl_penyusutan.php", "-", "Depreciation schedule"),
    @("Rekening Koran", "Tbl_rekening_koran.php", "-", "Bank statement"),
    @("Penjualan Accounting", "Tbl_penjualan_accounting.php", "-", "Akuntansi penjualan")
)

Add-Heading "3.6 Modul Master Data dan Sistem" 3
Add-Table @("Kategori", "Modul", "Tabel/Controller") @(
    @("Master Barang", "Nama Barang", "sys_nama_barang"),
    @("Master Partner", "Supplier", "sys_supplier"),
    @("Master Partner", "Konsumen", "sys_konsumen"),
    @("Master Partner", "Pengirim", "tbl_pengirim"),
    @("Master Akun", "Kode Akun", "sys_kode_akun"),
    @("Master Akun", "Accounting Group", "tbl_accounting_group"),
    @("Master Akun", "Accounting Detail", "tbl_accounting_detail"),
    @("Master Organisasi", "Unit", "sys_unit"),
    @("Master Organisasi", "Gudang", "sys_gudang"),
    @("Master Organisasi", "Bank", "sys_bank"),
    @("Master Organisasi", "Pajak", "sys_pajak"),
    @("Master Organisasi", "Kas Nominal", "sys_kas_nominal"),
    @("Sistem", "User dan Level", "tbl_user, tbl_user_level"),
    @("Sistem", "Menu Dinamis", "menu, tbl_hak_akses"),
    @("Sistem", "Login/Auth", "Anekadharmamasuk, Authpage"),
    @("Sistem", "Dashboard", "Dashboard.php"),
    @("Sistem", "Monitoring", "Monitoring_system.php"),
    @("Sistem", "WA Broadcast", "Kirimwa.php"),
    @("Sistem", "REST API", "RestApi.php, Penjualan_detail_rest.php")
)

Add-Heading "3.7 Alur Data dan Integrasi Modul" 3
Add-Paragraph "Diagram alur integrasi data antar modul utama:" 11 $true

Add-ColorBox "Alur Pembelian → Persediaan → Jurnal" "PEMBELIAN (input barang + SPOP) → PERSEDIAAN (auto insert/update stock) → JURNAL PEMBELIAN (auto-generate berdasarkan kode akun) → BUKU BESAR → NERACA SALDO → NERACA / LAPORAN"

Add-ColorBox "Alur Penjualan → Persediaan → Jurnal" "PENJUALAN (input barang + kontrol stock) → PERSEDIAAN (kurangi stock) → JURNAL PENJUALAN (auto-generate) → BUKU BESAR → LABA RUGI"

Add-ColorBox "Alur Kas" "PENERIMAAN KAS / PENGELUARAN KAS → JURNAL KAS → BUKU BESAR → BUKU BANK → REKENING KORAN"

Add-ColorBox "Alur Produksi" "INPUT PRODUKSI (sys_unit_produk) → PERSEDIAAN (kurangi bahan, tambah produk jadi) → STOCK BARANG"

Add-Heading "3.8 Keamanan dan Hak Akses" 3
Add-Table @("Aspek", "Implementasi") @(
    @("Autentikasi", "Session-based login via Anekadharmamasuk"),
    @("Autorisasi", "tbl_hak_akses - menu per user"),
    @("User Level", "tbl_user_level - role-based access"),
    @("Login Security", "login_security_helper.php"),
    @("XSS Protection", "xss_helper.php"),
    @("Password", "Hash password, lupa password module"),
    @("Session Check", "is_login construct di setiap controller"),
    @("Menu Dinamis", "Menu hanya tampil sesuai hak akses user aktif")
)

Add-Heading "3.9 Laporan dan Cetak Dokumen" 3
Add-Table @("Jenis Laporan", "Format", "Controller/Method") @(
    @("Cetak Penjualan", "PDF", "Tbl_penjualan → cetak"),
    @("Cetak Pembelian", "PDF/Excel", "Tbl_pembelian → export"),
    @("Cetak Persediaan", "PDF", "Persediaan → cetak"),
    @("Cetak Pengajuan Bayar", "PDF", "Tbl_pembelian_pengajuan_bayar"),
    @("Cetak Kas Kecil", "PDF/Excel", "Tbl_kas_kecil"),
    @("Laporan Neraca", "PDF/Print", "Laporan, LaporanTcpdf, LaporanDompdf"),
    @("Laba Rugi", "PDF/Print", "Tbl_laba_rugi"),
    @("Neraca Saldo", "Screen/Print", "Neraca_saldo"),
    @("Buku Besar", "Screen/Print", "Buku_besar"),
    @("Buku Bank", "Screen/Print", "Bukubank"),
    @("Rekap Penjualan", "Excel", "Tbl_penjualan rekap"),
    @("Export Excel", "XLS", "exportexcel_helper.php")
)

Add-PageBreak

Add-Heading "3.10 Peta File dan Struktur Direktori" 3
Add-Paragraph "Struktur direktori utama proyek:" 11

$dirStructure = @"
anekadharma_new_20241025/
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
├── assets/AdminLTE310/        (UI template & plugins)
├── vendor/                    (Composer dependencies)
├── system/                    (CodeIgniter core)
└── tools/                     (Utility & generator scripts)
"@

$p = $doc.Content.Paragraphs.Add()
$p.Range.Text = $dirStructure
$p.Range.Font.Name = "Consolas"
$p.Range.Font.Size = 9
$p.Range.Shading.BackgroundPatternColor = 0xF2F2F2
$p.Range.ParagraphFormat.SpaceAfter = 12

Add-Heading "Peta Modul View per Folder" 3
Add-Table @("Folder View", "Modul", "Jumlah File") @(
    @("tbl_pembelian/", "Pembelian", "15+"),
    @("tbl_penjualan/", "Penjualan", "20+"),
    @("tbl_penjualan_jasa/", "Penjualan Jasa", "15+"),
    @("persediaan/", "Persediaan", "5+"),
    @("stock/", "Stock Barang", "3+"),
    @("jurnal_kas/", "Jurnal Kas", "8+"),
    @("jurnal_umum/", "Jurnal Umum", "3+"),
    @("jurnal_penyesuaian/", "Jurnal Penyesuaian", "3+"),
    @("buku_besar/", "Buku Besar", "3+"),
    @("buku_bank/", "Buku Bank", "3+"),
    @("neraca_saldo/", "Neraca Saldo", "3+"),
    @("sys_unit_produk/", "Unit Produk/Produksi", "5+"),
    @("monitoring_system/", "Monitoring", "2+"),
    @("Anekadharmamasuk/", "Login/Auth", "3+")
)

Add-PageBreak

# ===== PENUTUP =====
Add-Heading "PENUTUP" 1
Add-ColorBox "Kesimpulan" "Aplikasi Aneka Dharma telah berkembang dari inisialisasi proyek pada Oktober 2024 menjadi sistem ERP/akuntansi terintegrasi dengan $totalCommits commit. Modul operasional (pembelian, penjualan, persediaan, produksi) terhubung langsung dengan modul akuntansi (jurnal, buku besar, neraca, laba rugi) melalui sistem kode akun dan auto-jurnal. Periode 2026 fokus pada validasi data (compare helpers) dan penyempurnaan modul jurnal kas."

Add-Paragraph "Dokumen ini dihasilkan otomatis dari analisis Git repository dan struktur kode aplikasi Aneka Dharma." 10 $false 0 12
Add-Paragraph "- Akhir Dokumen -" 11 $true
$pEnd = $doc.Content.Paragraphs.Add()
$pEnd.Range.ParagraphFormat.Alignment = 1
$pEnd.Range.Text = "BLUEPRINT ANEKADHARMA - $(Get-Date -Format 'dd/MM/yyyy')"

# Save
if (Test-Path $outputFile) { Remove-Item $outputFile -Force }
$doc.SaveAs([ref]$outputFile, [ref]16)  # wdFormatDocumentDefault = 16
$doc.Close()
$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($doc) | Out-Null
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null
[GC]::Collect()
[GC]::WaitForPendingFinalizers()

Write-Output "SUCCESS: $outputFile"
