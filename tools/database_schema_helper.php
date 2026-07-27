<?php
/**
 * Metadata & fetch skema database anekadharma_db untuk blueprint DOCX
 */

function db_schema_mysqli()
{
    static $mysqli = null;
    if ($mysqli === null) {
        $mysqli = @new mysqli('localhost', 'root', '', 'anekadharma_db');
        if ($mysqli->connect_error) {
            return null;
        }
        $mysqli->set_charset('utf8mb4');
    }
    return $mysqli;
}

function db_schema_fetch_columns($tableName)
{
    $db = db_schema_mysqli();
    if (!$db) {
        return [];
    }
    $tableName = $db->real_escape_string($tableName);
    $sql = "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, EXTRA, COLUMN_COMMENT
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = 'anekadharma_db' AND TABLE_NAME = '{$tableName}'
            ORDER BY ORDINAL_POSITION";
    $rows = [];
    if ($res = $db->query($sql)) {
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        $res->free();
    }
    return $rows;
}

function db_schema_table_exists($tableName)
{
    $db = db_schema_mysqli();
    if (!$db) {
        return false;
    }
    $tableName = $db->real_escape_string($tableName);
    $res = $db->query("SHOW TABLES LIKE '{$tableName}'");
    return $res && $res->num_rows > 0;
}

function db_schema_resolve_table($preferred, $alternates = [])
{
    if (db_schema_table_exists($preferred)) {
        return $preferred;
    }
    foreach ($alternates as $alt) {
        if (db_schema_table_exists($alt)) {
            return $alt;
        }
    }
    return $preferred;
}

/**
 * Definisi tabel inti: kategori, deskripsi, relasi, query contoh
 */
