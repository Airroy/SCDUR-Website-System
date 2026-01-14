# โครงสร้างโปรเจกต์และฟังก์ชันแต่ละไฟล์

> **📌 อัปเดตล่าสุด:** 14 มกราคม 2026  
> **โครงสร้าง:** Laravel 12 Standard - แบ่งโซน Frontend/Backend ชัดเจน

## 📁 Root Directory Files

### ไฟล์ Configuration
```
.env                    # ตั้งค่า environment (ห้าม push)
.env.example           # Template สำหรับเพื่อน
.gitignore             # ไฟล์ที่ไม่ต้อง push
.gitattributes         # Git attributes
.editorconfig          # Editor configuration
```

### ไฟล์ Laravel
```
artisan                # Laravel CLI command tool
composer.json          # PHP dependencies
composer.lock          # Lock PHP versions
phpunit.xml            # Testing configuration
```

### ไฟล์ Frontend
```
package.json           # NPM dependencies
package-lock.json      # Lock NPM versions
vite.config.js         # Vite bundler config
tailwind.config.js     # Tailwind CSS config
postcss.config.js      # PostCSS config
```

### Docker
```
compose.yaml           # Docker Compose configuration (Sail)
```

### เอกสาร
```
README.md              # Project overview
SETUP.md               # คู่มือติดตั้งสำหรับเพื่อน
PROJECT_STRUCTURE.md   # เอกสารนี้
```

---

## 📂 app/ - Application Logic (212KB)

### app/Console/Commands/
```
CreateAdminUser.php    # คำสั่งสร้าง admin user
                       ฟังก์ชัน: php artisan admin:create
```

### app/Http/Controllers/

#### Auth/
```
VerifyEmailController.php
  ฟังก์ชัน: ยืนยัน email address ของผู้ใช้
```

#### Backend/
```
ScdYearController.php
  ฟังก์ชัน: จัดการปี SCD ในหลังบ้าน
  - index()     : แสดงรายการปีทั้งหมด
  - create()    : แสดงฟอร์มสร้างปีใหม่
  - store()     : บันทึกปีใหม่
  - edit()      : แสดงฟอร์มแก้ไขปี
  - update()    : อัพเดทข้อมูลปี
  - destroy()   : ลบปี
```

#### Frontend/
```
FrontendController.php
  ฟังก์ชัน: จัดการหน้าบ้าน
  - about()         : หน้าเกี่ยวกับหน่วยงาน
  - contact()       : หน้าติดต่อเรา
  - announcements() : หน้าประกาศ/คำสั่งตามปี

HomeController.php
  ฟังก์ชัน: หน้าแรกของเว็บไซต์
  - index()  : แสดงหน้า home พร้อม banner, content sections
               รองรับการเลือกดูข้อมูลตามปี
```

#### Base/
```
Controller.php
  ฟังก์ชัน: Base controller สำหรับ controllers อื่นๆ
```

### app/Http/Requests/Backend/
```
StoreScdYearRequest.php
  ฟังก์ชัน: Validate ข้อมูลเมื่อสร้างปี SCD ใหม่
  - rules()    : กำหนด validation rules
  - messages() : ข้อความ error แบบกำหนดเอง

UpdateScdYearRequest.php
  ฟังก์ชัน: Validate ข้อมูลเมื่ออัพเดทปี SCD
  - rules()    : กำหนด validation rules
  - messages() : ข้อความ error แบบกำหนดเอง
```

### app/Livewire/

