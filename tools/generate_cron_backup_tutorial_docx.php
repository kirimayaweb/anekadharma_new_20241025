<?php
/**
 * Generator: TUTORIAL_CRON_BACKUP_LENGKAP.docx
 * Jalankan: D:\xampp74\php\php.exe tools/generate_cron_backup_tutorial_docx.php
 */

require_once __DIR__ . '/docx_writer.php';

$token = 'ADCronBk20260717xK9mP2vL8nQ4wR7tY3u';
$baseUrl = 'https://anekadharma.my.id/index.php/cron_backup';
$cronCmd = 'curl -s "https://anekadharma.my.id/index.php/cron_backup/run?token=' . $token . '" > /dev/null 2>&1';

$outDir = dirname(__DIR__) . '/DEPLOY_CRON_BACKUP_20260717';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}
$outFile = $outDir . '/TUTORIAL_CRON_BACKUP_LENGKAP.docx';

$doc = new SimpleDocxWriter();
$today = date('d F Y');

// SAMPUL
$doc->title('TUTORIAL LENGKAP', 1);
$doc->title('CRON BACKUP DATABASE OTOMATIS', 1);
$doc->title('APLIKASI ANEKA DHARMA', 2);
$doc->paragraph('Instalasi & Penggunaan — Panduan Step-by-Step', ['italic' => true, 'size' => 24]);
$doc->paragraph('');
$doc->paragraph('Website: anekadharma.my.id');
$doc->paragraph('Proyek: anekadharma_new_20241025');
$doc->paragraph('Tanggal Dokumen: ' . $today);
$doc->paragraph('Jadwal Backup: Setiap hari jam 08:00 & 20:00 WIB');
$doc->pageBreak();

// DAFTAR ISI
$doc->title('DAFTAR ISI', 2);
foreach ([
    '1. Apa itu fitur ini?',
    '2. Token cron — apa itu & token Anda',
    '3. File yang perlu di-upload',
    '4. Langkah instalasi (upload ke server)',
    '5. Langkah setting cron job di cPanel',
    '6. Langkah test manual (wajib sebelum production)',
    '7. Verifikasi backup berhasil',
    '8. Penggunaan sehari-hari',
    '9. Troubleshooting (jika error)',
    '10. Ringkasan URL penting',
    '11. Checklist instalasi',
] as $item) {
    $doc->bullet($item);
}
$doc->pageBreak();

// 1
$doc->title('1. Apa itu fitur ini?', 2);
$doc->paragraph('Fitur ini mem-backup database MySQL aplikasi Aneka Dharma secara OTOMATIS tanpa perlu login ke aplikasi.');
$doc->bullet('Jadwal: setiap hari jam 08:00 pagi dan 20:00 malam (WIB)');
$doc->bullet('Isi backup: setiap tabel database disimpan ke 1 file .zip');
$doc->bullet('Lokasi simpan: folder backups/database/ di server');
$doc->bullet('Riwayat: tercatat di menu Backup Database Per Tabel (user: CRON AUTO)');
$doc->paragraph('Server menjalankan backup lewat cron job — penjadwal otomatis di hosting cPanel.');
$doc->pageBreak();

// 2
$doc->title('2. Token cron — apa itu & token Anda', 2);
$doc->paragraph('Token = password rahasia agar orang lain tidak bisa memicu backup lewat URL.');
$doc->paragraph('TOKEN ANDA (sudah diset di cron_backup.php):', ['bold' => true]);
$doc->codeBlock($token);
$doc->paragraph('PENTING: Simpan token ini di catatan pribadi. Jangan dibagikan ke publik.', ['color' => 'C00000']);
$doc->paragraph('Token dipakai di 2 tempat:');
$doc->bullet('(A) File config: application/config/cron_backup.php (sudah diisi)');
$doc->bullet('(B) Perintah cron di cPanel (Anda isi manual saat setup cron job)');
$doc->pageBreak();

// 3
$doc->title('3. File yang perlu di-upload', 2);
$doc->paragraph('Semua file sudah ada di folder: DEPLOY_CRON_BACKUP_20260717/');
$doc->paragraph('Struktur upload ke server (public_html atau root aplikasi):');
$doc->codeBlock(
    "public_html/\n"
    . "├── application/config/cron_backup.php          ← BARU\n"
    . "├── application/config/auth_public.php          ← UPDATE\n"
    . "├── application/config/routes.php               ← UPDATE\n"
    . "├── application/controllers/Cron_backup.php     ← BARU\n"
    . "├── application/libraries/Cron_backup_lib.php   ← BARU\n"
    . "└── backups/database/\n"
    . "    ├── .htaccess                               ← BARU\n"
    . "    └── index.html                              ← BARU"
);
$doc->paragraph('Cara upload:');
$doc->bullet('Via File Manager cPanel');
$doc->bullet('Via FTP (FileZilla)');
$doc->bullet('Via Git pull (jika deploy lewat git)');
$doc->paragraph('PENTING: Upload dengan struktur folder yang SAMA. Jangan taruh semua file di satu folder datar.', ['bold' => true]);
$doc->pageBreak();

