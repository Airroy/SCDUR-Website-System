# โครงสร้างโปรเจกต์ SCD - คู่มือสำหรับนักพัฒนา

> **อัปเดตล่าสุด:** 4 มีนาคม 2026  
> **Laravel Version:** 12.x  
> **PHP Version:** 8.4  
> **Architecture:** MVC + Livewire Full-Page Components  
> **Cache:** Redis  
> **Containerization:** Docker (Laravel Sail)

---

## 📁 โครงสร้างหลัก

```
scd-project/
├── app/                    # Application Logic
├── bootstrap/              # Framework Bootstrap
├── config/                 # Configuration Files
├── database/               # Migrations & Seeders
├── docker/                 # Docker Configuration (Published)
│   ├── 8.2/                # PHP 8.2 Dockerfile
│   ├── 8.3/                # PHP 8.3 Dockerfile
│   ├── 8.4/                # PHP 8.4 Dockerfile (ใช้งานหลัก)
│   ├── 8.5/                # PHP 8.5 Dockerfile
│   └── mysql/              # MySQL init scripts
├── public/                 # Public Assets & Entry Point
├── resources/              # Views, CSS, JS
├── routes/                 # Route Definitions
├── storage/                # File Storage & Logs
├── tests/                  # Automated Tests
└── vendor/                 # Composer Dependencies (gitignored)
```

---

## 🐳 Docker Directory (Infrastructure)

### docker/8.4/ (PHP 8.4 - ใช้งานหลัก)
**Docker configuration สำหรับ Laravel Application**

```
Dockerfile
  หน้าที่: สร้าง Docker image สำหรับ Laravel
  เนื้อหา:
    - Base image: Ubuntu 24.04
    - PHP 8.4 + Extensions ที่ Laravel ต้องการ
    - Composer, Node.js, NPM
    - Nginx / PHP-FPM
    - Supervisor สำหรับจัดการ processes

php.ini
  หน้าที่: PHP Configuration
  การตั้งค่าสำคัญ:
    - memory_limit = 512M
    - upload_max_filesize = 100M
    - post_max_size = 100M
    - max_execution_time = 60

start-container
  หน้าที่: Startup script เมื่อ container เริ่มทำงาน
  การทำงาน:
    - ตั้งค่า permissions
    - รัน supervisord
    - เริ่ม PHP-FPM

supervisord.conf
  หน้าที่: Process manager configuration
  Processes ที่จัดการ:
    - php-fpm (PHP processor)
    - nginx (Web server) - บาง version
```

### docker/mysql/
**MySQL initialization scripts**

```
create-testing-database.sh
  หน้าที่: สร้าง testing database อัตโนมัติ
  การทำงาน:
    - รันเมื่อ MySQL container เริ่มครั้งแรก
    - สร้าง database: testing
    - ใช้สำหรับ phpunit tests
```

### compose.yaml (Root)
**Docker Compose configuration**

```yaml
Services:
  laravel.test:
    - Image: sail-8.4/app (build จาก docker/8.4/)
    - Ports: 80 (web), 5173 (vite)
    - Volumes: mount โปรเจกต์เข้า container
    - Depends on: mysql

  mysql:
    - Image: mysql:8.4
    - Port: 3306
    - Volume: sail-mysql (persistent data)
    - Environment: credentials จาก .env

  redis:
    - Image: redis:alpine
    - Port: 6379
    - Volume: sail-redis (persistent data)

  phpmyadmin:
    - Image: phpmyadmin:latest
    - Port: 8080
    - Environment: auto-login ด้วย .env credentials
```

### วิธีเปลี่ยน PHP Version
```yaml
# ใน compose.yaml แก้ไข:
services:
    laravel.test:
        build:
            context: './docker/8.5'    # เปลี่ยนจาก 8.4 → 8.5
        image: 'sail-8.5/app'          # เปลี่ยนชื่อ image
```

### วิธีปรับแต่ง PHP Configuration
```bash
# แก้ไขไฟล์:
docker/8.4/php.ini

# แล้ว rebuild container:
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
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

ClearLogs.php
  Command: php artisan logs:clear {--days=7}
  ฟังก์ชัน: ลบไฟล์ log เก่า
  - Option: --days=N (ค่าเริ่มต้น 7 วัน)
  - ลบไฟล์ใน storage/logs/ ที่เก่ากว่า N วัน

MonitorContentNodesPerformance.php
  Command: php artisan content:monitor
  ฟังก์ชัน: ตรวจสอบ performance ของตาราง content
  - แสดงจำนวน records ในแต่ละตาราง
  - ทดสอบ query performance
  - แนะนำการ optimize
```

---

### app/Http/Controllers/

> **หมายเหตุ:** หน้า Admin ทั้งหมดใช้ Livewire Full-Page Components โดยตรง ไม่ผ่าน Controller

#### Frontend/
**Controllers สำหรับหน้าบ้าน**

