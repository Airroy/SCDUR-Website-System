# โครงสร้างโฟลเดอร์ Views

## 📁 โครงสร้างหลัก

```
resources/views/
├── frontend/           ← หน้าบ้าน (Public)
│   ├── layouts/
│   │   └── app.blade.php
│   ├── components/
│   │   ├── section.blade.php
│   │   ├── card.blade.php
│   │   └── button.blade.php
│   └── pages/
│       ├── home.blade.php
│       └── welcome.blade.php
│
├── admin/              ← หน้าแอดมิน
│   ├── layouts/
│   │   └── admin.blade.php
│   ├── components/
│   │   ├── card-item.blade.php
│   │   ├── modal.blade.php
│   │   └── ...
│   └── pages/
│       ├── dashboard.blade.php
│       └── scd-years/
│
├── auth/               ← หน้า Authentication
│   ├── layouts/
│   │   ├── app.blade.php (logged in)
│   │   └── guest.blade.php (guest)
│   └── pages/
│
├── components/         ← Shared Components (Laravel Default)
│   ├── input-label.blade.php
│   ├── text-input.blade.php
│   └── ...
│
└── livewire/
    ├── frontend/       ← Livewire Components สำหรับหน้าบ้าน
    │   └── banner-slider.blade.php
    ├── backend/        ← Livewire Components สำหรับแอดมิน
    │   ├── banner-manager.blade.php
    │   ├── content-section-manager.blade.php
    │   └── ...
    ├── layout/
    │   └── navigation.blade.php
    └── pages/
        └── auth/
```

## 🎯 หน้าที่แต่ละโฟลเดอร์

### Frontend (หน้าบ้าน)
- **Path:** `resources/views/frontend/`
- **ใช้สำหรับ:** หน้าเว็บที่ผู้ใช้ทั่วไปเข้าถึงได้
- **ตัวอย่าง:** หน้าแรก, รายการประกาศ, หมวดหมู่เนื้อหา
- **การใช้งาน:** `view('frontend.pages.home')`

### Admin (หน้าแอดมิน)
- **Path:** `resources/views/admin/`
- **ใช้สำหรับ:** หน้าจัดการระบบสำหรับผู้ดูแล
- **ตัวอย่าง:** Dashboard, จัดการปี SCD, จัดการ Banner
- **การใช้งาน:** `view('admin.pages.dashboard')`

### Auth (หน้า Authentication)
- **Path:** `resources/views/auth/`
- **ใช้สำหรับ:** หน้าเกี่ยวกับการยืนยันตัวตน
- **ตัวอย่าง:** Login, Register, Profile
- **การใช้งาน:** `view('auth.layouts.guest')`

### Livewire Components
- **Frontend:** `resources/views/livewire/frontend/`
- **Backend:** `resources/views/livewire/backend/`
- **ใช้สำหรับ:** Components แบบ dynamic/interactive
- **การใช้งาน:** `<livewire:frontend.banner-slider />`

## 📝 ตัวอย่างการใช้งาน

### 1. สร้างหน้าใหม่ใน Frontend
```php
// Controller
return view('frontend.pages.about');

// Blade file location
resources/views/frontend/pages/about.blade.php
```

### 2. ใช้ Component ใน Frontend
```blade
<x-frontend.components.card 
    title="ชื่อการ์ด"
    description="รายละเอียด"
/>
```

### 3. ใช้ Layout
```blade
{{-- Frontend Layout --}}
<x-frontend.layouts.app>
    เนื้อหา
</x-frontend.layouts.app>

{{-- Admin Layout --}}
<x-admin.layouts.admin>
    เนื้อหา
</x-admin.layouts.admin>
```

## ⚠️ ข้อควรระวัง

1. **อย่าสับสน namespace**
   - Frontend: `<x-frontend.components.*>`
   - Admin: `<x-admin.components.*>`
   - Shared: `<x-*>` (Laravel default)

2. **Path ใน Controller**
   - ใช้ dot notation: `view('frontend.pages.home')`
   - ไม่ใช่: `view('frontend/pages/home')`

3. **Livewire Components**
   - Class: `App\Livewire\Frontend\BannerSlider`
   - View: `resources/views/livewire/frontend/banner-slider.blade.php`
   - Usage: `<livewire:frontend.banner-slider />`

## ✅ ข้อดี

1. **แยกชัดเจน** - ไม่งงว่าไฟล์ไหนสำหรับหน้าไหน
2. **ค้นหาง่าย** - รู้เลยว่าต้องไปดูที่โฟลเดอร์ไหน
3. **แก้ไขปลอดภัย** - แก้หน้าบ้านไม่กระทบแอดมิน
4. **Scale ได้** - เพิ่มหน้าใหม่ไม่ยุ่งยาก
