# SCD Project - ระบบจัดอันดับมหาวิทยาลัยด้านการพัฒนาชุมชนอย่างยั่งยืน

> **Sustainable Community Development University Ranking System**  
> ระบบจัดการและแสดงผลข้อมูล ARU-SCD สำหรับมหาวิทยาลัยราชภัฏพระนครศรีอยุธยา

---

## 📋 เกี่ยวกับโปรเจกต์

ระบบเว็บไซต์สำหรับจัดการและเผยแพร่ข้อมูล SCD (Sustainable Community Development) ของมหาวิทยาลัย ประกอบด้วย 2 ส่วนหลัก:

### 🌐 หน้าบ้าน (Frontend)
- **หน้าแรก** - Banner Slider พร้อม Content Sections แบ่งเป็นหมวดหมู่
- **SCD Ranking** - แสดงข้อมูลจัดอันดับแยกตามปี
- **ดาวน์โหลดรายงาน** - ไฟล์รายงานผล SCD (PDF)
- **ประกาศ/คำสั่ง** - แยกตามหมวดหมู่และปี พร้อมไฟล์แนบดาวน์โหลด
- **เกี่ยวกับหน่วยงาน** - ข้อมูลองค์กร
- **ติดต่อเรา** - ที่อยู่ เบอร์โทร แผนที่

### 🔧 หลังบ้าน (Admin Panel)
- **Dashboard** - สรุปภาพรวมข้อมูลทั้งหมด
- **จัดการปี SCD** - เพิ่ม/แก้ไข/ลบปี, เปิด-ปิดการเผยแพร่
- **จัดการ Banner** - อัปโหลดรูป Slider หน้าแรก, จัดลำดับ
- **จัดการ Content** - แก้ไข Content Sections หน้าแรก, อัปโหลดรูปภาพ
- **จัดการรายงาน** - อัปโหลดไฟล์รายงาน PDF
- **จัดการประกาศ** - เพิ่ม/แก้ไข/ลบประกาศและคำสั่ง พร้อมไฟล์แนบ
- **จัดการโปรไฟล์** - แก้ไขข้อมูลส่วนตัว เปลี่ยนรหัสผ่าน

---

## 🛠️ เทคโนโลยีที่ใช้

### Backend
- **Framework:** Laravel 11.x
- **Authentication:** Laravel Breeze (Livewire)
- **Real-time UI:** Livewire 3.x
- **Database:** MySQL 8.4

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Alpine.js
- **Module Bundler:** Vite
- **Icons:** SVG Icons

### Development
- **Environment:** Docker (Laravel Sail)
- **Package Manager:** Composer, NPM
- **Testing:** PHPUnit

---

## 🚀 Quick Start

### ✅ ข้อกำหนดเบื้องต้น
- **Docker Desktop** (เวอร์ชันล่าสุด)
- **Git** (สำหรับ clone โปรเจกต์)

> ⚠️ **ไม่ต้องติดตั้ง** PHP, Composer, Node.js, MySQL บนเครื่อง - ทุกอย่างรันผ่าน Docker

---

### 📥 ขั้นตอนการติดตั้ง

#### 1. Clone โปรเจกต์
```bash
git clone <your-repository-url>
cd scd-project
```

#### 2. ติดตั้ง PHP Dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

#### 3. Setup Environment
```bash
cp .env.example .env
```

#### 4. เริ่ม Docker Containers
```bash
./vendor/bin/sail up -d
```

#### 5. Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

#### 6. Run Database Migrations
```bash
./vendor/bin/sail artisan migrate
```

#### 7. Create Storage Link
```bash
./vendor/bin/sail artisan storage:link
```

#### 8. ติดตั้ง Frontend Dependencies
```bash
./vendor/bin/sail npm install
```

#### 9. Build Assets (เลือกอย่างใดอย่างหนึ่ง)
```bash
# Development mode (watch mode)
./vendor/bin/sail npm run dev

# Production build
./vendor/bin/sail npm run build
```

#### 10. สร้าง Admin User
```bash
./vendor/bin/sail artisan admin:create
```
ระบุข้อมูล: ชื่อ, อีเมล, รหัสผ่าน ตาม prompt

---

### 🌍 เข้าใช้งานระบบ

- **หน้าเว็บหลัก:** http://localhost
- **หน้า Admin Login:** http://localhost/login
- **หน้า Admin Dashboard:** http://localhost/admin/dashboard (ต้อง login ก่อน)

