#!/bin/bash

echo "🚀 Starting CuanQ Migration Process..."
echo "=================================="

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker first."
    exit 1
fi

print_status "Docker is running"

if ! docker-compose ps | grep -q "Up"; then
    print_warning "Containers are not running. Starting them..."
    docker-compose up -d
    echo "Waiting for containers to be ready..."
    sleep 10
fi

print_status "Containers are running"

echo "⏳ Waiting for database to be ready..."
timeout=60
counter=0
while [ $counter -lt $timeout ]; do
    if docker-compose exec -T db mysql -u root -proot -e "SELECT 1;" > /dev/null 2>&1; then
        break
    fi
    sleep 2
    counter=$((counter + 2))
    echo -n "."
done

if [ $counter -ge $timeout ]; then
    print_error "Database is not responding after ${timeout} seconds"
    exit 1
fi

print_status "Database is ready"

echo "🔄 Running CodeIgniter migrations..."
if docker-compose exec -T app php spark migrate; then
    print_status "CodeIgniter migrations completed successfully"
else
    print_error "CodeIgniter migrations failed"
    exit 1
fi

echo "🔍 Checking if manual_transactions table exists..."
if ! docker-compose exec -T db mysql -u root -proot cuanq -e "DESCRIBE manual_transactions;" > /dev/null 2>&1; then
    print_warning "manual_transactions table not found. Creating it manually..."
    
    docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
CREATE TABLE IF NOT EXISTS manual_transactions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255),
    type ENUM('pemasukan', 'pengeluaran'),
    source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'bank_account',
    jumlah INT(11) UNSIGNED,
    kategori ENUM('manual', 'pesanan', 'pembelian_bahan') DEFAULT 'manual',
    reference_id INT(11) UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
EOF
    
    if [ $? -eq 0 ]; then
        print_status "manual_transactions table created successfully"
    else
        print_error "Failed to create manual_transactions table"
        exit 1
    fi
else
    print_status "manual_transactions table already exists"
fi

echo "🔍 Checking if financial_summary table exists..."
if ! docker-compose exec -T db mysql -u root -proot cuanq -e "DESCRIBE financial_summary;" > /dev/null 2>&1; then
    print_warning "financial_summary table not found. Creating it manually..."
    
    docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
CREATE TABLE IF NOT EXISTS financial_summary (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utang_total INT(11) UNSIGNED DEFAULT 0,
    bank_account_balance INT(11) UNSIGNED DEFAULT 0,
    shopee_pocket_balance INT(11) UNSIGNED DEFAULT 0,
    last_updated DATETIME NULL
);

INSERT INTO financial_summary (utang_total, bank_account_balance, shopee_pocket_balance, last_updated) 
SELECT 0, 0, 0, NOW()
WHERE NOT EXISTS (SELECT 1 FROM financial_summary LIMIT 1);
EOF
    
    if [ $? -eq 0 ]; then
        print_status "financial_summary table created successfully"
    else
        print_error "Failed to create financial_summary table"
        exit 1
    fi
else
    print_status "financial_summary table already exists"
fi

echo "🔄 Adding missing columns to existing tables..."

echo "📝 Adding status column to pesanan table..."
docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS status ENUM('pesanan_baru', 'dalam_proses', 'dikirim', 'selesai', 'dicairkan') DEFAULT 'pesanan_baru' AFTER tanggal_pesanan;
UPDATE pesanan SET status = 'pesanan_baru' WHERE status IS NULL;
EOF

if [ $? -eq 0 ]; then
    print_status "Status column added to pesanan table"
else
    print_warning "Status column might already exist in pesanan table"
fi

# Add source_money column to pembelian_bahan table
echo "📝 Adding source_money column to pembelian_bahan table..."
docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
ALTER TABLE pembelian_bahan ADD COLUMN IF NOT EXISTS source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'duit_pribadi' AFTER nama_pembelian;
EOF

if [ $? -eq 0 ]; then
    print_status "Source_money column added to pembelian_bahan table"
else
    print_warning "Source_money column might already exist in pembelian_bahan table"
fi

