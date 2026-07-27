<?php
/**
 * Generator: UpdateNEXTsecurity.docx
 * Daftar perbaikan keamanan berikutnya (setelah modul Persediaan selesai).
 * Jalankan: php tools/generate_update_next_security_docx.php
 */

require_once __DIR__ . '/docx_writer.php';

$doc = new SimpleDocxWriter();
$today = date('d F Y');

// ═══ SAMPUL ═══════════════════════════════════════════════════════════════════
$doc->title('ROADMAP KEAMANAN BERIKUTNYA', 1);
$doc->title('APLIKASI ANEKA DHARMA', 1);
$doc->paragraph('UpdateNEXTsecurity — Checklist Implementasi', ['italic' => true, 'size' => 24]);
$doc->paragraph('');
$doc->paragraph('Proyek: anekadharma_new_20241025');
$doc->paragraph('Framework: CodeIgniter 3.1.5 + PHP 7.4');
$doc->paragraph('Tanggal Dokumen: ' . $today);
$doc->paragraph('Status: BELUM DIIMPLEMENTASI — kerjakan setelah modul Persediaan selesai');
$doc->paragraph('');
$doc->paragraph('Dokumen ini berisi daftar perbaikan keamanan yang masih perlu diproses. Gunakan sebagai panduan kerja bertahap. Dokumen audit yang sudah selesai ada di: docs/Audit_Keamanan_Login_AnekaDharma.doc (Bagian A–D).');
$doc->pageBreak();

// ═══ DAFTAR ISI ═══════════════════════════════════════════════════════════════
$doc->title('DAFTAR ISI', 2);
foreach ([
    '1. Ringkasan — Apa yang Sudah Aman vs Belum',
    '2. Prioritas Implementasi (Urutan Disarankan)',
    '3. Detail Per Item — Langkah Kerja & File Terkait',
    '4. Checklist Kerja (Centang Saat Selesai)',
    '5. Skenario Pengujian Setelah Implementasi',
    '6. Catatan Penting & Referensi File',
] as $item) {
    $doc->bullet($item);
}
$doc->pageBreak();

// ═══ 1. RINGKASAN ═════════════════════════════════════════════════════════════
$doc->title('1. Ringkasan — Apa yang Sudah Aman vs Belum', 2);
$doc->paragraph('Keamanan login dan proteksi controller sudah diperkuat (Juni 2026). Aplikasi sudah cukup aman untuk penggunaan internal, tetapi belum “100% hardened” untuk standar enterprise.');

$doc->title('1.1 Yang Sudah Diimplementasi (Jangan Ulangi)', 3);
$doc->table(['Area', 'Status', 'Lokasi / Catatan'], [
    ['CSRF di form login', 'SELESAI', 'login_security_helper.php, Anekadharmamasuk'],
    ['XSS di halaman login', 'SELESAI', 'html_escape(), login_flash_message()'],
    ['Anti-brute force login', 'SELESAI', '10 gagal / lockout 15 menit (IP-based cache)'],
    ['Anti-brute force lupa password', 'SELESAI', '5 request / lockout 30 menit'],
    ['MFA WhatsApp (admin)', 'SELESAI', 'Level 1 & 99 — verifymfa.php'],
    ['Session hardening', 'SELESAI', 'regenerate, HttpOnly, Secure, SameSite=Lax'],
    ['HTTPS redirect (production)', 'SELESAI', 'login_force_https_if_required()'],
    ['Pesan error generik', 'SELESAI', 'Anti-enumerasi login & lupa password'],
    ['is_login() diperkuat', 'SELESAI', 'racode_helper.php'],
    ['Auth hook global', 'SELESAI', 'auth_hook.php + auth_public.php whitelist'],
    ['Create_password_hash block prod', 'SELESAI', '404 di production'],
]);

