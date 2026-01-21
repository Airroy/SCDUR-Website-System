# 🚀 คู่มือติดตั้ง SCD Project

> **อัปเดตล่าสุด:** 21 มกราคม 2026  
> **PHP:** 8.5 | **Laravel:** 12.x | **Database:** MySQL 8.4 | **Cache:** Redis

---

## 📋 สารบัญ

- [Part A: สำหรับนักพัฒนา (Development)](#part-a-สำหรับนักพัฒนา-development)
- [Part B: สำหรับ Production (DigitalOcean / VPS)](#part-b-สำหรับ-production-digitalocean--vps)
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

## 🛠️ ขั้นตอนติดตั้ง (10 ขั้นตอน)

### 1. Clone โปรเจกต์
```bash
git clone https://github.com/panchaphon-oil/scd-project.git
cd scd-project
git checkout backend
```

### 2. สร้างไฟล์ Environment
```bash
cp .env.example .env
```

### 3. ติดตั้ง PHP Dependencies

**Linux / macOS:**
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs
```

**Windows (PowerShell):**
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php85-composer:latest `
    composer install --ignore-platform-reqs
```

### 4. เริ่ม Docker
```bash
./vendor/bin/sail up -d
```

> ⏳ ครั้งแรกจะใช้เวลา 5-10 นาที (ดาวน์โหลด images)

### 5. Generate App Key
```bash
./vendor/bin/sail artisan key:generate
```

### 6. สร้างตาราง Database
```bash
./vendor/bin/sail artisan migrate
```

### 7. สร้าง Storage Link
```bash
./vendor/bin/sail artisan storage:link
```

### 8. ติดตั้ง Frontend Dependencies
```bash
./vendor/bin/sail npm install
```

### 9. รัน Frontend Dev Server
```bash
./vendor/bin/sail npm run dev
```

> 💡 **เปิด terminal ไว้** - ต้องรันตลอดเวลาที่พัฒนา

### 10. สร้าง Admin User
```bash
./vendor/bin/sail artisan admin:create
```

กรอกข้อมูล: ชื่อ, อีเมล, รหัสผ่าน

---

## ✅ เสร็จแล้ว! เข้าใช้งาน

| URL | หน้า |
|-----|------|
| http://localhost | เว็บไซต์หลัก |
| http://localhost/login | เข้าสู่ระบบ Admin |
| http://localhost:8080 | phpMyAdmin (จัดการ DB) |

---

## 💻 การใช้งานประจำวัน

### เปิดทำงาน (ทุกครั้งที่เริ่มทำงาน)
```bash
cd scd-project
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```

### ปิดเมื่อเลิกทำงาน
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

## 📁 โครงสร้างโปรเจกต์ที่ควรรู้

```
scd-project/
├── app/
│   ├── Livewire/           # ← Components หลัก (แก้ไขบ่อย)
│   │   ├── Backend/        #    Admin Panel
│   │   └── Frontend/       #    หน้าบ้าน
│   └── Models/             # ← Database Models
├── resources/views/        # ← Blade Templates (แก้ไขบ่อย)
│   ├── layouts/            #    โครงร่างหน้า
│   ├── livewire/           #    Views ของ Livewire
│   └── components/         #    Components ย่อย
├── routes/web.php          # ← Routes ทั้งหมด
├── docker/8.5/             # ← Docker configuration
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

## ❌ Port ชนกัน

**แก้ไขใน `.env`:**
```env
APP_PORT=8000
FORWARD_DB_PORT=3307
VITE_PORT=5174
```

จากนั้น:
```bash
sail down && sail up -d
```

เข้าที่: http://localhost:8000

---

## ❌ Permission Denied

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

# 🎯 Checklist

## สำหรับ Development
- [ ] Docker Desktop รันอยู่
- [ ] `sail up -d` สำเร็จ
- [ ] เข้า http://localhost ได้
- [ ] `sail npm run dev` รันอยู่
- [ ] Login เข้า Admin ได้

## สำหรับ Production
- [ ] `docker compose ps` เห็น 4 containers running
- [ ] เข้า http://your-domain ได้
- [ ] Login เข้า Admin ได้
- [ ] อัปโหลดรูปภาพได้
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
