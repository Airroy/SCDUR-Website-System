# 🚀 คู่มือติดตั้ง SCD Project

> **อัปเดตล่าสุด:** 28 มกราคม 2026  
> **PHP:** 8.4 | **Laravel:** 12.x | **Database:** MySQL 8.4 | **Cache:** Redis

---

## 📋 สารบัญ

- [Part A: สำหรับนักพัฒนา (Development)](#part-a-สำหรับนักพัฒนา-development)
- [Part B: สำหรับ Production (DigitalOcean / VPS)](#part-b-สำหรับ-production-digitalocean--vps)
- [การอัปโหลดไฟล์](#-การอัปโหลดไฟล์)
- [คำสั่งที่ใช้บ่อย](#คำสั่งที่ใช้บ่อย)
- [การแก้ไขปัญหา](#การแก้ไขปัญหา)

---

# Part A: สำหรับนักพัฒนา (Development)

> 👨‍💻 **สำหรับเพื่อนที่จะ Clone โปรเจกต์ไปแก้ไขต่อบนเครื่องตัวเอง**

## ✅ ข้อกำหนด

ติดตั้งแค่ 2 อย่างนี้:

| Software | ดาวน์โหลด |
|----------|-----------|
| **Docker Desktop** | [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop) |
| **Git** | [git-scm.com/downloads](https://git-scm.com/downloads) |

> ❌ **ไม่ต้องติดตั้ง:** PHP, Composer, Node.js, MySQL, Redis (Docker จัดการให้หมด!)

---

## 🛠️ ขั้นตอนติดตั้ง

### ขั้นตอนที่ 1: Clone และเตรียมโปรเจกต์

```bash
git clone https://github.com/panchaphon-oil/scd-project.git
cd scd-project
git checkout backend
cp .env.example .env
```

---

### ขั้นตอนที่ 2: ติดตั้ง PHP Dependencies

**🐧 Linux / 🍎 macOS:**
```bash
rm -rf vendor 2>/dev/null; docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

**🪟 Windows (PowerShell):**
```powershell
Remove-Item -Recurse -Force vendor -ErrorAction SilentlyContinue; docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php84-composer:latest `
    composer install --ignore-platform-reqs
```

> ⏳ รอสักครู่... จะเห็น `Generating optimized autoload files` เมื่อสำเร็จ

---

### ขั้นตอนที่ 3: เริ่ม Docker และ Setup

```bash
./vendor/bin/sail up -d
```

> ⏳ **ครั้งแรก** จะใช้เวลา 5-10 นาที (build Docker image)

**รอจนเห็น containers ทำงาน แล้วรันคำสั่งต่อไปนี้ทีละบรรทัด:**

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link
./vendor/bin/sail npm install
```

---

### ขั้นตอนที่ 4: รัน Development Server

```bash
./vendor/bin/sail npm run dev
```

> 💡 **เปิด terminal นี้ไว้** ตลอดเวลาที่พัฒนา

---

### ขั้นตอนที่ 5: สร้าง Admin User

เปิด **terminal ใหม่** แล้วรัน:
```bash
cd scd-project
./vendor/bin/sail artisan admin:create
```

กรอก: ชื่อ, อีเมล, รหัสผ่าน

---

## ✅ เสร็จแล้ว! เข้าใช้งาน

| URL | หน้า |
|-----|------|
| http://localhost | เว็บไซต์หลัก |
| http://localhost/login | เข้าสู่ระบบ Admin |
| http://localhost:8080 | phpMyAdmin (จัดการ DB) |

---

## 💻 การใช้งานประจำวัน

### 🟢 เปิดทำงาน (ทุกครั้งที่เริ่มทำงาน)
```bash
cd scd-project
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```

### 🔴 ปิดเมื่อเลิกทำงาน
```bash
./vendor/bin/sail down
```

---

## 🔧 สร้าง Alias (แนะนำ)

ลดการพิมพ์ `./vendor/bin/sail` ทุกครั้ง:

**Linux / macOS:**
```bash
echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

**หลังจากนี้ใช้:**
```bash
sail up -d           # แทน ./vendor/bin/sail up -d
sail artisan migrate # แทน ./vendor/bin/sail artisan migrate
sail npm run dev     # แทน ./vendor/bin/sail npm run dev
```

---

## 📤 การอัปโหลดไฟล์

โปรเจกต์นี้รองรับการอัปโหลดไฟล์ขนาดใหญ่แล้ว:

### ขนาดไฟล์สูงสุด

| ประเภทไฟล์ | ขนาดสูงสุด | ใช้สำหรับ |
|-----------|-----------|-----------|
| **Banner/Slider** | 10 MB | รูป Banner หน้าแรก |
| **รูปปก Content** | 5 MB | รูปปกหมวดหมู่ |
| **ไฟล์ PDF** | 20 MB | เอกสาร/ประกาศ/คำสั่ง |
| **รูปโปรไฟล์** | 2 MB | รูปโปรไฟล์ผู้ใช้ |

### การตั้งค่า

การตั้งค่าอัปโหลดไฟล์อยู่ที่:
- **Config:** `config/upload.php`
- **PHP Settings:** `docker/8.4/php.ini` (ตั้งไว้ 100MB)
- **Validation:** Livewire Components (`app/Livewire/Backend/`)

> ✅ **ไม่ต้องตั้งค่าอะไรเพิ่ม!** Pull code มาแล้วใช้งานได้เลย

### สำหรับผู้ที่ Clone โปรเจกต์ใหม่

หลังจาก `sail up -d` ครั้งแรก การอัปโหลดไฟล์จะทำงานอัตโนมัติ

**ตรวจสอบว่าทำงานถูกต้อง:**
```bash
./vendor/bin/sail shell
php -i | grep upload_max_filesize
php -i | grep post_max_size
exit
```

ควรเห็น:
```
upload_max_filesize => 100M => 100M
post_max_size => 100M => 100M
```

### หากต้องการเปลี่ยนขนาดไฟล์

แก้ไขที่ `config/upload.php`:
```php
'max_file_sizes' => [
    'banner' => 10240,  // 10 MB
    'cover' => 5120,    // 5 MB
    'pdf' => 20480,     // 20 MB
],
```

แล้ว clear cache:
```bash
sail artisan config:clear
```

---

## 📁 โครงสร้างโปรเจกต์ที่ควรรู้

```
scd-project/
├── app/
│   ├── Livewire/           # ← Components หลัก (แก้ไขบ่อย)
│   │   ├── Backend/        #    Admin Panel
│   │   └── Frontend/       #    หน้าบ้าน
│   └── Models/             # ← Database Models
├── config/
│   └── upload.php          # ← การตั้งค่าอัปโหลดไฟล์
├── resources/views/        # ← Blade Templates (แก้ไขบ่อย)
│   ├── layouts/            #    โครงร่างหน้า
│   ├── livewire/           #    Views ของ Livewire
│   └── components/         #    Components ย่อย
├── routes/web.php          # ← Routes ทั้งหมด
├── docker/8.4/             # ← Docker configuration
│   └── php.ini             #    PHP settings (upload limits)
└── compose.yaml            # ← Docker Compose
```

> 📚 ดูรายละเอียดเพิ่มใน [RESOURCES_GUIDE.md](RESOURCES_GUIDE.md) และ [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)

---

# Part B: สำหรับ Production (DigitalOcean / VPS)

> 🌐 **สำหรับ Deploy ขึ้น Server จริง**

## ✅ ข้อกำหนด Server

| รายการ | ขั้นต่ำ |
|--------|---------|
| **OS** | Ubuntu 22.04 LTS |
| **RAM** | 2 GB |
| **Storage** | 20 GB |
| **Docker** | ติดตั้งบน server |

---

## 🛠️ ขั้นตอน Deploy

### 1. ติดตั้ง Docker บน Server
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker
```

### 2. Clone โปรเจกต์
```bash
git clone https://github.com/panchaphon-oil/scd-project.git
cd scd-project
git checkout backend
```

### 3. Setup Environment
```bash
cp .env.example .env
nano .env
```

**แก้ไขค่าสำคัญ:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_PASSWORD=your-strong-password-here
```

### 4. Build และ Start Containers
```bash
docker compose up --build -d
```

> ⏳ ครั้งแรกใช้เวลา 10-15 นาที

### 5. Setup Application
```bash
# ติดตั้ง dependencies
docker compose exec laravel.test composer install --optimize-autoloader --no-dev

# Generate key
docker compose exec laravel.test php artisan key:generate

# สร้างตาราง database
docker compose exec laravel.test php artisan migrate --force

# สร้าง storage link
docker compose exec laravel.test php artisan storage:link

# Build frontend
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build

# Optimize
docker compose exec laravel.test php artisan optimize
docker compose exec laravel.test php artisan view:cache
docker compose exec laravel.test php artisan route:cache
```

### 6. สร้าง Admin User
```bash
docker compose exec laravel.test php artisan admin:create
```

---

## ✅ เสร็จแล้ว!

เข้าใช้งานที่: `http://your-server-ip`

---

## 🔄 อัปเดตโค้ด (หลัง git pull)

```bash
git pull origin backend
docker compose exec laravel.test composer install --optimize-autoloader --no-dev
docker compose exec laravel.test php artisan migrate --force
docker compose exec laravel.test npm run build
docker compose exec laravel.test php artisan optimize
```

---

## 📊 ดูสถานะ

```bash
# ดู containers
docker compose ps

# ดู logs
docker compose logs -f

# ดู logs เฉพาะ Laravel
docker compose logs -f laravel.test

# รีสตาร์ท
docker compose restart
```

---

# คำสั่งที่ใช้บ่อย

## Docker / Sail

| คำสั่ง | หน้าที่ |
|--------|---------|
| `sail up -d` | เริ่มระบบ |
| `sail down` | หยุดระบบ |
| `sail restart` | รีสตาร์ท |
| `sail ps` | ดูสถานะ containers |
| `sail logs -f` | ดู logs แบบ real-time |
| `sail shell` | เข้า bash ใน container |
| `sail mysql` | เข้า MySQL shell |

## Laravel Artisan

| คำสั่ง | หน้าที่ |
|--------|---------|
| `sail artisan migrate` | รัน migrations |
| `sail artisan migrate:fresh` | ลบตารางทั้งหมดและสร้างใหม่ |
| `sail artisan migrate:fresh --seed` | Migrate + Seed data |
| `sail artisan cache:clear` | Clear cache |
| `sail artisan config:clear` | Clear config cache |
| `sail artisan optimize:clear` | Clear ทุก cache |
| `sail artisan storage:link` | สร้าง storage symlink |
| `sail artisan admin:create` | สร้าง admin user |

## NPM

| คำสั่ง | หน้าที่ |
|--------|---------|
| `sail npm install` | ติดตั้ง packages |
| `sail npm run dev` | Development mode (hot reload) |
| `sail npm run build` | Production build |

---

# การแก้ไขปัญหา

## ❌ Port ชนกัน (port 80 ถูกใช้งานอยู่)

แก้ไขใน `.env` แล้ว restart:
```bash
# เพิ่มใน .env
APP_PORT=8000

# restart
./vendor/bin/sail down && ./vendor/bin/sail up -d
```
เข้าที่: http://localhost:8000

---

## ❌ Permission Denied (storage หรือ bootstrap/cache)

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

---

## ❌ CSS/JS ไม่อัปเดต

```bash
sail npm run build
sail artisan view:clear
sail artisan optimize:clear
```

หรือกด `Ctrl+Shift+R` ใน browser

---

## ❌ รูปภาพไม่แสดง

```bash
sail artisan storage:link
```

---

## ❌ Database Connection Error

ตรวจสอบ `.env`:
```env
DB_HOST=mysql        # ต้องเป็น 'mysql' ไม่ใช่ 'localhost'
```

```bash
sail artisan config:clear
sail down && sail up -d
```

---

## ❌ Livewire ไม่ทำงาน

```bash
sail artisan livewire:discover
sail artisan view:clear
sail artisan optimize:clear
```

---

## ❌ อัปโหลดไฟล์ไม่ได้ / File too large

**ตรวจสอบ PHP settings:**
```bash
./vendor/bin/sail shell
php -i | grep upload_max_filesize
php -i | grep post_max_size
exit
```

**ถ้าค่าไม่ใช่ 100M ให้ rebuild Docker:**
```bash
sail down
sail build --no-cache
sail up -d
```

**แล้ว clear cache:**
```bash
sail artisan config:clear
sail artisan cache:clear
```

**ตรวจสอบว่าไฟล์ไม่เกินขนาดที่กำหนด:**
- Banner: ≤ 10 MB
- Cover: ≤ 5 MB
- PDF: ≤ 20 MB

---

# 🎯 Checklist

## สำหรับ Development
- [ ] Docker Desktop รันอยู่
- [ ] `sail up -d` สำเร็จ
- [ ] เข้า http://localhost ได้
- [ ] `sail npm run dev` รันอยู่
- [ ] Login เข้า Admin ได้
- [ ] อัปโหลดรูป Banner (8-9 MB) ได้
- [ ] อัปโหลดรูปปก (4 MB) ได้
- [ ] อัปโหลด PDF (15-18 MB) ได้

## สำหรับ Production
- [ ] `docker compose ps` เห็น 4 containers running
- [ ] เข้า http://your-domain ได้
- [ ] Login เข้า Admin ได้
- [ ] อัปโหลดรูปภาพได้
- [ ] อัปโหลดไฟล์ PDF ขนาดใหญ่ได้ (ทดสอบ 15-20 MB)
- [ ] ตั้งค่า SSL/HTTPS

---

# 📚 เอกสารเพิ่มเติม

- [README.md](README.md) - ภาพรวมโปรเจกต์
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - โครงสร้างโค้ดละเอียด
- [RESOURCES_GUIDE.md](RESOURCES_GUIDE.md) - คู่มือ Views/Components ทุกไฟล์

---

# 🆘 ต้องการความช่วยเหลือ?

1. ดู logs: `sail logs -f` หรือ `storage/logs/laravel.log`
2. ตรวจสอบหัวข้อ [การแก้ไขปัญหา](#การแก้ไขปัญหา)
3. ถามใน GitHub Issues

---

**Happy Coding! 🚀**