#### Backend/ (ระบบจัดการหลังบ้าน)
```
ScdYearManager.php
  ฟังก์ชัน: จัดการปี SCD (CRUD)
  - mount()         : โหลดข้อมูลเริ่มต้น
  - create()        : เปิด modal สร้างปี
  - store()         : บันทึกปีใหม่
  - edit()          : เปิด modal แก้ไข
  - update()        : อัพเดทข้อมูล
  - delete()        : ลบปี
  - togglePublish() : เปิด/ปิดการเผยแพร่

BannerManager.php
  ฟังก์ชัน: จัดการ Banner สไลด์
  - mount()         : โหลด banners ตามปี
  - store()         : เพิ่ม banner ใหม่ (พร้อมอัพโหลดรูป)
  - update()        : แก้ไข banner
  - delete()        : ลบ banner
  - reorder()       : จัดลำดับการแสดง

ContentSectionManager.php
  ฟังก์ชัน: จัดการ Content Sections (boxes หน้าแรก)
  - mount()         : โหลด sections ตามปี
  - store()         : เพิ่ม section ใหม่
  - update()        : แก้ไข section
  - delete()        : ลบ section
  - uploadImage()   : อัพโหลดรูปภาพ

ScdReportManager.php
  ฟังก์ชัน: จัดการไฟล์รายงาน SCD (PDF)
  - mount()         : โหลดรายงานตามปี
  - upload()        : อัพโหลดไฟล์ PDF
  - delete()        : ลบไฟล์รายงาน
  - download()      : ดาวน์โหลดไฟล์

AnnouncementManager.php
  ฟังก์ชัน: จัดการประกาศ/คำสั่ง
  - mount()         : โหลดประกาศตามปี
  - store()         : เพิ่มประกาศใหม่
  - update()        : แก้ไขประกาศ
  - delete()        : ลบประกาศ
  - uploadFile()    : อัพโหลดไฟล์แนบ
```

#### Frontend/ (หน้าบ้าน)
```
BannerSlider.php
  ฟังก์ชัน: แสดง Banner สไลด์หน้าแรก
  - mount()         : โหลด banners ที่เผยแพร่
  - render()        : แสดงผล slider
```

#### Forms/
```
LoginForm.php
  ฟังก์ชัน: ฟอร์มเข้าสู่ระบบ
  - authenticate()  : ตรวจสอบ login
  - rules()         : validation rules
```

#### Actions/
```
Logout.php
  ฟังก์ชัน: ออกจากระบบ
  - __invoke()      : ทำการ logout
```

### app/Models/
```
User.php
  ฟังก์ชัน: ข้อมูลผู้ใช้งาน (Admin)
  - Relations: ไม่มี
  - Attributes: name, email, password

ScdYear.php
  ฟังก์ชัน: ข้อมูลปี SCD
  - Relations:
    * hasMany(Banner)         : มี banners หลายอัน
    * hasMany(ContentNode)    : มี content sections หลายอัน
    * hasOne(ScdReport)       : มีรายงาน 1 ไฟล์
  - Attributes: year, is_published
  - Scopes:
    * scopePublished()        : เอาเฉพาะที่เผยแพร่

Banner.php
  ฟังก์ชัน: Banner สไลด์
  - Relations:
    * belongsTo(ScdYear)      : อยู่ในปีใดปีหนึ่ง
  - Attributes: title, image_path, link_url, order

ContentNode.php
  ฟังก์ชัน: Content Section (boxes หน้าแรก)
  - Relations:
    * belongsTo(ScdYear)      : อยู่ในปีใดปีหนึ่ง
    * hasMany(self)           : มี children nodes (ถ้าเป็น tree structure)
  - Attributes: name, image_path, content, order

ScdReport.php
  ฟังก์ชัน: รายงาน SCD (PDF)
  - Relations:
    * belongsTo(ScdYear)      : อยู่ในปีใดปีหนึ่ง
  - Attributes: title, file_path, file_size
```

### app/Services/
```
BannerService.php
  ฟังก์ชัน: Business logic สำหรับ Banner
  - store()         : สร้าง banner พร้อมอัพโหลดรูป
  - update()        : อัพเดท banner
  - delete()        : ลบ banner (และไฟล์รูป)
  - reorder()       : จัดลำดับ

ContentNodeService.php
  ฟังก์ชัน: Business logic สำหรับ Content Sections
  - store()         : สร้าง content section
  - update()        : อัพเดท section
  - delete()        : ลบ section (และไฟล์รูป)

FileUploadService.php
  ฟังก์ชัน: จัดการการอัพโหลดไฟล์
  - uploadImage()   : อัพโหลดรูปภาพ (resize, optimize)
  - uploadPdf()     : อัพโหลดไฟล์ PDF
  - deleteFile()    : ลบไฟล์
  - getFileSize()   : คำนวณขนาดไฟล์
```

### app/Providers/
```
AppServiceProvider.php
  ฟังก์ชัน: Service provider หลัก
  - register()      : Register services
  - boot()          : Bootstrap application

VoltServiceProvider.php
  ฟังก์ชัน: Livewire Volt provider
  - boot()          : กำหนด Volt paths
```