$doc->title('1.2 Yang Masih Perlu Dikerjakan', 3);
$doc->table(['No', 'Area', 'Prioritas', 'Risiko Jika Ditinggal'], [
    ['1', 'CSRF global + token AJAX di template admin', 'TINGGI', 'CSRF di modul internal (Persediaan, Penjualan, dll.)'],
    ['2', 'Default deny controller tidak ada di tbl_menu', 'TINGGI', 'User login bisa akses URL tersembunyi'],
    ['3', 'Upgrade paksa password MD5 → bcrypt', 'TINGGI', 'Password legacy mudah di-crack'],
    ['4', 'Logout via POST + CSRF', 'SEDANG', 'CSRF logout (risiko rendah)'],
    ['5', 'Security headers global (HSTS, CSP)', 'SEDANG', 'Clickjacking, XSS tambahan, mixed content'],
    ['6', 'Audit log keamanan', 'SEDANG', 'Tidak ada jejak login gagal / akses ditolak'],
    ['7', 'Review XSS di modul output DB', 'SEDANG', 'XSS stored di Penjualan, Persediaan, dll.'],
    ['8', 'Hapus utility dev dari production', 'SEDANG', 'Create_password_hash masih ada di codebase'],
    ['9', 'Seragamkan view login alternatif', 'RENDAH', 'auth/login.php, masuk/masuk.php belum hardened'],
    ['10', 'REST API auth (JWT/API key)', 'RENDAH–SEDANG', 'RestApi/Kontak pakai session saja'],
    ['11', 'Session timeout / idle logout', 'RENDAH', 'Session lama tanpa aktivitas'],
    ['12', 'Hardening server (di luar kode)', 'SEDANG', 'Firewall, PHP update, DB privilege, backup'],
]);
$doc->pageBreak();

// ═══ 2. PRIORITAS ═════════════════════════════════════════════════════════════
$doc->title('2. Prioritas Implementasi (Urutan Disarankan)', 2);
$doc->paragraph('Kerjakan secara berurutan agar modul Persediaan/Penjualan tidak rusak saat CSRF global diaktifkan.');

$doc->diagram(function ($d) {
    $d->verticalFlow([
        ['FASE 1 — CSRF Global', 'Aktifkan csrf_protection + ajaxSetup di template admin', 'C00000'],
        ['FASE 2 — Hak Akses Ketat', 'Default deny controller tidak terdaftar di tbl_menu', 'ED7D31'],
        ['FASE 3 — Password Legacy', 'Upgrade MD5 ke password_hash saat login sukses', '7030A0'],
        ['FASE 4 — Logout & Headers', 'POST logout + HSTS/CSP global via hook', '2E75B6'],
        ['FASE 5 — Audit & XSS Review', 'Log keamanan + escape output modul', '548235'],
        ['FASE 6 — Opsional', 'API auth, session timeout, hapus dev tools', '7F7F7F'],
    ], 1.2, 4.2, 0.62, 0.28);
});

$doc->paragraph('');
$doc->paragraph('Estimasi waktu kasar (dengan bantuan Cursor AI): Fase 1–2 = 1–2 hari | Fase 3–4 = 0.5–1 hari | Fase 5 = 1–2 hari | Fase 6 = sesuai kebutuhan.');
$doc->pageBreak();

// ═══ 3. DETAIL PER ITEM ═══════════════════════════════════════════════════════
$doc->title('3. Detail Per Item — Langkah Kerja & File Terkait', 2);

// --- ITEM 1 ---
$doc->title('3.1 [PRIORITAS TINGGI] CSRF Global + Token AJAX', 3);
$doc->paragraph('Masalah: CSRF hanya aktif di halaman login. User yang sudah login masih rentan CSRF di form/AJAX modul internal.');
$doc->paragraph('Langkah kerja:');
$doc->bullet('Set csrf_protection = TRUE di application/config/config.php');
$doc->bullet('Set csrf_token_name, csrf_cookie_name, csrf_expire, csrf_regenerate sesuai kebutuhan');
$doc->bullet('Tambahkan meta tag CSRF di template header admin (AdminLTE):');
$doc->codeBlock('<meta name="csrf-token" content="<?= \$this->security->get_csrf_hash() ?>">');
$doc->bullet('Tambahkan $.ajaxSetup di footer admin agar semua AJAX POST otomatis kirim token:');
$doc->codeBlock('$.ajaxSetup({' . "\n" . '  data: {' . "\n" . '    \'<?= \$this->security->get_csrf_token_name() ?>\': \'<?= \$this->security->get_csrf_hash() ?>\'' . "\n" . '  }' . "\n" . '});');
$doc->bullet('Uji modul Persediaan, Penjualan, Pembelian — pastikan AJAX tidak 403');
$doc->bullet('Form POST manual: tambahkan <?= \$this->security->get_csrf_token_name() ?> hidden field');
$doc->paragraph('File terkait: config.php, views template admin (header/footer), semua form POST custom');
$doc->paragraph('Risiko implementasi: AJAX lama bisa error 403 jika token tidak dikirim — uji menyeluruh sebelum deploy production.');

