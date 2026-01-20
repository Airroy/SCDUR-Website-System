# 📁 คู่มือโครงสร้างโฟลเดอร์ Resources (ฉบับสมบูรณ์)

> **สำหรับนักพัฒนาใหม่** - อธิบายหน้าที่ของทุกไฟล์และโฟลเดอร์ใน `resources/`  
> **อัปเดตล่าสุด:** 20 มกราคม 2026

---

## 📂 ภาพรวมโครงสร้าง

```
resources/
├── css/                        # ไฟล์ CSS
│   └── app.css                 # Tailwind CSS หลัก
├── js/                         # ไฟล์ JavaScript  
│   ├── app.js                  # JS หลัก
│   └── bootstrap.js            # ตั้งค่า Axios
└── views/                      # Blade Templates
    ├── admin/pages/            # หน้า Admin แบบ static
    ├── components/             # Components ที่ใช้ซ้ำได้
    │   ├── backend/            # Components สำหรับ Admin
    │   └── frontend/           # Components สำหรับหน้าบ้าน
    ├── frontend/pages/         # หน้าเว็บสำหรับผู้เยี่ยมชม
    ├── layouts/                # Layout หลัก (โครงร่างหน้า)
    ├── livewire/               # Livewire Components (interactive)
    │   ├── auth/               # Authentication
    │   ├── backend/            # Admin Components
    │   ├── frontend/           # Frontend Components
    │   └── profile/            # Profile Components
    └── pages/auth/             # Volt Pages (Auth)
```

---

# 📁 CSS - ไฟล์ Styles

## `css/app.css`

**หน้าที่:** ไฟล์ CSS หลักของโปรเจกต์

**เนื้อหา:**
```css
@tailwind base;       /* Tailwind base styles */
@tailwind components; /* Tailwind component classes */
@tailwind utilities;  /* Tailwind utility classes */

/* Custom utility: ซ่อน scrollbar */
.scrollbar-hide { ... }
```

**วิธีแก้ไข:**
- เพิ่ม custom CSS ต่อท้ายไฟล์นี้
- ใช้ `@layer utilities` สำหรับ utility classes ใหม่

---

# 📁 JS - ไฟล์ JavaScript

## `js/app.js`

**หน้าที่:** ไฟล์ JavaScript หลัก - entry point

**เนื้อหา:**
```javascript
import './bootstrap';
```

**หมายเหตุ:** Alpine.js และ Livewire ถูก inject อัตโนมัติผ่าน Vite

---

## `js/bootstrap.js`

**หน้าที่:** ตั้งค่า Axios สำหรับ AJAX requests

**เนื้อหา:**
```javascript
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

**ใช้เมื่อ:** ต้องการเรียก API ด้วย Axios

---

# 📁 LAYOUTS - โครงร่างหน้า

> **Layout** คือ "กรอบ" ของหน้าเว็บ มี header, footer, sidebar ที่ใช้ร่วมกัน

## `layouts/admin.blade.php`

**หน้าที่:** Layout สำหรับหลังบ้าน (Admin Panel)

**โครงสร้าง:**
```
┌─────────────────────────────────────┐
│  🍔  SCD System      👤 Admin ▾    │ ← Header
├───────┬─────────────────────────────┤
│       │                             │
│ เมนู  │      เนื้อหา                 │
│       │      @yield('content')      │
│       │                             │
└───────┴─────────────────────────────┘
  Sidebar        Main Content
```

**ฟีเจอร์:**
- Sidebar พร้อมเมนู (Dashboard, จัดการปี, โปรไฟล์)
- Header พร้อม user dropdown (3 จุด)
- Responsive (Hamburger menu บน mobile)
- Gradient สีแดง

**ใช้โดย:** ทุกหน้า Admin (Dashboard, Years, Banners, etc.)

**วิธีใช้:**
```blade
@extends('layouts.admin')
@section('content')
    <h1>เนื้อหา</h1>
