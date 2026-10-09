@echo off
setlocal
cd /d "%~dp0"
title Lumora Development Server

echo.
echo ==========================================
echo   LUMORA - Menyiapkan aplikasi
echo ==========================================
echo.

where php >nul 2>nul
if errorlevel 1 goto :php_missing
where composer >nul 2>nul
if errorlevel 1 goto :composer_missing
where npm >nul 2>nul
if errorlevel 1 goto :npm_missing

if not exist ".env" copy ".env.example" ".env" >nul

if not exist "vendor\autoload.php" (
    echo [1/5] Memasang dependensi PHP...
    call composer install
    if errorlevel 1 goto :failed
) else (
    echo [1/5] Dependensi PHP tersedia.
)

if not exist "node_modules" (
    echo [2/5] Memasang dependensi tampilan...
    call npm install
    if errorlevel 1 goto :failed
) else (
    echo [2/5] Dependensi tampilan tersedia.
)

findstr /B /C:"APP_KEY=base64:" ".env" >nul 2>nul
if errorlevel 1 (
    echo [3/5] Membuat application key...
    php artisan key:generate
    if errorlevel 1 goto :failed
) else (
    echo [3/5] Application key tersedia.
)

set "LUMORA_NEW_DATABASE=0"
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
    set "LUMORA_NEW_DATABASE=1"
)

echo [4/5] Menyiapkan database dan cache...
php artisan optimize:clear
if errorlevel 1 goto :failed
php artisan migrate --force
if errorlevel 1 goto :failed
if "%LUMORA_NEW_DATABASE%"=="1" (
    echo Mengisi akun dan data demo pertama kali...
    php artisan db:seed --force
    if errorlevel 1 goto :failed
)

echo [5/5] Membangun tampilan...
call npm run build
if errorlevel 1 goto :failed

echo.
echo Lumora tersedia di: http://127.0.0.1:8000
echo Jangan tutup jendela ini selama Lumora digunakan.
echo Tekan Ctrl+C untuk menghentikan seluruh layanan.
echo.
start "" powershell.exe -NoProfile -WindowStyle Hidden -Command "Start-Sleep -Seconds 6; Start-Process 'http://127.0.0.1:8000'"
call composer run dev:realtime
goto :end

:php_missing
echo ERROR: PHP tidak ditemukan. Tambahkan folder PHP XAMPP ke PATH Windows.
echo Contoh lokasi: C:\xampp\php
goto :failed_pause

:composer_missing
echo ERROR: Composer tidak ditemukan. Instal Composer dan pilih PHP dari XAMPP.
goto :failed_pause

:npm_missing
echo ERROR: Node.js/npm tidak ditemukan. Instal Node.js versi LTS.
goto :failed_pause

:failed
echo.
echo Proses berhenti karena terjadi kesalahan. Baca pesan di atas.

:failed_pause
pause

:end
endlocal
