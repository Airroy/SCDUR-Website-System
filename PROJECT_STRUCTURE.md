# โครงสร้างโปรเจกต์ SCD - คู่มือสำหรับนักพัฒนา

> **อัปเดตล่าสุด:** 19 มกราคม 2026  
> **Laravel Version:** 11.x  
> **Architecture:** MVC + Livewire Components

---

## 📁 โครงสร้างหลัก

```
scd-project/
├── app/                    # Application Logic
├── bootstrap/              # Framework Bootstrap
├── config/                 # Configuration Files
├── database/               # Migrations & Seeders
├── public/                 # Public Assets & Entry Point
├── resources/              # Views, CSS, JS
├── routes/                 # Route Definitions
├── storage/                # File Storage & Logs
├── tests/                  # Automated Tests
└── vendor/                 # Composer Dependencies (gitignored)
```

---

## 📂 App Directory (Application Logic)

### app/Console/Commands/
**คำสั่ง Artisan ที่สร้างเอง**

```
CreateAdminUser.php
  Command: php artisan admin:create
  ฟังก์ชัน: สร้าง admin user ใหม่ผ่าน CLI
  - รับ input: name, email, password
  - Validate ข้อมูล
  - Hash password
  - สร้าง user พร้อม email_verified_at
```

---

### app/Http/Controllers/

#### Backend/
**Controllers สำหรับหลังบ้าน (Admin Panel)**

```
YearsController.php
  Route: /admin/years
  ฟังก์ชัน: จัดการหน้าแสดงรายการปี SCD
  - index()  : แสดงรายการปี SCD ทั้งหมด

ReportsController.php
  Route: /admin/years/{year}/reports
  ฟังก์ชัน: จัดการหน้ารายงาน SCD
  - index() : แสดงหน้าจัดการรายงาน

BannersController.php
  Route: /admin/years/{year}/banners
  ฟังก์ชัน: จัดการหน้า Banner Slider
  - index() : แสดงหน้าจัดการ banner

ContentsController.php
  Route: /admin/years/{year}/contents
  ฟังก์ชัน: จัดการหน้า Content Sections
  - index() : แสดงหน้าจัดการ content

AnnouncementsController.php
  Route: /admin/years/{year}/announcements
  ฟังก์ชัน: จัดการหน้าประกาศ/คำสั่ง
  - index() : แสดงหน้าจัดการประกาศ
```

#### Frontend/
**Controllers สำหรับหน้าบ้าน**

```
HomeController.php
  Route: GET /
  ฟังก์ชัน: หน้าแรกของเว็บไซต์
  - index($year = null)
    * โหลดปีล่าสุด หรือปีที่เลือก
    * โหลด banners สำหรับ slider
    * โหลด content sections
    * ส่งข้อมูลไปยัง view

FrontendController.php
  ฟังก์ชัน: หน้าต่างๆ ของเว็บไซต์
  - about()         : หน้าเกี่ยวกับหน่วยงาน
  - contact()       : หน้าติดต่อเรา
  - announcements() : หน้าประกาศ/คำสั่งตามปี
```

#### Auth/
**Controllers สำหรับ Authentication**

```
VerifyEmailController.php
  ฟังก์ชัน: ยืนยันอีเมลผู้ใช้
  - __invoke() : ตรวจสอบและยืนยันอีเมล
```

---

### app/Livewire/

#### Backend/
**Livewire Components สำหรับ Admin Panel**