// --- ITEM 2 ---
$doc->title('3.2 [PRIORITAS TINGGI] Default Deny Controller (Hak Akses Ketat)', 3);
$doc->paragraph('Masalah: Controller yang TIDAK ada di tbl_menu masih bisa diakses user login (asal tahu URL). Auth hook hanya cegah anonim, bukan user tanpa hak.');
$doc->paragraph('Langkah kerja:');
$doc->bullet('Edit is_login() di racode_helper.php: jika controller tidak ditemukan di tbl_menu → redirect blokir (kecuali whitelist admin level 99)');
$doc->bullet('Buat daftar controller sistem yang memang internal (ajax, api) — putuskan apakah perlu entry di tbl_menu atau whitelist khusus');
$doc->bullet('Audit semua controller vs tbl_menu — tambahkan entry menu yang hilang atau blok akses');
$doc->paragraph('File terkait: racode_helper.php, tbl_menu (database), auth_public.php');

// --- ITEM 3 ---
$doc->title('3.3 [PRIORITAS TINGGI] Upgrade Password MD5 → bcrypt', 3);
$doc->paragraph('Masalah: _verify_user_password() masih menerima hash MD5/plain text legacy.');
$doc->paragraph('Langkah kerja:');
$doc->bullet('Saat login sukses dengan password MD5: re-hash dengan password_hash() dan update kolom password di tbl_user');
$doc->bullet('Set flag/timestamp migrasi password di database (opsional)');
$doc->bullet('Setelah periode transisi: nonaktifkan verifikasi MD5');
$doc->bullet('Tambahkan validasi password minimal (panjang, kompleksitas) di form ganti password');
$doc->paragraph('File terkait: Anekadharmamasuk.php (_verify_user_password), model user, form profil/ganti password');

// --- ITEM 4 ---
$doc->title('3.4 [PRIORITAS SEDANG] Logout via POST + CSRF', 3);
$doc->paragraph('Masalah: Logout masih link GET — bisa dipaksa logout via CSRF (risiko rendah).');
$doc->paragraph('Langkah kerja:');
$doc->bullet('Ubah link logout di sidebar/header menjadi form POST kecil dengan hidden CSRF token');
$doc->bullet('Method keluar() di Anekadharmamasuk: terima hanya POST, verifikasi CSRF');
$doc->paragraph('File terkait: template sidebar admin, Anekadharmamasuk.php (method keluar/logout)');

// --- ITEM 5 ---
$doc->title('3.5 [PRIORITAS SEDANG] Security Headers Global', 3);
$doc->paragraph('Masalah: Header keamanan (X-Frame-Options, dll.) hanya di controller login, belum global.');
$doc->paragraph('Langkah kerja:');
$doc->bullet('Buat hook pre_controller atau post_controller_constructor: security_headers_hook.php');
$doc->bullet('Set header: X-Frame-Options: SAMEORIGIN, X-Content-Type-Options: nosniff, Referrer-Policy: strict-origin-when-cross-origin');
$doc->bullet('Production: Strict-Transport-Security (HSTS) max-age=31536000');
$doc->bullet('CSP bertahap — mulai report-only, sesuaikan dengan CDN/script AdminLTE');
$doc->paragraph('File terkait: hooks.php, hooks/security_headers_hook.php (baru)');

// --- ITEM 6 ---
$doc->title('3.6 [PRIORITAS SEDANG] Audit Log Keamanan', 3);
$doc->paragraph('Langkah kerja:');
$doc->bullet('Buat tabel tbl_security_log (timestamp, ip, user_id, event, detail)');
$doc->bullet('Log: login gagal, lockout, MFA gagal, akses ditolak/blokir, logout, ganti password');
$doc->bullet('Buat halaman admin (level 99) untuk melihat log — read-only');
$doc->paragraph('File terkait: migration SQL, helper security_log, Anekadharmamasuk, racode_helper, controller Security_log (baru)');

// --- ITEM 7 ---
$doc->title('3.7 [PRIORITAS SEDANG] Review XSS di Modul Output Database', 3);
$doc->paragraph('Langkah kerja:');
$doc->bullet('Audit view yang echo data user/DB tanpa html_escape() — terutama Persediaan, Penjualan, Pembelian, Master Data');
$doc->bullet('Gunakan html_escape() atau $this->security->xss_clean() konsisten di output');
$doc->bullet('DataTables AJAX: pastikan JSON response tidak mengandung executable script');
$doc->paragraph('File terkait: application/views/anekadharma/** — per modul');

