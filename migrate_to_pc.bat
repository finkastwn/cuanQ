@echo off
echo ========================================
echo    CuanQ System Migration Script
echo ========================================
echo.

REM Check if Docker is installed
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not installed or not in PATH
    echo Please install Docker Desktop first:
    echo https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe
    pause
    exit /b 1
)

echo [INFO] Docker is available
echo.

REM Check if docker-compose.yml exists
if not exist "docker-compose.yml" (
    echo [ERROR] docker-compose.yml not found
    echo Please make sure you're in the cuanQ project directory
    pause
    exit /b 1
)

echo [INFO] Starting Docker containers...
docker-compose up -d

if %errorlevel% neq 0 (
    echo [ERROR] Failed to start containers
    pause
    exit /b 1
)

echo [INFO] Waiting for MySQL to be ready...
timeout /t 30 /nobreak >nul

echo [INFO] Checking if database backup exists...
if not exist "cuanq_database_backup.sql" (
    echo [WARNING] Database backup not found
    echo Skipping database import...
    goto :skip_import
)

echo [INFO] Importing database...
type cuanq_database_backup.sql | docker-compose exec -T db mysql -u root -proot cuanq

if %errorlevel% neq 0 (
    echo [WARNING] Database import may have failed
    echo You can try importing manually later
) else (
    echo [SUCCESS] Database imported successfully
)

:skip_import
echo.
echo [INFO] Running any pending migrations...
docker-compose exec app php spark migrate

echo.
echo ========================================
echo        Migration Complete!
echo ========================================
echo.
echo Your CuanQ system should now be running at:
echo http://localhost:8080
echo.
echo Database credentials:
echo - Host: localhost:3306
echo - Username: root
echo - Password: root
echo - Database: cuanq
echo.
echo To check container status: docker-compose ps
echo To view logs: docker-compose logs app
echo To stop: docker-compose down
echo.
pause