# Add source_money column to manual_transactions table if it doesn't exist
echo "📝 Adding source_money column to manual_transactions table..."
docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
ALTER TABLE manual_transactions ADD COLUMN IF NOT EXISTS source_money ENUM('duit_pribadi', 'bank_account', 'shopee_pocket') DEFAULT 'bank_account' AFTER type;
EOF

if [ $? -eq 0 ]; then
    print_status "Source_money column added to manual_transactions table"
else
    print_warning "Source_money column might already exist in manual_transactions table"
fi

# Migrate existing pesanan financial data
echo "💰 Migrating existing pesanan financial data..."
docker-compose exec -T db mysql -u root -proot cuanq << 'EOF'
-- Create manual_transactions for existing pesanan with 'selesai' status
INSERT IGNORE INTO manual_transactions (tanggal, keterangan, type, source_money, jumlah, kategori, reference_id, created_at, updated_at)
SELECT 
    tanggal_pesanan,
    CONCAT('Pesanan Selesai: ', nama_pembeli),
    'pemasukan',
    'shopee_pocket',
    total_harga,
    'pesanan',
    id,
    NOW(),
    NOW()
FROM pesanan 
WHERE status = 'selesai'
AND id NOT IN (
    SELECT DISTINCT reference_id 
    FROM manual_transactions 
    WHERE kategori = 'pesanan' 
    AND reference_id IS NOT NULL
    AND type = 'pemasukan'
    AND source_money = 'shopee_pocket'
);

-- Create manual_transactions for existing pesanan with 'dicairkan' status
INSERT IGNORE INTO manual_transactions (tanggal, keterangan, type, source_money, jumlah, kategori, reference_id, created_at, updated_at)
SELECT 
    tanggal_pesanan,
    CONCAT('Pencairan dari Shopee: ', nama_pembeli),
    'pengeluaran',
    'shopee_pocket',
    total_harga,
    'pesanan',
    id,
    NOW(),
    NOW()
FROM pesanan 
WHERE status = 'dicairkan'
AND id NOT IN (
    SELECT DISTINCT reference_id 
    FROM manual_transactions 
    WHERE kategori = 'pesanan' 
    AND reference_id IS NOT NULL
    AND type = 'pengeluaran'
    AND source_money = 'shopee_pocket'
);

INSERT IGNORE INTO manual_transactions (tanggal, keterangan, type, source_money, jumlah, kategori, reference_id, created_at, updated_at)
SELECT 
    tanggal_pesanan,
    CONCAT('Pencairan ke Bank: ', nama_pembeli),
    'pemasukan',
    'bank_account',
    total_harga,
    'pesanan',
    id,
    NOW(),
    NOW()
FROM pesanan 
WHERE status = 'dicairkan'
AND id NOT IN (
    SELECT DISTINCT reference_id 
    FROM manual_transactions 
    WHERE kategori = 'pesanan' 
    AND reference_id IS NOT NULL
    AND type = 'pemasukan'
    AND source_money = 'bank_account'
);
EOF

if [ $? -eq 0 ]; then
    print_status "Existing pesanan financial data migrated successfully"
else
    print_warning "Some pesanan financial data might already be migrated"
fi

# Show final status
echo ""
echo "🎉 Migration completed successfully!"
echo "=================================="
echo ""

# Show table status
echo "📊 Database Tables Status:"
echo "=========================="

# Check tables exist
tables=("pesanan" "pembelian_bahan" "manual_transactions" "financial_summary")
for table in "${tables[@]}"; do
    if docker-compose exec -T db mysql -u root -proot cuanq -e "DESCRIBE $table;" > /dev/null 2>&1; then
        count=$(docker-compose exec -T db mysql -u root -proot cuanq -e "SELECT COUNT(*) FROM $table;" 2>/dev/null | tail -n 1)
        print_status "$table table exists ($count records)"
    else
        print_error "$table table missing"
    fi
done

echo ""
echo "✨ Your CuanQ application is now ready!"
echo "   - Access the app: http://localhost:8080"
echo "   - Access phpMyAdmin: http://localhost:8081"
echo ""
echo "🔗 Next steps:"
echo "   1. Test creating a new pesanan"
echo "   2. Test changing pesanan status to see financial updates"
echo "   3. Check the keuangan page for financial summaries"
echo ""
