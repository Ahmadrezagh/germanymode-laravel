<?php

/**
 * Sidebar structure matched to https://panel123456.germanymode.com/admin
 */
return [
    'sections' => [
        [
            'title' => 'داشبورد / آموزش',
            'icon' => 'dashboard',
            'items' => [
                ['title' => 'داشبورد،آموزش', 'route' => 'admin.dashboard'],
            ],
        ],
        [
            'title' => 'مدیریت صفحات و منوها',
            'icon' => 'pages',
            'items' => [
                ['title' => 'گالری موزیک و کلیپ', 'route' => 'admin.content.gallery'],
                ['title' => 'بلاگ', 'route' => 'admin.content.blog'],
                ['title' => 'درباره ما', 'route' => 'admin.content.about'],
                ['title' => 'تماس با ما', 'route' => 'admin.content.contact'],
                ['title' => 'دسترسی سریع', 'route' => 'admin.content.quick-links'],
            ],
        ],
        [
            'title' => 'مدیـریت فروشگـاه',
            'icon' => 'shop',
            'items' => [
                ['title' => 'ایجاد کالا', 'route' => 'admin.products.create', 'icon' => 'plus', 'color' => '#28a745'],
                ['title' => 'آرشیو کالاها', 'route' => 'admin.products.index', 'icon' => 'archive', 'color' => '#3c8dbc'],
                ['title' => 'ایجاد کالا با اکسل', 'route' => 'admin.shop.excel-add', 'icon' => 'excel', 'color' => '#00a65a'],
                ['title' => 'ویرایش کالا با اکسل', 'route' => 'admin.shop.excel-edit', 'icon' => 'excel-edit', 'color' => '#39cccc'],
                ['title' => 'ویرایش سریع کالاها', 'route' => 'admin.shop.quick-edit', 'icon' => 'flash', 'color' => '#f39c12'],
                ['title' => 'چیدمان تخفیف ویژه', 'route' => 'admin.shop.special-discounts', 'icon' => 'percent', 'color' => '#dd4b39'],
                ['title' => 'کالای برگزیده', 'route' => 'admin.shop.featured', 'icon' => 'star', 'color' => '#f012be'],
                ['title' => 'تخفیف های فروشگاه', 'route' => 'admin.shop.prepare', 'icon' => 'warehouse', 'color' => '#605ca8'],
                ['title' => 'دسته بندی کالاها', 'route' => 'admin.shop.categories', 'icon' => 'layers', 'color' => '#00c0ef'],
                ['title' => 'مدیریت اَپ اندروید', 'route' => 'admin.shop.android', 'icon' => 'android', 'color' => '#01ff70'],
            ],
        ],
        [
            'title' => 'سفـارشات فروشگـاه',
            'icon' => 'orders',
            'items' => [
                ['title' => 'آخرین سفارشات', 'route' => 'admin.orders.new', 'badge' => 'orders_new'],
                ['title' => 'سفارشات انجام شده', 'route' => 'admin.orders.done'],
                ['title' => 'واریزی کاربران', 'route' => 'admin.settings.settlements', 'badge' => 'settlements_pending'],
            ],
        ],
        [
            'title' => 'تنظیمـات سـایت',
            'icon' => 'settings',
            'items' => [
                ['title' => 'تنظیمات اصلی', 'route' => 'admin.settings'],
                ['title' => 'پارک دامنه', 'route' => 'admin.settings.domain'],
                ['title' => 'خبر برگزیده', 'route' => 'admin.settings.featured-news'],
                ['title' => 'افزونه سبد خرید', 'route' => 'admin.settings.cart'],
                ['title' => 'تنظیمات استوری سایت', 'route' => 'admin.settings.stories'],
                ['title' => 'بنرهای صفحه اصلی', 'route' => 'admin.settings.banners'],
                ['title' => 'تنظیمات پاورقی موبایل', 'route' => 'admin.settings.mobile-footer'],
                ['title' => 'تنظیمات تولبار', 'route' => 'admin.settings.toolbar'],
                ['title' => 'کوتاه کننده لینک', 'route' => 'admin.settings.short-links'],
                ['title' => 'تنظیمات لینک‌ها', 'route' => 'admin.settings.url-friendly'],
                ['title' => 'مدیریت فایل‌ها', 'route' => 'admin.settings.files'],
                ['title' => 'فتوشاپ آنلاین + هوش مصنوعی', 'route' => 'admin.settings.photoshop'],
                ['title' => 'مدیریت قالب ها', 'route' => 'admin.settings.templates'],
            ],
        ],
        [
            'title' => 'سرویــس های افزایش فروش',
            'icon' => 'growth',
            'items' => [
                ['title' => 'افزونه ها', 'route' => 'admin.settings.plugins'],
            ],
        ],
        [
            'title' => 'فرم ســاز',
            'icon' => 'forms',
            'items' => [
                ['title' => 'فرم ساز', 'route' => 'admin.settings.form-builder'],
                ['title' => 'فرم ساز عضویت', 'route' => 'admin.settings.signup-form'],
            ],
        ],
        [
            'title' => 'تعاملات سایت (مدیران/کاربران)',
            'icon' => 'users',
            'items' => [
                ['title' => 'مدیران', 'route' => 'admin.interactions.admins'],
                ['title' => 'مشتریان|کاربران', 'route' => 'admin.users.index'],
                ['title' => 'نظــــرات', 'route' => 'admin.interactions.comments'],
                ['title' => 'آمار بازدید', 'route' => 'admin.interactions.visits'],
                ['title' => 'نظرسنجی', 'route' => 'admin.interactions.polls'],
                ['title' => 'پیام ها (پشتیبانی)', 'route' => 'admin.interactions.tickets'],
            ],
        ],
        [
            'title' => 'ایجاد صفحات کاربران',
            'icon' => 'profile',
            'items' => [
                ['title' => 'تکمیل اطلاعات کاربران', 'route' => 'admin.settings.user-complete'],
                ['title' => 'ویرایش اطلاعات کاربری', 'route' => 'admin.settings.user-profile'],
                ['title' => 'تغییر رمز عبور', 'route' => 'admin.settings.change-password'],
                ['title' => 'ویرایش کاربری', 'route' => 'admin.interactions.profile'],
            ],
        ],
    ],
];