@endsection
```

---

## `layouts/frontend.blade.php`

**หน้าที่:** Layout สำหรับหน้าบ้าน (Public)

**โครงสร้าง:**
```
┌─────────────────────────────────────┐
│      <x-frontend.header />          │ ← Top bar + Banner image
├─────────────────────────────────────┤
│      <x-frontend.navigation />      │ ← เมนู Navbar (sticky)
├─────────────────────────────────────┤
│                                     │
│         @yield('content')           │ ← เนื้อหาแต่ละหน้า
│                                     │
├─────────────────────────────────────┤
│      <x-frontend.footer />          │ ← Footer
└─────────────────────────────────────┘
```

**ฟีเจอร์:**
- รับ `$publishedYears` สำหรับ dropdown เมนู
- `@yield('title')` สำหรับ page title
- Livewire scripts รวมอยู่แล้ว

**ใช้โดย:** หน้าแรก, เกี่ยวกับ, ติดต่อ, ประกาศ

---

## `layouts/guest.blade.php`

**หน้าที่:** Layout สำหรับหน้า Authentication (ไม่ต้อง login)

**โครงสร้าง:**
```
┌─────────────────────────────────────┐
│                                     │
│              [Logo]                 │
│         ┌───────────────┐           │
│         │    ฟอร์ม      │           │ ← Card กลางหน้า
│         │   Login       │           │
│         └───────────────┘           │
│                                     │
└─────────────────────────────────────┘
```

**ฟีเจอร์:**
- พื้นหลังสีเทา
- Card สีขาวกลางหน้า
- โลโก้ด้านบน

**ใช้โดย:** Login, Forgot Password, Reset Password, Verify Email

---

# 📁 COMPONENTS - ชิ้นส่วน UI ที่ใช้ซ้ำ

> **Component** คือชิ้นส่วน UI เล็กๆ ที่เรียกใช้ได้หลายที่ด้วย `<x-component-name />`

## 🔧 Components ทั่วไป

### `components/application-logo.blade.php`

**หน้าที่:** แสดงโลโก้ Laravel (SVG)

**การใช้งาน:**
```blade
<x-application-logo class="w-20 h-20" />
```

---

### `components/auth-session-status.blade.php`

**หน้าที่:** แสดงข้อความ success (สีเขียว)

**Props:** `status` - ข้อความที่จะแสดง

**การใช้งาน:**
```blade
<x-auth-session-status :status="session('status')" />
```

**ผลลัพธ์:** แสดงข้อความสีเขียวถ้ามี status

---

### `components/input-error.blade.php`

**หน้าที่:** แสดง validation error (สีแดง)

**Props:** `messages` - array ของ error messages

**การใช้งาน:**
```blade
<x-input-error :messages="$errors->get('email')" />
```

**ผลลัพธ์:**
```
• อีเมลไม่ถูกต้อง
• อีเมลนี้มีอยู่แล้ว
```

---

### `components/input-label.blade.php`

**หน้าที่:** Label สำหรับ input field

**Props:** `value` - ข้อความ label

**การใช้งาน:**
```blade
<x-input-label for="email" value="อีเมล" />
<!-- หรือ -->
<x-input-label for="email">อีเมล</x-input-label>
```

---

### `components/text-input.blade.php`

**หน้าที่:** Input field พร้อม styling

**Props:** `disabled` - ปิดการใช้งาน (default: false)

**การใช้งาน:**
```blade
<x-text-input type="email" name="email" wire:model="email" />
```

**Styling:** Border สีเทา, focus สีน้ำเงิน

---

### `components/primary-button.blade.php`

**หน้าที่:** ปุ่มหลัก (สีเทาเข้ม)

**การใช้งาน:**
```blade
<x-primary-button>บันทึก</x-primary-button>
```

**Styling:** พื้นหลังสีเทาเข้ม, hover สีเทาอ่อน, ตัวอักษรสีขาว

---

### `components/notification.blade.php`

**หน้าที่:** แสดงการแจ้งเตือน popup กลางหน้าจอ

**ฟีเจอร์:**
- รับ event `notify` จาก Livewire
- แสดง 3 วินาทีแล้วหายไป
- รองรับ success/error types
- Alpine.js based

**การใช้งาน:**
```blade
<!-- ใส่ใน layout -->
<x-notification />