```
YearsIndex.php
  View: livewire.backend.years-index
  ฟังก์ชัน: จัดการปี SCD (CRUD)
  Methods:
    - mount()         : โหลดข้อมูลเริ่มต้น
    - openCreateModal() : เปิด modal สร้างปีใหม่
    - store()         : บันทึกปีใหม่
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดตข้อมูลปี
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบปี
    - togglePublish() : เปิด/ปิดการเผยแพร่

ReportsIndex.php
  View: livewire.backend.reports-index
  ฟังก์ชัน: จัดการรายงาน SCD (PDF)
  Methods:
    - mount($year)    : โหลดรายงานตามปี
    - openCreateModal() : เปิด modal อัปโหลดรายงาน
    - store()         : อัปโหลดไฟล์ PDF
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบรายงาน
  Validation:
    - PDF only, max 10MB

BannersIndex.php
  View: livewire.backend.banners-index
  ฟังก์ชัน: จัดการ Banner Slider
  Methods:
    - mount($year)    : โหลด banners ตามปี
    - openCreateModal() : เปิด modal สร้าง banner
    - store()         : บันทึก banner (อัปโหลดรูป)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดต banner
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบ banner
    - updateOrder()   : จัดลำดับการแสดง
  Validation:
    - JPG, PNG only, max 5MB
    - Resize รูปภาพอัตโนมัติ

ContentsIndex.php
  View: livewire.backend.contents-index
  ฟังก์ชัน: จัดการ Content Sections
  Methods:
    - mount($year)    : โหลด contents ตามปี
    - openCreateModal() : เปิด modal สร้าง content
    - store()         : บันทึก content (อัปโหลดรูป)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดต content
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบ content
  Features:
    - Support หมวดหมู่ (โฟลเดอร์)
    - Upload รูปภาพ

AnnouncementsIndex.php
  View: livewire.backend.announcements-index
  ฟังก์ชัน: จัดการประกาศ/คำสั่ง
  Methods:
    - mount($year)    : โหลดประกาศตามปี
    - openCreateModal() : เปิด modal สร้างประกาศ
    - store()         : บันทึกประกาศ (อัปโหลดไฟล์)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดตประกาศ
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบประกาศ
  Features:
    - แยกหมวด: ประกาศ, คำสั่ง
    - อัปโหลดไฟล์แนบ (PDF)
```

#### Frontend/
**Livewire Components สำหรับหน้าบ้าน**

```
BannerSlider.php
  View: livewire.frontend.banner-slider
  ฟังก์ชัน: แสดง Banner Slider หน้าแรก
  Methods:
    - mount($year)    : โหลด banners ที่เผยแพร่
    - render()        : แสดงผล slider
  Features:
    - Auto-slide (ถ้ามี JS)
    - Responsive images
```

#### Profile/
**Livewire Components สำหรับจัดการโปรไฟล์**

```
UpdateProfileInformation.php
  View: livewire.profile.update-profile-information
  ฟังก์ชัน: แก้ไขข้อมูลส่วนตัว
  Methods:
    - mount()                       : โหลดข้อมูล user
    - updateProfileInformation()    : อัปเดตชื่อและอีเมล
    - sendVerification()            : ส่ง email verification
  Validation:
    - ชื่อต้องไม่ว่าง
    - อีเมลต้อง unique

UpdatePassword.php
  View: livewire.profile.update-password
  ฟังก์ชัน: เปลี่ยนรหัสผ่าน
  Methods:
    - updatePassword() : ตรวจสอบรหัสเดิมและบันทึกรหัสใหม่
  Validation:
    - รหัสเดิมต้องถูกต้อง
    - รหัสใหม่ต้องยืนยันซ้ำ
    - รหัสใหม่ขั้นต่ำ 8 ตัวอักษร

DeleteUser.php
  View: livewire.profile.delete-user
  ฟังก์ชัน: ลบบัญชีผู้ใช้
  Methods:
    - confirmUserDeletion() : เปิด modal ยืนยัน
    - deleteUser()          : ลบบัญชีพร้อม logout
  Security:
    - ต้องใส่รหัสผ่านยืนยัน
    - Logout อัตโนมัติ
```

#### Auth/
**Livewire Components สำหรับ Authentication**

