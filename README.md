# SCD Project - ระบบจัดอันดับมหาวิทยาลัยด้านการพัฒนาชุมชนอย่างยั่งยืน

ระบบสำหรับจัดการและเผยแพร่ข้อมูล SCD (Sustainable Community Development) ของมหาวิทยาลัยราชภัฏพระนครศรีอยุธยา ประกอบด้วยหน้าบ้านสำหรับเผยแพร่ข้อมูล และหลังบ้านสำหรับจัดการเนื้อหา รายงาน และเอกสารประกอบ

## 🛠️ เทคโนโลยีที่ใช้

| เทคโนโลยี | เวอร์ชัน |
|---|---|
| Laravel | 12.x |
| Livewire + Volt | 3.x / 1.x |
| MySQL | 8.4 |
| Tailwind CSS | latest |
| Alpine.js | 3.x |
| Vite | latest |
| Docker (Laravel Sail) | latest |

## ✨ ฟีเจอร์หลัก

- **หน้าบ้าน (Frontend)** — เผยแพร่ข้อมูล SCD Rankings, รายงานผล, ประกาศ/คำสั่ง
- **หลังบ้าน (Admin Panel)** — จัดการเนื้อหา, Banner, รายงาน, เอกสาร
- **Banner Slider** — แสดง Banner แบบ Infinite Slider พร้อม Autoplay
- **PDF Viewer** — เปิดดูเอกสาร PDF ในระบบ
- **Multi-year Support** — รองรับข้อมูลหลายปีงบประมาณ

## 📁 โครงสร้างโปรเจกต์ (แยกหน้าบ้าน/หลังบ้าน)
```
scd-project/
├── app/
│   ├── Http/Controllers/
│   │   └── Frontend/            # Controllers หน้าบ้าน
│   ├── Livewire/
│   │   ├── Frontend/            # Livewire หน้าบ้าน
│   │   ├── Backend/             # Livewire หลังบ้าน (Full-page)
│   │   └── Profile/             # โปรไฟล์หลังบ้าน
│   └── Models/                  # Eloquent Models
├── resources/
│   ├── views/
│   │   ├── frontend/            # หน้าเว็บหลัก
│   │   ├── admin/               # หน้าหลังบ้านแบบ view
│   │   ├── livewire/backend/    # Blade สำหรับ Livewire หลังบ้าน
│   │   ├── livewire/frontend/   # Blade สำหรับ Livewire หน้าบ้าน
│   │   └── components/          # Components ใช้ร่วมกัน
│   ├── css/                     # Tailwind
│   └── js/                      # Alpine.js, Vite
├── routes/
│   ├── web.php                  # Frontend + Admin routes
│   └── auth.php                 # Auth routes
└── public/                      # Public Assets
```

## 📄 เอกสารเพิ่มเติม

- [SETUP.md](SETUP.md) — คู่มือการติดตั้งและ Deploy
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) — โครงสร้างโปรเจกต์โดยละเอียด