```
HomeController.php
  Route: GET / , GET /scd/{year}
  ฟังก์ชัน: หน้าแรกของเว็บไซต์
  - index($request, $year = null)
    * โหลดปีที่เผยแพร่ (เรียงจากมากไปน้อย)
    * โหลด announcements (root, ไม่ซ่อน, max 6 รายการ)
    * โหลด content sections (root, ไม่ซ่อน)
    * ส่งข้อมูลไปยัง view: activeYear, publishedYears, announcements, contentSections, isYearPage

FrontendController.php
  ฟังก์ชัน: หน้าต่างๆ ของเว็บไซต์ + การจัดการไฟล์
  - about()                  : หน้าเกี่ยวกับหน่วยงาน
  - contact()                : หน้าติดต่อเรา
  - announcements($year)     : หน้าประกาศ/คำสั่งตามปี (แยก announcements กับ orders)
  - contentSection($year, $slug) : หน้าแสดง content section ย่อย (ค้นหาจาก slug)
  - viewFile($source, $id, $filename)   : เปิดดูไฟล์ PDF ในเบราว์เซอร์ (นับ view_count)
  - downloadFile($source, $id)          : ดาวน์โหลดไฟล์ PDF (นับ download_count)
  - viewBannerPdf($id, $filename)       : เปิดดู PDF ของ banner
  - viewScdReport($year, $filename)     : เปิดดูรายงาน SCD (นับ view_count)
  - downloadScdReport($year)            : ดาวน์โหลดรายงาน SCD

AnnouncementOrderController.php
  Route: GET /year/{year}/announcements-orders
  ฟังก์ชัน: หน้าประกาศและคำสั่ง (แบบรวม)
  - index($year)
    * โหลดประกาศ + คำสั่ง แบบ tree structure
    * กรอง is_hidden = false
```

#### Auth/
**Controllers สำหรับ Authentication**

```
VerifyEmailController.php
  ฟังก์ชัน: ยืนยันอีเมลผู้ใช้
  - __invoke() : ตรวจสอบและยืนยันอีเมล
  หมายเหตุ: ปัจจุบัน route ถูก comment ไว้ (ยังไม่ได้ตั้งค่า Mail)
```

---

### app/Http/Middleware/

```
AdminAuth.php
  ฟังก์ชัน: ป้องกันหน้า Admin
  - ตรวจสอบ auth()->check()
  - ถ้าไม่ได้ login จะ abort(404) (ซ่อนหน้า admin)
  - ใช้ใน route group: middleware('admin.auth')

AccessLog.php
  ฟังก์ชัน: บันทึก log ทุก request
  - บันทึก: Method, Status, Duration, Path, IP, User, Device, Browser
  - วิเคราะห์ความเร็ว: FAST/NORMAL/SLOW/VERY SLOW
  - ตรวจจับ Device (Mobile/Tablet/Desktop) และ Browser
  - Log ตาม severity: error (5xx), warning (4xx), info (2xx/3xx)
```

---

### app/Http/Requests/Backend/

```
StoreScdYearRequest.php
  ฟังก์ชัน: Form Request Validation สำหรับสร้าง ScdYear
  - Validate: year, created_date, is_published

UpdateScdYearRequest.php
  ฟังก์ชัน: Form Request Validation สำหรับอัปเดต ScdYear
```

---

### app/Livewire/

#### Backend/
**Livewire Full-Page Components สำหรับ Admin Panel**

> **หมายเหตุ:** ทุก component ใช้ `#[Layout('components.layouts.admin')]` เป็น full-page component

```
AdminDashboard.php
  View: livewire.backend.admin-dashboard
  Route: /admin/dashboard (Livewire full-page)
  ฟังก์ชัน: Dashboard หน้าหลัก Admin
  Methods:
    - mount()         : ส่ง event updateTitle
    - render()        : คำนวณสถิติ (จำนวนปี, เผยแพร่, รายงาน, แบนเนอร์)
  Stats:
    - total_years, published_years, total_reports, total_banners

YearsIndex.php
  View: livewire.backend.years-index
  Route: /admin/years (Livewire full-page)
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
  Route: /admin/reports/{year?} (Livewire full-page)
  ฟังก์ชัน: จัดการรายงาน SCD (PDF)
  Methods:
    - mount($year)    : โหลดรายงานตามปี
    - openCreateModal() : เปิด modal อัปโหลดรายงาน
    - store()         : อัปโหลดไฟล์ PDF
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบรายงาน
  Validation:
    - PDF only, max 100MB

BannersIndex.php
  View: livewire.backend.banners-index
  Route: /admin/banners/{year?} (Livewire full-page)
  ฟังก์ชัน: จัดการ Banner Slider
  Methods:
    - mount($year)    : โหลด banners ตามปี
    - openCreateModal() : เปิด modal สร้าง banner
    - store()         : บันทึก banner (อัปโหลดรูป + PDF)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดต banner
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบ banner
    - updateOrder()   : จัดลำดับการแสดง (sort modal)
  Features:
    - Image cropper สำหรับรูปภาพ
    - รองรับ link_type: none, url, pdf
    - Category: 0 (slider), 1 (อื่นๆ)

ContentsIndex.php
  View: livewire.backend.contents-index
  Route: /admin/contents/{year?} , /admin/contents/{year}/folder/{folderId}
  ฟังก์ชัน: จัดการ Content Sections (Livewire full-page)
  Methods:
    - mount($year, $folderId)  : โหลด contents ตามปี/โฟลเดอร์
    - openCreateModal() : เปิด modal สร้าง content
    - store()         : บันทึก content (อัปโหลดรูป)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดต content
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบ content
  Features:
    - โครงสร้าง tree (โฟลเดอร์/ไฟล์)
    - Breadcrumbs navigation
    - Sort modal (จัดลำดับ)
    - is_hidden toggle

AnnouncementsIndex.php
  View: livewire.backend.announcements-index
  Route: /admin/announcements/{year?} , /admin/directives/{year?}
         /admin/announcements/{year}/folder/{folderId}
         /admin/directives/{year}/folder/{folderId}
  ฟังก์ชัน: จัดการประกาศและคำสั่ง (Livewire full-page)
  Methods:
    - mount($year, $folderId)  : โหลดประกาศ/คำสั่งตามปี
    - openCreateModal() : เปิด modal สร้างประกาศ
    - store()         : บันทึกประกาศ (อัปโหลดไฟล์)
    - openEditModal() : เปิด modal แก้ไข
    - update()        : อัปเดตประกาศ
    - confirmDelete() : ยืนยันการลบ
    - delete()        : ลบประกาศ
  Features:
    - ใช้ route-based category switching (announcements vs directives)
    - โครงสร้าง tree (โฟลเดอร์/ไฟล์)
    - Breadcrumbs navigation
    - Sort modal (จัดลำดับ)
    - is_hidden toggle

AnnouncementManager.php
  View: livewire.backend.announcement-manager
  ฟังก์ชัน: Sub-component สำหรับ modal เพิ่ม/แก้ไขประกาศ/คำสั่ง
  - ใช้ภายใน AnnouncementsIndex

ContentSectionManager.php
  View: livewire.backend.content-section-manager
  ฟังก์ชัน: Sub-component สำหรับ modal เพิ่ม/แก้ไข content section
  - รองรับ image cropper + PDF upload
  - ใช้ภายใน ContentsIndex
```

