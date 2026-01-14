# SCD Project - ระบบจัดอันดับมหาวิทยาลัยด้านการพัฒนาชุมชนอย่างยั่งยืน

> **Sustainable Community Development University Ranking System**  
> ระบบจัดการและแสดงผลข้อมูล ARU-SCD สำหรับมหาวิทยาลัยราชภัฏพระนครศรีอยุธยา

---

## 📋 เกี่ยวกับโปรเจกต์

ระบบเว็บไซต์สำหรับจัดการและเผยแพร่ข้อมูล SCD (Sustainable Community Development) ประกอบด้วย:

### หน้าบ้าน (Frontend)
- หน้าแรกพร้อม Banner Slider และ Content Sections
- แสดงข้อมูล SCD Ranking แยกตามปี
- ดาวน์โหลดรายงานผล SCD
- ประกาศ/คำสั่งแยกตามหมวดหมู่
- หน้าเกี่ยวกับหน่วยงาน และติดต่อเรา

### หลังบ้าน (Admin)
- Dashboard สรุปข้อมูล
- จัดการปี SCD (เพิ่ม/แก้ไข/ลบ)
- จัดการ Banner Slider
- จัดการรายงานผล (อัปโหลด PDF)
- จัดการประกาศ/คำสั่ง (อัปโหลดไฟล์แนบ)
- จัดการ Content Sections

---

## 🛠️ เทคโนโลยีที่ใช้

- **Framework:** Laravel 12.x
- **Frontend:** Livewire 3.x + Volt
- **Authentication:** Laravel Breeze
- **CSS:** Tailwind CSS 3.x
- **JavaScript:** Alpine.js
- **Database:** MySQL 8.4
- **Development:** Docker (Laravel Sail)
- **Image Processing:** CropperJS

---

## 🚀 Quick Start

### ข้อกำหนดระบบ
- Docker Desktop
- Git
- ไม่ต้องติดตั้ง PHP, Composer, Node.js, MySQL (รันผ่าน Docker)

### ติดตั้งและรัน
```bash
# 1. Clone โปรเจกต์
git clone https://github.com/panchaphon-oil/scd-project.git
cd scd-project

# 2. ติดตั้ง dependencies
docker run --rm -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 3. Setup environment
cp .env.example .env

# 4. เริ่มระบบ
./vendor/bin/sail up -d

# 5. Generate key และ migrate
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link

# 6. Install frontend dependencies
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# 7. สร้าง Admin User
./vendor/bin/sail artisan make:admin
```

### เข้าใช้งาน
- **เว็บไซต์หลัก:** http://localhost
- **Admin Login:** http://localhost/login

---

## 📚 เอกสารเพิ่มเติม

- **[SETUP.md](SETUP.md)** - คู่มือติดตั้งและใช้งานโดยละเอียด
- **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** - โครงสร้างโปรเจกต์และฟังก์ชันแต่ละไฟล์

---

## 🔐 ความปลอดภัย

- Admin login **ไม่มีลิงก์ในหน้าบ้าน** (ต้องรู้ URL)
- Register **ปิดการใช้งาน** - สร้าง admin ผ่าน command เท่านั้น
- Protected routes ด้วย `auth` + `verified` middleware
- File uploads จำกัดเฉพาะ PDF, JPG, PNG

---

## 👥 ทีมพัฒนา

**Phranakhon Si Ayutthaya Rajabhat University**  
สำนักงานพัฒนาชุมชนอย่างยั่งยืน

---

## 📄 License

Laravel Framework: [MIT License](https://opensource.org/licenses/MIT)