<!-- ส่ง event จาก Livewire -->
$this->dispatch('notify', message: 'บันทึกสำเร็จ!', type: 'success');
```

---

## 🔧 Components Backend (`components/backend/`)

### `backend/modal.blade.php`

**หน้าที่:** Modal dialog (popup) พื้นฐาน

**Props:**
- `show` (boolean) - แสดง/ซ่อน modal
- `title` (string) - หัวข้อ
- `maxWidth` (string) - ขนาด: sm, md, lg, xl, 2xl
- `closeEvent` (string) - ชื่อ property ที่จะ toggle เมื่อปิด

**การใช้งาน:**
```blade
<x-backend.modal :show="$showModal" maxWidth="lg">
    <div class="p-6">
        เนื้อหา modal
    </div>
</x-backend.modal>
```

---

### `backend/modal-form.blade.php`

**หน้าที่:** Modal พร้อมฟอร์ม + ปุ่ม Save/Cancel

**Props:**
- `title` (string) - หัวข้อ
- `submitLabel` (string) - ข้อความปุ่ม submit (default: บันทึก)
- `cancelLabel` (string) - ข้อความปุ่ม cancel (default: ยกเลิก)
- `closeEvent` (string) - property ที่จะ toggle เมื่อปิด

**การใช้งาน:**
```blade
<x-backend.modal :show="$showModal">
    <x-backend.modal-form wire:submit.prevent="save" title="เพิ่มข้อมูล">
        <input type="text" wire:model="name" />
    </x-backend.modal-form>
</x-backend.modal>
```

**ฟีเจอร์:**
- ปุ่ม Save สีแดง
- ปุ่ม Cancel สีเทา
- Loading state (กำลังบันทึก...)

---

### `backend/year-tabs.blade.php`

**หน้าที่:** แถบเมนูย่อยในแต่ละปี

**Props:**
- `selectedYear` - object ปีที่เลือก
- `currentPage` - หน้าปัจจุบัน (reports, banners, announcements, contents)

**แสดงผล:**
```
┌──────────┬──────────┬──────────────┬──────────┐
│ 📄รายงาน │ 🖼️รูปสไลด์│ 📢ประกาศ/คำสั่ง │ 📦เนื้อหา  │
└──────────┴──────────┴──────────────┴──────────┘
```

**การใช้งาน:**
```blade
<x-backend.year-tabs :selectedYear="$selectedYear" currentPage="banners" />
```

---

## 🌐 Components Frontend (`components/frontend/`)

### `frontend/header.blade.php`

**หน้าที่:** Header ด้านบนสุด + Banner image

**Props:** `publishedYears` - รายการปีที่เผยแพร่ (ส่งไป navigation)

**โครงสร้าง:**
```
┌─────────────────────────────────────┐
│ Phranakhon Si Ayutthaya Rajabhat... │ ← Top bar สีเหลือง
├─────────────────────────────────────┤
│         [Header Banner Image]       │ ← รูปภาพ header
├─────────────────────────────────────┤
│      <x-frontend.navigation />      │ ← เมนู
└─────────────────────────────────────┘
```

---

### `frontend/navigation.blade.php`

**หน้าที่:** Navbar หลัก (sticky top)

**Props:** `publishedYears` - รายการปีสำหรับ dropdown

**เมนู:**
- หน้าหลัก
- เกี่ยวกับหน่วยงาน
- SCD Rankings ▾ (dropdown)
- รายงานผล SCD ▾ (dropdown)
- ประกาศ/คำสั่ง ▾ (dropdown)
- ติดต่อเรา

**ฟีเจอร์:**
- Sticky top (ติดด้านบนเมื่อ scroll)
- พื้นหลังสีแดง (#af1a00)
- Hover effect สีชมพู (#ff9f8e)
- Mobile hamburger menu
- Dropdown แบบ hover (desktop) / click (mobile)

---

### `frontend/footer.blade.php`

**หน้าที่:** Footer 2 ส่วน

**โครงสร้าง:**
```
┌─────────────────────────────────────────────────┐
│ แนะนำหน่วยงาน │ เอกสารประชาสัมพันธ์ │ ติดต่อเรา  │ ← Footer 1 (ขาว)
├─────────────────────────────────────────────────┤
│    Copyright © ... Designed By ...             │ ← Footer 2 (ดำ)
└─────────────────────────────────────────────────┘
```

**ข้อมูล:**
- ที่อยู่มหาวิทยาลัย
- อีเมล: ISC@aru.ac.th
- โทร: 0-3532-2589

---

### `frontend/dropdown.blade.php`

**หน้าที่:** Dropdown menu container

**Props:** `title` - ข้อความที่แสดง

**การใช้งาน:**
```blade
<x-frontend.dropdown title="เมนู">
    <x-frontend.dropdown-item href="/page1">หน้า 1</x-frontend.dropdown-item>
    <x-frontend.dropdown-item href="/page2">หน้า 2</x-frontend.dropdown-item>