#### Frontend/
**Livewire Components สำหรับหน้าบ้าน**

```
BannerSlider.php
  View: livewire.frontend.banner-slider
  ฟังก์ชัน: แสดง Banner Slider หน้าแรก
  Methods:
    - mount($year)    : โหลด banners ที่ category=0 ตามปี
    - render()        : แสดงผล slider
  Features:
    - Auto-slide (Alpine.js)
    - Navigation arrows (ซ้าย/ขวา)
    - Indicators (จุดด้านล่าง)
    - Responsive images
    - รองรับ link (internal/external/PDF)
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
  Route: GET /aru-scdur-panel (ซ่อน URL login)
  ฟังก์ชัน: ฟอร์มเข้าสู่ระบบ
  Methods:
    - login() : ใช้ LoginForm ตรวจสอบ email + password และ login
  Features:
    - Remember me
    - Throttle login attempts (ผ่าน LoginForm)
    - Redirect ไป admin.dashboard หลัง login สำเร็จ
```

#### Forms/
**Livewire Form Objects**

```
LoginForm.php
  ฟังก์ชัน: Form object สำหรับ Login
  Properties:
    - email (required, email)
    - password (required)
    - remember (boolean)
  Methods:
    - authenticate()         : ตรวจสอบ credentials + rate limiting
    - ensureIsNotRateLimited() : ป้องกัน brute force (max 5 attempts)
    - throttleKey()          : สร้าง key สำหรับ rate limiter
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
    - created_date (date)
    - is_published (boolean)
    - timestamps
  Relations:
    - hasMany(Banner)          : scd_banners
    - hasMany(Announcement)    : scd_announcements
    - hasMany(Order)           : scd_directives
    - hasMany(ContentSection)  : scd_contents
    - hasOne(ScdReport)        : scd_reports
  Accessors:
    - getYearNameAttribute()   : return 'SCD {year}'

Banner.php
  Table: scd_banners
  ฟังก์ชัน: Banner Slider + รูปภาพอื่นๆ
  Columns:
    - id, scd_year_id
    - category (integer: 0=slider, 1=อื่นๆ)
    - sequence (integer: ลำดับการแสดง)
    - image_path
    - link_type (none/url/pdf)
    - link_url (สำหรับ link_type=url)
    - pdf_name, pdf_path (สำหรับ link_type=pdf)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
  Features:
    - Sortable by sequence
    - Category-based grouping

Announcement.php
  Table: scd_announcements
  ฟังก์ชัน: ประกาศ (Announcements)
  Columns:
    - id, scd_year_id, parent_id
    - type (folder/file)
    - name, sequence, image_path, file_path
    - view_count, download_count
    - is_hidden (boolean)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
    - hasMany(self) as children : สำหรับโครงสร้าง tree
    - belongsTo(self) as parent
  Scopes:
    - scopeRootNodes()  : whereNull('parent_id')
    - scopeFolders()    : where('type', 'folder')
    - scopeFiles()      : where('type', 'file')
  Helpers:
    - isFolder(), isFile(), hasChildren()
  Features:
    - Hierarchical structure (tree)
    - Recursive allChildren() relation

Order.php
  Table: scd_directives
  ฟังก์ชัน: คำสั่ง (Directives)
  Columns:
    - id, scd_year_id, parent_id
    - type (folder/file)
    - name, sequence, image_path, file_path
    - view_count, download_count
    - is_hidden (boolean)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
    - hasMany(self) as children : สำหรับโครงสร้าง tree
    - belongsTo(self) as parent
  Scopes:
    - scopeRootNodes(), scopeFolders(), scopeFiles()
  Helpers:
    - isFolder(), isFile(), hasChildren()
  Features:
    - Hierarchical structure (tree)
    - Recursive allChildren() relation

ContentSection.php
  Table: scd_contents
  ฟังก์ชัน: ข้อมูล SCD ย่อย (SCD Contents)
  Columns:
    - id, scd_year_id, parent_id
    - type (folder/file)
    - name, sequence, image_path, file_path
    - view_count, download_count
    - is_hidden (boolean)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
    - hasMany(self) as children : สำหรับโครงสร้าง tree
    - belongsTo(self) as parent
  Scopes:
    - scopeRootNodes(), scopeFolders(), scopeFiles()
  Helpers:
    - isFolder(), isFile(), hasChildren()
  Features:
    - Hierarchical structure (tree)
    - Recursive allChildren() relation

ScdReport.php
  Table: scd_reports
  ฟังก์ชัน: ไฟล์รายงาน SCD (PDF)
  Columns:
    - id, scd_year_id
    - file_name (ชื่อไฟล์)
    - file_path (path ใน storage)
    - view_count (จำนวนการดู)
    - timestamps
  Relations:
    - belongsTo(ScdYear)
  Features:
    - One report per year

```