---

## 📖 คำสั่งที่ใช้บ่อย

### Docker Management
```bash
./vendor/bin/sail up -d          # เริ่มระบบแบบ background
./vendor/bin/sail down           # หยุดระบบ
./vendor/bin/sail restart        # รีสตาร์ทระบบ
./vendor/bin/sail ps             # ดูสถานะ containers
./vendor/bin/sail logs           # ดู logs
```

### Database
```bash
./vendor/bin/sail artisan migrate              # Run migrations
./vendor/bin/sail artisan migrate:fresh        # Drop tables และ migrate ใหม่
./vendor/bin/sail artisan migrate:fresh --seed # Migrate + Seed data
./vendor/bin/sail artisan db:seed              # Seed data เฉพาะ
./vendor/bin/sail mysql                        # เข้า MySQL shell
```

### Cache & Optimization
```bash
./vendor/bin/sail artisan cache:clear          # Clear cache
./vendor/bin/sail artisan config:clear         # Clear config cache
./vendor/bin/sail artisan route:clear          # Clear route cache
./vendor/bin/sail artisan view:clear           # Clear view cache
./vendor/bin/sail artisan optimize             # Optimize application
```

### Artisan Commands
```bash
./vendor/bin/sail artisan admin:create         # สร้าง admin user ใหม่
./vendor/bin/sail artisan storage:link         # สร้าง storage symlink
./vendor/bin/sail artisan list                 # ดูคำสั่งทั้งหมด
```

---

## 📚 เอกสารเพิ่มเติม

- **[SETUP.md](SETUP.md)** - คู่มือติดตั้งและการแก้ปัญหาโดยละเอียด
- **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** - โครงสร้างโปรเจกต์และคำอธิบายไฟล์ทั้งหมด

---

## 🔐 ความปลอดภัย

### การเข้าถึง Admin
- 🔒 **ไม่มีลิงก์ใน Frontend** - Admin login ต้องรู้ URL (`/login`)
- 🚫 **ปิด Registration** - สร้าง admin ผ่าน command line เท่านั้น
- ✅ **Protected Routes** - ใช้ `auth` middleware ป้องกัน
- 📧 **Email Verification** - ต้องยืนยันอีเมลก่อนใช้งาน

### File Upload Security
- ✅ จำกัดประเภทไฟล์: **PDF, JPG, PNG** เท่านั้น
- ✅ ตรวจสอบขนาดไฟล์สูงสุด
- ✅ เก็บไฟล์ใน `storage/app/public/` (ไม่ใช่ `public/` โดยตรง)
- ✅ สร้าง symlink ด้วย `storage:link` เท่านั้น

---

## 🐛 การแก้ไขปัญหา

### Port ชนกัน
แก้ไขใน `.env`:
```env
APP_PORT=8000
FORWARD_DB_PORT=3307
VITE_PORT=5174
```

### Permission ไม่ถูกต้อง
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Assets ไม่อัปเดต
```bash
./vendor/bin/sail npm run build
./vendor/bin/sail artisan optimize:clear
```

### รูปภาพไม่แสดง
```bash
./vendor/bin/sail artisan storage:link
```

ดูรายละเอียดเพิ่มเติมใน **[SETUP.md](SETUP.md)**

---

## 🏗️ โครงสร้างหลัก

```
scd-project/
├── app/
│   ├── Http/Controllers/        # Controllers (Frontend, Backend)
│   ├── Livewire/               # Livewire Components
│   │   ├── Backend/            # Admin components
│   │   ├── Frontend/           # Public components
│   │   └── Profile/            # Profile management
│   ├── Models/                 # Eloquent Models
│   └── Services/               # Business Logic Services
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── css/                    # Styles (Tailwind)
│   ├── js/                     # JavaScript
│   └── views/
│       ├── layouts/            # Layout templates
│       ├── frontend/           # Frontend views
│       ├── admin/              # Admin views
│       ├── livewire/           # Livewire blade views
│       └── components/         # Reusable components
├── routes/
│   ├── web.php                 # Web routes
│   └── auth.php                # Auth routes
└── public/
    └── storage/                # Symlink to storage/app/public
```

---

## 👥 ทีมพัฒนา

**มหาวิทยาลัยราชภัฏพระนครศรีอยุธยา**  
สำนักงานพัฒนาชุมชนอย่างยั่งยืน

---

## 📄 License

This project uses the **Laravel Framework**, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
