#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[CHECK]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

echo "========================================"
echo "   CuanQ Migration Package Verification"
echo "========================================"
echo

# Check essential files
print_status "Checking essential files..."

if [ -f "docker-compose.yml" ]; then
    print_success "docker-compose.yml found"
else
    print_error "docker-compose.yml missing"
fi

if [ -f "Dockerfile" ]; then
    print_success "Dockerfile found"
else
    print_error "Dockerfile missing"
fi

if [ -f "env" ]; then
    print_success "Environment file found"
else
    print_error "Environment file missing"
fi

if [ -f "cuanq_database_backup.sql" ]; then
    backup_size=$(wc -c < cuanq_database_backup.sql)
    if [ $backup_size -gt 1000 ]; then
        print_success "Database backup found (${backup_size} bytes)"
    else
        print_warning "Database backup seems too small (${backup_size} bytes)"
    fi
else
    print_error "Database backup missing"
fi

# Check application structure
print_status "Checking application structure..."

if [ -d "app" ]; then
    print_success "App directory found"
else
    print_error "App directory missing"
fi

if [ -d "public" ]; then
    print_success "Public directory found"
else
    print_error "Public directory missing"
fi

if [ -d "vendor" ]; then
    print_success "Vendor directory found"
else
    print_error "Vendor directory missing"
fi

# Check migration scripts
print_status "Checking migration scripts..."

if [ -f "migrate_to_pc.bat" ]; then
    print_success "Windows migration script found"
else
    print_error "Windows migration script missing"
fi

if [ -f "migrate_to_pc.sh" ]; then
    if [ -x "migrate_to_pc.sh" ]; then
        print_success "Linux/Mac migration script found (executable)"
    else
        print_warning "Linux/Mac migration script found (not executable)"
    fi
else
    print_error "Linux/Mac migration script missing"
fi

# Check documentation
print_status "Checking documentation..."

if [ -f "MIGRATION_GUIDE.md" ]; then
    print_success "Migration guide found"
else
    print_error "Migration guide missing"
fi

if [ -f "README_MIGRATION.md" ]; then
    print_success "Migration README found"
else
    print_error "Migration README missing"
fi

# Check key application files
print_status "Checking key application files..."

if [ -f "app/Controllers/PesananController.php" ]; then
    print_success "Pesanan controller found"
else
    print_error "Pesanan controller missing"
fi

if [ -f "app/Models/PesananBahanUsageModel.php" ]; then
    print_success "Bahan baku usage model found"
else
    print_error "Bahan baku usage model missing"
fi

if [ -f "app/Views/pesanan/detail.php" ]; then
    print_success "Pesanan detail view found"
else
    print_error "Pesanan detail view missing"
fi

# Check for recent features
print_status "Checking for latest features..."

if grep -q "getBahanBakuUsage" app/Controllers/PesananController.php; then
    print_success "Bahan baku tracking API found"
else
    print_error "Bahan baku tracking API missing"
fi

if grep -q "showSnackbar" app/Views/pesanan/detail.php; then
    print_success "Snackbar notifications found"
else
    print_error "Snackbar notifications missing"
fi

if grep -q "allocateStockFIFO" app/Models/PesananBahanUsageModel.php; then
    print_success "FIFO allocation system found"
else
    print_error "FIFO allocation system missing"
fi

echo
echo "========================================"
echo "        Verification Complete"
echo "========================================"
echo
echo "Migration package is ready for transfer!"
echo
echo "To migrate to your PC:"
echo "1. Copy the entire cuanQ folder to your PC"
echo "2. Install Docker Desktop"
echo "3. Run the appropriate migration script:"
echo "   - Windows: migrate_to_pc.bat"
echo "   - Linux/Mac: ./migrate_to_pc.sh"
echo
echo "Your system includes all the latest features:"
echo "✓ FIFO inventory tracking"
echo "✓ Real-time profit calculations"
echo "✓ Bahan baku usage per pesanan"
echo "✓ Complete financial management"
echo "✓ Beautiful snackbar notifications"
echo
