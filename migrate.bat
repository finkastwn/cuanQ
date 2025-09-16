@echo off
echo 🚀 Starting CuanQ Migration Process...
echo ==================================

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker is not running. Please start Docker first.
    pause
    exit /b 1
)

echo ✅ Docker is running

REM Start containers if not running
echo ⏳ Starting containers...
docker-compose up -d

echo ⏳ Waiting for containers to be ready...
timeout /t 10 /nobreak >nul

REM Wait for database to be ready
echo ⏳ Waiting for database to be ready...
:wait_db
docker-compose exec -T db mysql -u root -proot -e "SELECT 1;" >nul 2>&1
if errorlevel 1 (
    timeout /t 2 /nobreak >nul
    goto wait_db
)

echo ✅ Database is ready

REM Run CodeIgniter migrations
echo 🔄 Running CodeIgniter migrations...
docker-compose exec -T app php spark migrate
if errorlevel 1 (
    echo ❌ CodeIgniter migrations failed
    pause
    exit /b 1
)

echo ✅ CodeIgniter migrations completed successfully

REM Create manual_transactions table if not exists
echo 🔍 Checking and creating required tables...
docker-compose exec -T db mysql -u root -proot cuanq -e "CREATE TABLE IF NOT EXISTS manual_transactions (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, tanggal DATE NOT NULL, keterangan VARCHAR(255), type ENUM('pemasukan', 'pengeluaran'), source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'bank_account', jumlah INT(11) UNSIGNED, kategori ENUM('manual', 'pesanan', 'pembelian_bahan') DEFAULT 'manual', reference_id INT(11) UNSIGNED NULL, created_at DATETIME NULL, updated_at DATETIME NULL);"

REM Create financial_summary table if not exists
docker-compose exec -T db mysql -u root -proot cuanq -e "CREATE TABLE IF NOT EXISTS financial_summary (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, utang_total INT(11) UNSIGNED DEFAULT 0, bank_account_balance INT(11) UNSIGNED DEFAULT 0, shopee_pocket_balance INT(11) UNSIGNED DEFAULT 0, last_updated DATETIME NULL); INSERT IGNORE INTO financial_summary (utang_total, bank_account_balance, shopee_pocket_balance, last_updated) VALUES (0, 0, 0, NOW());"

REM Add missing columns
echo 📝 Adding missing columns...
docker-compose exec -T db mysql -u root -proot cuanq -e "ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS status ENUM('pesanan_baru', 'dalam_proses', 'dikirim', 'selesai', 'dicairkan') DEFAULT 'pesanan_baru' AFTER tanggal_pesanan;"

docker-compose exec -T db mysql -u root -proot cuanq -e "ALTER TABLE pembelian_bahan ADD COLUMN IF NOT EXISTS source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'duit_pribadi' AFTER nama_pembelian;"

docker-compose exec -T db mysql -u root -proot cuanq -e "ALTER TABLE manual_transactions ADD COLUMN IF NOT EXISTS source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'bank_account' AFTER type;"

echo ✅ Migration completed successfully!
echo ==================================
echo.
echo ✨ Your CuanQ application is now ready!
echo    - Access the app: http://localhost:8080
echo    - Access phpMyAdmin: http://localhost:8081
echo.
pause
