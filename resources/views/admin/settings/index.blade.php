@extends('layouts.admin')

@section('title', 'تنظیمات اصلی | مدیریت سایت')
@section('tab', 'تنظیمات اصلی')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')

<div class="shop-page-head">
    <h1>تنظیمات اصلی</h1>
</div>

<form method="post" action="{{ route('admin.settings.save') }}">
    @csrf
    <div class="shop-card">
        <div class="shop-grid-2">
            <div>
                <label class="shop-label">عنوان سایت (تگ title)</label>
                <input class="shop-input" name="site_title" value="{{ $settings['site_title'] }}">
            </div>
            <div>
                <label class="shop-label">نام سایت شما</label>
                <input class="shop-input" name="site_name" value="{{ $settings['site_name'] }}">
            </div>
            <div>
                <label class="shop-label">شماره تماس</label>
                <input class="shop-input" name="site_phone" value="{{ $settings['site_phone'] }}">
            </div>
            <div>
                <label class="shop-label">تعداد نمایش صفحه‌بندی</label>
                <select class="shop-select" name="per_page">
                    @foreach([2,3,4,5,6,8,9,10,11,12] as $n)
                        <option value="{{ $n }}" @selected((int)$settings['per_page'] === $n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                <label class="shop-label">دیسکریپشن سایت</label>
                <textarea class="shop-input" name="site_description" style="min-height:90px">{{ $settings['site_description'] }}</textarea>
            </div>
            <div class="full">
                <label class="shop-label">آدرس محل فعالیت</label>
                <textarea class="shop-input" name="site_address" style="min-height:80px">{{ $settings['site_address'] }}</textarea>
            </div>
        </div>
    </div>

    <div class="shop-card">
        <h3 style="margin:0 0 14px;color:#1a5f9e">بخش‌های سایت</h3>
        <div class="shop-grid-2">
            @foreach([
                'stats_enabled' => 'بخش آمار سایت',
                'auto_archive' => 'بارگزاری اتوماتیک صفحات آرشیو',
                'search_enabled' => 'بخش جستجو',
                'vertical_menu' => 'منوی عمودی',
                'horizontal_menu' => 'منوی افقی',
                'user_menu' => 'منوی کاربران',
                'enamad' => 'نماد اعتماد الکترونیکی',
                'samandehi' => 'نماد ساماندهی',
                'weather_enabled' => 'وضعیت آب و هوا',
                'prayer_enabled' => 'اوقات شرعی',
            ] as $key => $label)
                <label style="display:flex;gap:8px;align-items:center;font-weight:600;color:#314356">
                    <input type="checkbox" name="{{ $key }}" value="1" @checked(!empty($settings[$key]))>
                    {{ $label }}
                </label>
            @endforeach
            <div>
                <label class="shop-label">شهر پیش‌فرض آب و هوا</label>
                <input class="shop-input" name="weather_city" value="{{ $settings['weather_city'] }}">
            </div>
            <div>
                <label class="shop-label">شهر پیش‌فرض اوقات شرعی</label>
                <input class="shop-input" name="prayer_city" value="{{ $settings['prayer_city'] }}">
            </div>
        </div>
        <div class="shop-actions">
            <button class="btn btn-green" type="submit">ارسال</button>
        </div>
    </div>
</form>
@endsection