function db_schema_core_tables()
{
    $namaBarang = db_schema_resolve_table('sys_nama_barang', ['sys_nama_barang_x']);

    return [
        // ── TRANSAKSI ──
        'persediaan' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Tabel inti persediaan multi-unit per bulan. Setiap baris = satu barang dalam periode tanggal_beli (YYYY-MM). Kolom unit (CETAK, GRAFIKITA, Sekretariat, medis, pu_outsor, dll.) menyimpan alokasi stok per divisi bisnis. Terhubung ke pembelian via uuid_spop dan penjualan via uuid_persediaan.',
            'relasi' => 'uuid_spop → tbl_pembelian | uuid_barang → ' . $namaBarang . ' | uuid_persediaan ← tbl_penjualan',
            'query' => "-- Persediaan bulan Januari 2026\nSELECT namabarang, sa, beli, CETAK, GRAFIKITA, nilai_persediaan\nFROM persediaan\nWHERE tanggal_beli BETWEEN '2026-01-01' AND '2026-01-31'\nORDER BY namabarang;",
        ],
        'tbl_pembelian' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Transaksi pembelian barang (SPOP). Setiap baris = satu item dalam SPOP. Menyimpan supplier, barang, harga, jumlah, kode akun, dan link ke persediaan (uuid_persediaan). Digunakan untuk jurnal pembelian dan posting buku_besar.',
            'relasi' => 'uuid_supplier → sys_supplier | uuid_barang → ' . $namaBarang . ' | uuid_persediaan → persediaan | id_buku_besar → buku_besar',
            'query' => "-- Pembelian per SPOP\nSELECT spop, supplier_nama, uraian, jumlah, harga_satuan, harga_total, tgl_po\nFROM tbl_pembelian\nWHERE spop = '893'\nORDER BY id;",
        ],
        'tbl_pembelian_jasa' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Mirror tbl_pembelian untuk transaksi pembelian jasa (bukan barang fisik). Struktur dan alur SPOP sama dengan pembelian barang.',
            'relasi' => 'uuid_supplier → sys_supplier | mirip tbl_pembelian',
            'query' => "SELECT spop, supplier_nama, uraian, harga_total, tgl_po\nFROM tbl_pembelian_jasa\nWHERE YEAR(tgl_po) = 2026\nORDER BY tgl_po DESC\nLIMIT 20;",
        ],
        'tbl_jasa' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Master data jasa yang dapat dibeli melalui modul pembelian jasa.',
            'relasi' => 'Digunakan oleh tbl_pembelian_jasa',
            'query' => "SELECT * FROM tbl_jasa ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_pembelian_pengajuan_bayar' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Pengajuan pembayaran ke supplier berdasarkan SPOP. Digunakan untuk cetak PDF pengajuan bayar.',
            'relasi' => 'uuid_spop → tbl_pembelian',
            'query' => "SELECT * FROM tbl_pembelian_pengajuan_bayar\nWHERE uuid_spop IS NOT NULL\nORDER BY id DESC LIMIT 10;",
        ],
        'tbl_pembelian_pecah_satuan' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Log transaksi pecah satuan barang dari pembelian (mis. 1 dus → 12 pcs).',
            'relasi' => 'uuid_pembelian → tbl_pembelian',
            'query' => "SELECT * FROM tbl_pembelian_pecah_satuan ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_penjualan' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Transaksi penjualan barang. Setiap baris = satu item dalam order (nmrpesan/nmrkirim). Mengurangi stok di persediaan per unit. Terhubung ke konsumen, unit bisnis, dan kode akun.',
            'relasi' => 'uuid_persediaan → persediaan | uuid_konsumen → sys_konsumen | uuid_unit → sys_unit | id_buku_besar → buku_besar',
            'query' => "-- Penjualan per nomor kirim\nSELECT nmrkirim, konsumen_nama, nama_barang, unit, jumlah, harga_satuan, total_nominal, tgl_jual\nFROM tbl_penjualan\nWHERE nmrkirim = '01'\nORDER BY id;",
        ],
        'tbl_penjualan_pembayaran' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Data pembayaran penjualan dari konsumen (piutang → lunas).',
            'relasi' => 'uuid_penjualan → tbl_penjualan',
            'query' => "SELECT * FROM tbl_penjualan_pembayaran ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_penjualan_accounting' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Versi penjualan dengan integrasi akuntansi penuh (piutang, PPN, uang muka).',
            'relasi' => 'uuid_penjualan → tbl_penjualan',
            'query' => "SELECT * FROM tbl_penjualan_accounting ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_penjualan_accounting_pembayaran' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Pembayaran untuk transaksi penjualan versi accounting.',
            'relasi' => 'tbl_penjualan_accounting',
            'query' => "SELECT * FROM tbl_penjualan_accounting_pembayaran ORDER BY id DESC LIMIT 10;",
        ],
        'persediaan_gen_recalc_log' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Log header proses Generate & Recalculate persediaan per bulan target. Mencatat jumlah insert/update dari generate dan sync pembelian.',
            'relasi' => 'id → persediaan_gen_recalc_item.id_log',
            'query' => "SELECT bulan_target, generate_insert, generate_update, pembelian_update, created_at\nFROM persediaan_gen_recalc_log\nORDER BY created_at DESC LIMIT 10;",
        ],
        'persediaan_gen_recalc_item' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Detail baris per item dalam proses generate/recalculate. Menyimpan JSON item hasil proses batch AJAX.',
            'relasi' => 'id_log → persediaan_gen_recalc_log (FK CASCADE)',
            'query' => "SELECT id_log, jenis, nomor, LEFT(item_json, 80) AS preview\nFROM persediaan_gen_recalc_item\nWHERE id_log = 1\nORDER BY jenis, nomor;",
        ],
        'persediaan_rekap_view' => [
            'kategori' => 'A. Transaksi Operasional',
            'deskripsi' => 'Tabel/view rekap nilai persediaan agregat per periode (Sediaan Awal + Pembelian per unit).',
            'relasi' => 'Agregasi dari persediaan',
            'query' => "SELECT * FROM persediaan_rekap_view LIMIT 10;",
        ],

        // ── AKUNTANSI ──
        'buku_besar' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Buku besar — posting transaksi dari pembelian/penjualan/jurnal. Kolom source menandakan asal posting (pembelian, penjualan, dll.).',
            'relasi' => 'kode_akun → sys_kode_akun | tbl_pembelian.id_buku_besar / tbl_penjualan.id_buku_besar',
            'query' => "-- Buku besar per kode akun bulan berjalan\nSELECT tanggal, kode_akun, keterangan, debet, kredit\nFROM buku_besar\nWHERE MONTH(tanggal) = MONTH(CURDATE())\n  AND YEAR(tanggal) = YEAR(CURDATE())\nORDER BY tanggal, id;",
        ],
        'bukubank' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Buku bank — catatan transaksi rekening bank (debet/kredit per rekening).',
            'relasi' => 'norek → sys_bank',
            'query' => "SELECT tanggal, bank, norek, keterangan, debet, kredit\nFROM bukubank\nORDER BY tanggal DESC LIMIT 20;",
        ],
        'jurnal_kas' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal kas harian — semua transaksi kas masuk/keluar per tanggal.',
            'relasi' => 'Agregasi ke buku_besar, neraca_saldo',
            'query' => "SELECT tanggal, keterangan, debet, kredit\nFROM jurnal_kas\nWHERE MONTH(tanggal) = MONTH(CURDATE())\nORDER BY tanggal, id;",
        ],
        'jurnal_pembelian' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal posting dari transaksi pembelian (view/report).',
            'relasi' => 'tbl_pembelian',
            'query' => "SELECT * FROM jurnal_pembelian ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_penjualan' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal posting dari transaksi penjualan (view/report).',
            'relasi' => 'tbl_penjualan',
            'query' => "SELECT * FROM jurnal_penjualan ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_umum' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal umum — entri manual di luar pembelian/penjualan.',
            'relasi' => 'sys_kode_akun',
            'query' => "SELECT * FROM jurnal_umum ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_penerimaan_kas' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal penerimaan kas.',
            'relasi' => 'jurnal_kas',
            'query' => "SELECT * FROM jurnal_penerimaan_kas ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_pengeluaran_kas' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal pengeluaran kas.',
            'relasi' => 'jurnal_kas',
            'query' => "SELECT * FROM jurnal_pengeluaran_kas ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_penyesuaian' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Jurnal penyesuaian akhir periode.',
            'relasi' => 'buku_besar',
            'query' => "SELECT * FROM jurnal_penyesuaian ORDER BY id DESC LIMIT 10;",
        ],
        'jurnal_kas_saldo_akhir_bulan' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Saldo akhir kas per bulan.',
            'relasi' => 'jurnal_kas',
            'query' => "SELECT * FROM jurnal_kas_saldo_akhir_bulan ORDER BY id DESC LIMIT 10;",
        ],
        'neraca_saldo' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Neraca saldo — agregasi debet/kredit per kode akun per periode.',
            'relasi' => 'sys_kode_akun, buku_besar',
            'query' => "SELECT * FROM neraca_saldo ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_accounting_group' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Grup akuntansi untuk pengelompokan akun dalam laporan.',
            'relasi' => 'tbl_accounting_detail',
            'query' => "SELECT * FROM tbl_accounting_group;",
        ],
        'tbl_accounting_detail' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Detail transaksi akuntansi per grup.',
            'relasi' => 'tbl_accounting_group',
            'query' => "SELECT * FROM tbl_accounting_detail ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_laba_rugi' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Data laporan laba rugi per tahun/bulan.',
            'relasi' => 'sys_kode_akun, tbl_pembelian.tgl_po (periode)',
            'query' => "SELECT * FROM tbl_laba_rugi ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_neraca_data' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Data neraca keuangan per periode.',
            'relasi' => 'sys_kode_akun',
            'query' => "SELECT * FROM tbl_neraca_data ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_kas_kecil' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Transaksi kas kecil (petty cash).',
            'relasi' => 'jurnal_kas',
            'query' => "SELECT * FROM tbl_kas_kecil ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_penyusutan' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Data penyusutan aset tetap.',
            'relasi' => 'sys_group_penyusutan',
            'query' => "SELECT * FROM tbl_penyusutan ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_rekening_koran' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Rekening koran bank — mutasi rekening.',
            'relasi' => 'sys_bank, bukubank',
            'query' => "SELECT * FROM tbl_rekening_koran ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_bea_operasional' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Bea operasional.',
            'relasi' => 'buku_besar',
            'query' => "SELECT * FROM tbl_bea_operasional ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_pendapatan_lain_lain' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Pendapatan lain-lain di luar penjualan utama.',
            'relasi' => 'buku_besar',
            'query' => "SELECT * FROM tbl_pendapatan_lain_lain ORDER BY id DESC LIMIT 10;",
        ],
        'tbl_uang_muka_didepan' => [
            'kategori' => 'B. Akuntansi & Jurnal',
            'deskripsi' => 'Uang muka di depan (prepaid).',
            'relasi' => 'buku_besar',
            'query' => "SELECT * FROM tbl_uang_muka_didepan ORDER BY id DESC LIMIT 10;",
        ],

        // ── MASTER DATA ──
        $namaBarang => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Master barang/produk. Menyimpan kode barang, nama, kategori, satuan, harga referensi. Digunakan di pembelian, penjualan, dan persediaan via uuid_barang.',
            'relasi' => 'uuid_barang ← tbl_pembelian, tbl_penjualan, persediaan',
            'query' => "SELECT kode_barang, nama_barang, kategori, satuan, harga\nFROM {$namaBarang}\nORDER BY nama_barang\nLIMIT 20;",
        ],
        'sys_konsumen' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Master pelanggan/konsumen. Digunakan di penjualan (uuid_konsumen).',
            'relasi' => 'uuid_konsumen ← tbl_penjualan',
            'query' => "SELECT uuid_konsumen, nama_konsumen, alamat FROM sys_konsumen ORDER BY nama_konsumen LIMIT 20;",
        ],
        'sys_supplier' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Master supplier/pemasok. Digunakan di pembelian (uuid_supplier).',
            'relasi' => 'uuid_supplier ← tbl_pembelian',
            'query' => "SELECT uuid_supplier, nama_supplier FROM sys_supplier ORDER BY nama_supplier LIMIT 20;",
        ],
        'sys_unit' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Unit bisnis (CETAK, GRAFIKITA, PU-KBS, Sekretariat, dll.). Mapping ke kolom persediaan dan field unit di penjualan.',
            'relasi' => 'uuid_unit ← tbl_penjualan | kode_unit → kolom persediaan',
            'query' => "SELECT id, kode_unit, nama_unit FROM sys_unit ORDER BY id;",
        ],
        'sys_kode_akun' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Chart of Accounts (CoA). Kode akun untuk jurnal, buku besar, laba rugi, neraca.',
            'relasi' => 'kode_akun ← tbl_pembelian, tbl_penjualan, buku_besar',
            'query' => "SELECT kode_akun, nama_akun, jenis FROM sys_kode_akun ORDER BY kode_akun LIMIT 30;",
        ],
        'sys_bank' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Data bank untuk buku bank dan rekening koran.',
            'relasi' => 'bukubank, tbl_rekening_koran',
            'query' => "SELECT * FROM sys_bank;",
        ],
        'sys_gudang' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Master gudang/lokasi penyimpanan.',
            'relasi' => 'uuid_gudang di pembelian/persediaan',
            'query' => "SELECT * FROM sys_gudang;",
        ],
        'sys_pajak' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Konfigurasi tarif pajak (PPN, dll.).',
            'relasi' => 'Penjualan accounting',
            'query' => "SELECT * FROM sys_pajak;",
        ],
        'sys_kategori_barang' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Kategori barang (KERTAS, ATK, dll.).',
            'relasi' => $namaBarang . '.kategori',
            'query' => "SELECT * FROM sys_kategori_barang;",
        ],
        'sys_kas_nominal' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Nominal referensi kas.',
            'relasi' => 'jurnal_kas',
            'query' => "SELECT * FROM sys_kas_nominal;",
        ],
        'sys_status_transaksi' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Status transaksi (draft, selesai, dll.).',
            'relasi' => 'Transaksi pembelian/penjualan',
            'query' => "SELECT * FROM sys_status_transaksi;",
        ],
        'sys_unit_produk' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Produk per unit bisnis.',
            'relasi' => 'sys_unit, sys_unit_produk_bahan',
            'query' => "SELECT * FROM sys_unit_produk LIMIT 10;",
        ],
        'sys_unit_produk_bahan' => [
            'kategori' => 'C. Master Data (sys_*)',
            'deskripsi' => 'Bahan/komponen produk per unit.',
            'relasi' => 'sys_unit_produk',
            'query' => "SELECT * FROM sys_unit_produk_bahan LIMIT 10;",
        ],

        // ── USER & AKSES ──
        'tbl_user' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Akun pengguna aplikasi. Menyimpan email, password hash, id_user_level, no HP (WhatsApp MFA).',
            'relasi' => 'id_user_level → tbl_user_level',
            'query' => "SELECT id_users, username, email, id_user_level, no_hp FROM tbl_user;",
        ],
        'tbl_user_level' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Level/role user: 1=Admin, 99=Administrator, 2=Manager, 7=Kasir, 3=Sales, 4=Customer.',
            'relasi' => 'tbl_user.id_user_level, tbl_hak_akses.id_user_level',
            'query' => "SELECT * FROM tbl_user_level;",
        ],
        'tbl_user_lupapassword' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Token reset password sementara (expire 5 menit).',
            'relasi' => 'tbl_user (via email/WA)',
            'query' => "SELECT id, email, date_input FROM tbl_user_lupapassword ORDER BY date_input DESC LIMIT 5;",
        ],
        'tbl_hak_akses' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Hak akses menu per user level atau per user. Digunakan guard is_login().',
            'relasi' => 'id_menu → tbl_menu / menu',
            'query' => "SELECT ha.id_user_level, ul.nama_level, m.url\nFROM tbl_hak_akses ha\nJOIN tbl_user_level ul ON ul.id = ha.id_user_level\nJOIN tbl_menu m ON m.id_menu = ha.id_menu\nLIMIT 20;",
        ],
        'menu' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Menu navigasi top navbar (dinamis). Field: link, is_parent, is_active.',
            'relasi' => 'tbl_hak_akses.id_menu',
            'query' => "SELECT id, label, link, is_parent, is_active FROM menu WHERE is_active='1' LIMIT 20;",
        ],
        'tbl_menu' => [
            'kategori' => 'D. User & Akses',
            'deskripsi' => 'Mapping URL controller ke id_menu untuk guard akses is_login().',
            'relasi' => 'url = controller name | tbl_hak_akses.id_menu',
            'query' => "SELECT id_menu, url, nama_menu FROM tbl_menu LIMIT 20;",
        ],
    ];
}