```
Login.php
  View: livewire.auth.login
  ฟังก์ชัน: ฟอร์มเข้าสู่ระบบ
  Methods:
    - login() : ตรวจสอบ email + password และ login
  Features:
    - Remember me
    - Throttle login attempts
```

#### Actions/
**Livewire Actions**

```
Logout.php
  ฟังก์ชัน: ออกจากระบบ
  - __invoke() : Logout และ redirect ไป /
```

---

### app/Models/

```
User.php
  Table: users
  ฟังก์ชัน: ผู้ใช้งาน (Admin)
  Columns:
    - id, name, email, password
    - email_verified_at
    - remember_token
    - timestamps
  Relations: ไม่มี
  Features:
    - Eloquent User Authentication
    - Email verification

ScdYear.php
  Table: scd_years
  ฟังก์ชัน: ข้อมูลปี SCD
  Columns:
    - id, year (unique)
    - is_published (boolean)
    - timestamps
  Relations:
    - hasMany(Banner)       : banners
    - hasMany(ContentNode)  : content_nodes
    - hasOne(ScdReport)     : scd_report
    - hasMany(Announcement) : announcements
  Scopes:
    - scopePublished()      : เฉพาะปีที่เผยแพร่

Banner.php
  Table: banners
  ฟังก์ชัน: Banner Slider หน้าแรก
  Columns:
    - id, scd_year_id
    - title, image_path, link_url
    - order (ลำดับการแสดง)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
  Features:
    - Sortable by order

ContentNode.php
  Table: content_nodes
  ฟังก์ชัน: Content Sections หน้าแรก
  Columns:
    - id, scd_year_id, parent_id
    - type (โฟลเดอร์/ไฟล์)
    - name, image_path, content
    - order
    - timestamps
  Relations:
    - belongsTo(ScdYear)
    - hasMany(self) as children : สำหรับโครงสร้าง tree
    - belongsTo(self) as parent
  Features:
    - Hierarchical structure (tree)
    - Support หมวดหมู่

ScdReport.php
  Table: scd_reports
  ฟังก์ชัน: ไฟล์รายงาน SCD (PDF)
  Columns:
    - id, scd_year_id (unique)
    - title, file_path
    - file_size
    - timestamps
  Relations:
    - belongsTo(ScdYear)
  Features:
    - One report per year

Announcement.php
  Table: announcements
  ฟังก์ชัน: ประกาศและคำสั่ง
  Columns:
    - id, scd_year_id
    - category (ประกาศ/คำสั่ง)
    - title, description
    - file_path
    - announced_at (วันที่ประกาศ)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
  Features:
    - แยกหมวดหมู่
    - แนบไฟล์ได้
```

---

### app/Services/

```
FileUploadService.php
  ฟังก์ชัน: จัดการการอัปโหลดไฟล์
  Methods:
    - uploadImage($file, $path)
      * Validate image (jpg, png)
      * Resize ถ้าใหญ่เกินไป
      * บันทึกใน storage/app/public/{$path}
      * Return file path
    
    - uploadPdf($file, $path)
      * Validate PDF
      * จำกัดขนาด
      * บันทึกใน storage
      * Return file path
    
    - deleteFile($path)
      * ลบไฟล์จาก storage
      * Return boolean
    
    - getFileSize($path)
      * คำนวณขนาดไฟล์
      * Return size in KB/MB
```

---

### app/Providers/

```
AppServiceProvider.php
  ฟังก์ชัน: Service Provider หลัก
  - register() : Register services
  - boot()     : Bootstrap services

VoltServiceProvider.php
  ฟังก์ชัน: Livewire Volt Provider
  - boot() : Mount Volt directories
    * resources/views/pages
    * resources/views/livewire
```

---

## 📂 Database Directory

### database/migrations/

