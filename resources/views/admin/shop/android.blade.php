@extends('layouts.admin')

@section('title', 'مدیریت اَپ اندروید | مدیریت سایت')
@section('tab', 'مدیریت اَپ اندروید')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>مدیریت اَپ اندروید</h1>
</div>

<div class="shop-card">
    <div class="android-box">
        <div class="android-preview">
            <div>
                <div style="font-size:28px;margin-bottom:8px">🤖</div>
                جرمنی مد
                <div style="font-size:12px;opacity:.85;margin-top:8px;font-weight:500">پیش‌نمایش اپ</div>
            </div>
        </div>
        <div>
            <div class="shop-hint">تنظیمات اپلیکیشن اندروید فروشگاه — نام، آیکون، پکیج و لینک دانلود را اینجا مدیریت کنید.</div>
            <div class="shop-grid-2">
                <div>
                    <label class="shop-label">نام اپ</label>
                    <input class="shop-input" type="text" value="جرمنی مد">
                </div>
                <div>
                    <label class="shop-label">نام پکیج</label>
                    <input class="shop-input" type="text" value="com.germanymode.app">
                </div>
                <div>
                    <label class="shop-label">نسخه</label>
                    <input class="shop-input" type="text" value="1.0.0">
                </div>
                <div>
                    <label class="shop-label">وضعیت</label>
                    <select class="shop-select">
                        <option>فعال</option>
                        <option>غیرفعال</option>
                    </select>
                </div>
                <div class="full">
                    <label class="shop-label">لینک دانلود (کافه بازار / مستقیم)</label>
                    <input class="shop-input" type="url" placeholder="https://">
                </div>
                <div class="full">
                    <label class="shop-label">آیکون اپ</label>
                    @include('partials.file-drop', [
                        'name' => 'app_icon',
                        'accept' => 'image/*',
                        'title' => 'آیکون اپ را اینجا رها کنید',
                        'sub' => 'PNG یا JPG مربعی، حداقل ۵۱۲×۵۱۲',
                    ])
                </div>
                <div class="full">
                    <label class="shop-label">توضیحات کوتاه فروشگاه در اپ</label>
                    <textarea class="shop-input" style="min-height:100px">خرید مستقیم از آلمان و اروپا</textarea>
                </div>
            </div>
            <div class="shop-actions">
                <button class="btn btn-green" type="button">ارسال</button>
                <a class="btn btn-red-outline" href="{{ route('admin.dashboard') }}">انصراف</a>
            </div>
        </div>
    </div>
</div>
@endsection
