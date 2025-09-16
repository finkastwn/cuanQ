#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

echo "========================================"
echo "    CuanQ System Migration Script"
echo "========================================"
echo

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed"
    echo "Please install Docker first:"
    echo "- Ubuntu/Debian: sudo apt-get install docker.io docker-compose"
    echo "- CentOS/RHEL: sudo yum install docker docker-compose"
    echo "- macOS: Install Docker Desktop"
    exit 1
fi

print_status "Docker is available"

# Check if docker-compose.yml exists
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found"
    echo "Please make sure you're in the cuanQ project directory"
    exit 1
fi

print_status "Starting Docker containers..."
if ! docker-compose up -d; then
    print_error "Failed to start containers"
    exit 1
fi

print_status "Waiting for MySQL to be ready..."
sleep 30

# Check if database backup exists
if [ ! -f "cuanq_database_backup.sql" ]; then
    print_warning "Database backup not found"
    print_warning "Skipping database import..."
else
    print_status "Importing database..."
    if docker-compose exec -T db mysql -u root -proot cuanq < cuanq_database_backup.sql; then
        print_success "Database imported successfully"
    else
        print_warning "Database import may have failed"
        echo "You can try importing manually later"
    fi
fi

echo
print_status "Running any pending migrations..."
docker-compose exec app php spark migrate

echo
echo "========================================"
echo "        Migration Complete!"
echo "========================================"
echo
echo "Your CuanQ system should now be running at:"
echo "http://localhost:8080"
echo
echo "Database credentials:"
echo "- Host: localhost:3306"
echo "- Username: root"
echo "- Password: root"
echo "- Database: cuanq"
echo
echo "Useful commands:"
echo "- Check container status: docker-compose ps"
echo "- View logs: docker-compose logs app"
echo "- Stop containers: docker-compose down"
echo