// 4
$doc->title('4. Langkah instalasi (upload ke server)', 2);

$doc->title('Langkah 4.1 — Login cPanel hosting', 3);
$doc->bullet('Buka cPanel hosting anekadharma.my.id');
$doc->bullet('Masuk File Manager');

$doc->title('Langkah 4.2 — Upload file', 3);
$doc->bullet('Masuk ke folder root aplikasi (biasanya public_html/)');
$doc->bullet('Upload file-file dari DEPLOY_CRON_BACKUP_20260717/ sesuai struktur');
$doc->bullet('Overwrite jika diminta (auth_public.php dan routes.php)');

$doc->title('Langkah 4.3 — Cek folder backup', 3);
$doc->bullet('Pastikan folder backups/database/ ada');
$doc->bullet('Di dalamnya ada .htaccess dan index.html');
$doc->bullet('Folder ini akan otomatis terisi file backup nanti');

$doc->title('Langkah 4.4 — Cek permission folder', 3);
$doc->bullet('Folder backups/database/ harus bisa ditulis PHP (writable)');
$doc->bullet('Biasanya permission 755 sudah cukup');
$doc->bullet('Jika backup gagal "Gagal membuat folder", ubah ke 775');
$doc->pageBreak();

// 5
$doc->title('5. Langkah setting cron job di cPanel', 2);
$doc->paragraph('Cron job = penjadwal otomatis di server. Server akan memanggil URL backup setiap jam 08:00 dan 20:00.');

$doc->title('Langkah 5.1 — Buka menu Cron Jobs', 3);
$doc->paragraph('cPanel → Advanced → Cron Jobs');

$doc->title('Langkah 5.2 — Tambah CRON PAGI (08:00 WIB)', 3);
$doc->table(['Field', 'Isi'], [
    ['Minute', '0'],
    ['Hour', '8'],
    ['Day', '*'],
    ['Month', '*'],
    ['Weekday', '*'],
]);
$doc->paragraph('Command (copy-paste):');
$doc->codeBlock($cronCmd);
$doc->paragraph('Klik "Add New Cron Job".');

$doc->title('Langkah 5.3 — Tambah CRON MALAM (20:00 WIB)', 3);
$doc->table(['Field', 'Isi'], [
    ['Minute', '0'],
    ['Hour', '20'],
    ['Day', '*'],
    ['Month', '*'],
    ['Weekday', '*'],
]);
$doc->paragraph('Command (sama seperti cron pagi):');
$doc->codeBlock($cronCmd);
$doc->paragraph('Klik "Add New Cron Job".');

$doc->title('Langkah 5.4 — Cek timezone server', 3);
$doc->bullet('Aplikasi sudah set timezone Asia/Jakarta (WIB)');
$doc->bullet('Pastikan cron server juga WIB');
$doc->paragraph('Jika server UTC, sesuaikan jam:');
$doc->table(['WIB', 'UTC', 'Cron Expression'], [
    ['08:00 pagi', '01:00', '0 1 * * *'],
    ['20:00 malam', '13:00', '0 13 * * *'],
]);
$doc->pageBreak();

// 6
$doc->title('6. Langkah test manual (wajib sebelum production)', 2);
$doc->paragraph('Jangan tunggu jam 8 — test dulu sekarang dengan parameter force=1.');

$doc->title('Langkah 6.1 — Test lewat browser', 3);
$doc->paragraph('Buka URL ini di browser (Chrome/Firefox):');
$doc->codeBlock($baseUrl . '/run?token=' . $token . '&force=1');
$doc->paragraph('TUNGGU beberapa menit (backup butuh waktu jika tabel banyak). Halaman akan menampilkan JSON seperti:');
$doc->codeBlock(
    "{\n"
    . '    "success": true,' . "\n"
    . '    "message": "Backup otomatis selesai | Total tabel: 85 | Total zip: 85",' . "\n"
    . '    "folder_name": "2026-07-18_08-05_auto",' . "\n"
    . '    "total_tables": 85,' . "\n"
    . '    "total_files": 85' . "\n"
    . '}'
);

$doc->title('Langkah 6.2 — Test cek status', 3);
$doc->codeBlock($baseUrl . '/status?token=' . $token);

$doc->title('Langkah 6.3 — Respon jika token salah', 3);
$doc->codeBlock('{"success": false, "message": "Token cron tidak valid."}');
$doc->paragraph('Solusi: Cek token di cron_backup.php harus sama persis dengan URL.');

$doc->title('Langkah 6.4 — Respon jika di luar jadwal (tanpa force=1)', 3);
$doc->codeBlock('{"success": true, "skipped": true, "message": "Di luar jadwal backup..."}');
$doc->paragraph('Normal. Cron otomatis hanya jalan jam 8 & 20. Test pakai &force=1.');
$doc->pageBreak();

// 7
$doc->title('7. Verifikasi backup berhasil', 2);