---

### app/Services/

```
FileUploadService.php
  ฟังก์ชัน: จัดการการอัปโหลดไฟล์
  Methods:
    - uploadImage($file, $directory)
      * รับ UploadedFile หรือ TemporaryUploadedFile
      * บันทึกใน storage/app/public/{$directory}
      * Return file path
    
    - uploadPdf($file, $directory)
      * บันทึก PDF ใน storage
      * Return file path
    
    - delete($path)
      * ลบไฟล์จาก storage (public disk)
      * Return boolean
    
    - replaceFile($newFile, $existingPath, $directory)
      * ลบไฟล์เดิม แล้วอัปโหลดไฟล์ใหม่
      * Return new file path
    
    - exists($path)
      * ตรวจสอบว่าไฟล์มีอยู่จริง
      * Return boolean
    
    - getUrl($path)
      * คืนค่า public URL ของไฟล์
      * Return URL string

BannerService.php
  ฟังก์ชัน: จัดการ Banner CRUD + ไฟล์
  Dependencies: FileUploadService
  Methods:
    - getBanners($year)
      * ดึง banners ของปีที่ระบุ เรียงตาม category, sequence
    
    - create($data, $imageFile, $pdfFile)
      * สร้าง banner ใหม่พร้อมอัปโหลดรูป/PDF
      * จัดการ link_type (none/url/pdf)
    
    - update($banner, $data, $imageFile, $pdfFile)
      * อัปเดต banner พร้อมเปลี่ยนรูป/PDF (ถ้ามี)
      * ลบไฟล์เก่าเมื่อเปลี่ยน link_type
    
    - delete($banner)
      * ลบ banner + ไฟล์รูป + ไฟล์ PDF
    
    - isValidCategory($category)
      * ตรวจสอบค่า category (0 หรือ 1)

ContentNodeService.php
  ฟังก์ชัน: จัดการ CRUD สำหรับ Announcement/Order/ContentSection (generic)
  Dependencies: FileUploadService
  Methods:
    - getModelClass($categoryGroup)
      * คืนค่า model class ตาม category: announcement, order, content_section
    
    - getNodes($year, $parentId, $categoryGroup)
      * ดึง nodes ของปีที่ระบุ ตาม parent
    
    - create($data, $imageFile, $pdfFile, $categoryGroup)
      * สร้าง node ใหม่ (folder หรือ file)
      * อัปโหลดรูปสำหรับ folder, PDF สำหรับ file
    
    - update($node, $data, $imageFile, $pdfFile)
      * อัปเดต node พร้อมเปลี่ยนไฟล์
    
    - delete($node)
      * ลบ node + ไฟล์ที่เกี่ยวข้อง
    
    - getBreadcrumbs($node)
      * สร้าง breadcrumb path จาก node ไปถึง root
    
    - getNextSequence($year, $parentId, $categoryGroup)
      * คืนค่า sequence ถัดไป
    
    - hasFolders($year, $parentId, $categoryGroup)
    - hasFiles($year, $parentId, $categoryGroup)
```

---

### app/Providers/

```
AppServiceProvider.php
  ฟังก์ชัน: Service Provider หลัก
  - register() : Register services
  - boot()     : Bootstrap services
    * Carbon Macro: toThaiDateTimeFull() - แปลงวันที่เป็น พ.ศ. + เวลา
    * Carbon Macro: toThaiDateFull() - แปลงวันที่เป็น พ.ศ. (ไม่มีเวลา)

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
  - ปี SCD พร้อมสถานะการเผยแพร่ (year, created_date, is_published)

2026_01_08_050547_create_scd_reports_table.php
  สร้างตาราง: scd_reports
  - ไฟล์รายงาน PDF แต่ละปี (scd_year_id, file_name, file_path)

2026_01_08_050551_create_banners_table.php
  สร้างตาราง: banners
  - Banner slider (scd_year_id, sequence, image_path, link_type, link_url, pdf_name, pdf_path)

2026_02_03_230028_add_title_to_banners_table.php
  เพิ่มคอลัมน์ title ให้ banners

2026_02_04_132952_add_view_count_to_scd_reports_table.php
  เพิ่มคอลัมน์ view_count ให้ scd_reports

2026_02_06_000001_split_content_nodes_into_separate_tables.php
  สร้างตาราง: announcements, orders, content_sections
  - แยกตาราง content_nodes เดิมออกเป็น 3 ตาราง
  - announcements: ประกาศ
  - orders: คำสั่ง
  - content_sections: ข้อมูล SCD ย่อย

2026_02_06_000002_rename_tables_with_scd_prefix.php
  เปลี่ยนชื่อตาราง:
  - announcements → scd_announcements
  - orders → scd_directives
  - content_sections → scd_contents
  - banners → scd_banners

2026_02_17_000001_replace_sequence_with_category_in_scd_banners.php
  - ลบ sequence จาก scd_banners
  - เพิ่ม category (0=slider, 1=อื่นๆ)

2026_02_17_100000_drop_title_from_scd_banners_table.php
  ลบคอลัมน์ title จาก scd_banners

2026_02_23_000001_add_sequence_to_scd_banners_and_drop_unique_constraints.php
  - เพิ่ม sequence กลับให้ scd_banners
  - ลบ unique constraints ใน 3 ตาราง content

2026_03_01_000001_add_is_hidden_to_announcements_directives_contents.php
  เพิ่มคอลัมน์ is_hidden (boolean, default: false) ให้:
  - scd_announcements
  - scd_directives
  - scd_contents
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

#### Layouts (components/layouts/)
**Layout Templates หลัก - ใช้เป็น Blade Component**

```
components/layouts/admin.blade.php
  ฟังก์ชัน: Layout สำหรับหลังบ้าน (Admin)
  ใช้โดย: <x-layouts.admin> หรือ #[Layout('components.layouts.admin')]
  Structure:
    - Sidebar navigation (Dashboard, จัดการปี, โปรไฟล์)
    - Header with user dropdown
    - Content area ({{ $slot }})
    - Notification system
  Features:
    - Responsive sidebar (Hamburger menu บน mobile)
    - User profile dropdown (3 จุด)
    - Gradient สีแดง
    - Logout functionality