</x-frontend.dropdown>
```

---

### `frontend/dropdown-item.blade.php`

**หน้าที่:** รายการใน dropdown

**Props:** `href` - URL ปลายทาง

**การใช้งาน:**
```blade
<x-frontend.dropdown-item href="/about">เกี่ยวกับ</x-frontend.dropdown-item>
```

---

### `frontend/nav-link.blade.php`

**หน้าที่:** ลิงก์ใน navbar

**Props:**
- `href` - URL ปลายทาง
- `active` (boolean) - เป็นหน้าปัจจุบันหรือไม่

**การใช้งาน:**
```blade
<x-frontend.nav-link href="/" :active="request()->is('/')">หน้าหลัก</x-frontend.nav-link>
```

---

### `frontend/button.blade.php`

**หน้าที่:** ปุ่มสำหรับ frontend

**Props:**
- `type` - button/submit (default: button)
- `variant` - primary/secondary/outline

**การใช้งาน:**
```blade
<x-frontend.button variant="primary">คลิก</x-frontend.button>
<x-frontend.button variant="outline">ยกเลิก</x-frontend.button>
```

---

### `frontend/card.blade.php`

**หน้าที่:** Card สำหรับแสดงเนื้อหา

**Props:**
- `image` - URL รูปภาพ
- `title` - หัวข้อ
- `description` - รายละเอียด
- `link` - URL เมื่อคลิก

**การใช้งาน:**
```blade
<x-frontend.card 
    image="/images/sample.jpg"
    title="หัวข้อ"
    description="รายละเอียด..."
    link="/detail"
/>
```

---

### `frontend/section.blade.php`

**หน้าที่:** Section wrapper พร้อมหัวข้อ

**Props:**
- `title` - หัวข้อหลัก
- `subtitle` - หัวข้อรอง

**การใช้งาน:**
```blade
<x-frontend.section title="ข่าวสาร" subtitle="อัพเดทล่าสุด">
    <div>เนื้อหา...</div>