```
0001_01_01_000000_create_users_table.php
  สร้างตาราง: users, password_reset_tokens, sessions

0001_01_01_000001_create_cache_table.php
  สร้างตาราง: cache, cache_locks

0001_01_01_000002_create_jobs_table.php
  สร้างตาราง: jobs, job_batches, failed_jobs

2026_01_08_050543_create_scd_years_table.php
  สร้างตาราง: scd_years
  - ปี SCD พร้อมสถานะการเผยแพร่

2026_01_08_050547_create_scd_reports_table.php
  สร้างตาราง: scd_reports
  - ไฟล์รายงาน PDF แต่ละปี

2026_01_08_050551_create_banners_table.php
  สร้างตาราง: banners
  - Banner slider พร้อมลำดับการแสดง

2026_01_08_050556_create_content_nodes_table.php
  สร้างตาราง: content_nodes
  - Content sections แบบ tree structure

2026_01_15_create_announcements_table.php
  สร้างตาราง: announcements
  - ประกาศและคำสั่ง
```

### database/seeders/

```
DatabaseSeeder.php
  ฟังก์ชัน: Main seeder
  - run() : เรียก seeders อื่นๆ (ถ้ามี)
```

### database/factories/

```
UserFactory.php
  ฟังก์ชัน: สร้างข้อมูล User สำหรับ testing
  - definition() : กำหนดข้อมูล fake
```

---

## 📂 Resources Directory

### resources/views/

#### layouts/
**Layout Templates หลัก**

```
admin.blade.php
  ฟังก์ชัน: Layout สำหรับหลังบ้าน (Admin)
  ใช้โดย: @extends('layouts.admin')
  Structure:
    - Sidebar navigation
    - Header with user dropdown
    - Content area (@yield('content'))
    - Notification system
  Features:
    - Responsive sidebar
    - User profile dropdown (3 dots)
    - Logout functionality

guest.blade.php
  ฟังก์ชัน: Layout สำหรับหน้า authentication
  ใช้กับ: Login, Register, Forgot Password
  Structure:
    - Centered card layout
    - Application logo
    - Simple design

frontend.blade.php
  ฟังก์ชัน: Layout สำหรับหน้าบ้าน
  ใช้โดย: @extends('layouts.frontend')
  Structure:
    - <x-frontend.header> (banner + navigation)
    - @yield('content')
    - <x-frontend.footer>
  Features:
    - SEO-friendly structure
    - @yield('title') for page titles
```

#### admin/pages/
**หน้าต่างๆ ของ Admin Panel**

```
dashboard.blade.php
  Route: /admin/dashboard
  ฟังก์ชัน: Dashboard หน้าหลัก
  - แสดงสถิติภาพรวม
  - @extends('layouts.admin')

profile.blade.php
  Route: /admin/profile
  ฟังก์ชัน: หน้าจัดการโปรไฟล์
  - @livewire('profile.update-profile-information')
  - @livewire('profile.update-password')
  - @livewire('profile.delete-user')

years/
  index.blade.php
    Route: /admin/years
    ฟังก์ชัน: รายการปี SCD
    - @livewire('backend.years-index')
  
  reports.blade.php
    Route: /admin/years/{year}/reports
    ฟังก์ชัน: จัดการรายงาน
    - @livewire('backend.reports-index')
  
  banners.blade.php
    Route: /admin/years/{year}/banners
    ฟังก์ชัน: จัดการ banner
    - @livewire('backend.banners-index')
  
  contents.blade.php
    Route: /admin/years/{year}/contents
    ฟังก์ชัน: จัดการ content
    - @livewire('backend.contents-index')
  
  announcements.blade.php
    Route: /admin/years/{year}/announcements
    ฟังก์ชัน: จัดการประกาศ
    - @livewire('backend.announcements-index')
```

#### frontend/pages/
**หน้าต่างๆ ของเว็บไซต์**

