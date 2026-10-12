<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\MediaFile;
use App\Models\MobileFooterItem;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Product;
use App\Models\QuickLink;
use App\Models\Settlement;
use App\Models\ShortLink;
use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Story;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\UrlRedirect;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@germanymode.com'],
            [
                'name' => 'مدیر',
                'last_name' => 'سایت',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'admin',
                'mobile' => '09120000000',
            ]
        );

        $customers = [
            ['name' => 'علیرضا', 'last_name' => 'الیاسی', 'email' => 'elyassialireza@gmail.com', 'mobile' => '09361162170'],
            ['name' => 'مجتبی', 'last_name' => 'زارعی', 'email' => 'mojtabazarei13561356@gmail.com', 'mobile' => '09124408620'],
            ['name' => 'سالار', 'last_name' => 'احمدی', 'email' => 'aseman8993@yahoo.com', 'mobile' => '09120467849'],
            ['name' => 'فاطمه', 'last_name' => 'باجلان', 'email' => 'matinabajelan@gmail.com', 'mobile' => '09362009689'],
            ['name' => 'ناصر', 'last_name' => 'حیدری', 'email' => 'naser.heydari@example.com', 'mobile' => '09173155916'],
            ['name' => 'الهام', 'last_name' => 'زکی پور', 'email' => 'elham.zakipoor@example.com', 'mobile' => '09120001122'],
            ['name' => 'احمدرضا', 'last_name' => 'بریموندی', 'email' => 'ahmadbirvandi@gmail.com', 'mobile' => '09121234567'],
        ];

        foreach ($customers as $customer) {
            User::query()->updateOrCreate(
                ['email' => $customer['email']],
                [
                    ...$customer,
                    'password' => Hash::make('123456'),
                    'is_admin' => false,
                    'role' => 'customer',
                    'is_blocked' => false,
                ]
            );
        }

        $settings = [
            'site_title' => 'فروشگاه اینترنتی جرمنی مد',
            'site_name' => 'جرمنی مد',
            'site_description' => 'خرید مستقیم از آلمان و اروپا — لوازم خانگی و برندهای اروپایی',
            'site_phone' => '',
            'site_address' => '',
            'stats_enabled' => true,
            'search_enabled' => true,
            'vertical_menu' => true,
            'horizontal_menu' => true,
            'user_menu' => true,
            'auto_archive' => false,
            'per_page' => 12,
            'enamad' => false,
            'samandehi' => false,
            'weather_enabled' => false,
            'weather_city' => 'تهران',
            'prayer_enabled' => false,
            'prayer_city' => 'تهران',
            'active_template' => 'orkide',
            'toolbar' => [
                'text' => '🔥 تخفیف ویژه امروز - همین حالا خرید کنید 🔥',
                'bg' => '#dd4b39',
                'color' => '#ffffff',
                'size' => '18px',
                'link' => '',
                'target' => '_self',
                'text_effect' => '',
                'bg_effect' => '',
                'enabled' => false,
            ],
            'cart_plugin' => ['template' => 'default', 'auto_post' => true],
            'domain_park' => ['domain' => 'germanymode.com', 'dns' => 'ns1.example.com', 'status' => 'active'],
            'plugins' => [
                ['name' => 'تولبار', 'key' => 'toolbar', 'enabled' => false],
                ['name' => 'استوری سایت', 'key' => 'stories', 'enabled' => true],
                ['name' => 'سبد خرید پیشرفته', 'key' => 'cart', 'enabled' => true],
                ['name' => 'فرم‌ساز', 'key' => 'forms', 'enabled' => true],
                ['name' => 'کوتاه‌کننده لینک', 'key' => 'shortlinks', 'enabled' => true],
                ['name' => 'فتوشاپ آنلاین', 'key' => 'photoshop', 'enabled' => false],
            ],
            'signup_fields' => [
                'name' => true, 'last_name' => true, 'mobile' => true,
                'email' => true, 'password' => true, 'national_code' => false, 'address' => false,
            ],
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::setValue($key, $value);
        }

        Post::query()->updateOrCreate(
            ['type' => 'page', 'slug' => 'about'],
            [
                'title' => 'درباره ما',
                'body' => "فروشگاه اینترنتی جرمنی مد، مرجع خرید مستقیم لوازم خانگی و برندهای اروپایی از آلمان و اروپا است.\n\nهدف ما ارائه کالای اورجینال با گارانتی و قیمت شفاف است.",
                'author' => 'مدیر سایت',
                'is_hidden' => false,
                'published_at' => now(),
            ]
        );

        $blogPosts = [
            ['title' => 'خرید اقساط از رویال برند مال', 'views' => 661, 'days' => 400],
            ['title' => 'چگونه کالا اورجینال را بشناسیم', 'views' => 526, 'days' => 400],
            ['title' => 'اسپرسو ساز اسمگ ایتالیا در رویال برند مال', 'views' => 518, 'days' => 404],
            ['title' => 'واردات خودرو کارکرده از آلمان', 'views' => 1219, 'days' => 440],
            ['title' => 'اورجینال چیست', 'views' => 3690, 'days' => 490],
            ['title' => 'قیمت و خرید لوازم اورجینال آشپزخانه اسمگ ایتالیا', 'views' => 938, 'days' => 700],
            ['title' => 'معرفی محصولات کنوود انگلستان', 'views' => 2160, 'days' => 1500],
            ['title' => 'معرفی محصولات گوچی ایتالیا', 'views' => 2134, 'days' => 1505],
            ['title' => 'معرفی محصولات هیلتی', 'views' => 2510, 'days' => 1540],
            ['title' => 'معرفی محصولات میلواکی آمریکا', 'views' => 1136, 'days' => 1545],
            ['title' => 'معرفی محصولات نینجا آمریکا', 'views' => 4914, 'days' => 1600],
            ['title' => 'معرفی محصولات دایسون انگلستان', 'views' => 805, 'days' => 1605],
            ['title' => 'معرفی محصولات فیلیپس هلند', 'views' => 1420, 'days' => 1610],
            ['title' => 'معرفی محصولات زیمنس آلمان', 'views' => 1069, 'days' => 1612],
            ['title' => 'معرفی محصولات تفال فرانسه', 'views' => 527, 'days' => 1614],
        ];

        foreach ($blogPosts as $i => $row) {
            Post::query()->updateOrCreate(
                ['type' => 'blog', 'slug' => Str::slug($row['title']).'-'.$i],
                [
                    'title' => $row['title'],
                    'body' => 'مطلب منتشرشده در پنل جرمنی مد — '.$row['title'],
                    'author' => 'Hamidreza Joneydi Jafari',
                    'views' => $row['views'],
                    'is_hidden' => false,
                    'is_featured' => $i < 4,
                    'published_at' => now()->subDays($row['days']),
                ]
            );
        }

        foreach ([
            'ویدیو معرفی فروشگاه',
            'کلیپ تحویل کالا',
            'موزیک تبلیغاتی جرمنی مد',
        ] as $i => $title) {
            Post::query()->updateOrCreate(
                ['type' => 'gallery', 'slug' => 'gallery-'.$i],
                [
                    'title' => $title,
                    'body' => $title,
                    'author' => 'مدیر سایت',
                    'views' => random_int(80, 900),
                    'published_at' => now()->subDays(30 + $i),
                ]
            );
        }

        foreach ([
            ['title' => 'راهنمای سفارش', 'url' => '/guide'],
            ['title' => 'درباره ما', 'url' => '/about'],
        ] as $i => $link) {
            QuickLink::query()->updateOrCreate(
                ['title' => $link['title']],
                ['url' => $link['url'], 'sort_order' => $i + 1, 'is_hidden' => false]
            );
        }

        $storiesPath = database_path('data/stories.json');
        if (is_file($storiesPath)) {
            Story::query()->delete();
            $stories = json_decode((string) file_get_contents($storiesPath), true) ?: [];
            foreach ($stories as $i => $row) {
                Story::query()->create([
                    'profile_name' => $row['profile_name'] ?? ('استوری '.($i + 1)),
                    'profile_image' => $row['profile_image'] ?? null,
                    'title' => $row['title'] ?? ($row['profile_name'] ?? null),
                    'media_path' => $row['media_path'] ?? null,
                    'short_text' => $row['short_text'] ?? ($row['alt'] ?? null),
                    'link_title' => $row['link_title'] ?? null,
                    'link_image' => $row['link_image'] ?? null,
                    'link_url' => $row['link_url'] ?? null,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                    'views' => $i === 0 ? 460 : random_int(20, 200),
                ]);
            }
        }

        if (Banner::query()->count() === 0) {
            foreach ([
                ['image' => '/images/banners/banner-amazon.jpg', 'title' => 'خرید از آمازون آلمان و اروپا', 'button_title' => 'مشاهده کالاها', 'button_url' => '/search'],
                ['image' => '/images/banners/banner-philips.jpg', 'title' => 'محصولات فیلیپس هلند', 'button_title' => 'خرید فیلیپس', 'button_url' => '/search?q=فیلیپس'],
                ['image' => '/images/banners/banner-smeg.jpg', 'title' => 'اسمگ ایتالیا — اورجینال', 'button_title' => 'خرید اسمگ', 'button_url' => '/search?q=اسمگ'],
                ['image' => '/images/banners/banner-dyson.jpg', 'title' => 'دایسون انگلستان', 'button_title' => 'خرید دایسون', 'button_url' => '/search?q=دایسون'],
            ] as $i => $row) {
                Banner::query()->create([
                    ...$row,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]);
            }
        }

        foreach ([
            ['title' => 'خانه', 'url' => '/', 'icon' => 'home'],
            ['title' => 'دسته‌ها', 'url' => '/search', 'icon' => 'grid'],
            ['title' => 'سبد', 'url' => '/cart', 'icon' => 'cart'],
            ['title' => 'حساب', 'url' => '/profile', 'icon' => 'user'],
        ] as $i => $item) {
            MobileFooterItem::query()->updateOrCreate(
                ['title' => $item['title']],
                [...$item, 'sort_order' => $i + 1, 'is_active' => true]
            );
        }

        ContactMessage::query()->updateOrCreate(
            ['phone' => '09173155916', 'name' => 'ناصر حیدری'],
            [
                'email' => null,
                'body' => "سلام ۱۴۰۴/۶/۱۰ خرید جعبه ساعت اتوماتیک انجام دادم واتساپ\nیکماه پیش پیام داد که هفته دیگه محصول میرسه\nوای الان یک ماهه پیام ها را سین نمیکنه و جواب نمیده 09173155916\nناصر حیدری مبلغ ۱۰۸۹۰۰۰۰ تومان",
                'is_seen' => false,
                'needs_reply' => true,
                'created_at' => now()->subDays(20),
            ]
        );
        ContactMessage::query()->updateOrCreate(
            ['name' => 'الهام زکی پور', 'body' => 'سلام وقت بخیر لطفا بفرمایید سفارش من کی ارسال می شه؟ زمان زیادی از سفارش گذشته شماره فاکتور ۹۹۵۱۱۷'],
            [
                'email' => null,
                'phone' => null,
                'is_seen' => false,
                'needs_reply' => true,
                'created_at' => now()->subDays(21),
            ]
        );
        ContactMessage::query()->updateOrCreate(
            ['email' => 'ahmadbirvandi@gmail.com'],
            [
                'name' => 'احمد رضا بریموندی',
                'body' => 'سلام در مورد اسباب بازی کمباین کنترلی سوال داشتم مشخصات و قیمت میخواستم',
                'is_seen' => true,
                'needs_reply' => false,
                'created_at' => now()->subDays(45),
            ]
        );

        $product = Product::query()->latest('id')->first();
        $commentUser = User::query()->where('email', 'elyassialireza@gmail.com')->first();
        Comment::query()->updateOrCreate(
            ['author_email' => 'elyassialireza@gmail.com', 'body' => 'کیفیت کالا عالی بود، بسته‌بندی استاندارد.'],
            [
                'user_id' => $commentUser?->id,
                'author_name' => 'علیرضا الیاسی',
                'commentable_type' => $product ? Product::class : Post::class,
                'commentable_id' => $product?->id ?: Post::query()->where('type', 'blog')->value('id'),
                'is_approved' => true,
            ]
        );
        Comment::query()->updateOrCreate(
            ['author_email' => 'aseman8993@yahoo.com', 'body' => 'زمان ارسال طولانی شد، پیگیری کنید.'],
            [
                'user_id' => User::query()->where('email', 'aseman8993@yahoo.com')->value('id'),
                'author_name' => 'سالار احمدی',
                'commentable_type' => $product ? Product::class : Post::class,
                'commentable_id' => $product?->id ?: Post::query()->where('type', 'blog')->value('id'),
                'is_approved' => false,
            ]
        );

        $ticketUser = User::query()->where('email', 'matinabajelan@gmail.com')->first();
        $ticket = Ticket::query()->updateOrCreate(
            ['subject' => 'پیگیری سفارش فاکتور ۹۹۵۱۱۷'],
            [
                'user_id' => $ticketUser?->id,
                'status' => 'open',
                'priority' => 'high',
            ]
        );
        if ($ticket->messages()->count() === 0) {
            TicketMessage::query()->create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticketUser?->id,
                'body' => 'سلام، سفارش من هنوز ارسال نشده. لطفاً وضعیت را اعلام کنید.',
                'is_staff' => false,
            ]);
        }

        $poll = Poll::query()->updateOrCreate(
            ['title' => 'از خرید آنلاین جرمنی مد راضی هستید؟'],
            ['is_active' => true]
        );
        if ($poll->options()->count() === 0) {
            foreach ([['عالی', 42], ['خوب', 28], ['متوسط', 11], ['ضعیف', 3]] as [$title, $votes]) {
                $poll->options()->create(['title' => $title, 'votes' => $votes]);
            }
        }

        for ($i = 0; $i < 14; $i++) {
            $date = now()->subDays($i)->toDateString();
            Visit::query()->updateOrCreate(
                ['visited_on' => $date, 'path' => '/'],
                [
                    'hits' => random_int(120, 480),
                    'unique_hits' => random_int(80, 300),
                ]
            );
            Visit::query()->updateOrCreate(
                ['visited_on' => $date, 'path' => '/search'],
                [
                    'hits' => random_int(40, 180),
                    'unique_hits' => random_int(30, 120),
                ]
            );
        }

        Settlement::query()->updateOrCreate(
            ['invoice_no' => '995117'],
            [
                'user_id' => $ticketUser?->id,
                'buyer_name' => 'فاطمه باجلان',
                'amount' => 10890000,
                'is_confirmed' => false,
                'is_seen' => false,
                'paid_at' => now()->subDays(25),
            ]
        );
        Settlement::query()->updateOrCreate(
            ['invoice_no' => '994880'],
            [
                'user_id' => $commentUser?->id,
                'buyer_name' => 'علیرضا الیاسی',
                'amount' => 2450000,
                'is_confirmed' => true,
                'is_seen' => true,
                'paid_at' => now()->subDays(10),
            ]
        );

        ShortLink::query()->updateOrCreate(
            ['code' => 'gmhome'],
            ['original_url' => 'https://germanymode.com', 'visits' => 128]
        );

        UrlRedirect::query()->updateOrCreate(
            ['old_url' => '/old-shop'],
            ['title' => 'فروشگاه قدیمی', 'new_url' => '/', 'is_active' => true]
        );

        MediaFile::query()->updateOrCreate(
            ['path' => 'uploads/logo.png'],
            ['name' => 'logo.png', 'mime' => 'image/png', 'size' => 24576]
        );

        $this->command?->info('Site content, interactions and settings seeded from panel captures.');
    }
}