### app/View/Components/
```
AppLayout.php
  ฟังก์ชัน: Layout component สำหรับหน้า admin
  - render()        : แสดง layout

GuestLayout.php
  ฟังก์ชัน: Layout component สำหรับหน้า guest (login)
  - render()        : แสดง layout
```

---

## 📂 resources/ - Views & Assets (552KB)

> **โครงสร้าง Laravel 12 Standard:**
> - `layouts/` - Layouts หลัก (guest, app, admin) อยู่ในตำแหน่งมาตรฐาน
> - `components/` - Blade components แยกตาม zone (frontend/backend)
> - `admin/` - View สำหรับหลังบ้าน ใช้ @extends('layouts.admin')
> - `frontend/` - View สำหรับหน้าบ้าน ใช้ x-layouts.frontend
> - `auth/` - View สำหรับ authentication ใช้ layout จาก layouts/

### resources/views/layouts/
```
guest.blade.php
  ฟังก์ชัน: Layout สำหรับหน้า authentication
  - ใช้กับ Login, Register, Forgot Password
  - Simple centered layout
  - Breeze default design

app.blade.php
  ฟังก์ชัน: Layout สำหรับ authenticated users
  - ใช้กับ Dashboard, Profile
  - มี navigation bar
  - Breeze default design

admin.blade.php
  ฟังก์ชัน: Layout หลักสำหรับหลังบ้าน (Backend)
  - Sidebar navigation
  - Header with user menu
  - Content area สำหรับ admin pages
  - ใช้โดย: @extends('layouts.admin')
```

### resources/views/admin/

#### pages/
```
dashboard.blade.php
  ฟังก์ชัน: หน้า Dashboard แสดงสถิติ
  - @extends('layouts.admin')

profile.blade.php
  ฟังก์ชัน: หน้าจัดการโปรไฟล์ admin
  - @extends('layouts.admin')

scd-years/index.blade.php
  ฟังก์ชัน: รายการปี SCD ทั้งหมด
  - @extends('layouts.admin')

scd-years/manage.blade.php
  ฟังก์ชัน: จัดการข้อมูลปี SCD
  - @extends('layouts.admin')

scd-years/banners.blade.php
  ฟังก์ชัน: จัดการ Banners ของปีนั้นๆ
  - @extends('layouts.admin')

scd-years/contents.blade.php
  ฟังก์ชัน: จัดการ Content Sections
  - @extends('layouts.admin')

scd-years/reports.blade.php
  ฟังก์ชัน: จัดการไฟล์รายงาน
  - @extends('layouts.admin')

scd-years/announcements.blade.php
  ฟังก์ชัน: จัดการประกาศ/คำสั่ง
  - @extends('layouts.admin')

scd-years/announcement-category.blade.php
  ฟังก์ชัน: จัดการหมวดหมู่ประกาศ
  - @extends('layouts.admin')
```

#### components/
```
year-card.blade.php          # Card แสดงปี SCD
year-stats.blade.php         # สถิติของปี
feature-card.blade.php       # Card สำหรับฟีเจอร์
card-item.blade.php          # Card item ทั่วไป
card-add.blade.php           # Card เพิ่มรายการใหม่
form-input.blade.php         # Input field
form-toggle.blade.php        # Toggle switch
modal.blade.php              # Modal dialog
confirm-modal.blade.php      # Confirm dialog
alert.blade.php              # Alert message
notification.blade.php       # Notification
```

### resources/views/frontend/

#### pages/
```
home.blade.php
  ฟังก์ชัน: หน้าแรกของเว็บไซต์
  - แสดง Banner slider
  - แสดงแถบ ARU-SCD [ปี]
  - แสดง Content Sections (Grid 4 boxes)

about.blade.php
  ฟังก์ชัน: หน้าเกี่ยวกับหน่วยงาน
  - แสดงข้อมูลหน่วยงาน (hard-code ในโค้ด)

contact.blade.php
  ฟังก์ชัน: หน้าติดต่อเรา
  - แสดง Google Maps
  - ที่อยู่ เบอร์โทร
  - ฟอร์มติดต่อ (ถ้ามี)

announcements.blade.php
  ฟังก์ชัน: หน้าแสดงประกาศ/คำสั่งตามปี
  - แสดงตาราง 2 หมวด: ประกาศ, คำสั่ง
  - สามารถดาวน์โหลดไฟล์แนบได้

welcome.blade.php
  ฟังก์ชัน: หน้า welcome (Laravel default)
```

