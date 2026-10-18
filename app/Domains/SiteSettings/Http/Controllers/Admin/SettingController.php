<?php

namespace App\Domains\SiteSettings\Http\Controllers\Admin;

use App\Shared\Http\Controller;
use App\Domains\Marketing\Models\Banner;
use App\Domains\SiteSettings\Models\MediaFile;
use App\Domains\Marketing\Models\MobileFooterItem;
use App\Domains\Orders\Models\Settlement;
use App\Domains\SiteSettings\Models\ShortLink;
use App\Domains\SiteSettings\Models\SiteSetting;
use App\Domains\Marketing\Models\Story;
use App\Domains\SiteSettings\Models\UrlRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => [
                'site_title' => SiteSetting::getValue('site_title', 'فروشگاه اینترنتی جرمنی مد'),
                'site_name' => SiteSetting::getValue('site_name', 'جرمنی مد'),
                'site_description' => SiteSetting::getValue('site_description', 'خرید مستقیم از آلمان و اروپا — لوازم خانگی و برندهای اروپایی'),
                'site_phone' => SiteSetting::getValue('site_phone', ''),
                'site_address' => SiteSetting::getValue('site_address', ''),
                'stats_enabled' => SiteSetting::getValue('stats_enabled', true),
                'search_enabled' => SiteSetting::getValue('search_enabled', true),
                'vertical_menu' => SiteSetting::getValue('vertical_menu', true),
                'horizontal_menu' => SiteSetting::getValue('horizontal_menu', true),
                'user_menu' => SiteSetting::getValue('user_menu', true),
                'auto_archive' => SiteSetting::getValue('auto_archive', false),
                'per_page' => SiteSetting::getValue('per_page', 12),
                'enamad' => SiteSetting::getValue('enamad', false),
                'samandehi' => SiteSetting::getValue('samandehi', false),
                'weather_enabled' => SiteSetting::getValue('weather_enabled', false),
                'weather_city' => SiteSetting::getValue('weather_city', 'تهران'),
                'prayer_enabled' => SiteSetting::getValue('prayer_enabled', false),
                'prayer_city' => SiteSetting::getValue('prayer_city', 'تهران'),
            ],
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $keys = [
            'site_title', 'site_name', 'site_description', 'site_phone', 'site_address',
            'weather_city', 'prayer_city',
        ];
        foreach ($keys as $key) {
            SiteSetting::setValue($key, (string) $request->input($key, ''));
        }

        foreach ([
            'stats_enabled', 'search_enabled', 'vertical_menu', 'horizontal_menu',
            'user_menu', 'auto_archive', 'enamad', 'samandehi', 'weather_enabled', 'prayer_enabled',
        ] as $boolKey) {
            SiteSetting::setValue($boolKey, $request->boolean($boolKey));
        }

        SiteSetting::setValue('per_page', (int) $request->input('per_page', 12));

        return back()->with('ok', 'تنظیمات اصلی ذخیره شد.');
    }

    public function stories(): View
    {
        $stories = Story::query()->orderBy('sort_order')->orderByDesc('id')->get();
        $totalViews = (int) Story::query()->sum('views');

        return view('admin.settings.stories', compact('stories', 'totalViews'));
    }

    public function storeStory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'profile_name' => 'required|string|max:120',
            'title' => 'nullable|string|max:255',
            'short_text' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'link_title' => 'nullable|string|max:120',
        ]);

        Story::query()->create([
            ...$data,
            'is_active' => true,
            'sort_order' => (int) Story::query()->max('sort_order') + 1,
            'views' => 0,
        ]);

        return back()->with('ok', 'استوری اضافه شد.');
    }

    public function destroyStory(Story $story): RedirectResponse
    {
        $story->delete();

        return back()->with('ok', 'استوری حذف شد.');
    }

    public function banners(): View
    {
        $banners = Banner::query()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.settings.banners', compact('banners'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:5120',
            'title' => 'nullable|string|max:255',
            'button_title' => 'nullable|string|max:120',
            'button_url' => 'nullable|string|max:500',
        ]);

        $image = trim((string) ($data['image'] ?? ''));
        if ($request->hasFile('image_file')) {
            $dir = public_path('uploads/banners');
            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $file = $request->file('image_file');
            $name = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($dir, $name);
            $image = '/uploads/banners/'.$name;
        }

        if ($image === '') {
            return back()->withErrors(['image' => 'تصویر بنر الزامی است.'])->withInput();
        }

        Banner::query()->create([
            'image' => $image,
            'title' => $data['title'] ?? null,
            'button_title' => $data['button_title'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'is_active' => true,
            'sort_order' => (int) Banner::query()->max('sort_order') + 1,
        ]);

        return back()->with('ok', 'بنر اضافه شد.');
    }

    public function destroyBanner(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('ok', 'بنر حذف شد.');
    }

    public function toolbar(): View
    {
        return view('admin.settings.toolbar', [
            'toolbar' => SiteSetting::getValue('toolbar', [
                'text' => '🔥 تخفیف ویژه امروز - همین حالا خرید کنید 🔥',
                'bg' => '#dd4b39',
                'color' => '#ffffff',
                'size' => '18px',
                'link' => '',
                'target' => '_self',
                'text_effect' => '',
                'bg_effect' => '',
                'enabled' => false,
            ]),
        ]);
    }

    public function saveToolbar(Request $request): RedirectResponse
    {
        SiteSetting::setValue('toolbar', [
            'text' => (string) $request->input('text', ''),
            'bg' => (string) $request->input('bg', '#dd4b39'),
            'color' => (string) $request->input('color', '#ffffff'),
            'size' => (string) $request->input('size', '18px'),
            'link' => (string) $request->input('link', ''),
            'target' => (string) $request->input('target', '_self'),
            'text_effect' => (string) $request->input('text_effect', ''),
            'bg_effect' => (string) $request->input('bg_effect', ''),
            'enabled' => $request->boolean('enabled'),
        ]);

        return back()->with('ok', 'تنظیمات تولبار ذخیره شد.');
    }

    public function mobileFooter(): View
    {
        $items = MobileFooterItem::query()->orderBy('sort_order')->get();

        return view('admin.settings.mobile-footer', compact('items'));
    }

    public function storeMobileFooter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:80',
            'url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:80',
        ]);

        MobileFooterItem::query()->create([
            ...$data,
            'sort_order' => (int) MobileFooterItem::query()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        return back()->with('ok', 'آیتم پاورقی اضافه شد.');
    }

    public function destroyMobileFooter(MobileFooterItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('ok', 'آیتم حذف شد.');
    }

    public function cartPlugin(): View
    {
        return view('admin.settings.cart-plugin', [
            'cart' => SiteSetting::getValue('cart_plugin', [
                'template' => 'default',
                'auto_post' => true,
            ]),
        ]);
    }

    public function saveCartPlugin(Request $request): RedirectResponse
    {
        SiteSetting::setValue('cart_plugin', [
            'template' => (string) $request->input('template', 'default'),
            'auto_post' => $request->boolean('auto_post'),
        ]);

        return back()->with('ok', 'افزونه سبد خرید ذخیره شد.');
    }

    public function domainPark(): View
    {
        return view('admin.settings.domain-park', [
            'domain' => SiteSetting::getValue('domain_park', [
                'domain' => 'germanymode.com',
                'dns' => 'ns1.example.com',
                'status' => 'active',
            ]),
        ]);
    }

    public function saveDomainPark(Request $request): RedirectResponse
    {
        SiteSetting::setValue('domain_park', [
            'domain' => (string) $request->input('domain', ''),
            'dns' => (string) $request->input('dns', ''),
            'status' => (string) $request->input('status', 'pending'),
        ]);

        return back()->with('ok', 'تنظیمات دامنه ذخیره شد.');
    }

    public function featuredNews(): View
    {
        $posts = \App\Domains\Content\Models\Post::query()
            ->where('type', 'blog')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->paginate(30);

        return view('admin.settings.featured-news', compact('posts'));
    }

    public function toggleFeaturedNews(\App\Domains\Content\Models\Post $post): RedirectResponse
    {
        $post->update(['is_featured' => ! $post->is_featured]);

        return back()->with('ok', 'وضعیت برگزیده تغییر کرد.');
    }

    public function shortLinks(): View
    {
        $links = ShortLink::query()->latest('id')->paginate(30);

        return view('admin.settings.short-links', compact('links'));
    }

    public function storeShortLink(Request $request): RedirectResponse
    {
        $data = $request->validate(['original_url' => 'required|string|max:1000']);
        ShortLink::query()->create([
            'original_url' => $data['original_url'],
            'code' => Str::lower(Str::random(7)),
            'visits' => 0,
        ]);

        return back()->with('ok', 'لینک کوتاه ساخته شد.');
    }

    public function destroyShortLink(ShortLink $link): RedirectResponse
    {
        $link->delete();

        return back()->with('ok', 'لینک حذف شد.');
    }

    public function urlFriendly(): View
    {
        $items = UrlRedirect::query()->latest('id')->paginate(30);

        return view('admin.settings.url-friendly', compact('items'));
    }

    public function storeUrlFriendly(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'old_url' => 'required|string|max:500',
            'new_url' => 'required|string|max:500',
        ]);
        UrlRedirect::query()->create([...$data, 'is_active' => true]);

        return back()->with('ok', 'آدرس دوستانه ثبت شد.');
    }

    public function destroyUrlFriendly(UrlRedirect $item): RedirectResponse
    {
        $item->delete();

        return back()->with('ok', 'حذف شد.');
    }

    public function files(): View
    {
        $files = MediaFile::query()->latest('id')->paginate(40);

        return view('admin.settings.files', compact('files'));
    }

    public function templates(): View
    {
        return view('admin.settings.templates', [
            'active' => SiteSetting::getValue('active_template', 'orkide'),
            'templates' => [
                ['key' => 'orkide', 'name' => 'ارکیده', 'preview' => null],
                ['key' => 'classic', 'name' => 'کلاسیک', 'preview' => null],
                ['key' => 'modern', 'name' => 'مدرن', 'preview' => null],
            ],
        ]);
    }

    public function saveTemplate(Request $request): RedirectResponse
    {
        SiteSetting::setValue('active_template', (string) $request->input('template', 'orkide'));

        return back()->with('ok', 'قالب فعال شد.');
    }

    public function plugins(): View
    {
        $plugins = SiteSetting::getValue('plugins', [
            ['name' => 'تولبار', 'key' => 'toolbar', 'enabled' => false],
            ['name' => 'استوری سایت', 'key' => 'stories', 'enabled' => true],
            ['name' => 'سبد خرید پیشرفته', 'key' => 'cart', 'enabled' => true],
            ['name' => 'فرم‌ساز', 'key' => 'forms', 'enabled' => true],
            ['name' => 'کوتاه‌کننده لینک', 'key' => 'shortlinks', 'enabled' => true],
            ['name' => 'فتوشاپ آنلاین', 'key' => 'photoshop', 'enabled' => false],
        ]);

        return view('admin.settings.plugins', compact('plugins'));
    }

    public function savePlugins(Request $request): RedirectResponse
    {
        $enabled = $request->input('enabled', []);
        $plugins = SiteSetting::getValue('plugins', []);
        foreach ($plugins as &$plugin) {
            $plugin['enabled'] = in_array($plugin['key'], $enabled, true);
        }
        SiteSetting::setValue('plugins', $plugins);

        return back()->with('ok', 'وضعیت افزونه‌ها ذخیره شد.');
    }

    public function photoshop(): View
    {
        return view('admin.settings.photoshop');
    }

    public function formBuilder(): View
    {
        $forms = SiteSetting::getValue('forms', [
            ['title' => 'فرم تماس با ما', 'created_at' => '1403/01/01'],
            ['title' => 'فرم درخواست همکاری', 'created_at' => '1403/02/12'],
        ]);

        return view('admin.settings.form-builder', compact('forms'));
    }

    public function signupForm(): View
    {
        return view('admin.settings.signup-form', [
            'fields' => SiteSetting::getValue('signup_fields', [
                'name' => true,
                'last_name' => true,
                'mobile' => true,
                'email' => true,
                'password' => true,
                'national_code' => false,
                'address' => false,
            ]),
        ]);
    }

    public function saveSignupForm(Request $request): RedirectResponse
    {
        $fields = [];
        foreach (['name', 'last_name', 'mobile', 'email', 'password', 'national_code', 'address'] as $f) {
            $fields[$f] = $request->boolean($f);
        }
        SiteSetting::setValue('signup_fields', $fields);

        return back()->with('ok', 'فرم عضویت ذخیره شد.');
    }

    public function settlements(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = Settlement::query()
            ->with('user')
            ->when($q !== '', fn ($query) => $query->where('invoice_no', 'like', "%{$q}%")
                ->orWhere('buyer_name', 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(20)
            ->appends(['q' => $q]);

        return view('admin.settings.settlements', compact('items', 'q'));
    }

    public function confirmSettlement(Settlement $settlement): RedirectResponse
    {
        $settlement->update(['is_confirmed' => true, 'is_seen' => true]);

        return back()->with('ok', 'واریزی تایید شد.');
    }

    public function userCompleteInfo(): View
    {
        return view('admin.settings.user-complete-info', [
            'fields' => SiteSetting::getValue('user_complete_fields', [
                'password', 'password_confirmation', 'avatar', 'workplace_phone',
                'national_code', 'birth_date', 'fax', 'gender', 'name', 'last_name',
            ]),
        ]);
    }

    public function userProfileForm(): View
    {
        return view('admin.settings.user-profile-form');
    }

    public function changePasswordForm(): View
    {
        return view('admin.settings.change-password-form');
    }
}