// --- ITEM 8 ---
$doc->title('3.8 [PRIORITAS SEDANG] Hapus Utility Dev dari Production', 3);
$doc->paragraph('Langkah kerja:');
$doc->bullet('Hapus atau pindahkan Create_password_hash ke folder tools/ di luar webroot');
$doc->bullet('Pastikan tidak ada route/controller debug di production');
$doc->paragraph('File terkait: Create_password_hash.php controller');

// --- ITEM 9 ---
$doc->title('3.9 [PRIORITAS RENDAH] Seragamkan View Login Alternatif', 3);
$doc->paragraph('View auth/login.php, masuk/masuk.php, authpage — pastikan tidak reachable atau diseragamkan dengan proteksi login (CSRF, rate limit, escape).');
$doc->paragraph('File terkait: application/views/auth/, masuk/, controllers Auth.php, Masuk.php');

// --- ITEM 10 ---
$doc->title('3.10 [OPSIONAL] REST API Auth (JWT / API Key)', 3);
$doc->paragraph('RestApi, Kontak, Penjualan_detail_rest saat ini pakai session is_login(). Untuk integrasi eksternal, pertimbangkan token-based auth + rate limit terpisah.');
$doc->paragraph('File terkait: controllers RestApi.php, Kontak.php, config REST');

// --- ITEM 11 ---
$doc->title('3.11 [OPSIONAL] Session Timeout / Idle Logout', 3);
$doc->paragraph('Set sess_expiration di config.php. Tambahkan JavaScript idle timer di template admin untuk role sensitif (admin/akuntansi) — redirect ke logout setelah X menit tidak aktif.');
$doc->paragraph('File terkait: config.php, template footer admin');

// --- ITEM 12 ---
$doc->title('3.12 [DI LUAR KODE] Hardening Server', 3);
$doc->bullet('Update PHP ke versi supported (minimal 8.x jika memungkinkan migrasi CI)');
$doc->bullet('MySQL user dengan privilege minimal (bukan root di production)');
$doc->bullet('Firewall, fail2ban, backup terenkripsi');
$doc->bullet('Sembunyikan error PHP di production (display_errors=Off)');
$doc->bullet('Pisahkan .env / config sensitif dari repository Git');
$doc->pageBreak();

// ═══ 4. CHECKLIST ═════════════════════════════════════════════════════════════
$doc->title('4. Checklist Kerja (Centang Saat Selesai)', 2);
$doc->paragraph('Gunakan checklist ini saat mulai fase keamanan setelah Persediaan selesai.');

$checklist = [
    'FASE 1 — CSRF Global' => [
        'csrf_protection = TRUE di config.php',
        'Meta CSRF token di template header admin',
        'ajaxSetup CSRF di template footer admin',
        'Uji AJAX Persediaan — semua endpoint OK',
        'Uji AJAX Penjualan — semua endpoint OK',
        'Uji AJAX Pembelian — semua endpoint OK',
        'Uji form POST manual — tidak 403',
        'Update Audit_Keamanan_Login_AnekaDharma.doc (Bagian E)',
    ],
    'FASE 2 — Hak Akses Ketat' => [
        'Default deny di is_login() untuk controller tidak di tbl_menu',
        'Whitelist admin level 99 (jika diperlukan)',
        'Audit tbl_menu vs daftar controller',
        'Uji user biasa akses URL tersembunyi → redirect blokir',
        'Update dokumen audit (Bagian E)',
    ],
    'FASE 3 — Password Legacy' => [
        'Re-hash MD5 → bcrypt saat login sukses',
        'Validasi password baru (min panjang/kompleksitas)',
        'Uji login user password lama → otomatis upgrade',
        'Update dokumen audit (Bagian E)',
    ],
    'FASE 4 — Logout & Headers' => [
        'Logout POST + CSRF token',
        'Security headers hook global',
        'HSTS di production',
        'CSP report-only (opsional tahap 1)',
        'Update dokumen audit (Bagian E)',
    ],
    'FASE 5 — Audit & XSS' => [
        'Tabel tbl_security_log dibuat',
        'Log login gagal, lockout, MFA, blokir',
        'Halaman admin lihat log',
        'Review XSS modul Persediaan',
        'Review XSS modul Penjualan & Pembelian',
        'Update dokumen audit (Bagian E)',
    ],
    'FASE 6 — Opsional' => [
        'Hapus Create_password_hash dari production deploy',
        'Seragamkan view login alternatif',
        'Session idle timeout',
        'REST API token auth (jika dibutuhkan)',
        'Hardening server checklist',
    ],
];