```
home.blade.php
  Route: /
  ฟังก์ชัน: หน้าแรก
  - @extends('layouts.frontend')
  - @livewire('frontend.banner-slider')
  - แสดง content sections (grid 4 boxes)
  - รองรับการเลือกดูตามปี

about.blade.php
  Route: /about
  ฟังก์ชัน: เกี่ยวกับหน่วยงาน
  - @extends('layouts.frontend')
  - แสดงข้อมูลหน่วยงาน (hard-coded)

contact.blade.php
  Route: /contact
  ฟังก์ชัน: ติดต่อเรา
  - @extends('layouts.frontend')
  - แสดงที่อยู่ เบอร์โทร
  - Google Maps (ถ้ามี)

announcements.blade.php
  Route: /announcements/{year}
  ฟังก์ชัน: หน้าประกาศ/คำสั่งตามปี
  - @extends('layouts.frontend')
  - ตาราง 2 หมวด: ประกาศ, คำสั่ง
  - ดาวน์โหลดไฟล์แนบ
```

#### components/

##### backend/
**Components สำหรับ Admin**

```
year-tabs.blade.php
  ฟังก์ชัน: แถบเลือกเมนูในแต่ละปี
  - รายงาน, Banner, ประกาศ, เนื้อหา
  - Responsive (mobile-friendly)
  - Active state highlighting

modal.blade.php
  ฟังก์ชัน: Modal dialog สำหรับ CRUD
  - รับ props: id, title, maxWidth
  - Alpine.js สำหรับ open/close

modal-form.blade.php
  ฟังก์ชัน: Modal พร้อมฟอร์ม
  - ปุ่ม Save/Cancel
  - Loading states
```

##### frontend/
**Components สำหรับหน้าบ้าน**

```
header.blade.php
  ฟังก์ชัน: Header พร้อม banner image
  - Banner image เดียว (single image)
  - Include navigation component

navigation.blade.php
  ฟังก์ชัน: Navbar หลัก (sticky)
  - หน้าหลัก
  - เกี่ยวกับหน่วยงาน
  - SCD Ranking (dropdown)
  - รายงานผล SCD (dropdown)
  - ประกาศ/คำสั่ง (dropdown)
  - ติดต่อเรา
  Features:
    - Sticky top
    - Mobile hamburger menu
    - Hover effects

footer.blade.php
  ฟังก์ชัน: Footer 2 sections
  - Footer 1: แนะนำหน่วยงาน, เอกสาร, ติดต่อ
  - Footer 2: Copyright

dropdown.blade.php
  ฟังก์ชัน: Dropdown menu component
  - Alpine.js based
  - Responsive

Other components:
  - button.blade.php       : Button styles
  - card.blade.php         : Card wrapper
  - section.blade.php      : Section wrapper
  - nav-link.blade.php     : Navigation link
  - dropdown-item.blade.php: Dropdown item
```

##### Shared Components
**Components ที่ใช้ร่วมกัน**

```
application-logo.blade.php
  ฟังก์ชัน: Logo ของแอพพลิเคชัน

auth-session-status.blade.php
  ฟังก์ชัน: แสดงสถานะ session (success/error)
  - ใช้ใน login, password reset

input-error.blade.php
  ฟังก์ชัน: แสดง validation error
  - รับ $messages array

input-label.blade.php
  ฟังก์ชัน: Label สำหรับ input field

text-input.blade.php
  ฟังก์ชัน: Text input field พร้อม styling

primary-button.blade.php
  ฟังก์ชัน: ปุ่มหลัก (primary button)

notification.blade.php
  ฟังก์ชัน: แสดงการแจ้งเตือน
  - Success/Error notifications
  - Alpine.js auto-hide
```

#### livewire/
**Blade views สำหรับ Livewire Components**

