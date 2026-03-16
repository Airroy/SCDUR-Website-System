# โครงสร้างโปรเจกต์ SCD - คู่มือสำหรับนักพัฒนา

> **อัปเดตล่าสุด:** 15 มีนาคม 2026  
> **Laravel:** 12.x  
> **PHP:** 8.4 (Sail), รองรับ ^8.2 ตาม composer  
> **Livewire:** 3.x + Volt 1.x  
> **Database:** MySQL 8.4  
> **Cache:** database (default), Redis (optional)  
> **Containerization:** Docker (Laravel Sail)

---

## 📁 โครงสร้างโปรเจกต์ (ละเอียด)

```
scd-project/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── ClearLogs.php
│   │       ├── CreateAdminUser.php
│   │       └── MonitorContentNodesPerformance.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── VerifyEmailController.php
│   │   │   ├── Frontend/
│   │   │   │   ├── FrontendController.php
│   │   │   │   └── HomeController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── AccessLog.php
│   │   │   └── AdminAuth.php
│   │   └── Requests/
│   │       └── Backend/
│   │           ├── StoreScdYearRequest.php
│   │           └── UpdateScdYearRequest.php
│   ├── Livewire/
│   │   ├── Actions/
│   │   │   └── Logout.php
│   │   ├── Auth/
│   │   │   └── Login.php
│   │   ├── Backend/
│   │   │   ├── AdminDashboard.php
│   │   │   ├── AnnouncementManager.php
│   │   │   ├── AnnouncementsIndex.php
│   │   │   ├── BannersIndex.php
│   │   │   ├── ContentSectionManager.php
│   │   │   ├── ContentsIndex.php
│   │   │   ├── ReportsIndex.php
│   │   │   └── YearsIndex.php
│   │   ├── Forms/
│   │   │   └── LoginForm.php
│   │   ├── Frontend/
│   │   │   └── BannerSlider.php
│   │   └── Profile/
│   │       ├── DeleteUser.php
│   │       ├── UpdatePassword.php
│   │       └── UpdateProfileInformation.php
│   ├── Models/
│   │   ├── Announcement.php
│   │   ├── Banner.php
│   │   ├── ContentSection.php
│   │   ├── Order.php
│   │   ├── ScdReport.php
│   │   ├── ScdYear.php
│   │   └── User.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── VoltServiceProvider.php
│   └── Services/
│       ├── BannerService.php
│       ├── ContentNodeService.php
│       └── FileUploadService.php
├── bootstrap/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── livewire.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── upload.php
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_01_08_050543_create_scd_years_table.php
│   │   ├── 2026_01_08_050547_create_scd_reports_table.php
│   │   ├── 2026_01_08_050551_create_banners_table.php
│   │   ├── 2026_02_03_230028_add_title_to_banners_table.php
│   │   ├── 2026_02_04_132952_add_view_count_to_scd_reports_table.php
│   │   ├── 2026_02_06_000001_split_content_nodes_into_separate_tables.php
│   │   ├── 2026_02_06_000002_rename_tables_with_scd_prefix.php
│   │   ├── 2026_02_17_000001_replace_sequence_with_category_in_scd_banners.php
│   │   ├── 2026_02_17_100000_drop_title_from_scd_banners_table.php
│   │   ├── 2026_02_23_000001_add_sequence_to_scd_banners_and_drop_unique_constraints.php
│   │   ├── 2026_03_01_000001_add_is_hidden_to_announcements_directives_contents.php
│   │   ├── 2026_03_09_000002_add_separate_updated_at_to_scd_years_table.php
│   │   ├── 2026_03_09_000003_drop_separate_updated_at_from_scd_years_table.php
│   │   ├── 2026_03_09_133553_add_last_content_updated_at_to_scd_years_table.php
│   │   └── 2026_03_15_145840_create_sessions_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── docker/
│   ├── 8.2/
│   ├── 8.3/
│   ├── 8.4/
│   └── mysql/
├── public/
│   ├── build/
│   ├── images/
│   ├── index.php
│   ├── robots.txt
│   └── storage/   # symlink -> storage/app/public
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   └── pages/
│       │       └── profile.blade.php
│       ├── components/
│       │   ├── backend/
│       │   │   ├── action-button.blade.php
│       │   │   ├── modal-alpine.blade.php
│       │   │   ├── modal-form.blade.php
│       │   │   ├── modal.blade.php
│       │   │   ├── sort-modal.blade.php
│       │   │   └── year-tabs.blade.php
│       │   ├── frontend/
│       │   │   ├── breadcrumb.blade.php
│       │   │   ├── button.blade.php
│       │   │   ├── card.blade.php
│       │   │   ├── content-tree.blade.php
│       │   │   ├── dropdown-item.blade.php
│       │   │   ├── dropdown.blade.php
│       │   │   ├── footer.blade.php
│       │   │   ├── header.blade.php
│       │   │   ├── nav-link.blade.php
│       │   │   ├── navigation.blade.php
│       │   │   ├── related-sections.blade.php
│       │   │   ├── section-content.blade.php
│       │   │   ├── section-header.blade.php
│       │   │   └── section.blade.php
│       │   ├── layouts/
│       │   │   ├── admin.blade.php
│       │   │   ├── frontend.blade.php
│       │   │   └── guest.blade.php
│       │   ├── application-logo.blade.php
│       │   ├── auth-session-status.blade.php
│       │   ├── image-cropper-simple.blade.php
│       │   ├── input-error.blade.php
│       │   ├── input-label.blade.php
│       │   ├── notification.blade.php
│       │   ├── primary-button.blade.php
│       │   ├── responsive-nav-link.blade.php
│       │   └── text-input.blade.php
│       ├── errors/
│       │   ├── 403.blade.php
│       │   ├── 404.blade.php
│       │   ├── 419.blade.php
│       │   ├── 500.blade.php
│       │   └── 503.blade.php
│       ├── frontend/
│       │   └── pages/
│       │       ├── about.blade.php
│       │       ├── announcements.blade.php
│       │       ├── contact.blade.php
│       │       ├── content-section.blade.php
│       │       ├── home.blade.php
│       │       └── scd-report.blade.php
│       ├── livewire/
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   ├── backend/
│       │   │   ├── admin-dashboard.blade.php
│       │   │   ├── announcement-manager.blade.php
│       │   │   ├── announcements-index.blade.php
│       │   │   ├── banners-index.blade.php
│       │   │   ├── contents-index.blade.php
│       │   │   ├── reports-index.blade.php
│       │   │   ├── years-index.blade.php
│       │   │   └── partials/
│       │   │       ├── announcements-table.blade.php
│       │   │       ├── banners-table.blade.php
│       │   │       └── contents-table.blade.php
│       │   ├── frontend/
│       │   │   └── banner-slider.blade.php
│       │   └── profile/
│       │       ├── delete-user.blade.php
│       │       ├── update-password.blade.php
│       │       └── update-profile-information.blade.php
│       └── pages/
│           └── auth/
│               ├── confirm-password.blade.php
│               ├── forgot-password.blade.php
│               ├── reset-password.blade.php
│               └── verify-email.blade.php
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
├── storage/
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── ExampleTest.php
│   │   └── ProfileTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   └── TestCase.php
├── compose.yaml
├── composer.json
├── package.json
├── phpunit.xml
├── tailwind.config.js
└── vite.config.js
```

---

## ✅ หมายเหตุการใช้งาน

- Login URL ซ่อนอยู่ที่ `/aru-scdur-panel`
- `/login` ถูก redirect ไปหน้าแรก
- Email verification / password reset routes ถูกคอมเมนต์ไว้ (ยังไม่เปิดใช้)

