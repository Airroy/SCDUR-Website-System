# คู่มือระบบ (IT / Developer) — SCD System

> **ระบบจัดอันดับมหาวิทยาลัยด้านการพัฒนาชุมชนอย่างยั่งยืน**  
> **Sustainable Community Development University Ranking System**  
> มหาวิทยาลัยราชภัฏพระนครศรีอยุธยา  
> **เวอร์ชัน:** 1.2.0 | **อัปเดตล่าสุด:** 12 มีนาคม 2026

> คู่มือนี้จัดทำขึ้นสำหรับ **ผู้ดูแลระบบ IT และ Developer** ที่รับผิดชอบติดตั้ง บำรุงรักษา และพัฒนาระบบ  
> สำหรับคู่มือผู้เยี่ยมชม ดูที่ [USER_MANUAL.md](USER_MANUAL.md)  
> สำหรับคู่มือ Admin Panel ดูที่ [ADMIN_MANUAL.md](ADMIN_MANUAL.md)

---

## สารบัญ

1. [Tech Stack และสถาปัตยกรรมระบบ](#1-tech-stack-และสถาปัตยกรรมระบบ)
2. [โครงสร้างไฟล์โปรเจกต์](#2-โครงสร้างไฟล์โปรเจกต์)
3. [ฐานข้อมูล (Database Schema)](#3-ฐานข้อมูล-database-schema)
4. [การติดตั้งและรันระบบ](#4-การติดตั้งและรันระบบ)
5. [การจัดการบัญชี Admin](#5-การจัดการบัญชี-admin)
6. [Routes และ URL Structure](#6-routes-และ-url-structure)
7. [Middleware และความปลอดภัย](#7-middleware-และความปลอดภัย)
8. [File Storage](#8-file-storage)
9. [Artisan Commands](#9-artisan-commands)
10. [Logging และ Monitoring](#10-logging-และ-monitoring)
11. [Configuration](#11-configuration)
12. [การ Backup และ Maintenance](#12-การ-backup-และ-maintenance)
13. [การแก้ไขปัญหาทางเทคนิค](#13-การแก้ไขปัญหาทางเทคนิค)

---

## 1. Tech Stack และสถาปัตยกรรมระบบ

### Framework และ Library หลัก

| ส่วน | Technology | เวอร์ชัน |
|------|-----------|--------|
| Backend Framework | Laravel | 12.46.0 |
| PHP | PHP | 8.4 |
| Frontend Reactive | Livewire | 3.7.3 |
| Frontend Interactive | Alpine.js | 3.x (bundled กับ Livewire) |
| CSS Framework | Tailwind CSS | 3.4.19 |
| Build Tool | Vite | 7.3.1 |
| Database | MySQL | 8.4 |
| Cache | Redis | Alpine |
| Redis Client | predis/predis | ^3.4 |
| Container | Docker (Laravel Sail) | — |
| DB Management | phpMyAdmin | Latest |

### สถาปัตยกรรม

```
Browser
  │
  ▼
Laravel Router (routes/web.php)
  │
  ├── Frontend Routes → Controllers → Blade Views
  │     └── Livewire Components (Frontend)
  │
  └── Admin Routes (middleware: admin.auth)
        └── Livewire Components (Backend)
              └── Services / Models / Database
```

### Docker Services (compose.yaml)

| Service | Image | Port | หน้าที่ |
|---------|-------|------|--------|
| `laravel.test` | sail-8.4/app | 80 (APP), 5173 (Vite) | PHP Application |
| `mysql` | mysql:8.4 | 3306 | Database |
| `redis` | redis:alpine | 6379 | Cache |
| `phpmyadmin` | phpmyadmin:latest | 8080 | Database GUI |

---

## 2. โครงสร้างไฟล์โปรเจกต์

```
scd-project/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   │   ├── ClearLogs.php          # ล้าง log file เก่า
│   │   ├── CreateAdminUser.php    # สร้างบัญชี Admin
│   │   └── MonitorContentNodesPerformance.php  # Monitor DB
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Frontend/          # Frontend Controllers
│   │   │       ├── HomeController.php
│   │   │       ├── FrontendController.php
│   │   ├── Middleware/
│   │   │   ├── AdminAuth.php      # ป้องกัน Admin routes (abort 404)
│   │   │   └── AccessLog.php      # บันทึก access log
│   │   └── Requests/Backend/     # Form Request Validation
│   │       ├── StoreScdYearRequest.php
│   │       └── UpdateScdYearRequest.php
│   ├── Livewire/
│   │   ├── Actions/
│   │   │   └── Logout.php         # Logout action
│   │   ├── Auth/                  # Login component
│   │   ├── Backend/               # Admin Panel components
│   │   │   ├── AdminDashboard.php
│   │   │   ├── YearsIndex.php
│   │   │   ├── BannersIndex.php
│   │   │   ├── ReportsIndex.php
│   │   │   ├── AnnouncementsIndex.php  # ใช้ร่วมกับคำสั่ง
│   │   │   ├── AnnouncementManager.php
│   │   │   ├── ContentsIndex.php
│   │   │   └── ContentSectionManager.php  # จัดการ content section
│   │   ├── Forms/
│   │   │   └── LoginForm.php      # Login form logic
│   │   ├── Frontend/              # Frontend components
│   │   │   └── BannerSlider.php
│   │   └── Profile/               # Profile management
│   │       ├── DeleteUser.php
│   │       ├── UpdatePassword.php
│   │       └── UpdateProfileInformation.php
│   ├── Models/
│   │   ├── ScdYear.php            # ปี SCD
│   │   ├── ScdReport.php          # รายงาน PDF ประจำปี
│   │   ├── Banner.php             # Banner รูปสไลด์ (table: scd_banners)
│   │   ├── Announcement.php       # ประกาศ (table: scd_announcements)
│   │   ├── Order.php              # คำสั่ง (table: scd_directives)
│   │   ├── ContentSection.php     # ตัวชี้วัด (table: scd_contents)
│   │   └── User.php               # Admin users
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── VoltServiceProvider.php
│   └── Services/
│       ├── FileUploadService.php  # Upload/Delete ไฟล์
│       ├── BannerService.php
│       └── ContentNodeService.php
├── bootstrap/
│   ├── app.php                    # Application configuration
│   └── providers.php
├── config/
│   ├── upload.php                 # ขนาดไฟล์ upload สูงสุด
│   └── ...                        # Laravel standard configs
├── database/
│   ├── migrations/                # Database migrations
│   └── seeders/
├── docker/                        # Docker configurations
│   └── 8.4/
├── public/                        # Web root
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── components/            # Blade components
│       │   ├── layouts/           # Admin, Frontend, Guest layouts
│       │   ├── backend/           # Admin UI components
│       │   └── frontend/          # Frontend UI components
│       ├── frontend/pages/        # Frontend pages
│       ├── livewire/              # Livewire view files
│       └── admin/pages/           # Admin pages (non-Livewire)
├── routes/
│   ├── web.php                    # Main routes
│   └── auth.php                   # Authentication routes
├── storage/
│   ├── app/public/                # Uploaded files (symlinked to public/storage)
│   └── logs/                      # Application logs
└── compose.yaml                   # Docker Compose
```

---

## 3. ฐานข้อมูล (Database Schema)

### ตารางหลัก

#### `scd_years` — ปี SCD

| Column | Type | หมายเหตุ |
|--------|------|--------|
| `id` | bigint PK | |
| `year` | string (varchar) UNIQUE | ปี ค.ศ. เช่น 2024 |
| `created_date` | date | วันที่อ้างอิงทางการ |
| `is_published` | boolean | เผยแพร่ต่อสาธารณะ |
| `created_at`, `updated_at` | timestamps | |

#### `scd_reports` — รายงาน PDF

| Column | Type | หมายเหตุ |
|--------|------|--------|
| `id` | bigint PK | |
| `scd_year_id` | FK → scd_years | |
| `file_name` | string | ชื่อไฟล์ |
| `file_path` | string | path ใน storage/public |
| `view_count` | integer default 0 | |
| `created_at`, `updated_at` | timestamps | |

#### `scd_banners` — Banner รูปสไลด์

| Column | Type | หมายเหตุ |
|--------|------|--------|
| `id` | bigint PK | |
| `scd_year_id` | FK → scd_years | |
| `category` | integer | 0 = Slider หลัก, 1 = Slider รอง |
| `sequence` | integer | ลำดับในการแสดงผล |
| `image_path` | string | path รูปภาพ |
| `link_type` | string (varchar) | ประเภทลิงก์ (none, url, pdf) |
| `link_url` | string nullable | URL ปลายทาง |
| `pdf_name` | string nullable | ชื่อ PDF |
| `pdf_path` | string nullable | path ไฟล์ PDF |
| `created_at`, `updated_at` | timestamps | |

#### `scd_announcements` — ประกาศ (Tree Structure)

| Column | Type | หมายเหตุ |
|--------|------|--------|
| `id` | bigint PK | |
| `scd_year_id` | FK → scd_years | |
| `parent_id` | FK → self nullable | null = root node |
| `type` | string (varchar) | |
| `name` | string | ชื่อที่แสดง |
| `sequence` | integer | ลำดับ |
| `image_path` | string nullable | รูปประกอบ |
| `file_path` | string nullable | PDF path |
| `view_count` | bigint default 0 | |
| `download_count` | bigint default 0 | |
| `is_hidden` | boolean default false | ซ่อนจากสาธารณะ |
| `created_at`, `updated_at` | timestamps | |

#### `scd_directives` — คำสั่ง (โครงสร้างเหมือน scd_announcements)

โครงสร้างเหมือนกับ `scd_announcements` ทุก column

#### `scd_contents` — ตัวชี้วัด/เนื้อหา (โครงสร้างเหมือน scd_announcements)

โครงสร้างเหมือนกับ `scd_announcements` ทุก column

#### `users` — ผู้ใช้งาน (Admin)

| Column | Type | หมายเหตุ |
|--------|------|--------|
| `id` | bigint PK | |
| `name` | string | |
| `email` | string UNIQUE | |
| `email_verified_at` | timestamp nullable | |
| `password` | string | bcrypt hashed |
| `remember_token` | string nullable | |
| `created_at`, `updated_at` | timestamps | |

### Relationships

```
ScdYear
  ├── hasOne  → ScdReport
  ├── hasMany → Banner
  ├── hasMany → Announcement
  ├── hasMany → Order (Directive)
  └── hasMany → ContentSection

Announcement / Order / ContentSection (self-referential tree)
  ├── belongsTo → parent (self)
  └── hasMany   → children (self)
```

---

## 4. การติดตั้งและรันระบบ

### Requirements

- Docker Desktop (หรือ Docker Engine + Docker Compose)
- Git

### ขั้นตอนติดตั้งครั้งแรก

```bash
# 1. Clone project
git clone <repository-url> scd-project
cd scd-project

# 2. Copy environment file
cp .env.example .env

# 3. แก้ไข .env ตามที่ต้องการ
# DB_DATABASE=scd_project
# DB_USERNAME=sail
# DB_PASSWORD=password

# 4. Install PHP dependencies
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --no-interaction --no-plugins --no-scripts

# 5. Start containers
./vendor/bin/sail up -d

# 6. Generate application key
./vendor/bin/sail artisan key:generate

# 7. Run database migrations
./vendor/bin/sail artisan migrate

# 8. Seed initial data (ถ้ามี)
./vendor/bin/sail artisan db:seed

# 9. Create storage symlink
./vendor/bin/sail artisan storage:link

# 10. Install Node dependencies และ build assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run build

# 11. สร้างบัญชี Admin แรก
./vendor/bin/sail artisan admin:create
```

### การเริ่ม/หยุดระบบ

```bash
# เริ่มระบบ
./vendor/bin/sail up -d

# หยุดระบบ
./vendor/bin/sail down

# ดู logs
./vendor/bin/sail logs

# ดู logs แบบ real-time
./vendor/bin/sail logs -f laravel.test
```

### Development Mode (Hot Reload)

```bash
# รัน Vite dev server
./vendor/bin/sail npm run dev
```

---

## 5. การจัดการบัญชี Admin

ระบบไม่มีหน้า Register — บัญชี Admin ต้องสร้างผ่าน Artisan Command เท่านั้น

### สร้างบัญชี Admin ใหม่

```bash
# Interactive (ระบบถามทีละช่อง)
./vendor/bin/sail artisan admin:create

# หรือระบุ options โดยตรง
./vendor/bin/sail artisan admin:create \
    --email=admin@aru.ac.th \
    --name="ผู้ดูแลระบบ" \
    --password=yourpassword
```

**Validation:**
- email ต้องเป็น format อีเมลที่ถูกต้อง
- password ขั้นต่ำ 4 ตัวอักษร (แนะนำ 8+ ตัว)
- ถ้า email มีอยู่แล้ว ระบบถามว่าจะ Update password หรือไม่

### Reset รหัสผ่าน Admin

```bash
# รันคำสั่ง admin:create ด้วย email เดิม แล้วเลือก "Yes" เมื่อถามว่าจะ Update
./vendor/bin/sail artisan admin:create --email=admin@aru.ac.th
```

### ดูรายการ Admin ทั้งหมด (ผ่าน phpMyAdmin)

เปิด `http://localhost:8080` แล้วดูตาราง `users`

---

## 6. Routes และ URL Structure

### Frontend Routes (Public)

| Method | URL | Controller | หมายเหตุ |
|--------|-----|-----------|--------|
| GET | `/` | `HomeController@index` | หน้าแรก (ปีล่าสุด) |
| GET | `/scd/{year}` | `HomeController@index` | หน้าแรกกรองตามปี |
| GET | `/about` | `FrontendController@about` | |
| GET | `/contact` | `FrontendController@contact` | |
| GET | `/announcements-directives/{year}` | `FrontendController@announcements` | |
| GET | `/announcements-directives/{year}/folder/{folder}` | `FrontendController@announcementFolder` | |
| GET | `/file/{source}/{id}/view/{filename}` | `FrontendController@viewFile` | แสดง PDF inline |
| GET | `/file/{source}/{id}/download` | `FrontendController@downloadFile` | ดาวน์โหลด PDF |
| GET | `/banner/{id}/view/{filename}` | `FrontendController@viewBannerPdf` | แสดง Banner PDF |
| GET | `/scd/{year}/{section}` | `FrontendController@contentSection` | ตัวชี้วัดรายหมวด |
| GET | `/scd/{year}/{sectionId}/folder/{folder}` | `FrontendController@contentSectionFolder` | |
| GET | `/scd-report/{year}/view/{filename}` | `FrontendController@viewScdReport` | แสดงรายงาน |
| GET | `/scd-report/{year}/download` | `FrontendController@downloadScdReport` | ดาวน์โหลดรายงาน |

**หมายเหตุ `source` parameter:** `announcement` | `directive` | `content`

### Admin Routes (Protected — middleware: `admin.auth`)

| URL | Livewire Component |
|-----|------------------|
| `/admin/dashboard` | `AdminDashboard` |
| `/admin/years` | `YearsIndex` |
| `/admin/reports/{year?}` | `ReportsIndex` |
| `/admin/banners/{year?}` | `BannersIndex` |
| `/admin/announcements/{year?}` | `AnnouncementsIndex` |
| `/admin/announcements/{year}/folder/{folderId}` | `AnnouncementsIndex` |
| `/admin/directives/{year?}` | `AnnouncementsIndex` |
| `/admin/directives/{year}/folder/{folderId}` | `AnnouncementsIndex` |
| `/admin/contents/{year?}` | `ContentsIndex` |
| `/admin/contents/{year}/folder/{folderId}` | `ContentsIndex` |
| `/admin/profile` | View: `admin.pages.profile` |

---

## 7. Middleware และความปลอดภัย

### AdminAuth Middleware

```php
// app/Http/Middleware/AdminAuth.php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        abort(404);  // ไม่แสดง 401/403 เพื่อปิดบังว่ามีหน้า Admin
    }
    return $next($request);
}
```

**Security design:**
- ถ้าไม่ได้ login → `abort(404)` (ไม่ใช่ redirect ไป login) เพื่อซ่อนการมีอยู่ของ Admin Panel
- `GET /login` redirect ไปหน้าแรก — Admin Login URL จริงคือ `/aru-scdur-panel` (ซ่อนจากสาธารณะ)
- Register, Forgot Password, Verify Email — ปิดไว้ทั้งหมด (commented out ใน auth.php)
- ระบบ Rate Limiting ของ Laravel ป้องกัน Brute Force (5 ครั้ง / นาที)

### AccessLog Middleware

บันทึก access log ทุก request รวมข้อมูล:
- IP address
- Method, URL, Status code
- Response time (ms) พร้อมสถานะ FAST/NORMAL/SLOW/VERY SLOW
- User Agent, Browser, Device type

Log ถูกเขียนไปที่ `storage/logs/laravel.log`

### Configure Middleware (bootstrap/app.php)

```php
// Trust Cloudflare / Reverse Proxy headers
$middleware->trustProxies(at: '*', headers: ...);

// Register alias
$middleware->alias(['admin.auth' => AdminAuth::class]);

// AccessLog ทุก web request
$middleware->web(append: [AccessLog::class]);
```

---

## 8. File Storage

### Storage Structure

```
storage/app/public/
├── reports/          # รายงาน SCD PDF
├── banners/          # รูปภาพ Banner
├── announcements/    # ไฟล์ประกาศ PDF
├── directives/       # ไฟล์คำสั่ง PDF
└── contents/         # ไฟล์ตัวชี้วัด PDF + รูปภาพ
```

ทั้งหมดเข้าถึงได้ผ่าน `public/storage/` (symlink)

### Storage Configuration (config/filesystems.php)

- Disk: `public` → `storage/app/public/`
- Visibility: `public`

### ขีดจำกัดไฟล์ (config/upload.php)

| Key | ค่า (KB) | ค่า (MB) |
|-----|---------|---------|
| `banner` | 102400 | 100 MB |
| `pdf` | 102400 | 100 MB |
| `cover`, `content_image`, etc. | 102400 | 100 MB |

### FileUploadService

`app/Services/FileUploadService.php` จัดการการอัปโหลด:

```php
$service->uploadImage($file, 'banners');   // อัปโหลดรูปภาพ
$service->uploadPdf($file, 'reports');     // อัปโหลด PDF
$service->delete($path);                   // ลบไฟล์
$service->replaceFile($newFile, $oldPath, 'dir'); // แทนที่ไฟล์
```

---

## 9. Artisan Commands

### Commands ที่สร้างขึ้นเอง

#### `admin:create` — สร้างบัญชี Admin

```bash
./vendor/bin/sail artisan admin:create [--email=] [--name=] [--password=]
```

#### `logs:clear` — ล้าง Log ไฟล์เก่า

```bash
# ลบ log ที่เก่ากว่า 7 วัน (default)
./vendor/bin/sail artisan logs:clear

# ลบ log ที่เก่ากว่า 30 วัน
./vendor/bin/sail artisan logs:clear --days=30
```

#### `content:monitor` — ตรวจสอบ Performance ตาราง Content

```bash
./vendor/bin/sail artisan content:monitor
```

แสดงข้อมูล:
- จำนวน record ใน announcements, directives, contents
- Query performance test (ms) พร้อมสถานะ
- คำแนะนำในการ optimize

### Commands Laravel มาตรฐานที่ใช้บ่อย

```bash
# Clear caches
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan route:clear

# Migrate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:rollback
./vendor/bin/sail artisan migrate:status

# Storage
./vendor/bin/sail artisan storage:link
```

---

## 10. Logging และ Monitoring

### Application Logs

ไฟล์ log อยู่ที่ `storage/logs/laravel.log`

**Log format (AccessLog middleware):**
```
--- REQUEST LOG ---
Time: 2026-03-10 10:30:00
Method: GET
Status: 200 (OK)
Duration: 45.23ms (FAST)
Path: scd/2024
IP: 192.168.1.100
User: Guest
Device: Desktop
Browser: Chrome
Session: a1b2c3d4
Memory: 12.5MB
```

### ล้าง Log ที่เก่า

```bash
# ลบ log เก่ากว่า 7 วัน
./vendor/bin/sail artisan logs:clear

# ลบ log เก่ากว่า N วัน
./vendor/bin/sail artisan logs:clear --days=N
```

### ดู Docker Logs

```bash
# ดู logs ทั้งหมด
./vendor/bin/sail logs

# ดู logs แบบ real-time
./vendor/bin/sail logs -f laravel.test

# ดู logs เฉพาะ MySQL
./vendor/bin/sail logs mysql
```

### Monitor Performance ฐานข้อมูล

```bash
./vendor/bin/sail artisan content:monitor
```

---

## 11. Configuration

### Environment Variables สำคัญ (.env)

```dotenv
APP_NAME="SCD Project"
APP_ENV=production          # production | local
APP_DEBUG=false             # false ใน production เสมอ
APP_URL=https://www.tamamimi-mimimi.xyz

APP_LOCALE=th
APP_FALLBACK_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=mysql               # Docker service name
DB_PORT=3306
DB_DATABASE=scd_project
DB_USERNAME=sail
DB_PASSWORD=yourpassword

SESSION_DRIVER=cookie       # เก็บ session ใน browser cookie
SESSION_LIFETIME=120

CACHE_STORE=redis           # ใช้ Redis สำหรับ cache
QUEUE_CONNECTION=sync       # รัน queue ทันที (ไม่มีคิว)

# Redis Configuration
REDIS_CLIENT=predis
REDIS_HOST=redis            # Docker service name
REDIS_PASSWORD=null
REDIS_PORT=6379

# Docker ports
APP_PORT=80
VITE_PORT=5173
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
FORWARD_PHPMYADMIN_PORT=8080
```

### config/upload.php — ขีดจำกัดไฟล์อัปโหลด

```php
'max_file_sizes' => [
    'banner'         => 102400,  // 100 MB (KB)
    'pdf'            => 102400,  // 100 MB (KB)
    'content_image'  => 102400,  // 100 MB (KB)
    // ...
],
```

แก้ไขค่าได้โดยตรงที่ไฟล์นี้ แล้ว `cache:clear` เพื่อให้มีผล

---

## 12. การ Backup และ Maintenance

### Backup ฐานข้อมูล

```bash
# Backup ผ่าน Docker exec
docker exec scd-project-mysql-1 mysqldump \
    -u sail -pyourpassword scd_project > backup_$(date +%Y%m%d).sql

# หรือใช้ phpMyAdmin: http://localhost:8080 → Export
```

### Backup ไฟล์ที่อัปโหลด

```bash
# Copy storage folder ออกมา
cp -r storage/app/public/ /backup/storage_$(date +%Y%m%d)/
```

### Restore ฐานข้อมูล

```bash
docker exec -i scd-project-mysql-1 mysql \
    -u sail -pyourpassword scd_project < backup_YYYYMMDD.sql
```

### การ Update ระบบ

```bash
# 1. Pull code ใหม่
git pull origin main

# 2. Install/update dependencies
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# 3. Build assets
./vendor/bin/sail npm run build

# 4. Run migrations
./vendor/bin/sail artisan migrate

# 5. Clear caches
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear

# 6. Restart containers (ถ้าจำเป็น)
./vendor/bin/sail down && ./vendor/bin/sail up -d
```

---

## 13. การแก้ไขปัญหาทางเทคนิค

### ปัญหา: รูปภาพ/ไฟล์ไม่แสดงผล (404)

```bash
# สร้าง symlink ใหม่
./vendor/bin/sail artisan storage:link

# ตรวจสอบ permissions
ls -la public/storage
```

### ปัญหา: เว็บขึ้น Error 500

```bash
# ตรวจสอบ logs
tail -f storage/logs/laravel.log

# หรือผ่าน Docker
./vendor/bin/sail logs -f laravel.test

# เปิด Debug Mode ชั่วคราว (สำหรับ dev เท่านั้น)
# แก้ .env: APP_DEBUG=true แล้ว cache:clear
./vendor/bin/sail artisan cache:clear
```

### ปัญหา: อัปโหลดไฟล์ขนาดใหญ่ไม่ได้ (413 Payload Too Large)

ตรวจสอบ PHP config ใน docker/8.4/:

```ini
; php.ini หรือ docker environment
upload_max_filesize = 100M
post_max_size = 110M
memory_limit = 256M
max_execution_time = 120
```

phpMyAdmin มีการตั้ง `UPLOAD_LIMIT: 100M` ไว้ใน `compose.yaml` แล้ว

### ปัญหา: Session หมดเร็ว / Login หลุดบ่อย

```bash
# ตรวจ .env
# SESSION_DRIVER=cookie
# SESSION_LIFETIME=120  (นาที)
```

ระบบใช้ cookie-based session — session จะหมดอายุตามค่า `SESSION_LIFETIME` หรือเมื่อ browser ปิด

### ปัญหา: Cache ไม่อัปเดต

```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan route:clear
```

### ปัญหา: Database Migration Error

```bash
# ดูสถานะ migrations
./vendor/bin/sail artisan migrate:status

# Rollback และ migrate ใหม่ (ระวัง: อาจสูญเสียข้อมูล)
./vendor/bin/sail artisan migrate:fresh --seed

# หรือ rollback แค่ครั้งล่าสุด
./vendor/bin/sail artisan migrate:rollback
```

### ปัญหา: Redis ต่อไม่ได้

```bash
# Restart Redis
docker restart scd-project-redis-1

# ตรวจสอบ .env
# REDIS_HOST=redis
# REDIS_PORT=6379
```

### ปัญหา: Livewire ไม่ทำงาน / หน้าขาว

```bash
# Clear view cache
./vendor/bin/sail artisan view:clear

# ตรวจ browser console errors (F12)
# ตรวจสอบว่า APP_URL ถูกต้องใน .env
```

---

## ติดต่อขอความช่วยเหลือ

**Developer / IT Contact:**  
ติดต่อ: Airroy

**ฝ่ายเทคโนโลยีสารสนเทศ (IT)**  
มหาวิทยาลัยราชภัฏพระนครศรีอยุธยา

---

*คู่มือระบบ SCD เวอร์ชัน 1.2.0 | Laravel 12.46.0 + PHP 8.4.17 + MySQL 8.4 | อัปเดตล่าสุด: 12 มีนาคม 2026*
