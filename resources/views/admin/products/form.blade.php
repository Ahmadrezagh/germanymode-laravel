@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'ایجاد کالا' : 'ویرایش کالا').' | مدیریت سایت')
@section('tab', $mode === 'create' ? 'ایجاد کالا' : 'ویرایش کالا')

@section('content')
@php
    $meta = old('meta', $product->meta ?? []);
    $tabs = [
        ['id' => 'basic', 'label' => 'اطلاعات کالا', 'cls' => 'shop', 'color' => '#2e86c1', 'icon' => 'boxes'],
        ['id' => 'seo', 'label' => 'تنظیمات SEO', 'cls' => 'seo', 'color' => '#e74c3c', 'icon' => 'seo'],
        ['id' => 'keywords', 'label' => 'کلید واژه', 'cls' => 'kelid', 'color' => '#2980b9', 'icon' => 'key'],
        ['id' => 'accessory', 'label' => 'اکسسوری', 'cls' => '3D_2', 'color' => '#6c5ce7', 'icon' => 'boxes'],
        ['id' => 'gallery', 'label' => 'گالری تصویر', 'cls' => 'img', 'color' => '#e84393', 'icon' => 'image'],
        ['id' => 'related', 'label' => 'کالاهای مرتبط', 'cls' => 'mortabet', 'color' => '#00cec9', 'icon' => 'dots'],
        ['id' => 'settings', 'label' => 'تنظیمات', 'cls' => 'setting', 'color' => '#636e72', 'icon' => 'gear'],
        ['id' => 'warranty', 'label' => 'گارانتی', 'cls' => 'garanti', 'color' => '#a29bfe', 'icon' => 'checkdoc'],
        ['id' => 'specs', 'label' => 'مشخصات تکمیلی', 'cls' => 'insert_tex', 'color' => '#0984e3', 'icon' => 'doc'],
        ['id' => 'short', 'label' => 'توضیحات کوتاه', 'cls' => 'news', 'color' => '#6c5ce7', 'icon' => 'boxes2'],
        ['id' => 'discount', 'label' => 'تخفیف ویژه', 'cls' => 'takhfif', 'color' => '#a55eea', 'icon' => 'price'],
        ['id' => 'featured', 'label' => 'کالا برگزیده', 'cls' => '3D', 'color' => '#00b894', 'icon' => 'hex'],
        ['id' => 'faq', 'label' => 'پرسش‌های متداول', 'cls' => 'faq', 'color' => '#e84393', 'icon' => 'faq'],
        ['id' => 'upload', 'label' => 'آپلود فایل', 'cls' => 'upload', 'color' => '#74b9ff', 'icon' => 'upload'],
        ['id' => 'social', 'label' => 'شبکه‌های اجتماعی', 'cls' => 'Social_network', 'color' => '#e67e22', 'icon' => 'megaphone'],
    ];
@endphp
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>{{ $mode === 'create' ? 'ایجاد کالا' : 'ویرایش کالا' }}</h1>
    <a class="btn-archive" href="{{ route('admin.products.index') }}">آرشیو ←</a>
</div>

