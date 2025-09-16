# 🚀 CuanQ System Migration Guide

## 📋 What You Need to Transfer

### 1. **Complete Project Files**

Copy the entire `cuanQ` folder to your PC, which includes:

- All PHP application files (`app/`, `public/`, `vendor/`)
- Configuration files (`docker-compose.yml`, `env`, etc.)
- Database backup (`cuanq_database_backup.sql`)

### 2. **System Requirements on Your PC**

- Docker Desktop (Windows/Linux)
- Git (optional, for version control)

## 🔧 Migration Steps

### **Step 1: Install Docker Desktop**

1. Download Docker Desktop for your OS:
   - Windows: https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe
   - Linux: Follow instructions at https://docs.docker.com/desktop/install/linux-install/
2. Install and start Docker Desktop
3. Verify installation: `docker --version`

### **Step 2: Transfer Project Files**

1. Copy the entire `cuanQ` folder to your PC
2. Place it in a convenient location (e.g., `C:\Projects\cuanQ` or `~/Projects/cuanQ`)

### **Step 3: Setup on Your PC**

1. Open terminal/command prompt
2. Navigate to the cuanQ folder: `cd /path/to/cuanQ`
3. Start the containers: `docker-compose up -d`
4. Wait for containers to start (first time will download images)

### **Step 4: Import Database**

1. Wait for MySQL container to be ready (about 30 seconds)
2. Import the database:

   ```bash
   # Windows Command Prompt:
   docker-compose exec -T db mysql -u root -proot cuanq < cuanq_database_backup.sql

   # Windows PowerShell:
   Get-Content cuanq_database_backup.sql | docker-compose exec -T db mysql -u root -proot cuanq

   # Linux/Mac:
   docker-compose exec -T db mysql -u root -proot cuanq < cuanq_database_backup.sql
   ```

### **Step 5: Verify Installation**

1. Open browser and go to: `http://localhost:8080`
2. Login with your existing credentials
3. Check if all data is present:
   - Produk data
   - Bahan Baku data
   - Pesanan data
   - Keuangan data

## 🛠️ Troubleshooting

### **Port Conflicts**

If port 8080 or 3306 is already in use:

1. Edit `docker-compose.yml`
2. Change ports (e.g., `8081:80` instead of `8080:80`)
3. Restart containers: `docker-compose down && docker-compose up -d`

### **Database Import Issues**

If database import fails:

1. Check if containers are running: `docker-compose ps`
2. Check MySQL logs: `docker-compose logs db`
3. Try importing manually:
   ```bash
   docker-compose exec db mysql -u root -proot
   CREATE DATABASE IF NOT EXISTS cuanq;
   USE cuanq;
   SOURCE /path/to/backup/file;
   ```

### **Permission Issues (Linux)**

If you get permission errors:

```bash
sudo chown -R $USER:$USER /path/to/cuanQ
chmod -R 755 /path/to/cuanQ
```

## 📁 File Structure After Migration

```
cuanQ/
├── app/                          # CodeIgniter application
├── public/                       # Web accessible files
├── vendor/                       # PHP dependencies
├── writable/                     # Logs and cache
├── docker-compose.yml            # Container configuration
├── Dockerfile                    # Application container config
├── env                           # Environment variables
├── cuanq_database_backup.sql     # Database backup
├── migrate.sh                    # Migration helper (Linux/Mac)
├── migrate.bat                   # Migration helper (Windows)
└── MIGRATION_GUIDE.md            # This guide
```

## ✅ Post-Migration Checklist

- [ ] Docker containers running (`docker-compose ps`)
- [ ] Website accessible at `http://localhost:8080`
- [ ] Login working with existing credentials
- [ ] All product data visible
- [ ] Bahan baku data present
- [ ] Pesanan system working
- [ ] Keuangan calculations correct
- [ ] Bahan baku tracking system functional

## 🔧 Useful Commands

### **Container Management**

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View running containers
docker-compose ps

# View logs
docker-compose logs app
docker-compose logs db

# Restart containers
docker-compose restart
```

### **Database Operations**

```bash
# Access MySQL shell
docker-compose exec db mysql -u root -proot cuanq

# Create database backup
docker-compose exec -T db mysqldump -u root -proot cuanq > backup.sql

# Import database backup
docker-compose exec -T db mysql -u root -proot cuanq < backup.sql

# Run migrations
docker-compose exec app php spark migrate
```

### **Application Operations**

```bash
# Access application shell
docker-compose exec app bash

# View application logs
docker-compose exec app tail -f /var/www/html/writable/logs/log-$(date +%Y-%m-%d).log

# Clear cache
docker-compose exec app php spark cache:clear
```

## 🎯 Success Indicators

After successful migration, you should be able to:

1. ✅ Access the website at `http://localhost:8080`
2. ✅ Login with existing credentials
3. ✅ See all your existing data (products, materials, orders)
4. ✅ Add new bahan baku usage with FIFO tracking
5. ✅ View profit calculations and financial summaries
6. ✅ Use all existing features without issues

---

**🎉 Congratulations! Your CuanQ system is now running on your PC with all data intact!**