```
backend/
  years-index.blade.php
  reports-index.blade.php
  banners-index.blade.php
  contents-index.blade.php
  announcements-index.blade.php
  partials/
    year-tabs.blade.php        # แถบเมนู
    years-table.blade.php      # ตารางปี
    reports-table.blade.php    # ตารางรายงาน
    banners-table.blade.php    # ตารางแบนเนอร์
    contents-table.blade.php   # ตารางเนื้อหา
    announcements-table.blade.php # ตารางประกาศ

frontend/
  banner-slider.blade.php      # Banner slider

profile/
  update-profile-information.blade.php
  update-password.blade.php
  delete-user.blade.php

auth/
  login.blade.php             # หน้า login (Livewire)

pages/auth/ (Volt Components)
  forgot-password.blade.php
  reset-password.blade.php
  verify-email.blade.php
  confirm-password.blade.php
```

### resources/css/

```
app.css
  ฟังก์ชัน: Main CSS file
  - @tailwind base
  - @tailwind components
  - @tailwind utilities
  - Custom styles
  - Scrollbar hiding utilities
```

### resources/js/

```
app.js
  ฟังก์ชัน: Main JavaScript
  - Import Alpine.js
  - Import Livewire scripts

bootstrap.js
  ฟังก์ชัน: Bootstrap configuration
  - Axios setup
  - CSRF token
```

---

## 📂 Routes Directory

```
web.php
  ฟังก์ชัน: Web routes

  Frontend:
    GET  /                      -> HomeController@index
    GET  /about                 -> FrontendController@about
    GET  /contact               -> FrontendController@contact
    GET  /announcements/{year}  -> FrontendController@announcements

  Admin (auth middleware):
    GET  /admin/dashboard       -> dashboard view
    GET  /admin/profile         -> profile view
    GET  /admin/years           -> YearsController@index
    GET  /admin/years/{year}/reports       -> ReportsController@index
    GET  /admin/years/{year}/banners       -> BannersController@index
    GET  /admin/years/{year}/contents      -> ContentsController@index
    GET  /admin/years/{year}/announcements -> AnnouncementsController@index

auth.php
  ฟังก์ชัน: Authentication routes
  - Login, Logout
  - Password Reset
  - Email Verification
  - Password Confirmation

console.php
  ฟังก์ชัน: Console routes (ไม่ได้ใช้)
```

---

## 📂 Public Directory

```
index.php               # Laravel entry point
.htaccess               # Apache configuration
robots.txt              # SEO robots file

images/
  header-banner.jpg     # Header banner image

build/                  # Vite compiled assets
  manifest.json
  assets/
    app-xxx.css         # Compiled CSS
    app-xxx.js          # Compiled JS

storage/                # Symlink → ../storage/app/public
  (ไฟล์ที่อัปโหลด)
```

---

## 📂 Config Directory

**ไฟล์ configuration สำคัญ:**

```
app.php         # Application config (name, locale, timezone)
auth.php        # Authentication guards & providers
database.php    # Database connections
filesystems.php # Storage disks configuration
session.php     # Session driver & settings
```

---

## 📂 Storage Directory

```
app/
  public/                # ไฟล์ที่เข้าถึงได้จาก public/storage
    banners/             # รูปภาพ banner
    contents/            # รูปภาพ content
    reports/             # ไฟล์รายงาน PDF
    announcements/       # ไฟล์แนบประกาศ
  
  private/               # ไฟล์ private (ไม่สามารถเข้าถึงจาก URL)

framework/
  cache/                 # Framework cache
  sessions/              # Session files
  views/                 # Compiled Blade views

logs/
  laravel.log            # Application logs
```

---

## 🗂️ ไฟล์ที่ Gitignore (ไม่ต้อง push)

```
/vendor/                # Composer dependencies
/node_modules/          # NPM dependencies
.env                    # Environment config (มีข้อมูลลับ)
/storage/logs/          # Log files
/storage/framework/     # Cache & compiled files
/public/build/          # Compiled assets
/public/hot             # Vite HMR file
/public/storage/        # Symlink
database.sqlite         # SQLite database (ถ้าใช้)
*.log                   # Log files
.phpunit.result.cache   # PHPUnit cache
```

