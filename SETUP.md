# คู่มือติดตั้ง SCD Project

> **อัปเดตล่าสุด:** 15 มีนาคม 2026  
> **Laravel:** 12.x  
> **PHP:** 8.4 (Sail), รองรับ ^8.2 ตาม composer  
> **Database:** MySQL 8.4  
> **Cache:** database (default), Redis (optional)

---

## Part A: Development (ใช้งานบนเครื่องตัวเอง)

### ข้อกำหนด
- Docker Desktop
- Git
- แนะนำใช้งานบน WSL (Ubuntu) สำหรับ Windows

> ไม่ต้องติดตั้ง PHP/Composer/Node/MySQL/Redis บนเครื่อง

---

### ขั้นตอนติดตั้ง

1) Clone โปรเจกต์
```bash
git clone <your-repository-url>
cd scd-project
```

2) ติดตั้ง PHP dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

3) ตั้งค่า environment
```bash
cp .env.example .env
```

4) เริ่ม containers
```bash
./vendor/bin/sail up -d
```

5) ตั้งค่าระบบ
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link
```

6) ติดตั้งและ build frontend
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

7) สร้าง admin user
```bash
./vendor/bin/sail artisan admin:create
```

---

### เข้าใช้งาน

- หน้าเว็บหลัก: http://localhost
- Admin login: http://localhost/aru-scdur-panel
- Admin dashboard: http://localhost/admin/dashboard
- phpMyAdmin: http://localhost:8080

> หมายเหตุ: `/login` จะ redirect ไปหน้าแรก

---

## Part B: Production (Deploy ด้วย Docker)

### ขั้นตอนหลัก (ย่อ)

```bash
# Clone + set env
git clone <your-repository-url>
cd scd-project
cp .env.example .env

# Build + start
Docker compose up --build -d

# Setup app
docker compose exec laravel.test composer install --optimize-autoloader --no-dev
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate --force
docker compose exec laravel.test php artisan storage:link

# Build assets
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build

# Create admin
docker compose exec laravel.test php artisan admin:create
```

---

## ไฟล์อัปโหลด

- ตั้งค่าขนาดไฟล์: [config/upload.php](config/upload.php)
- ค่า PHP upload: [docker/8.4/php.ini](docker/8.4/php.ini)

ตรวจสอบค่าปัจจุบัน:
```bash
./vendor/bin/sail shell
php -i | grep upload_max_filesize
php -i | grep post_max_size
exit
```

---

## คำสั่งที่ใช้บ่อย

```bash
./vendor/bin/sail up -d
./vendor/bin/sail down
./vendor/bin/sail artisan storage:link
./vendor/bin/sail npm run dev
./vendor/bin/sail test
```

---

## หมายเหตุเพิ่มเติม

- Redis ถูกเตรียมไว้ใน compose.yaml แต่ค่า default ของ cache ยังเป็น database
- ถ้าจะใช้ Redis เป็นค่าเริ่มต้น ให้ตั้งใน .env:
  - CACHE_STORE=redis
  - (optional) SESSION_DRIVER=redis, QUEUE_CONNECTION=redis