components/layouts/guest.blade.php
  ฟังก์ชัน: Layout สำหรับหน้า authentication
  ใช้กับ: Login, Forgot Password
  ใช้โดย: <x-layouts.guest>
  Structure:
    - Centered card layout (พื้นหลังสีเทา)
    - Application logo
    - Simple design

components/layouts/frontend.blade.php
  ฟังก์ชัน: Layout สำหรับหน้าบ้าน
  ใช้โดย: <x-layouts.frontend title="..."> หรือ <x-layouts.frontend :title="$var">
  Props:
    - title: ชื่อหน้า (สำหรับ <title> tag)
  Structure:
    - <x-frontend.header> (banner + navigation)
    - {{ $slot }}
    - <x-frontend.footer>
  Features:
    - รับ $publishedYears สำหรับ dropdown เมนู
    - SEO-friendly structure
    - Livewire scripts รวมอยู่แล้ว
```

#### admin/pages/
**หน้า Admin แบบ Static (view เท่านั้น)**

> **หมายเหตุ:** หน้า Admin ส่วนใหญ่เป็น Livewire Full-Page Components
> มีเพียง profile ที่เป็น static view

```
profile.blade.php
  Route: /admin/profile
  ฟังก์ชัน: หน้าจัดการโปรไฟล์
  - <x-layouts.admin>
  - @livewire('profile.update-profile-information')
  - @livewire('profile.update-password')
  - @livewire('profile.delete-user')
```

#### frontend/pages/
**หน้าต่างๆ ของเว็บไซต์**

```
home.blade.php
  Route: / , /scd/{year}
  ฟังก์ชัน: หน้าแรก
  - <x-layouts.frontend title="...">
  - <livewire:frontend.banner-slider />
  - แสดง content sections (grid)
  - รองรับการเลือกดูตามปี
  ข้อมูลที่รับ: $activeYear, $publishedYears, $announcements, $contentSections, $isYearPage

about.blade.php
  Route: /about
  ฟังก์ชัน: เกี่ยวกับหน่วยงาน
  - <x-layouts.frontend title="เกี่ยวกับหน่วยงาน">

contact.blade.php
  Route: /contact
  ฟังก์ชัน: ติดต่อเรา
  - <x-layouts.frontend title="ติดต่อเรา">
  - แสดงที่อยู่ เบอร์โทร อีเมล
  - Google Maps (iframe)

announcements.blade.php
  Route: /announcements-directives/{year}
  ฟังก์ชัน: หน้าประกาศ/คำสั่งตามปี
  - ตาราง 2 หมวด: ประกาศ, คำสั่ง
  - ดาวน์โหลดไฟล์แนบ
  ข้อมูลที่รับ: $year, $announcements, $orders

content-section.blade.php
  Route: /scd/{year}/{slug}
  ฟังก์ชัน: หน้าแสดง content section ย่อย
  - Breadcrumb navigation
  - แสดงรายการไฟล์/โฟลเดอร์ในหมวด
  ข้อมูลที่รับ: $year, $section, $items

scd-report.blade.php
  Route: /scd-report/{year}/view/{filename}
  ฟังก์ชัน: หน้าดูรายงาน SCD
  - Gradient header
  - แสดงปี พ.ศ.
  - ปุ่มดาวน์โหลด PDF
  ข้อมูลที่รับ: $year, $report
```

#### components/

##### backend/
**Components สำหรับ Admin**

```
year-tabs.blade.php
  ฟังก์ชัน: แถบเลือกเมนูในแต่ละปี
  Props: selectedYear, currentPage
  - รายงาน, Banner, ประกาศ/คำสั่ง, เนื้อหา
  - Responsive (mobile-friendly)
  - Active state highlighting

modal.blade.php
  ฟังก์ชัน: Modal dialog (Livewire-based)
  Props: show, title, maxWidth, closeEvent
  - Alpine.js สำหรับ open/close

modal-alpine.blade.php
  ฟังก์ชัน: Modal dialog (Alpine.js-based)
  Props: name, show, maxWidth
  - เปิดผ่าน window event 'open-modal'
  - ปิดด้วย Escape / click outside

modal-form.blade.php
  ฟังก์ชัน: Modal พร้อมฟอร์ม + ปุ่ม Save/Cancel
  Props: title, submitLabel, cancelLabel, closeEvent
  - ปุ่ม Save สีแดง, Cancel สีเทา
  - Loading state (กำลังบันทึก...)