foreach ($checklist as $phase => $items) {
    $doc->title($phase, 3);
    foreach ($items as $item) {
        $doc->bullet('[ ] ' . $item);
    }
}
$doc->pageBreak();

// ═══ 5. PENGUJIAN ═════════════════════════════════════════════════════════════
$doc->title('5. Skenario Pengujian Setelah Implementasi', 2);
$doc->table(['No', 'Skenario', 'Hasil Diharapkan'], [
    ['1', 'Login normal (user biasa)', 'Masuk dashboard tanpa MFA'],
    ['2', 'Login admin', 'Redirect verifymfa → OTP WhatsApp → dashboard'],
    ['3', 'Login salah 11x berturut', 'Lockout 15 menit, pesan generik'],
    ['4', '/Dashboard tanpa session', 'Redirect ke login'],
    ['5', 'User tanpa hak menu akses modul', 'Redirect /blokir'],
    ['6', 'User akses URL controller tidak di menu (setelah Fase 2)', 'Redirect /blokir'],
    ['7', 'AJAX Persediaan simpan data (setelah Fase 1)', 'Sukses, tidak 403'],
    ['8', 'AJAX tanpa CSRF token (setelah Fase 1)', 'Ditolak 403'],
    ['9', 'Logout via POST (setelah Fase 4)', 'Session hancur, redirect login'],
    ['10', 'Login user password MD5 lama (setelah Fase 3)', 'Sukses + password di-upgrade bcrypt'],
    ['11', 'Lupa password spam 6x', 'Lockout 30 menit'],
    ['12', 'Akses HTTP di production', 'Redirect HTTPS'],
]);

$doc->title('5.1 Environment Pengujian', 3);
$doc->bullet('Lokal: localhost/xampp — HTTPS redirect biasanya skip');
$doc->bullet('Staging/Production: wajib uji HTTPS, cookie Secure, HSTS');
$doc->bullet('Browser: Chrome + Firefox — cek cookie SameSite di DevTools');
$doc->pageBreak();

// ═══ 6. REFERENSI ═════════════════════════════════════════════════════════════
$doc->title('6. Catatan Penting & Referensi File', 2);

$doc->title('6.1 File Keamanan yang Sudah Ada (Referensi)', 3);
$doc->table(['File', 'Fungsi'], [
    ['application/helpers/login_security_helper.php', 'CSRF login, rate limit, MFA, HTTPS, validasi'],
    ['application/config/login_security.php', 'Konfigurasi rate limit, MFA, HTTPS, pesan'],
    ['application/controllers/Anekadharmamasuk.php', 'Login, MFA, lupa password, logout'],
    ['application/helpers/racode_helper.php', 'is_login(), is_logged_in(), cek menu ACL'],
    ['application/hooks/auth_hook.php', 'Guard global semua controller'],
    ['application/config/auth_public.php', 'Whitelist controller publik (tanpa login)'],
    ['application/config/hooks.php', 'Registrasi hook auth + (nanti) security headers'],
    ['application/config/config.php', 'Session, cookie, csrf_protection (masih FALSE)'],
    ['docs/Audit_Keamanan_Login_AnekaDharma.doc', 'Dokumen audit master Bagian A–D'],
    ['docs/UpdateNEXTsecurity.docx', 'Dokumen ini — roadmap berikutnya'],
]);

$doc->title('6.2 Aturan Update Dokumentasi', 3);
$doc->bullet('Setelah setiap fase selesai: tambahkan Bagian E (atau sub-bagian baru) ke Audit_Keamanan_Login_AnekaDharma.doc');
$doc->bullet('Centang checklist di Bagian 4 dokumen ini');
$doc->bullet('Regenerate UpdateNEXTsecurity.docx jika ada item baru: php tools/generate_update_next_security_docx.php');

$doc->title('6.3 Perintah Regenerate Dokumen Ini', 3);
$doc->codeBlock('cd D:\\xampp74\\htdocs\\anekadharma_new_20241025\nphp tools/generate_update_next_security_docx.php');

$doc->paragraph('');
$doc->paragraph('Dokumen: docs/UpdateNEXTsecurity.docx | Revisi: ' . $today . ' | Status: ROADMAP — belum diimplementasi', ['italic' => true, 'size' => 18]);

// ═══ SAVE ═════════════════════════════════════════════════════════════════════
$out = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'UpdateNEXTsecurity.docx';
$saved = $doc->save($out);
echo "Berhasil: {$saved}\n";