<form method="post" action="{{ $mode === 'create' ? route('admin.products.store') : route('admin.products.update', $product) }}" class="product-edit-layout" id="productForm">
    @csrf
    @if($mode === 'edit')
        @method('PUT')
    @endif

    <div class="product-edit-main">
        <div class="shop-card product-tab-panel active" data-panel="basic">
            <h2 class="product-panel-title">اطلاعات اصلی کالا</h2>
            <div class="shop-grid-2">
                <div>
                    <label class="shop-label">وارد شود به انبار <a href="{{ route('admin.shop.prepare') }}">(درج انبار جدید)</a></label>
                    <select class="shop-select" name="warehouse_id">
                        <option value="">—</option>
                        @foreach(($warehouses ?? []) as $w)
                            <option value="{{ $w->id }}" @selected(old('warehouse_id', $product->warehouse_id) == $w->id)>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="shop-label">شرکت سازنده محصول (نام برند) <a href="{{ route('admin.shop.prepare') }}">(درج برند جدید)</a></label>
                    <select class="shop-select" name="brand_id">
                        <option value="">—</option>
                        @foreach(($brands ?? []) as $b)
                            <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="shop-label">انتخاب گروه کالا <a href="{{ route('admin.shop.categories') }}">(درج گروه کالا جدید)</a></label>
                    <select class="shop-select" name="category_id">
                        <option value="">—</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="shop-label">نام محصول (مثال: لپتاپ هوشمند XLd)</label>
                    <input class="shop-input" type="text" name="title" value="{{ old('title', $product->title) }}" placeholder="مثال:لپتاب هوشمند XLd" required>
                    @error('title')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="shop-label">انتخاب زیر گروه کالا</label>
                    <select class="shop-select" name="meta[sub_category]">
                        <option value="">—</option>
                        @foreach($categories->take(40) as $category)
                            <option value="{{ $category->id }}" @selected(data_get($meta, 'sub_category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label class="shop-label">وضعیت کالا</label>
                        <select class="shop-select" name="status">
                            <option @selected(old('status', $product->status) === 'موجود')>موجود</option>
                            <option @selected(old('status', $product->status) === 'ناموجود')>ناموجود</option>
                        </select>
                    </div>
                    <div>
                        <label class="shop-label">نوع جنس</label>
                        <select class="shop-select" name="meta[material_type]">
                            <option value=""></option>
                            <option @selected(data_get($meta, 'material_type') === 'اصل')>اصل</option>
                            <option @selected(data_get($meta, 'material_type') === 'های‌کپی')>های‌کپی</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="shop-label">مدل کالا</label>
                    <input class="shop-input" type="text" name="meta[model]" value="{{ data_get($meta, 'model') }}" placeholder="مثال: SL400EU">
                </div>
                <div>
                    <label class="shop-label">کد کالا / SKU</label>
                    <input class="shop-input" type="text" name="meta[sku]" value="{{ data_get($meta, 'sku') }}">
                </div>
            </div>
        </div>

        <div class="shop-card product-tab-panel" data-panel="seo">
            <h2 class="product-panel-title">تنظیمات SEO</h2>
            <div class="shop-grid-2">
                <div class="full">
                    <label class="shop-label">تایتل صفحه (Title)</label>
                    <input class="shop-input" type="text" name="meta[page_title]" value="{{ data_get($meta, 'page_title') }}" placeholder="تایتل صفحه">
                </div>
                <div class="full">
                    <label class="shop-label">توضیحات متا (Description)</label>
                    <textarea class="shop-input" name="meta[meta_description]" style="min-height:90px" placeholder="description">{{ data_get($meta, 'meta_description') }}</textarea>
                </div>
                <div>
                    <label class="shop-label">لینک Canonical</label>
                    <input class="shop-input" type="url" name="meta[canonical]" value="{{ data_get($meta, 'canonical') }}" placeholder="http://">
                </div>
                <div>
                    <label class="shop-label">لینک مجازی</label>
                    <input class="shop-input" type="url" name="meta[virtual_link]" value="{{ data_get($meta, 'virtual_link') }}" placeholder="لینک مجازی">
                </div>
                <div class="full">
                    <label class="shop-label">اسکریپت صفحه</label>
                    <textarea class="shop-input" name="meta[page_script]" style="min-height:90px" placeholder="اسکریپت">{{ data_get($meta, 'page_script') }}</textarea>
                </div>
            </div>
        </div>

        <div class="shop-card product-tab-panel" data-panel="keywords">
            <h2 class="product-panel-title">کلید واژه</h2>
            <label class="shop-label">کلمات کلیدی (با ویرگول جدا کنید)</label>
            <input class="shop-input" type="text" name="meta[keywords]" value="{{ data_get($meta, 'keywords') }}" placeholder="کلمه کلیدی">
            <p class="product-help">کلیدواژه‌ها در سئو و جستجوی داخلی فروشگاه استفاده می‌شوند.</p>
        </div>

        <div class="shop-card product-tab-panel" data-panel="accessory">
            <h2 class="product-panel-title">اکسسوری</h2>
            <label class="shop-label">جستجو و افزودن اکسسوری</label>
            <input class="shop-input" type="text" name="meta[accessory]" value="{{ data_get($meta, 'accessory') }}" placeholder="یک کالا سرچ کنید">
            <label class="shop-label" style="margin-top:12px">کد/شناسه اکسسوری‌های انتخاب‌شده</label>
            <textarea class="shop-input" name="meta[accessory_ids]" style="min-height:80px">{{ data_get($meta, 'accessory_ids') }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="gallery">
            <h2 class="product-panel-title">افزودن گالری تصویر</h2>
            <label class="shop-label">آپلود تصاویر گالری</label>
            @include('partials.file-drop', [
                'name' => 'gallery_files[]',
                'accept' => 'image/*',
                'title' => 'تصاویر را بکشید و اینجا رها کنید',
                'sub' => 'می‌توانید چند تصویر را همزمان انتخاب کنید',
            ])
            <label class="shop-label" style="margin-top:14px">آدرس تصاویر (هر خط یک URL)</label>
            <textarea class="shop-input" name="gallery_urls" style="min-height:160px" placeholder="http://">{{ old('gallery_urls', collect($product->gallery_urls ?? [])->implode("\n")) }}</textarea>
            <label class="shop-label" style="margin-top:12px">تصویر اصلی (URL)</label>
            <input class="shop-input" type="url" name="meta[main_image]" value="{{ data_get($meta, 'main_image') }}" placeholder="http://">
            <label style="display:flex;gap:8px;align-items:center;margin-top:12px;font-size:13px">
                <input type="checkbox" name="meta[watermark]" value="1" @checked(data_get($meta, 'watermark'))> واترمارک روی تصاویر گالری
            </label>
        </div>

        <div class="shop-card product-tab-panel" data-panel="related">
            <h2 class="product-panel-title">کالاهای مرتبط</h2>
            <label class="shop-label">جستجوی کالاهای مرتبط</label>
            <input class="shop-input" type="text" name="meta[related_search]" value="{{ data_get($meta, 'related_search') }}" placeholder="کالاهای مرتبط">
            <label class="shop-label" style="margin-top:12px">شناسه کالاهای مرتبط (با ویرگول)</label>
            <textarea class="shop-input" name="meta[related_ids]" style="min-height:90px">{{ data_get($meta, 'related_ids') }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="settings">
            <h2 class="product-panel-title">تنظیمات کالا</h2>
            <div class="shop-grid-2">
                <div>
                    <label class="shop-label">محدودیت خرید</label>
                    <input class="shop-input" type="number" name="meta[purchase_limit]" value="{{ data_get($meta, 'purchase_limit') }}">
                </div>
                <div>
                    <label class="shop-label">کف موجودی</label>
                    <input class="shop-input" type="number" name="meta[min_stock]" value="{{ data_get($meta, 'min_stock') }}">
                </div>
                <div>
                    <label class="shop-label">لایک‌ها</label>
                    <input class="shop-input" type="number" name="meta[likes]" value="{{ data_get($meta, 'likes') }}" placeholder="لایک ها">
                </div>
                <div>
                    <label class="shop-label">دیس‌لایک‌ها</label>
                    <input class="shop-input" type="number" name="meta[dislikes]" value="{{ data_get($meta, 'dislikes') }}">
                </div>
                <div>
                    <label class="shop-label">نمایش در جدیدترین / پربازدید</label>
                    <select class="shop-select" name="meta[show_in]">
                        <option value="">—</option>
                        <option @selected(data_get($meta, 'show_in') === 'new')>نمایش در جدیدترین کالا</option>
                        <option @selected(data_get($meta, 'show_in') === 'popular')>نمایش در پربازدیدترین کالا</option>
                        <option @selected(data_get($meta, 'show_in') === 'both')>هر دو</option>
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;justify-content:end">
                    <label style="display:flex;gap:8px;align-items:center;font-size:13px"><input type="checkbox" name="meta[free_shipping]" value="1" @checked(data_get($meta, 'free_shipping'))> ارسال رایگان</label>
                    <label style="display:flex;gap:8px;align-items:center;font-size:13px"><input type="checkbox" name="meta[price_chart]" value="1" @checked(data_get($meta, 'price_chart'))> نمودار قیمت</label>
                    <label style="display:flex;gap:8px;align-items:center;font-size:13px"><input type="checkbox" name="meta[our_offer]" value="1" @checked(data_get($meta, 'our_offer'))> پیشنهاد ما</label>
                </div>
            </div>
        </div>

        <div class="shop-card product-tab-panel" data-panel="warranty">
            <h2 class="product-panel-title">عنوان گارانتی</h2>
            <label class="shop-label">عنوان گارانتی</label>
            <input class="shop-input" type="text" name="meta[warranty_title]" value="{{ data_get($meta, 'warranty_title') }}" placeholder="مثلاً ۱۸ ماه گارانتی شرکتی">
            <label class="shop-label" style="margin-top:12px">توضیحات گارانتی</label>
            <textarea class="shop-input" name="meta[warranty_text]" style="min-height:120px">{{ data_get($meta, 'warranty_text') }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="specs">
            <h2 class="product-panel-title">درج مشخصات تکمیلی</h2>
            <label class="shop-label">مشخصات / توضیحات کامل</label>
            <textarea class="shop-input" name="specifications" style="min-height:220px">{{ old('specifications', $product->specifications) }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="short">
            <h2 class="product-panel-title">توضیحات کوتاه</h2>
            <div class="shop-hint">۶۰۰ کاراکتر اول توضیحات کوتاه در سرچ موتورهای جستجو اهمیت دارد.</div>
            <textarea class="shop-input" name="short_description" style="min-height:160px" placeholder="description">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="discount">
            <h2 class="product-panel-title">تخفیف ویژه</h2>
            <div class="shop-grid-2">
                <div>
                    <label class="shop-label">قیمت با تخفیف (تومان)</label>
                    <input class="shop-input" type="number" name="discounted_price" value="{{ old('discounted_price', $product->discounted_price) }}" min="0">
                </div>
                <div>
                    <label class="shop-label">قیمت عادی (تومان)</label>
                    <input class="shop-input" type="number" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" min="0">
                </div>
                <div>
                    <label class="shop-label">تاریخ انقضا تخفیف</label>
                    <input class="shop-input" type="date" name="meta[discount_expire]" value="{{ data_get($meta, 'discount_expire') }}">
                </div>
                <div style="display:flex;align-items:end">
                    <label style="display:flex;gap:8px;align-items:center;font-size:13px;margin-bottom:10px">
                        <input type="checkbox" name="is_special_discount" value="1" @checked(old('is_special_discount', $product->is_special_discount))>
                        فعالسازی تخفیف ویژه
                    </label>
                </div>
            </div>
        </div>

        <div class="shop-card product-tab-panel" data-panel="featured">
            <h2 class="product-panel-title">کالا برگزیده</h2>
            <label style="display:flex;gap:8px;align-items:center;font-size:14px;margin-bottom:14px">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                فعالسازی کالا برگزیده
            </label>
            <label class="shop-label">چیدمان کالا (عدد کوچکتر = اولویت بالاتر)</label>
            <input class="shop-input" type="number" name="meta[featured_sort]" value="{{ data_get($meta, 'featured_sort', $product->sort_order) }}">
            <label class="shop-label" style="margin-top:12px">تصویر سه بعدی (URL)</label>
            <input class="shop-input" type="url" name="meta[image_3d]" value="{{ data_get($meta, 'image_3d') }}" placeholder="http://">
        </div>

        <div class="shop-card product-tab-panel" data-panel="faq">
            <h2 class="product-panel-title">افزودن پرسش‌های متداول (FAQ)</h2>
            <label class="shop-label">پرسش</label>
            <input class="shop-input" type="text" name="meta[faq_q]" value="{{ data_get($meta, 'faq_q') }}" placeholder="سوال متداول">
            <label class="shop-label" style="margin-top:12px">پاسخ</label>
            <textarea class="shop-input" name="meta[faq_a]" style="min-height:100px">{{ data_get($meta, 'faq_a') }}</textarea>
            <label class="shop-label" style="margin-top:12px">لیست FAQ (هر خط: سوال | پاسخ)</label>
            <textarea class="shop-input" name="meta[faq_list]" style="min-height:140px" placeholder="سوال ۱ | پاسخ ۱">{{ data_get($meta, 'faq_list') }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="upload">
            <h2 class="product-panel-title">آپلود فایل</h2>
            <label class="shop-label">فایل دانلودی / کاتالوگ</label>
            @include('partials.file-drop', [
                'name' => 'download_file',
                'title' => 'فایل را بکشید و اینجا رها کنید',
                'sub' => 'PDF، ZIP یا فایل‌های پیوست محصول',
            ])
            <label class="shop-label" style="margin-top:14px">یا لینک فایل</label>
            <input class="shop-input" type="url" name="meta[file_url]" value="{{ data_get($meta, 'file_url') }}" placeholder="http://">
            <label class="shop-label" style="margin-top:12px">عنوان فایل</label>
            <input class="shop-input" type="text" name="meta[file_title]" value="{{ data_get($meta, 'file_title') }}">
            <label class="shop-label" style="margin-top:12px">توضیح فایل</label>
            <textarea class="shop-input" name="meta[file_note]" style="min-height:80px">{{ data_get($meta, 'file_note') }}</textarea>
        </div>

        <div class="shop-card product-tab-panel" data-panel="social">
            <h2 class="product-panel-title">لینک شبکه‌های اجتماعی</h2>
            <div class="shop-grid-2">
                <div>
                    <label class="shop-label">اینستاگرام</label>
                    <input class="shop-input" type="url" name="meta[instagram]" value="{{ data_get($meta, 'instagram') }}">
                </div>
                <div>
                    <label class="shop-label">تلگرام</label>
                    <input class="shop-input" type="url" name="meta[telegram]" value="{{ data_get($meta, 'telegram') }}">
                </div>
                <div>
                    <label class="shop-label">آپارات / یوتیوب</label>
                    <input class="shop-input" type="url" name="meta[video]" value="{{ data_get($meta, 'video') }}">
                </div>
                <div>
                    <label class="shop-label">لینک اشتراک‌گذاری</label>
                    <input class="shop-input" type="url" name="meta[share_link]" value="{{ data_get($meta, 'share_link') }}">
                </div>
            </div>
        </div>

        <div class="shop-actions sticky-actions">
            <button class="btn btn-green" type="submit">ارسال</button>
            <a class="btn btn-red-outline" href="{{ route('admin.products.index') }}">انصراف</a>
            <span class="product-active-tab-label" id="activeTabLabel">اطلاعات کالا</span>
        </div>
    </div>

    <aside class="product-vtabs" aria-label="بخش‌های ایجاد کالا">
        @foreach($tabs as $tab)
            <button type="button"
                    class="product-vtab {{ $loop->first ? 'active' : '' }}"
                    data-tab="{{ $tab['id'] }}"
                    title="{{ $tab['label'] }}"
                    style="--tab-color: {{ $tab['color'] }}">
                <span class="product-vtab-face">
                    @if($tab['icon'] === 'seo')
                        <span class="vtab-text">seo</span>
                    @elseif($tab['icon'] === 'faq')
                        <span class="vtab-text">FAQ</span>
                    @elseif($tab['icon'] === 'key')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12.65 10A5.99 5.99 0 0 0 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6a5.99 5.99 0 0 0 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
                    @elseif($tab['icon'] === 'boxes' || $tab['icon'] === 'boxes2')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/></svg>
                    @elseif($tab['icon'] === 'image')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14h18zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    @elseif($tab['icon'] === 'dots')
                        <svg viewBox="0 0 24 24"><circle cx="6" cy="12" r="2" fill="currentColor"/><circle cx="12" cy="12" r="2" fill="currentColor"/><circle cx="18" cy="12" r="2" fill="currentColor"/></svg>
                    @elseif($tab['icon'] === 'gear')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54A.5.5 0 0 0 13.9 2h-3.8a.5.5 0 0 0-.5.42l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96a.5.5 0 0 0-.6.22L2.71 8.48a.5.5 0 0 0 .12.64l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94L2.83 14.16a.5.5 0 0 0-.12.64l1.92 3.32c.14.24.41.33.62.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.26.42.5.42h3.8c.24 0 .45-.18.5-.42l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.11.48 0 .62-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.01-1.58zM12 15.6A3.6 3.6 0 1 1 12 8.4a3.6 3.6 0 0 1 0 7.2z"/></svg>
                    @elseif($tab['icon'] === 'checkdoc')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 13.5-2.5 2.5L8 15.5l1.2-1.2 1.3 1.3 3.3-3.3L15 13.5 13 15.5zM13 9V3.5L18.5 9H13z"/></svg>
                    @elseif($tab['icon'] === 'doc')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 15H9v-2h6v2zm0-4H9v-2h6v2zm-1-5V3.5L18.5 9H14z"/></svg>
                    @elseif($tab['icon'] === 'price')
                        <svg viewBox="0 0 24 24"><text x="3" y="17" fill="currentColor" font-size="11" font-weight="700">$%</text></svg>
                    @elseif($tab['icon'] === 'hex')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2l8 4.5v11L12 22l-8-4.5v-11L12 2zm0 3.2L7 8v8l5 2.8L17 16V8l-5-2.8z"/></svg>
                    @elseif($tab['icon'] === 'upload')
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M5 20h14v-2H5v2zm7-18L5.5 9h4.5v6h4V9h4.5L12 2z"/></svg>
                    @else
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M3 10v4h4l5 5V5L7 10H3zm13.5 2A4.5 4.5 0 0 0 14 8.1v7.8a4.48 4.48 0 0 0 2.5-3.9zM14 3.2v2.1a7 7 0 0 1 0 13.4v2.1a9 9 0 0 0 0-17.6z"/></svg>
                    @endif
                </span>
            </button>
        @endforeach
    </aside>
</form>

<script>
(function () {
  var tabs = document.querySelectorAll('.product-vtab');
  var panels = document.querySelectorAll('.product-tab-panel');
  var label = document.getElementById('activeTabLabel');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var id = tab.getAttribute('data-tab');
      tabs.forEach(function (t) { t.classList.remove('active'); });
      panels.forEach(function (p) { p.classList.toggle('active', p.getAttribute('data-panel') === id); });
      tab.classList.add('active');
      if (label) label.textContent = tab.getAttribute('title') || '';
    });
  });
})();
</script>
@endsection
