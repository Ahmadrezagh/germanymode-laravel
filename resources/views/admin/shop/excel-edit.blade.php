@extends('layouts.admin')

@section('title', 'ویرایش کالا با اکسل | مدیریت سایت')
@section('tab', 'ویرایش کالا با اکسل')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>ویرایش کالا از اکسل</h1>
    <a class="btn-archive" href="{{ route('admin.products.index') }}">آرشیو ←</a>
</div>

<div class="shop-hint">
    این بخش نسخه آزمایشی می‌باشد؛ برای استفاده ابتدا روی چند کالا ویرایش انجام دهید تا از نبود مشکل مطمئن گردید.
</div>

<div class="shop-card">
    <p style="margin-top:0;line-height:1.8;color:#555;font-size:13.5px">
        ابتدا آخرین نسخه کالاها را دانلود کنید، در اکسل ویرایش نمایید و فایل را دوباره آپلود کنید.
    </p>
    <a class="btn btn-blue" href="{{ route('admin.shop.export-excel') }}">⬇ دانلود آخرین نسخه کالاها برای ویرایش</a>
    <div class="shop-warn">⚠ اعداد قیمت، وزن، تعداد باید انگلیسی باشد.</div>

    <form method="post" action="#" enctype="multipart/form-data" style="margin-top:18px" onsubmit="alert('آپلود آزمایشی — ایمپورت کامل در مرحله بعد.'); return false;">
        @csrf
        <label class="shop-label">اکسل ویرایش شده</label>
        @include('partials.file-drop', [
            'name' => 'file',
            'accept' => '.csv,.xlsx,.xls',
            'required' => true,
            'title' => 'فایل ویرایش‌شده را اینجا رها کنید',
            'sub' => 'فرمت‌های مجاز: CSV، XLSX، XLS',
        ])
        <div class="shop-actions">
            <button class="btn btn-green" type="submit">آپلود و اعمال</button>
            <a class="btn btn-red-outline" href="{{ route('admin.products.index') }}">انصراف</a>
        </div>
    </form>
</div>
@endsection
