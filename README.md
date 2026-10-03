# Rayanvia Dashboard

قالب پایه داشبورد برند رایان‌ویا؛ Laravel + Blade + Alpine + Tailwind، فارسی و راست‌به‌چپ، با تم روشن و تیره.

## شروع

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate
composer run dev
```

برای دیدن قالب، در `.env` مقدار `UI_PREVIEW=true` بگذارید (فقط در محیط local) و `/` را باز کنید.

## داخل قالب

- **کتابخانه کامپوننت** (`/preview/kit`): رنگ‌ها، دکمه‌ها، فرم‌ها، نقشه، نمودار، جدول AJAX، مودال، توست و غیره.
- **منوی نمونه** (`config/menu.php`): داده‌محور؛ آیتم‌ها صفحه ندارند.
- **صفحه ورود** (`/login`).
- متن‌ها در `lang/fa/ui.php`، کامپوننت‌ها در `resources/views/components`.
