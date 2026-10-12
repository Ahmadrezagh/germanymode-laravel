@extends('layouts.admin')

@section('title', 'ایجاد کالا با اکسل | مدیریت سایت')
@section('tab', 'ایجاد کالا با اکسل')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>ورود کالا از اکسل</h1>
    <a class="btn-archive" href="{{ route('admin.products.index') }}">انصراف ←</a>
</div>

<div class="shop-info">
    <h3>📦 ورود روزانه کالا از طریق اکسل</h3>
    <p>شما می‌توانید روزانه تا ۵۰۰ کالا را از طریق فایل اکسل وارد سیستم نمایید. برای سهولت کار، لطفاً از نمونه فایل زیر استفاده کنید و اطلاعات خود را طبق آن وارد نمایید.</p>
    <a class="btn btn-blue" href="{{ route('admin.shop.sample-excel') }}">⬇ دانلود نمونه فایل اکسل</a>
    <div class="shop-warn">⚠ اعداد قیمت، وزن، تعداد باید انگلیسی باشد.</div>
</div>

<div class="shop-card">
    <form method="post" action="#" enctype="multipart/form-data" onsubmit="alert('آپلود آزمایشی — فایل خوانده می‌شود اما ایمپورت کامل در مرحله بعد وصل می‌شود.'); return false;">
        @csrf
        <div class="shop-grid-2">
            <div>
                <label class="shop-label">وارد شود به انبار <a href="{{ route('admin.shop.prepare') }}">(درج انبار جدید)</a></label>
                <select class="shop-select" name="warehouse_id">
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="shop-label">شرکت سازنده محصول (نام برند) <a href="{{ route('admin.shop.prepare') }}">(درج برند جدید)</a></label>
                <select class="shop-select" name="brand_id">
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="shop-label">انتخاب گروه کالا <a href="{{ route('admin.shop.categories') }}">(درج گروه کالا جدید)</a></label>
                <select class="shop-select" name="category_id">
                    <option value="">—</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="shop-label">فایل CSV / اکسل</label>
                @include('partials.file-drop', [
                    'name' => 'file',
                    'accept' => '.csv,.xlsx,.xls',
                    'required' => true,
                    'title' => 'فایل اکسل را اینجا رها کنید',
                    'sub' => 'فرمت‌های مجاز: CSV، XLSX، XLS',
                ])
            </div>
            <div>
                <label class="shop-label">انتخاب زیر گروه کالا <a href="{{ route('admin.shop.categories') }}">(درج زیرگروه جدید)</a></label>
                <select class="shop-select" name="sub_category_id">
                    <option value="">—</option>
                    @foreach($categories->take(30) as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="shop-actions">
            <button class="btn btn-green" type="submit">آپلود و نمایش</button>
        </div>
    </form>
</div>
@endsection