$doc->title('Cara 1 — Cek folder di server (File Manager / FTP)', 3);
$doc->paragraph('Path: public_html/backups/database/');
$doc->paragraph('Contoh folder: 2026-07-18_08-05_auto/');
$doc->paragraph('Isi folder:');
$doc->bullet('tbl_pembelian.zip');
$doc->bullet('tbl_penjualan.zip');
$doc->bullet('tbl_persediaan.zip');
$doc->bullet('sys_unit.zip');
$doc->bullet('... (1 zip per tabel)');
$doc->paragraph('Buka salah satu .zip → di dalam ada file .sql (chunk data tabel).');

$doc->title('Cara 2 — Cek lewat aplikasi (login admin)', 3);
$doc->bullet('Login → menu "Backup Database Per Tabel"');
$doc->bullet('Lihat riwayat: User = CRON AUTO, Status = completed');

$doc->title('Cara 3 — Cek lewat URL status', 3);
$doc->codeBlock($baseUrl . '/status?token=' . $token);
$doc->pageBreak();

// 8
$doc->title('8. Penggunaan sehari-hari', 2);
$doc->paragraph('Setelah instalasi selesai, Anda TIDAK perlu melakukan apa-apa lagi.');
$doc->paragraph('Alur otomatis:');
$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['Jam 08:00 setiap hari', 'Server cron panggil URL backup', 'Backup ke backups/database/'],
        ['Jam 20:00 setiap hari', 'Server cron panggil URL backup', 'Backup ke backups/database/'],
    ], 1.2, 4.2, 0.62, 0.28);
});
$doc->paragraph('');
$doc->paragraph('Retensi otomatis:');
$doc->bullet('Backup lebih dari 30 hari otomatis dihapus');
$doc->bullet('Ubah di cron_backup.php → cron_backup_retention_days');
$doc->paragraph('Download backup manual (jika perlu restore):');
$doc->bullet('Login cPanel → File Manager');
$doc->bullet('Buka backups/database/YYYY-MM-DD_HH-mm_auto/');
$doc->bullet('Download file .zip yang dibutuhkan');
$doc->bullet('Extract → dapat file .sql → import lewat phpMyAdmin');
$doc->pageBreak();

// 9
$doc->title('9. Troubleshooting (jika error)', 2);
$doc->table(['Error', 'Solusi'], [
    ['Token cron tidak valid', 'Token di URL harus SAMA dengan cron_backup.php. Cek spasi/huruf.'],
    ['Di luar jadwal backup', 'Normal jika test tanpa force=1. Tambahkan &force=1 untuk test.'],
    ['Gagal membuat folder backup', 'Set permission folder backups/database/ ke 755 atau 775.'],
    ['Ekstensi PHP ZipArchive tidak tersedia', 'Hubungi hosting, minta aktifkan ekstensi php-zip.'],
    ['Backup timeout / halaman blank', 'Normal jika tabel besar. Cek folder backup setelah 5-10 menit.'],
    ['Cron tidak jalan otomatis jam 8/20', 'Cek cron job di cPanel, timezone server, email log cron.'],
    ['Folder backup kosong setelah test', 'Cek koneksi MySQL, error log PHP, test URL force=1 lagi.'],
]);
$doc->pageBreak();

// 10
$doc->title('10. Ringkasan URL penting', 2);
$doc->paragraph('TOKEN ANDA:', ['bold' => true]);
$doc->codeBlock($token);
$doc->paragraph('Test backup (paksa jalan kapan saja):');
$doc->codeBlock($baseUrl . '/run?token=' . $token . '&force=1');
$doc->paragraph('Cek status backup:');
$doc->codeBlock($baseUrl . '/status?token=' . $token);
$doc->paragraph('Cron otomatis (tanpa force, dipakai di cPanel):');
$doc->codeBlock($baseUrl . '/run?token=' . $token);
$doc->paragraph('Perintah cron cPanel (copy-paste):');
$doc->codeBlock($cronCmd);
$doc->paragraph('Lokasi file backup di server:');
$doc->codeBlock('public_html/backups/database/');
$doc->paragraph('Config token & jadwal:');
$doc->codeBlock('public_html/application/config/cron_backup.php');
$doc->pageBreak();

// 11
$doc->title('11. Checklist instalasi', 2);
$doc->paragraph('Centang satu per satu saat menyelesaikan instalasi:');
$checklist = [
    'Upload semua file dari DEPLOY_CRON_BACKUP_20260717/',
    'Token sudah benar di cron_backup.php',
    'Folder backups/database/ ada dan writable',
    'Cron pagi 08:00 sudah ditambah di cPanel',
    'Cron malam 20:00 sudah ditambah di cPanel',
    'Test URL dengan &force=1 → success: true',
    'Folder backup terisi file .zip per tabel',
    'Riwayat CRON AUTO muncul di menu Backup Database',
];
foreach ($checklist as $i => $item) {
    $doc->paragraph('[ ] ' . ($i + 1) . '. ' . $item);
}
$doc->paragraph('');
$doc->paragraph('Selesai! Backup akan jalan otomatis setiap hari jam 08:00 dan 20:00 WIB.', ['bold' => true, 'color' => '006100']);

$saved = $doc->save($outFile);
echo "Berhasil: {$saved}\n";