### resources/views/components/

> **Component Organization:**
> - `layouts/frontend.blade.php` - Main frontend layout wrapper
> - `frontend/` - Frontend components (navigation, header, footer)
> - Components ใช้ผ่าน `<x-...>` syntax

#### layouts/
```
frontend.blade.php
  ฟังก์ชัน: Layout หลักสำหรับหน้าบ้าน
  - โหลด assets (CSS, JS)
  - แสดง Header
  - แสดง Main content
  - แสดง Footer
  - Livewire scripts
```

#### frontend/
```
header.blade.php
  ฟังก์ชัน: Header ของเว็บไซต์ (refactored)
  - Header banner image เดียว (single image)
  - Include navigation component
  - ไม่มี top bar แล้ว (removed)

navigation.blade.php
  ฟังก์ชัน: Navbar สีแดงเข้ม (sticky single bar)
  - หน้าหลัก
  - เกี่ยวกับหน่วยงาน
  - SCD Ranking (dropdown)
  - รายงานผล SCD (dropdown)
  - ประกาศ/คำสั่ง (dropdown)
  - ติดต่อเรา
  - Mobile responsive
  - Hover effects (bg-red-800)

footer.blade.php
  ฟังก์ชัน: Footer 2 ส่วน
  - Footer 1: แนะนำหน่วยงาน, เอกสาร, ติดต่อเรา
  - Footer 2: Copyright

dropdown.blade.php
  ฟังก์ชัน: Dropdown menu component

dropdown-item.blade.php
  ฟังก์ชัน: Dropdown menu item

nav-link.blade.php
  ฟังก์ชัน: Navigation link component

button.blade.php
  ฟังก์ชัน: Button component

card.blade.php
  ฟังก์ชัน: Card component

section.blade.php
  ฟังก์ชัน: Section wrapper component
```

#### อื่นๆ
```
application-logo.blade.php   # Logo component
modal.blade.php              # Modal component
auth-session-status.blade.php # Auth status message
input-error.blade.php        # Error message
input-label.blade.php        # Input label
text-input.blade.php         # Text input
primary-button.blade.php     # Primary button
secondary-button.blade.php   # Secondary button
danger-button.blade.php      # Danger button
dropdown.blade.php           # Dropdown (general)
dropdown-link.blade.php      # Dropdown link
nav-link.blade.php           # Nav link
responsive-nav-link.blade.php # Responsive nav link
action-message.blade.php     # Action message
```

### resources/views/livewire/

#### backend/
```
scd-year-manager.blade.php           # UI สำหรับจัดการปี
banner-manager.blade.php             # UI สำหรับจัดการ banner
content-section-manager.blade.php    # UI สำหรับจัดการ content
scd-report-manager.blade.php         # UI สำหรับจัดการรายงาน
announcement-manager.blade.php       # UI สำหรับจัดการประกาศ
```

#### frontend/
```
banner-slider.blade.php              # UI แสดง banner slider
```

#### pages/auth/
```
login.blade.php             # หน้า login
register.blade.php          # หน้าลงทะเบียน
forgot-password.blade.php   # หน้าลืมรหัสผ่าน
reset-password.blade.php    # หน้ารีเซ็ตรหัสผ่าน
verify-email.blade.php      # หน้ายืนยัน email
confirm-password.blade.php  # หน้ายืนยันรหัสผ่าน
```

#### profile/
```
update-profile-information-form.blade.php  # ฟอร์มแก้ไขข้อมูล
update-password-form.blade.php             # ฟอร์มเปลี่ยนรหัสผ่าน
delete-user-form.blade.php                 # ฟอร์มลบบัญชี
```

### resources/css/
```
app.css
  ฟังก์ชัน: Tailwind CSS main file
  - Import Tailwind directives
  - Custom styles
```