function db_schema_column_description($table, $column, $type, $key, $extra = '')
{
    static $hints = [
        'uuid_persediaan' => 'UUID unik baris persediaan; FK ke penjualan',
        'uuid_spop' => 'UUID SPOP pembelian',
        'uuid_pembelian' => 'UUID transaksi pembelian',
        'uuid_penjualan' => 'UUID header order penjualan',
        'uuid_barang' => 'UUID master barang',
        'uuid_supplier' => 'UUID master supplier',
        'uuid_konsumen' => 'UUID master konsumen',
        'uuid_unit' => 'UUID unit bisnis',
        'tanggal_beli' => 'Periode bulan persediaan (filter YYYY-MM)',
        'tgl_po' => 'Tanggal purchase order pembelian',
        'tgl_jual' => 'Tanggal transaksi penjualan',
        'kode_akun' => 'Kode akun akuntansi (CoA)',
        'id_buku_besar' => 'Link posting ke buku_besar',
        'hpp' => 'Harga Pokok Penjualan',
        'sa' => 'Saldo Awal (Sediaan Awal)',
        'beli' => 'Jumlah pembelian periode',
        'nilai_persediaan' => 'Nilai total persediaan (hpp × qty)',
        'nmrkirim' => 'Nomor surat jalan / kirim',
        'nmrpesan' => 'Nomor pesanan',
        'spop' => 'Nomor SPOP pembelian',
    ];
    if (isset($hints[$column])) {
        return $hints[$column];
    }
    if ($key === 'PRI') {
        return 'Primary Key' . (strpos($type, 'int') !== false && strpos($extra ?? '', 'auto_increment') !== false ? ' (AUTO_INCREMENT)' : '');
    }
    if ($key === 'MUL') {
        return 'Index / Foreign Key';
    }
    if ($key === 'UNI') {
        return 'Unique';
    }
    return '';
}