</x-frontend.section>
```

---

# 📁 FRONTEND PAGES - หน้าเว็บสำหรับผู้เยี่ยมชม

## `frontend/pages/home.blade.php`

**Route:** `GET /` หรือ `GET /?year={id}`

**หน้าที่:** หน้าแรกของเว็บไซต์

**เนื้อหา:**
1. Banner Slider (`<livewire:frontend.banner-slider />`)
2. ARU-SCD [ปี] Banner (แถบสีแดง)
3. Content Sections Grid (4 คอลัมน์)

**ข้อมูลที่รับ:**
- `$activeYear` - ปีที่เลือก
- `$contentSections` - เนื้อหาของปีนั้น
- `$publishedYears` - รายการปี (ส่งไป layout)

---

## `frontend/pages/about.blade.php`

**Route:** `GET /about`

**หน้าที่:** หน้าเกี่ยวกับหน่วยงาน

**เนื้อหา:** (ยังว่าง - "ยังไม่มีข้อมูล")

**สิ่งที่ต้องเพิ่ม:** ข้อมูลหน่วยงาน, ประวัติ, วิสัยทัศน์

---

## `frontend/pages/contact.blade.php`

**Route:** `GET /contact`

**หน้าที่:** หน้าติดต่อเรา

**เนื้อหา:**
- ข้อมูลติดต่อ (ที่อยู่, อีเมล, โทร)
- Google Maps (iframe)

---

## `frontend/pages/announcements.blade.php`

**Route:** `GET /announcements/{year}`

**หน้าที่:** หน้าประกาศ/คำสั่งตามปี

**เนื้อหา:**
- Breadcrumb
- ตารางประกาศ (ลำดับ, ชื่อ, ดาวน์โหลด)
- ตารางคำสั่ง (ลำดับ, ชื่อ, ดาวน์โหลด)

**ข้อมูลที่รับ:**
- `$year` - ปีที่เลือก
- `$announcements` - รายการประกาศ
- `$orders` - รายการคำสั่ง

---

# 📁 ADMIN PAGES - หน้า Admin แบบ Static

## `admin/pages/profile.blade.php`

**Route:** `GET /admin/profile`

**หน้าที่:** หน้าจัดการโปรไฟล์

**เนื้อหา:**
```blade
@livewire('profile.update-profile-information')  <!-- แก้ไขชื่อ/อีเมล -->
@livewire('profile.update-password')              <!-- เปลี่ยนรหัสผ่าน -->
@livewire('profile.delete-user')                  <!-- ลบบัญชี -->
```

---

# 📁 LIVEWIRE - Components แบบ Interactive

> **Livewire** = PHP + JavaScript ทำให้หน้าเว็บ interactive โดยไม่ต้องเขียน JS มาก  
> ไฟล์ใน `livewire/` จะถูกจับคู่กับ class ใน `app/Livewire/`

## 🔐 `livewire/auth/` - Authentication

### `auth/login.blade.php`

**Component:** `App\Livewire\Auth\Login`

**หน้าที่:** ฟอร์ม Login

**ฟิลด์:**
- อีเมล
- รหัสผ่าน
- Remember me (checkbox)
- ลิงก์ Forgot Password

**การทำงาน:**
1. กรอกอีเมล + รหัสผ่าน
2. กด Log in
3. ตรวจสอบ → redirect ไป Dashboard

---

## 🔧 `livewire/backend/` - Admin Components

### `backend/admin-dashboard.blade.php`

**Component:** `App\Livewire\Backend\AdminDashboard`

**หน้าที่:** Dashboard หน้าหลัก Admin

**แสดง:**
- 4 Stat Cards:
  - จำนวนปี
  - เผยแพร่แล้ว
  - รายงาน
  - ประกาศ

---

### `backend/years-index.blade.php`

**Component:** `App\Livewire\Backend\YearsIndex`

**Route:** `/admin/years`

**หน้าที่:** จัดการปี SCD

**ฟีเจอร์:**
- ตารางแสดงปีทั้งหมด (ปี, วันที่, สถานะ)
- ปุ่ม "เพิ่มปีใหม่"
- ปุ่มเปิด/ปิดเผยแพร่
- ปุ่มจัดการข้อมูล → ไปหน้า reports
- ปุ่มแก้ไข/ลบ

**Modal:**
- สร้างปีใหม่ (ปี ค.ศ., วันที่, เผยแพร่)
- แก้ไขปี

---

### `backend/reports-index.blade.php`

**Component:** `App\Livewire\Backend\ReportsIndex`

**Route:** `/admin/years/{year}/reports`

**หน้าที่:** จัดการรายงาน PDF

**ฟีเจอร์:**
- Year tabs (รายงาน | รูปสไลด์ | ประกาศ/คำสั่ง | เนื้อหา)
- ตารางรายงาน (ชื่อ, ไฟล์ PDF, อัปเดต)
- ปุ่มเพิ่มรายงาน
- ปุ่มแก้ไข/ลบ
- ดูไฟล์ PDF

---

### `backend/banners-index.blade.php`

**Component:** `App\Livewire\Backend\BannersIndex`

**Route:** `/admin/years/{year}/banners`

**หน้าที่:** จัดการ Banner Slider

**ฟีเจอร์:**
- Year tabs
- Include `partials/banners-table.blade.php`
- Modal เพิ่ม/แก้ไข banner:
  - ลำดับที่
  - อัปโหลดรูปภาพ
  - ประเภทลิงก์ (ไม่มี/URL/PDF)
  - Preview รูป

---

### `backend/contents-index.blade.php`

**Component:** `App\Livewire\Backend\ContentsIndex`

**Route:** `/admin/years/{year}/contents`

**หน้าที่:** จัดการ Content Sections

**ฟีเจอร์:**
- Year tabs
- Breadcrumbs (นำทางโฟลเดอร์)
- Include `partials/contents-table.blade.php`
- รองรับโครงสร้าง tree (โฟลเดอร์ซ้อนกัน)

---

### `backend/announcements-index.blade.php`

**Component:** `App\Livewire\Backend\AnnouncementsIndex`

**Route:** `/admin/years/{year}/announcements`

**หน้าที่:** จัดการประกาศ/คำสั่ง

**ฟีเจอร์:**
- Year tabs
- แถบสลับ: ประกาศ | คำสั่ง
- Breadcrumbs
- Include `partials/announcements-table.blade.php`
- รองรับโครงสร้าง tree (โฟลเดอร์ซ้อนกัน)

---

### `backend/content-section-manager.blade.php`

**หน้าที่:** Modal สำหรับเพิ่ม/แก้ไข content section

---

### `backend/announcement-manager.blade.php`

**หน้าที่:** Modal สำหรับเพิ่ม/แก้ไขประกาศ

---

### Partials (`backend/partials/`)

| ไฟล์ | หน้าที่ |
|------|---------|
| `banners-table.blade.php` | ตารางแสดง banners + ปุ่มเพิ่ม/แก้ไข/ลบ |
| `contents-table.blade.php` | ตารางแสดง contents + โฟลเดอร์ |
| `announcements-table.blade.php` | ตารางแสดงประกาศ/คำสั่ง + โฟลเดอร์ |

---

## 🌐 `livewire/frontend/` - Frontend Components

### `frontend/banner-slider.blade.php`

**Component:** `App\Livewire\Frontend\BannerSlider`

**หน้าที่:** Slider รูปภาพหน้าแรก

**ฟีเจอร์:**
- Auto-slide (เลื่อนอัตโนมัติ)
- Navigation arrows (ซ้าย/ขวา)
- Indicators (จุดด้านล่าง)
- Transition effects
- รองรับลิงก์ (internal/external)
- Alpine.js based

**การใช้งาน:**
```blade
<livewire:frontend.banner-slider />
```

---

## 👤 `livewire/profile/` - จัดการโปรไฟล์

### `profile/update-profile-information.blade.php`

**Component:** `App\Livewire\Profile\UpdateProfileInformation`

**หน้าที่:** แก้ไขชื่อและอีเมล

**ฟิลด์:**
- ชื่อ
- อีเมล
- ปุ่มส่งอีเมลยืนยันใหม่ (ถ้ายังไม่ verified)

---

### `profile/update-password.blade.php`

**Component:** `App\Livewire\Profile\UpdatePassword`

**หน้าที่:** เปลี่ยนรหัสผ่าน

**ฟิลด์:**
- รหัสผ่านปัจจุบัน
- รหัสผ่านใหม่
- ยืนยันรหัสผ่าน

---

### `profile/delete-user.blade.php`

**Component:** `App\Livewire\Profile\DeleteUser`

**หน้าที่:** ลบบัญชีผู้ใช้

**ฟีเจอร์:**
- ปุ่ม "ลบบัญชี"
- Modal ยืนยัน (ต้องใส่รหัสผ่าน)
- ลบแล้ว logout อัตโนมัติ

---

# 📁 PAGES (VOLT) - Auth Pages

> **Volt** = Livewire แบบ single-file (PHP + Blade อยู่ไฟล์เดียว)  
> ใช้สำหรับหน้าที่ไม่ค่อยแก้ไข

## `pages/auth/forgot-password.blade.php`

**Route:** `GET /forgot-password`

**หน้าที่:** ฟอร์มขอรีเซ็ตรหัสผ่าน

**การทำงาน:**
1. กรอกอีเมล
2. กดส่ง
3. ระบบส่ง email พร้อมลิงก์รีเซ็ต

---

## `pages/auth/reset-password.blade.php`

**Route:** `GET /reset-password/{token}`

**หน้าที่:** ฟอร์มตั้งรหัสผ่านใหม่

**การทำงาน:**
1. คลิกลิงก์จากอีเมล
2. กรอกรหัสผ่านใหม่ + ยืนยัน
3. กดบันทึก → redirect ไป login

---

## `pages/auth/verify-email.blade.php`

**Route:** `GET /verify-email`

**หน้าที่:** หน้ายืนยันอีเมล

**แสดง:**
- ข้อความ "กรุณายืนยันอีเมล"
- ปุ่มส่งอีเมลยืนยันอีกครั้ง
- ปุ่ม Log Out

---

## `pages/auth/confirm-password.blade.php`

**Route:** `GET /confirm-password`

**หน้าที่:** ยืนยันรหัสผ่านก่อนทำ action สำคัญ

**การทำงาน:**
1. แสดงก่อนเข้าหน้าที่ต้องความปลอดภัยสูง
2. กรอกรหัสผ่าน → ยืนยัน → ดำเนินการต่อ

---

# 🔄 วิธีหาไฟล์จาก URL/Component

## จาก URL

| URL | ไฟล์ |
|-----|------|
| `/` | `frontend/pages/home.blade.php` |
| `/about` | `frontend/pages/about.blade.php` |
| `/contact` | `frontend/pages/contact.blade.php` |
| `/announcements/1` | `frontend/pages/announcements.blade.php` |
| `/login` | `livewire/auth/login.blade.php` |
| `/admin/dashboard` | `livewire/backend/admin-dashboard.blade.php` |
| `/admin/years` | `livewire/backend/years-index.blade.php` |
| `/admin/years/2026/reports` | `livewire/backend/reports-index.blade.php` |
| `/admin/years/2026/banners` | `livewire/backend/banners-index.blade.php` |
| `/admin/years/2026/contents` | `livewire/backend/contents-index.blade.php` |
| `/admin/years/2026/announcements` | `livewire/backend/announcements-index.blade.php` |
| `/admin/profile` | `admin/pages/profile.blade.php` |

## จาก Component Syntax

| ใน Blade เห็น... | ไปที่ไฟล์... |
|-----------------|-------------|
| `<x-frontend.header />` | `components/frontend/header.blade.php` |
| `<x-backend.modal />` | `components/backend/modal.blade.php` |
| `@livewire('backend.years-index')` | `livewire/backend/years-index.blade.php` |
| `<livewire:frontend.banner-slider />` | `livewire/frontend/banner-slider.blade.php` |
| `@extends('layouts.admin')` | `layouts/admin.blade.php` |

---

# 💡 เคล็ดลับการแก้ไข

## ต้องการแก้ไข... → ไปที่ไฟล์...

| สิ่งที่ต้องการแก้ | ไฟล์ที่ต้องไป |
|-----------------|--------------|
| สี Sidebar Admin | `layouts/admin.blade.php` |
| เมนู Navbar หน้าบ้าน | `components/frontend/navigation.blade.php` |
| Footer | `components/frontend/footer.blade.php` |
| Banner Slider | `livewire/frontend/banner-slider.blade.php` |
| หน้าแรก | `frontend/pages/home.blade.php` |
| หน้าติดต่อ | `frontend/pages/contact.blade.php` |
| Dashboard สถิติ | `livewire/backend/admin-dashboard.blade.php` |
| ตาราง Years | `livewire/backend/years-index.blade.php` |
| ตาราง Banners | `livewire/backend/partials/banners-table.blade.php` |
| ฟอร์ม Login | `livewire/auth/login.blade.php` |
| Modal style | `components/backend/modal.blade.php` |
| ปุ่มหลัก | `components/primary-button.blade.php` |
| Input field | `components/text-input.blade.php` |

---

## คำสั่งที่มีประโยชน์

```bash
# ดู routes ทั้งหมด
./vendor/bin/sail artisan route:list

# ดู Livewire components ทั้งหมด
./vendor/bin/sail artisan livewire:discover

# Clear view cache (หลังแก้ไข blade)
./vendor/bin/sail artisan view:clear
```

---

**เอกสารนี้อธิบายทุกไฟล์ใน `resources/` โฟลเดอร์**  
**อัปเดตล่าสุด:** 20 มกราคม 2026