### resources/js/
```
app.js
  ฟังก์ชัน: Main JavaScript file
  - Import Alpine.js
  - Import other modules

bootstrap.js
  ฟังก์ชัน: Bootstrap configuration
  - Import Axios
  - CSRF token setup
```

---

## 📂 database/ - Database (140KB)

### database/migrations/
```
0001_01_01_000000_create_users_table.php
  ฟังก์ชัน: สร้างตาราง users
  - id, name, email, password, timestamps

0001_01_01_000001_create_cache_table.php
  ฟังก์ชัน: สร้างตาราง cache

0001_01_01_000002_create_jobs_table.php
  ฟังก์ชัน: สร้างตาราง jobs, job_batches, failed_jobs

2026_01_08_050543_create_scd_years_table.php
  ฟังก์ชัน: สร้างตาราง scd_years
  - id, year, is_published, timestamps

2026_01_08_050547_create_scd_reports_table.php
  ฟังก์ชัน: สร้างตาราง scd_reports
  - id, scd_year_id, title, file_path, file_size

2026_01_08_050551_create_banners_table.php
  ฟังก์ชัน: สร้างตาราง banners
  - id, scd_year_id, title, image_path, link_url, order

2026_01_08_050556_create_content_nodes_table.php
  ฟังก์ชัน: สร้างตาราง content_nodes
  - id, scd_year_id, parent_id, name, image_path, content, order
```

### database/seeders/
```
DatabaseSeeder.php
  ฟังก์ชัน: Seeder หลัก
  - run()  : เรียก seeders อื่นๆ
```

### database/factories/
```
UserFactory.php
  ฟังก์ชัน: Factory สำหรับสร้าง User ทดสอบ
  - definition()  : กำหนดข้อมูล fake
```

---

## 📂 routes/ - Routes (16KB)

```
web.php
  ฟังก์ชัน: Routes สำหรับหน้าเว็บ
  - หน้าบ้าน (Frontend):
    * GET  /                      -> home
    * GET  /about                 -> about
    * GET  /contact               -> contact
    * GET  /announcements/{year}  -> announcements
  
  - หลังบ้าน (Admin):
    * GET  /admin/dashboard       -> dashboard
    * GET  /admin/profile         -> profile
    * RESOURCE /admin/scd-years   -> SCD year management

auth.php
  ฟังก์ชัน: Routes สำหรับ authentication
  - Login, Register, Logout
  - Password reset
  - Email verification

console.php
  ฟังก์ชัน: Console commands routes
  - Artisan::command()
```

---

## 📂 public/ - Public Assets (14MB)

```
index.php               # Entry point
.htaccess              # Apache config
robots.txt             # Search engine rules

images/
  header-banner.jpg    # Header banner (14MB) ⚠️ ใหญ่มาก
  README.md            # คำอธิบาย

build/                 # Vite compiled assets
  manifest.json
  assets/

js/                    # Static JS files

storage -> symlink     # Symlink ไปที่ storage/app/public
```

---

## 📂 config/ - Configuration (64KB)

```
app.php                # แอพพลิเคชัน config (timezone, locale, etc.)
auth.php               # Authentication config
cache.php              # Cache config
database.php           # Database connections
filesystems.php        # File storage config
logging.php            # Logging config
mail.php               # Mail config
queue.php              # Queue config
services.php           # Third-party services
session.php            # Session config
```

---

## 📂 bootstrap/ - Bootstrap Files

```
app.php                # Bootstrap application
providers.php          # Service providers list
cache/                 # Cached config & routes
```

---

## 📂 tests/ - Testing (56KB)

### tests/Feature/
```
ExampleTest.php        # ตัวอย่างการเทส
ProfileTest.php        # เทส profile features

Auth/                  # Authentication tests
  AuthenticationTest.php
  PasswordResetTest.php
  PasswordUpdateTest.php
  PasswordConfirmationTest.php
  RegistrationTest.php
  EmailVerificationTest.php
```

### tests/Unit/
```
ExampleTest.php        # ตัวอย่าง unit test
```

---

## 🗂️ ไฟล์ที่ไม่ต้อง Push (Gitignored)

