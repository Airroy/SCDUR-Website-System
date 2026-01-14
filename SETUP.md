# SCD Project - คู่มือติดตั้งและใช้งาน

> สำหรับผู้พัฒนาและผู้ดูแลระบบ

---

## 📋 สารบัญ

1. [ข้อกำหนดระบบ](#ข้อกำหนดระบบ)
2. [ขั้นตอนการติดตั้ง](#ขั้นตอนการติดตั้ง)
3. [คำสั่งที่ใช้บ่อย](#คำสั่งที่ใช้บ่อย)
4. [การสร้าง Admin User](#การสร้าง-admin-user)
5. [การแก้ไขปัญหา](#การแก้ไขปัญหา)

---

## ข้อกำหนดระบบ

### Software ที่ต้องมี
- **Docker Desktop** (รุ่นล่าสุด)
- **Git** (สำหรับ clone project)

### ไม่ต้องติดตั้ง
- ❌ PHP
- ❌ Composer
- ❌ Node.js / NPM
- ❌ MySQL

> ทุกอย่างรันผ่าน Docker แล้ว!

### Docker Services
- **laravel.test** - Laravel Application (Port 80, 5173)
- **mysql** - MySQL 8.4 Database (Port 3306)

---

## ขั้นตอนการติดตั้ง

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

### 8. สร้าง Admin User
```bash
./vendor/bin/sail artisan admin:create --email=admin@aru.ac.th --name=Admin --password=1234
```
ทำตาม prompts เพื่อสร้าง admin user สำหรับเข้าระบบ

### 9. เปิดเว็บไซต์
เปิดเบราว์เซอร์ที่:
- **หน้าหลัก:** http://localhost
- **Admin Login:** http://localhost/login

---

## คำสั่งที่ใช้บ่อย

### 🐳 Docker Management
```bash
./vendor/bin/sail up        # เริ่มและดู logs
./vendor/bin/sail up -d     # เริ่มแบบ background
./vendor/bin/sail down      # หยุด containers
./vendor/bin/sail restart   # รีสตาร์ท
./vendor/bin/sail ps        # ดูสถานะ containers
```

### 💾 Database
```bash
./vendor/bin/sail artisan migrate              # Run migrations
./vendor/bin/sail artisan migrate:fresh        # Drop และ migrate ใหม่
./vendor/bin/sail artisan migrate:fresh --seed # พร้อม seeding
./vendor/bin/sail artisan db:seed              # Run seeders
./vendor/bin/sail mysql                        # เข้า MySQL shell
```

### ⚡ Livewire & Development
```bash
./vendor/bin/sail artisan livewire:make ComponentName    # สร้าง component
./vendor/bin/sail npm run dev                            # Watch mode
./vendor/bin/sail npm run build                          # Build production
./vendor/bin/sail artisan optimize                       # Optimize app
./vendor/bin/sail artisan cache:clear                    # Clear cache
```

### 📝 Logs & Debugging
```bash
./vendor/bin/sail logs         # ดู logs
./vendor/bin/sail logs -f      # Follow logs แบบ real-time
```

---

## การสร้าง Admin User

### วิธีที่ 1: ใช้ Command (แนะนำ)
```bash
./vendor/bin/sail artisan make:admin
```

### วิธีที่ 2: ใช้ Tinker
```bash
./vendor/bin/sail artisan tinker

# จากนั้นพิมพ์
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
```

> **หมายเหตุ:** Register ปิดการใช้งาน สร้าง admin ผ่าน command เท่านั้น

---

## การแก้ไขปัญหา

### ❌ Port ชนกัน
แก้ไขใน `.env`:
```env
APP_PORT=8000              # เปลี่ยนจาก 80
FORWARD_DB_PORT=3307       # เปลี่ยนจาก 3306
VITE_PORT=5174             # เปลี่ยนจาก 5173
```
จากนั้น: `./vendor/bin/sail restart`

### ❌ Permission Denied
```bash
sudo chmod -R 777 storage bootstrap/cache
```

### ❌ Composer/NPM ช้า
```bash
./vendor/bin/sail composer install --no-scripts
./vendor/bin/sail npm ci
```

### ❌ Migration Error
```bash
./vendor/bin/sail artisan migrate:fresh  # ลบและสร้างใหม่
```

### ❌ รูปภาพไม่แสดง
```bash
./vendor/bin/sail artisan storage:link  # สร้าง symlink ใหม่
```

### ❌ CSS/JS ไม่อัปเดต
```bash
./vendor/bin/sail npm run build  # Build ใหม่
# หรือ
Ctrl+C  # หยุด npm run dev
./vendor/bin/sail npm run dev  # รันใหม่
```

---

## 🎯 Tips & Best Practices

### Alias สำหรับ Sail
เพิ่มใน `~/.bashrc` หรือ `~/.zshrc`:
```bash
alias sail='./vendor/bin/sail'
```
จากนั้นใช้: `sail up -d` แทน `./vendor/bin/sail up -d`

### ทดสอบระบบ
```bash
sail artisan test              # Run tests
sail artisan test --parallel   # Run tests แบบ parallel
```

### Backup Database
```bash
sail mysql -e "mysqldump -u root -ppassword laravel > backup.sql"
```

### Restore Database
```bash
sail mysql laravel < backup.sql
```

---

## 📚 เอกสารเพิ่มเติม

- **[README.md](README.md)** - ภาพรวมโปรเจกต์
- **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** - โครงสร้างและฟังก์ชันแต่ละไฟล์
- **Laravel Documentation:** https://laravel.com/docs/12.x
- **Livewire Documentation:** https://livewire.laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com/docs