function db_schema_build_sections(SimpleDocxWriter $doc)
{
    $tables = db_schema_core_tables();
    $currentCategory = '';

    foreach ($tables as $tableName => $meta) {
        if ($meta['kategori'] !== $currentCategory) {
            $currentCategory = $meta['kategori'];
            $doc->title($currentCategory, 3);
        }

        $actualTable = $tableName;
        if (!db_schema_table_exists($actualTable)) {
            continue;
        }

        $doc->title('Tabel: `' . $actualTable . '`', 4);
        $doc->paragraph($meta['deskripsi']);
        $doc->paragraph('Relasi: ' . $meta['relasi'], ['size' => 20, 'italic' => true]);

        $columns = db_schema_fetch_columns($actualTable);
        if (!empty($columns)) {
            $doc->paragraph('Struktur Kolom:', ['bold' => true, 'size' => 20]);
            $rows = [];
            foreach ($columns as $col) {
                $desc = $col['COLUMN_COMMENT'] ?: db_schema_column_description(
                    $actualTable,
                    $col['COLUMN_NAME'],
                    $col['COLUMN_TYPE'],
                    $col['COLUMN_KEY'],
                    $col['EXTRA']
                );
                $extra = $col['EXTRA'] ? ' ' . $col['EXTRA'] : '';
                $key = $col['COLUMN_KEY'] ?: '-';
                $rows[] = [
                    $col['COLUMN_NAME'],
                    $col['COLUMN_TYPE'] . $extra,
                    $col['IS_NULLABLE'],
                    $key,
                    $desc,
                ];
            }
            $doc->table(['Kolom', 'Tipe Data', 'Null', 'Key', 'Keterangan'], $rows);
        }

        $doc->paragraph('Query Contoh:', ['bold' => true, 'size' => 20]);
        $doc->codeBlock($meta['query']);
        $doc->paragraph('');
    }
}