```
/vendor/               # PHP dependencies (ติดตั้งใหม่ด้วย composer install)
/node_modules/         # NPM dependencies (ติดตั้งใหม่ด้วย npm install)
.env                   # Environment config (มีข้อมูลลับ)
/storage/logs/         # Log files
/storage/framework/    # Framework cache
/public/build/         # Compiled assets (build ใหม่ด้วย npm run build)
/public/hot            # Vite hot reload
/public/storage/       # Symlink
database.sqlite        # SQLite database
*.log                  # Log files
```

---

## 📊 สถิติโครงสร้าง

### ขนาดโฟลเดอร์
- app/          : 212KB
- database/     : 140KB
- resources/    : 552KB
- routes/       : 16KB
- public/       : 14MB (เกือบทั้งหมดเป็นรูป header-banner.jpg)
- config/       : 64KB
- tests/        : 56KB

### จำนวนไฟล์
- PHP Files     : 27 ไฟล์
- Blade Files   : 50+ ไฟล์
- Models        : 5 models
- Migrations    : 7 migrations
- Controllers   : 5 controllers
- Livewire      : 7 components
- Services      : 3 services

---

## 🔄 การทำงานของระบบ

### 1. หน้าบ้าน (Frontend Flow)
```
User เข้า URL: http://localhost
  ↓
routes/web.php -> HomeController@index
  ↓
โหลดข้อมูล:
  - ScdYear (ปีล่าสุดหรือปีที่เลือก)
  - Banners (สำหรับ slider)
  - ContentNodes (สำหรับ grid boxes)
  ↓
แสดงผล: resources/views/frontend/pages/home.blade.php
  - ใช้ layout: components/layouts/frontend.blade.php
  - แสดง: Header, Navbar, Banner Slider, Content Grid, Footer
```

### 2. หลังบ้าน (Backend Flow)
```
Admin เข้า: http://localhost/admin/dashboard
  ↓
Middleware: auth (ต้อง login)
  ↓
แสดง Dashboard
  ↓
เลือกจัดการข้อมูล:
  - SCD Years    -> Livewire: ScdYearManager
  - Banners      -> Livewire: BannerManager
  - Contents     -> Livewire: ContentSectionManager
  - Reports      -> Livewire: ScdReportManager
  - Announcements -> Livewire: AnnouncementManager
  ↓
CRUD Operations:
  - Livewire Component -> Service -> Model -> Database
  - อัพโหลดไฟล์ -> FileUploadService -> storage/app/public/
```

### 3. Data Flow
```
Database (MySQL)
  ↓
Models (Eloquent ORM)
  ↓
Services (Business Logic)
  ↓
Controllers / Livewire Components
  ↓
Views (Blade Templates)
  ↓
Browser (HTML + Livewire JS)
```

---

## 🎯 ฟีเจอร์หลักที่ทำเสร็จแล้ว

### ✅ Frontend
- Header + Navbar (sticky, dropdown)
- Banner Slider (Livewire)
- Home page with Content Sections
- About, Contact pages
- Announcements page
- Responsive design

### ✅ Backend
- Dashboard
- SCD Year Management (CRUD)
- Banner Management (CRUD + image upload)
- Content Management (CRUD + image upload)
- Report Management (PDF upload)
- Announcement Management (CRUD + file upload)

### ✅ Database
- Users, SCD Years, Banners, Content Nodes, Reports
- Migrations ready

### ✅ Infrastructure
- Docker (Laravel Sail)
- MySQL 8.4
- Redis Cache
- Meilisearch
- Mailpit
- Selenium

---

## 📝 หมายเหตุสำคัญ

1. **ไฟล์รูปใหญ่:** `public/images/header-banner.jpg` มีขนาด 14MB ควรบีบอัดก่อน production
2. **Database:** ใช้ MySQL ผ่าน Docker (ไม่ใช่ SQLite)
3. **File Storage:** ไฟล์อัพโหลดเก็บใน `storage/app/public/` และ symlink ไปที่ `public/storage/`
4. **Livewire:** ใช้ Livewire 3.x สำหรับ dynamic components
5. **Styling:** ใช้ Tailwind CSS + Alpine.js
6. **Testing:** มี PHPUnit setup พร้อมใช้งาน

---

เอกสารนี้อธิบายโครงสร้างและฟังก์ชันของทุกไฟล์ในโปรเจกต์
อัพเดทล่าสุด: 13 มกราคม 2026
