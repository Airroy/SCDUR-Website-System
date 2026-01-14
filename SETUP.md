# SCD Project - Setup Guide

## ข้อกำหนดระบบ (Requirements)
- Docker Desktop
- Git
- ไม่ต้องติดตั้ง PHP, Composer, Node.js, MySQL (มีใน Docker แล้ว)

## Docker Services ที่ใช้งาน
โปรเจกต์นี้ใช้ Laravel Sail แบบเบาพร้อม services:
- **laravel.test** - Laravel Application (Port 80, 5173)
- **mysql** - MySQL 8.4 Database (Port 3306)

## ขั้นตอนการติดตั้ง (Installation)

### 1. Clone Project
```bash
git clone <repository-url>
cd scd-project
```

### 2. Setup Environment
```bash
cp .env.example .env
```
แก้ไข `.env` ให้เหมาะสม (ค่า default ใช้งานได้เลย)

### 3. ติดตั้ง Dependencies ผ่าน Sail
```bash
# ครั้งแรก ติดตั้ง dependencies ก่อนเริ่ม container
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# เริ่ม Docker containers
./vendor/bin/sail up -d
```

### 4. Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

### 5. Run Migrations
```bash
./vendor/bin/sail artisan migrate
```

### 6. Create Storage Link
```bash
./vendor/bin/sail artisan storage:link
```

### 7. Install NPM Dependencies และ Build Assets
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
# หรือสำหรับ production
./vendor/bin/sail npm run build
```

### 8. เปิดเว็บไซต์
เปิดเบราว์เซอร์ที่:
- **เว็บไซต์หลัก:** http://localhost

### 9. ตรวจสอบ Docker Containers
```bash
./vendor/bin/sail ps
```
ควรเห็น containers: laravel.test, mysql

## คำสั่งที่ใช้บ่อย (Common Commands)

### เริ่ม/หยุด Docker
```bash
./vendor/bin/sail up        # เริ่มและดู logs
./vendor/bin/sail up -d     # เริ่มแบบ background
./vendor/bin/sail down      # หยุด containers
./vendor/bin/sail restart   # รีสตาร์ท
```

### Database
```bash
./vendor/bin/sail artisan migrate          # Run migrations
./vendor/bin/sail artisan migrate:fresh    # Drop tables และ migrate ใหม่
./vendor/bin/sail artisan db:seed          # Run seeders
./vendor/bin/sail mysql                    # เข้า MySQL shell
```

### Livewire
```bash
./vendor/bin/sail artisan livewire:make ComponentName        # สร้าง component
./vendor/bin/sail artisan livewire:publish --config          # Publish config
```

### Development
```bash
./vendor/bin/sail npm run dev      # Watch mode สำหรับพัฒนา
./vendor/bin/sail npm run build    # Build สำหรับ production
./vendor/bin/sail artisan optimize # Optimize application
./vendor/bin/sail artisan cache:clear
```

### Logs
```bash
./vendor/bin/sail logs         # ดู logs
./vendor/bin/sail logs -f      # Follow logs
```

## ปัญหาที่พบบ่อย (Troubleshooting)

### Port ชนกัน
หาก port ถูกใช้งานอยู่ แก้ไขใน `.env`:
```env
APP_PORT=8000              # เปลี่ยนจาก 80
FORWARD_DB_PORT=3307       # เปลี่ยนจาก 3306
VITE_PORT=5174             # เปลี่ยนจาก 5173
```
จากนั้น restart: `./vendor/bin/sail restart`

### Permission Denied
```bash
sudo chmod -R 777 storage bootstrap/cache
```

### Composer หรือ NPM ช้า
```bash
./vendor/bin/sail composer install --no-scripts
./vendor/bin/sail npm ci
```

## โครงสร้างโปรเจกต์

- `app/` - Application logic
  - `Livewire/` - Livewire components
  - `Models/` - Database models
  - `Services/` - Business logic
- `resources/views/` - Blade templates
  - `components/frontend/` - Frontend components
  - `frontend/pages/` - Frontend pages
- `routes/web.php` - Web routes
- `database/migrations/` - Database migrations

## ข้อมูลเพิ่มเติม

- Laravel Sail: https://laravel.com/docs/12.x/sail
- Livewire: https://livewire.laravel.com/docs/
- Tailwind CSS: https://tailwindcss.com/docs

## หมายเหตุ

โปรเจกต์นี้ใช้:
- Laravel 12.x
- Livewire 3.x
- Tailwind CSS
- Alpine.js
- Docker (Laravel Sail)
- MySQL 8.4
- Redis (Cache)
- Meilisearch (Search Engine)
- Mailpit (Email Testing)
- Selenium (Browser Testing)