sort-modal.blade.php
  ฟังก์ชัน: Modal สำหรับ Drag & Drop จัดลำดับ
  Props: show, title, items (array of [id, label, sublabel, image]), maxWidth
  - ลากวางเพื่อจัดลำดับ sequence
  - ปุ่มบันทึก/ยกเลิก

action-button.blade.php
  ฟังก์ชัน: ปุ่ม Action แบบ multi-variant
  Props: color (gray/yellow/red/blue + outline variants), action, label, title, dispatch, href, confirm, target
  - SVG icons ตามสี
  - รองรับ confirm dialog
```

##### frontend/
**Components สำหรับหน้าบ้าน**

```
header.blade.php
  ฟังก์ชัน: Header ด้านบนสุด + Banner image
  Props: publishedYears
  - Top bar สีเหลือง + Banner image + Navigation

navigation.blade.php
  ฟังก์ชัน: Navbar หลัก (sticky)
  Props: publishedYears
  เมนู:
  - หน้าหลัก, เกี่ยวกับหน่วยงาน, SCD Rankings, รายงานผล SCD, ประกาศ/คำสั่ง, ติดต่อเรา
  Features:
    - Sticky top (สีแดง #af1a00)
    - Mobile hamburger menu
    - Dropdown แบบ hover (desktop) / click (mobile)

footer.blade.php
  ฟังก์ชัน: Footer 2 ส่วน
  - Footer 1 (ขาว): แนะนำหน่วยงาน, เอกสาร, ติดต่อ
  - Footer 2 (ดำ): Copyright

dropdown.blade.php
  ฟังก์ชัน: Dropdown menu container
  Props: title

dropdown-item.blade.php
  ฟังก์ชัน: รายการใน dropdown
  Props: href

nav-link.blade.php
  ฟังก์ชัน: ลิงก์ใน navbar
  Props: href, active

button.blade.php
  ฟังก์ชัน: ปุ่ม frontend
  Props: type, variant (primary/secondary/outline)

card.blade.php
  ฟังก์ชัน: Card แสดงเนื้อหา
  Props: image, title, description, link

section.blade.php
  ฟังก์ชัน: Section wrapper พร้อมหัวข้อ
  Props: title, subtitle

breadcrumb.blade.php
  ฟังก์ชัน: Breadcrumb navigation
  Props: items (array of [url, label])
  - แยกด้วย ">>"
  - Item สุดท้ายแสดงเป็นข้อความ (ไม่ใช่ลิงก์)

content-tree.blade.php
  ฟังก์ชัน: แสดงโครงสร้าง tree ของเอกสาร (recursive)
  Props: items, level (default 0), isTopLevel (default true)
  - Indent 24px ต่อ level
  - แสดง folder/file icons

section-header.blade.php
  ฟังก์ชัน: Hero header สำหรับ content section
  Props: title, image, year, itemCount, report, yearId

section-content.blade.php
  ฟังก์ชัน: กล่องแสดงรายการเนื้อหา
  Props: title, items, emptyTitle, emptyMessage, backUrl
  - แถบหัว้อสีแดง + document tree

related-sections.blade.php
  ฟังก์ชัน: Grid แสดงหมวดหมู่ที่เกี่ยวข้อง
  Props: sections (collection), title (default 'หมวดหมู่อื่นๆ')
  - Card รูปภาพ + ชื่อ พร้อมลิงก์
```

##### Shared Components
**Components ที่ใช้ร่วมกัน**

```
application-logo.blade.php
  ฟังก์ชัน: Logo ของแอพพลิเคชัน (SVG)

auth-session-status.blade.php
  ฟังก์ชัน: แสดงสถานะ session (success สีเขียว)
  Props: status

input-error.blade.php
  ฟังก์ชัน: แสดง validation error (สีแดง)
  Props: messages (array)

input-label.blade.php
  ฟังก์ชัน: Label สำหรับ input field
  Props: value

text-input.blade.php
  ฟังก์ชัน: Text input field พร้อม styling
  Props: disabled

primary-button.blade.php
  ฟังก์ชัน: ปุ่มหลัก (primary button)
  - พื้นหลังสีเทาเข้ม, ตัวอักษรสีขาว

notification.blade.php
  ฟังก์ชัน: แสดงการแจ้งเตือน popup กลางหน้าจอ
  - รับ event 'notify' จาก Livewire
  - แสดง 3 วินาที แล้วหายไป
  - รองรับ success/error types
  - Alpine.js based

image-cropper-simple.blade.php
  ฟังก์ชัน: Component อัปโหลดรูปภาพ พร้อม crop
  Props: name, label, required, existingImage, aspectRatio, outputWidth, outputHeight, helpText
  - ตั้ง aspect ratio ได้

responsive-nav-link.blade.php
  ฟังก์ชัน: Mobile responsive navigation link
  Props: active
  - แสดง active state ด้วย border สีน้ำเงิน
```

#### livewire/
**Blade views สำหรับ Livewire Components**

```
backend/
  admin-dashboard.blade.php    # Dashboard หน้าหลัก
  years-index.blade.php        # จัดการปี SCD
  reports-index.blade.php      # จัดการรายงาน
  banners-index.blade.php      # จัดการ banner
  contents-index.blade.php     # จัดการ content
  announcements-index.blade.php # จัดการประกาศ/คำสั่ง
  announcement-manager.blade.php # Sub-component: modal เพิ่ม/แก้ไขประกาศ
  partials/
    banners-table.blade.php    # ตารางแบนเนอร์ + ปุ่ม CRUD
    contents-table.blade.php   # ตารางเนื้อหา + โฟลเดอร์
    announcements-table.blade.php # ตารางประกาศ/คำสั่ง + โฟลเดอร์

frontend/
  banner-slider.blade.php      # Banner slider (auto-slide, arrows, indicators)

profile/
  update-profile-information.blade.php
  update-password.blade.php
  delete-user.blade.php

auth/
  login.blade.php             # หน้า login (Livewire)

pages/auth/ (Volt Components)
  forgot-password.blade.php   # (route ถูก comment ไว้)
  reset-password.blade.php    # (route ถูก comment ไว้)
  verify-email.blade.php      # (route ถูก comment ไว้)
  confirm-password.blade.php  # (route ถูก comment ไว้)
```

#### errors/
**Custom Error Pages**

```
403.blade.php   # Forbidden
404.blade.php   # Not Found
419.blade.php   # Page Expired
500.blade.php   # Internal Server Error
503.blade.php   # Service Unavailable
- Standalone HTML (ไม่ใช้ layout component)
- ใช้ Tailwind CDN
- ภาษาไทย
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
  - Import bootstrap.js

bootstrap.js
  ฟังก์ชัน: Bootstrap configuration
  - Axios setup
  - CSRF token
  หมายเหตุ: Alpine.js และ Livewire ถูก inject อัตโนมัติผ่าน Vite
```

---

## 📂 Routes Directory

```
web.php
  ฟังก์ชัน: Web routes ทั้งหมด

  Frontend:
    GET  /                                  -> HomeController@index
    GET  /scd/{year}                        -> HomeController@index (Year-specific)
    GET  /about                             -> FrontendController@about
    GET  /contact                           -> FrontendController@contact
    GET  /announcements-directives/{year}   -> FrontendController@announcements
    GET  /announcements-directives/{year}/folder/{folder} -> FrontendController@announcementFolder
    GET  /year/{year}/announcements-orders  -> AnnouncementOrderController@index
    GET  /scd/{year}/{section}              -> FrontendController@contentSection
    GET  /scd/{year}/{sectionId}/folder/{folder} -> FrontendController@contentSectionFolder

  File View/Download:
    GET  /file/{source}/{id}/view/{filename}  -> FrontendController@viewFile
    GET  /file/{source}/{id}/download         -> FrontendController@downloadFile
    GET  /banner/{id}/view/{filename}         -> FrontendController@viewBannerPdf
    GET  /scd-report/{year}/view/{filename}   -> FrontendController@viewScdReport
    GET  /scd-report/{year}/download          -> FrontendController@downloadScdReport

  Admin (admin.auth middleware → Livewire Full-Page Components):
    GET  /admin/dashboard       -> AdminDashboard::class
    GET  /admin/years           -> YearsIndex::class
    GET  /admin/reports/{year?} -> ReportsIndex::class
    GET  /admin/banners/{year?} -> BannersIndex::class
    GET  /admin/announcements/{year?}              -> AnnouncementsIndex::class
    GET  /admin/announcements/{year}/folder/{id}   -> AnnouncementsIndex::class
    GET  /admin/directives/{year?}                 -> AnnouncementsIndex::class
    GET  /admin/directives/{year}/folder/{id}      -> AnnouncementsIndex::class
    GET  /admin/contents/{year?}                   -> ContentsIndex::class
    GET  /admin/contents/{year}/folder/{id}        -> ContentsIndex::class
    GET  /admin/profile         -> admin.pages.profile (view)

  Other:
    GET  /login → redirect ไป /
    GET  /error-preview/{code} → error pages preview (auth middleware)

auth.php
  ฟังก์ชัน: Authentication routes
  - GET  /aru-scdur-panel → Login (URL ซ่อน)
  - POST /logout → Logout + redirect ไป login
  - หมายเหตุ: Forgot Password, Email Verification ถูก comment ไว้ (ยังไม่ตั้งค่า Mail)

console.php
  ฟังก์ชัน: Console routes
  - inspire command (default)
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
upload.php      # กำหนดค่าการอัปโหลดไฟล์ (custom)
  - max_file_sizes: ขนาดสูงสุดแต่ละประเภท (100MB ทั้งหมด)
  - allowed_mimes: ประเภทไฟล์ที่อนุญาต (image, pdf, document)
  - messages: ข้อความ validation ภาษาไทย
  - dimensions: ขนาดรูปภาพขั้นต่ำ (banner: 1200x400, cover: 800x600)
```

---

## 📂 Storage Directory

```
app/
  public/                # ไฟล์ที่เข้าถึงได้จาก public/storage
    banners/             # รูปภาพ banner
    banner-pdfs/         # ไฟล์ PDF ของ banner
    content-sections/    # รูปภาพ + ไฟล์ content sections
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
- 7 Models: User, ScdYear, Banner, Announcement, Order, ContentSection, ScdReport

### Controllers
- 3 Frontend Controllers (HomeController, FrontendController, AnnouncementOrderController)
- 1 Auth Controller (VerifyEmailController)
- ไม่มี Backend Controller (ใช้ Livewire Full-Page Components)

### Livewire Components
- 8 Backend Components (AdminDashboard, YearsIndex, ReportsIndex, BannersIndex, ContentsIndex, AnnouncementsIndex, AnnouncementManager, ContentSectionManager)
- 1 Frontend Component (BannerSlider)
- 3 Profile Components
- 1 Auth Component (Login)
- 1 Form Object (LoginForm)
- 1 Action (Logout)

### Middleware
- 2 Custom Middleware (AdminAuth, AccessLog)

### Services
- 3 Services: FileUploadService, BannerService, ContentNodeService

### Artisan Commands
- 3 Commands: admin:create, logs:clear, content:monitor

### Migrations
- 14 Migration files

### Routes
- 15+ Frontend routes (pages + file view/download)
- 12+ Admin routes (Livewire full-page)
- 2 Auth routes (login + logout)
- 5 Error preview routes

---

## 🔄 Data Flow

### 1. Frontend Request Flow
```
User → Route (web.php)
     → Controller (HomeController / FrontendController)
     → Load Models (Eloquent)
     → View (Blade + <x-layouts.frontend>)
     → Livewire Component (BannerSlider - ถ้ามี)
     → Browser
```

### 2. Admin CRUD Flow (Livewire Full-Page)
```
Admin → Route (admin.auth middleware)
      → Livewire Full-Page Component
      → Service (BannerService / ContentNodeService / FileUploadService)
      → Model (Eloquent)
      → Database
      → View Update (Livewire re-render)
```

### 3. File Upload Flow
```
Form → Livewire Component (Image Cropper / File Input)
    → FileUploadService (upload/replace)
    → Storage (storage/app/public/{directory})
    → Save path to Database
    → Return success + dispatch('notify')
```

### 4. File View/Download Flow (Frontend)
```
User คลิก → Route (file.view / file.download)
          → FrontendController (viewFile / downloadFile)
          → ค้นหา model (Announcement/Order/ContentSection)
          → นับ view_count / download_count (session-based)
          → Response::file() หรือ Response::download()
```

---

## 🎯 ฟีเจอร์สำคัญที่เสร็จแล้ว

### ✅ Frontend
- [x] Header + Navigation (responsive + sticky)
- [x] Banner Slider (Livewire + Alpine.js auto-slide)
- [x] Home page with Content Sections
- [x] Content Section detail pages (slug-based)
- [x] SCD Report view/download pages
- [x] About, Contact pages
- [x] Announcements/Directives pages
- [x] File view/download with count tracking
- [x] Breadcrumb navigation
- [x] Responsive design (mobile-friendly)
- [x] Custom error pages (403, 404, 419, 500, 503)

### ✅ Backend
- [x] Dashboard with stats
- [x] Years Management (CRUD + publish toggle)
- [x] Reports Management (PDF upload + view count)
- [x] Banners Management (Image cropper + sort + category)
- [x] Contents Management (Tree structure + folder navigation)
- [x] Announcements/Directives Management (Tree + sort + is_hidden)
- [x] Profile Management (Livewire)
- [x] Responsive admin layout
- [x] Sort modal (drag & drop reorder)

### ✅ Authentication
- [x] Login (Livewire + hidden URL)
- [x] Logout
- [x] Profile Management (Livewire)
- [x] No Public Registration
- [x] Admin auth middleware (404 on unauthorized)
- [ ] Email Verification (route ถูก comment ไว้)
- [ ] Password Reset (route ถูก comment ไว้)

### ✅ Infrastructure
- [x] Docker (Laravel Sail - PHP 8.4)
- [x] MySQL 8.4
- [x] Redis (Cache)
- [x] phpMyAdmin (Database Management)
- [x] Vite (Frontend bundler)
- [x] Tailwind CSS
- [x] Alpine.js
- [x] Livewire 3.x
- [x] Access logging middleware
- [x] Custom upload configuration (config/upload.php)

---

## 📝 หมายเหตุสำหรับนักพัฒนา

### Architecture Decisions
- **Livewire Full-Page Components:** หน้า Admin ทั้งหมดใช้ Livewire full-page components โดยตรง (ไม่ผ่าน Controller)
- **Volt:** ใช้เฉพาะ Auth pages ที่ไม่ค่อยแก้ไข (forgot-password, reset-password, verify-email, confirm-password)
- **<x-layouts.xxx>:** ใช้ Blade Component syntax สำหรับ layouts ทั้งหมด
- **Services:** ใช้ FileUploadService, BannerService, ContentNodeService แยก business logic
- **Hidden Admin URL:** Login URL ซ่อนไว้ที่ /aru-scdur-panel, /login redirect ไป /
- **Admin Auth:** ใช้ abort(404) แทน redirect ไป login เพื่อซ่อนหน้า admin

### File Storage
- **Storage Path:** `storage/app/public/`
- **Public URL:** ผ่าน symlink `public/storage/`
- **Command:** `sail artisan storage:link`
- **Directories:** banners/, banner-pdfs/, content-sections/, reports/, announcements/

### Responsive Design
- **Mobile First:** Tailwind responsive utilities
- **Breakpoints:** sm: 640px, md: 768px, lg: 1024px, xl: 1280px
- **Admin Tables:** ซ่อนคอลัมน์บางอันบน mobile
- **Navigation:** Hamburger menu บน mobile

### Database
- **Soft Deletes:** ไม่ได้ใช้ (ลบแบบ hard delete)
- **Timestamps:** ทุก model มี created_at, updated_at
- **Foreign Keys:** Cascade on delete
- **is_hidden:** รองรับการซ่อนรายการ (announcement, order, content_section)
- **Tree Structure:** ใช้ parent_id + type (folder/file) สำหรับโครงสร้างแบบ hierarchical

### Thai Date Support
- **Carbon Macros:** toThaiDateTimeFull(), toThaiDateFull()
- **แปลง ค.ศ. → พ.ศ.** อัตโนมัติ (+ 543)

---

**เอกสารนี้อธิบายโครงสร้างและฟังก์ชันของทุกไฟล์ในโปรเจกต์**  
**อัปเดตล่าสุด:** 4 มีนาคม 2026
