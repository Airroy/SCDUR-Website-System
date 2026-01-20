# คู่มือติดตั้งและใช้งาน SCD Project

> **คู่มือฉบับสมบูรณ์** สำหรับผู้ติดตั้งใหม่และผู้ดูแลระบบ

---

## 📋 สารบัญ

1. [ข้อกำหนดระบบ](#ข้อกำหนดระบบ)
2. [ขั้นตอนการติดตั้ง](#ขั้นตอนการติดตั้ง)
3. [การใช้งาน Laravel Sail](#การใช้งาน-laravel-sail)
4. [การจัดการฐานข้อมูล](#การจัดการฐานข้อมูล)
5. [การสร้าง Admin User](#การสร้าง-admin-user)
6. [คำสั่งที่ใช้บ่อย](#คำสั่งที่ใช้บ่อย)
7. [การแก้ไขปัญหา](#การแก้ไขปัญหา)
8. [Tips & Best Practices](#tips--best-practices)

---

## ข้อกำหนดระบบ

### 💻 Software ที่ต้องมี
- **Docker Desktop** (เวอร์ชันล่าสุด)
  - Windows: [ดาวน์โหลด Docker Desktop for Windows](https://www.docker.com/products/docker-desktop)
  - macOS: [ดาวน์โหลด Docker Desktop for Mac](https://www.docker.com/products/docker-desktop)
  - Linux: ติดตั้งผ่าน package manager
- **Git** (สำหรับ clone โปรเจกต์)
  - [ดาวน์โหลด Git](https://git-scm.com/downloads)

### ❌ ไม่ต้องติดตั้งบนเครื่อง
- PHP
- Composer
- Node.js / NPM
- MySQL
- Redis

> ✅ **ทุกอย่างรันผ่าน Docker containers แล้ว!**

### 🐳 Docker Services ที่จะถูกสร้าง
เมื่อรัน `sail up` จะมี containers ดังนี้:
- **laravel.test** - Laravel Application (PHP 8.3)
  - Port 80 → http://localhost
  - Port 5173 → Vite dev server
- **mysql** - MySQL 8.4 Database
  - Port 3306 (internal)
- **redis** - Redis Cache
  - Port 6379 (internal)
- **phpmyadmin** - Database Management UI
  - Port 8080 → http://localhost:8080

---

## ขั้นตอนการติดตั้ง

### 1️⃣ Clone โปรเจกต์
```bash
# Clone repository
git clone <your-repository-url>
cd scd-project
```

---

### 2️⃣ Setup Environment File
```bash
# Copy .env.example เป็น .env
cp .env.example .env
```

**ตรวจสอบค่าใน `.env`:**
```env
APP_NAME="SCD Project"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql                 # ชื่อ Docker service
DB_PORT=3306
DB_DATABASE=scd_project
DB_USERNAME=sail
DB_PASSWORD=password

CACHE_STORE=redis
REDIS_HOST=redis
```

> ⚠️ **สำคัญ:** `DB_HOST=mysql` และ `REDIS_HOST=redis` ต้องเป็นชื่อ service ใน Docker Compose ไม่ใช่ `localhost`

---

### 3️⃣ ติดตั้ง PHP Dependencies
เนื่องจากยังไม่มี PHP บนเครื่อง ต้องใช้ Docker รันคำสั่ง Composer:

**Linux / macOS:**
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**Windows (PowerShell):**
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php83-composer:latest `
    composer install --ignore-platform-reqs
```

> คำสั่งนี้จะดาวน์โหลด dependencies ทั้งหมดลง `vendor/` folder

---

### 4️⃣ เริ่ม Docker Containers
```bash
# เริ่มทุก services แบบ background
./vendor/bin/sail up -d
```

**คำสั่งนี้จะ:**
- สร้าง Docker containers ทั้งหมด
- รัน Laravel, MySQL, Redis
- Expose ports: 80 (web), 3306 (mysql), 5173 (vite)

**ตรวจสอบสถานะ:**
```bash
./vendor/bin/sail ps
```

คุณควรเห็น containers running:
```
NAME                IMAGE               STATUS
scd-project-laravel.test-1   sail-8.3/app    Up
scd-project-mysql-1          mysql:8.4       Up
```

---

### 5️⃣ Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

> สร้าง encryption key ใน `.env` (APP_KEY)

---

### 6️⃣ Run Database Migrations
```bash
# สร้างตารางฐานข้อมูลทั้งหมด
./vendor/bin/sail artisan migrate
```

**ตารางที่จะถูกสร้าง:**
- `users` - ผู้ใช้งาน (Admin)
- `scd_years` - ข้อมูลปี SCD
- `banners` - Banner Slider
- `content_nodes` - Content Sections
- `scd_reports` - ไฟล์รายงาน
- `announcements` - ประกาศ/คำสั่ง
- ตาราง system (cache, jobs, sessions)

---

### 7️⃣ สร้าง Storage Symlink
```bash
./vendor/bin/sail artisan storage:link
```

> สร้าง symbolic link จาก `public/storage` → `storage/app/public`  
> เพื่อให้เข้าถึงไฟล์ที่อัปโหลดผ่าน URL ได้

---

### 8️⃣ ติดตั้ง Frontend Dependencies
```bash
./vendor/bin/sail npm install
```

> ติดตั้ง Node.js packages (Tailwind CSS, Alpine.js, Vite)

---

### 9️⃣ Build Frontend Assets

**Development Mode (แนะนำขณะพัฒนา):**
```bash
./vendor/bin/sail npm run dev
```
- รัน Vite dev server
- Hot reload เมื่อแก้ไขไฟล์
- ใช้ port 5173

**Production Build:**
```bash
./vendor/bin/sail npm run build
```
- สร้างไฟล์ CSS/JS แบบ optimized
- ใช้สำหรับ production

---

### 🔟 สร้าง Admin User
```bash
./vendor/bin/sail artisan admin:create
```

**ระบุข้อมูล:**
```
 Name: Admin
 Email: admin@aru.ac.th
 Password: ********
```

> ✅ **เสร็จสิ้น!** ตอนนี้เข้าใช้งานได้แล้ว

---

## การใช้งาน Laravel Sail

### Sail Alias (แนะนำ)
แทนที่จะพิมพ์ `./vendor/bin/sail` ทุกครั้ง สร้าง alias:

**Linux / macOS:**
```bash
# เพิ่มใน ~/.bashrc หรือ ~/.zshrc
echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

**Windows (PowerShell):**
```powershell
# เพิ่มใน $PROFILE
function sail { ./vendor/bin/sail @args }
```

**หลังจากนี้ใช้:**
```bash
sail up -d           # แทน ./vendor/bin/sail up -d
sail artisan migrate # แทน ./vendor/bin/sail artisan migrate
```

---

### คำสั่ง Sail พื้นฐาน

#### เริ่ม/หยุดระบบ
```bash
sail up                # เริ่มและแสดง logs
sail up -d             # เริ่มแบบ background
sail down              # หยุดทุก containers
sail restart           # รีสตาร์ท
sail stop              # หยุดชั่วคราว (ไม่ลบ containers)
sail start             # เริ่มต่อหลัง stop
```

#### ดูสถานะและ Logs
```bash
sail ps                # ดูสถานะ containers
sail logs              # ดู logs ทั้งหมด
sail logs -f           # Follow logs แบบ real-time
sail logs laravel.test # ดู logs container เดียว
```

#### เข้า Shell
```bash
sail shell             # เข้า bash ใน Laravel container
sail root-shell        # เข้าแบบ root user
sail mysql             # เข้า MySQL shell
sail redis             # เข้า Redis CLI
```

#### Run Commands
```bash
sail artisan <command>    # รัน artisan commands
sail composer <command>   # รัน composer
sail npm <command>        # รัน npm
sail php <script>         # รัน PHP script
sail test                 # รัน PHPUnit tests
```

---

## การจัดการฐานข้อมูล

### Migrations
```bash
# รัน migrations ทั้งหมด
sail artisan migrate

# Rollback migration ล่าสุด
sail artisan migrate:rollback

# Rollback migrations ทั้งหมด
sail artisan migrate:reset

# Drop ตารางทั้งหมดและ migrate ใหม่
sail artisan migrate:fresh

# Migrate พร้อม seed data
sail artisan migrate:fresh --seed
```

### Seeders
```bash
# รัน database seeders
sail artisan db:seed

# รัน seeder เฉพาะ
sail artisan db:seed --class=UserSeeder
```

### เข้า MySQL Shell
```bash
sail mysql

# จากนั้นพิมพ์ SQL commands:
USE scd_project;
SHOW TABLES;
SELECT * FROM users;
```

### Backup Database
```bash
# Backup ฐานข้อมูลทั้งหมด
sail exec mysql mysqldump -u sail -ppassword scd_project > backup.sql

# Restore database
sail exec -T mysql mysql -u sail -ppassword scd_project < backup.sql
```

---

## การสร้าง Admin User

### วิธีที่ 1: ใช้ Artisan Command (แนะนำ)
```bash
sail artisan admin:create

# หรือระบุข้อมูลเลยในคำสั่ง
sail artisan admin:create \
    --name="Admin Name" \
    --email="admin@example.com" \
    --password="securepassword"
```

### วิธีที่ 2: ใช้ Tinker
```bash
sail artisan tinker
```

จากนั้นพิมพ์:
```php
use App\Models\User;

User::create([
    'name' => 'Admin',
    'email' => 'admin@aru.ac.th',
    'password' => bcrypt('your-secure-password'),
    'email_verified_at' => now(),
]);
```

> 📧 **Email Verification:** User ต้องยืนยันอีเมลก่อนเข้าใช้งาน หรือตั้ง `email_verified_at` เป็น `now()`

---

## คำสั่งที่ใช้บ่อย

### Laravel Artisan
```bash
# Cache Management
sail artisan cache:clear           # Clear application cache
sail artisan config:clear          # Clear config cache
sail artisan route:clear           # Clear route cache
sail artisan view:clear            # Clear compiled views
sail artisan optimize:clear        # Clear all caches

# Optimization
sail artisan optimize              # Optimize application
sail artisan config:cache          # Cache config
sail artisan route:cache           # Cache routes
sail artisan view:cache            # Cache views

# Storage
sail artisan storage:link          # Create storage symlink

# Queue & Jobs
sail artisan queue:work            # Process queue jobs
sail artisan queue:restart         # Restart queue workers
sail artisan queue:failed          # List failed jobs
```

### Composer
```bash
sail composer install              # Install dependencies
sail composer update               # Update dependencies
sail composer require <package>    # Add new package
sail composer dump-autoload        # Regenerate autoload files
```

### NPM
```bash
sail npm install                   # Install packages
sail npm run dev                   # Development build (watch)
sail npm run build                 # Production build
sail npm update                    # Update packages
```

### Testing
```bash
sail test                          # Run all tests
sail test --filter=ProfileTest     # Run specific test
sail test --parallel               # Parallel testing
```

---

## การแก้ไขปัญหา

### ❌ Port ชนกัน (Port already in use)

**ปัญหา:** Port 80, 3306 หรือ 5173 ถูกใช้งานแล้ว

**วิธีแก้:** แก้ไขใน `.env`
```env
APP_PORT=8000              # เปลี่ยนจาก 80
FORWARD_DB_PORT=3307       # เปลี่ยนจาก 3306
VITE_PORT=5174             # เปลี่ยนจาก 5173
```

จากนั้น:
```bash
sail down
sail up -d
```

เข้าใช้งานที่: http://localhost:8000

---

### ❌ Permission Denied (storage, cache)

**ปัญหา:** ไม่สามารถเขียนไฟล์ใน `storage/` หรือ `bootstrap/cache/`

**วิธีแก้:**
```bash
# ให้สิทธิ์เขียนได้
sudo chmod -R 775 storage bootstrap/cache

# เปลี่ยน owner (Linux/macOS)
sudo chown -R $USER:www-data storage bootstrap/cache
```

---

### ❌ CSS/JS ไม่อัปเดต

**ปัญหา:** แก้ไข CSS/JS แล้วไม่เปลี่ยน

**วิธีแก้:**
```bash
# หยุด npm run dev (Ctrl+C)
sail npm run build           # Build ใหม่
sail artisan view:clear      # Clear view cache
sail artisan optimize:clear  # Clear caches

# หรือรัน dev mode ใหม่
sail npm run dev
```

**ตรวจสอบ browser cache:**
- กด `Ctrl+Shift+R` (Hard refresh)
- เปิด DevTools → Disable cache

---

### ❌ รูปภาพไม่แสดง (404 Not Found)

**ปัญหา:** อัปโหลดรูปแล้วแต่ไม่แสดง

**วิธีแก้:**
```bash
# สร้าง storage symlink ใหม่
sail artisan storage:link

# ตรวจสอบว่ามี symlink
ls -la public/storage  # ควรเป็น symlink → ../storage/app/public
```

**ตรวจสอบ permissions:**
```bash
sail shell
ls -la storage/app/public/  # ต้องมีสิทธิ์เขียนได้
```

---

### ❌ Database Connection Failed

**ปัญหา:** ไม่สามารถเชื่อมต่อฐานข้อมูล

**วิธีแก้:**

1. **ตรวจสอบ MySQL container รันอยู่หรือไม่:**
```bash
sail ps
# ต้องเห็น mysql container status: Up
```

2. **ตรวจสอบ `.env` ว่าถูกต้อง:**
```env
DB_CONNECTION=mysql
DB_HOST=mysql           # ต้องเป็น 'mysql' ไม่ใช่ 'localhost'
DB_PORT=3306
DB_DATABASE=scd_project
DB_USERNAME=sail
DB_PASSWORD=password
```

3. **Clear config cache:**
```bash
sail artisan config:clear
sail artisan migrate
```

4. **ถ้ายังไม่ได้ ลอง restart:**
```bash
sail down
sail up -d
```

---

### ❌ Composer Install ช้ามาก

**วิธีแก้:**
```bash
# ใช้ --no-scripts เพื่อข้าม post-install scripts
sail composer install --no-scripts

# หรือใช้ --no-dev สำหรับ production
sail composer install --no-dev --optimize-autoloader
```

---

### ❌ Migration Error: Table already exists

**ปัญหา:** รัน migrate แล้วขึ้น error ว่าตารางมีอยู่แล้ว

**วิธีแก้:**
```bash
# ลบตารางทั้งหมดและสร้างใหม่
sail artisan migrate:fresh

# ถ้าต้องการ seed data ด้วย
sail artisan migrate:fresh --seed
```

> ⚠️ **คำเตือน:** `migrate:fresh` จะลบข้อมูลทั้งหมด!

---

### ❌ Livewire Component ไม่ทำงาน

**ปัญหา:** Livewire component ไม่ update หรือไม่ทำงาน

**วิธีแก้:**
```bash
# Clear Livewire cache
sail artisan livewire:discover
sail artisan view:clear
sail artisan optimize:clear

# ตรวจสอบว่า @livewireScripts อยู่ใน layout
```

---

## Tips & Best Practices

### 1. ใช้ Sail Alias
สร้าง alias เพื่อพิมพ์สั้นลง:
```bash
echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

### 2. Run npm dev ใน Background
```bash
# รัน npm dev แบบ detached
sail npm run dev > /dev/null 2>&1 &

# ดู process ที่รันอยู่
ps aux | grep vite
```

### 3. ใช้ Git Hooks
เพิ่ม pre-commit hook เพื่อ format code:
```bash
#!/bin/sh
./vendor/bin/sail composer format
./vendor/bin/sail npm run lint
```

### 4. Backup ก่อน Migrate Fresh
```bash
# Backup ก่อนลบข้อมูล
sail exec mysql mysqldump -u sail -ppassword scd_project > backup_$(date +%Y%m%d).sql

# จากนั้นค่อย migrate
sail artisan migrate:fresh
```

### 5. Monitor Logs
```bash
# ดู logs แบบ real-time ใน terminal แยก
sail logs -f

# หรือใช้ Laravel Telescope (ถ้าติดตั้ง)
sail artisan telescope:install
```

### 6. ใช้ Queue สำหรับงานหนัก
```bash
# เริ่ม queue worker
sail artisan queue:work

# หรือใช้ Supervisor (production)
sail artisan queue:restart
```

---

## 🎯 Checklist หลังติดตั้งเสร็จ

- [ ] `sail up -d` รันสำเร็จ
- [ ] เข้า http://localhost เห็นหน้าเว็บ
- [ ] `sail artisan migrate` สำเร็จ
- [ ] `sail artisan storage:link` สำเร็จ
- [ ] สร้าง admin user ได้แล้ว
- [ ] Login เข้า admin ได้
- [ ] `sail npm run dev` รันได้
- [ ] อัปโหลดรูปภาพแสดงผลได้

---

## 📚 เอกสารอ้างอิง

- **Laravel Documentation:** https://laravel.com/docs/11.x
- **Laravel Sail:** https://laravel.com/docs/11.x/sail
- **Livewire Documentation:** https://livewire.laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Docker Documentation:** https://docs.docker.com

---

## 🆘 ต้องการความช่วยเหลือ?

1. ตรวจสอบ Laravel Logs: `storage/logs/laravel.log`
2. ดู Docker Logs: `sail logs -f`
3. ตรวจสอบ [การแก้ไขปัญหา](#การแก้ไขปัญหา) ด้านบน
4. ติดต่อทีมพัฒนา

---

**อัปเดตล่าสุด:** 19 มกราคม 2026
