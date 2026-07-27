@echo off
REM Jalankan as Administrator jika Windows memblokir index.php / app.php
cd /d "%~dp0.."
echo Membuat app.php dan index.php dari front.php ...
copy /Y front.php app.php
copy /Y front.php index.php
if exist app.php (
  echo OK: app.php berhasil dibuat.
) else (
  echo GAGAL: app.php - Access denied. Pakai URL front.php atau app.php via .htaccess.
)
if exist index.php (
  echo OK: index.php berhasil dibuat.
) else (
  echo GAGAL: index.php - Access denied.
)
pause