---

## 📊 สถิติโปรเจกต์

### Models
- 5 Models: User, ScdYear, Banner, ContentNode, ScdReport, Announcement

### Controllers
- 2 Frontend Controllers
- 5 Backend Controllers
- 1 Auth Controller

### Livewire Components
- 5 Backend Components (CRUD)
- 1 Frontend Component (Slider)
- 3 Profile Components
- 1 Auth Component

### Migrations
- 7 Migration files

### Routes
- 10+ Frontend routes
- 10+ Admin routes
- Auth routes (Breeze)

---

## 🔄 Data Flow

### 1. Frontend Request Flow
```
User → Route (web.php)
     → Controller
     → Load Models
     → View (Blade)
     → Livewire Component (if needed)
     → Browser
```

### 2. Admin CRUD Flow
```
Admin → Route (auth middleware)
      → Controller
      → Livewire Component
      → Service (FileUploadService)
      → Model (Eloquent)
      → Database
      → Response (JSON/Redirect)
      → View Update (Livewire)
```

### 3. File Upload Flow
```
Form → Livewire Component
    → FileUploadService
    → Validation
    → Storage (storage/app/public)
    → Save path to Database
    → Return success
```

---

## 🎯 ฟีเจอร์สำคัญที่เสร็จแล้ว

### ✅ Frontend
- [x] Header + Navigation (responsive)
- [x] Banner Slider (Livewire)
- [x] Home page with Content Sections
- [x] About, Contact pages
- [x] Announcements page
- [x] Responsive design (mobile-friendly)
- [x] SEO structure

### ✅ Backend
- [x] Dashboard
- [x] Years Management (CRUD)
- [x] Reports Management (PDF upload)
- [x] Banners Management (Image upload + ordering)
- [x] Contents Management (Image upload + categories)
- [x] Announcements Management (File attachments)
- [x] Profile Management (Livewire)
- [x] Responsive admin layout

### ✅ Authentication
- [x] Login (Livewire)
- [x] Logout
- [x] Email Verification
- [x] Password Reset (Volt)
- [x] Profile Management (Livewire)
- [x] No Public Registration

### ✅ Infrastructure
- [x] Docker (Laravel Sail)
- [x] MySQL 8.4
- [x] Vite (Frontend bundler)
- [x] Tailwind CSS
- [x] Alpine.js
- [x] Livewire 3.x

---

## 📝 หมายเหตุสำหรับนักพัฒนา

### Architecture Decisions
- **Livewire > Volt:** ใช้ Livewire components สำหรับ Admin และ Profile เพื่อความสม่ำเสมอ
- **Volt:** ใช้เฉพาะ Auth pages ที่ไม่ค่อยแก้ไข (forgot-password, reset-password, verify-email, confirm-password)
- **@extends > Components:** Frontend layouts ใช้ `@extends('layouts.frontend')` แทน component syntax
- **Services:** ใช้ FileUploadService แยกออกจาก Livewire components

### File Storage
- **Storage Path:** `storage/app/public/`
- **Public URL:** ผ่าน symlink `public/storage/`
- **Command:** `sail artisan storage:link`

### Responsive Design
- **Mobile First:** Tailwind responsive utilities
- **Breakpoints:** sm: 640px, md: 768px, lg: 1024px, xl: 1280px
- **Admin Tables:** ซ่อนคอลัมน์บางอันบน mobile
- **Navigation:** Hamburger menu บน mobile

### Database
- **Soft Deletes:** ไม่ได้ใช้ (ลบแบบ hard delete)
- **Timestamps:** ทุก model มี created_at, updated_at
- **Foreign Keys:** Cascade on delete

---

**เอกสารนี้อธิบายโครงสร้างและฟังก์ชันของทุกไฟล์ในโปรเจกต์**  
**อัปเดตล่าสุด:** 19 มกราคม 2026
