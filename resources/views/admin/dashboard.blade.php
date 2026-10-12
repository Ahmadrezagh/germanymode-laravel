@extends('layouts.admin')

@section('title', 'داشبورد | مدیریت سایت')
@section('tab', 'داشبورد،آموزش')

@section('content')
    <p class="welcome">{{ auth()->user()->name }} خوش آمدید.</p>

    <div class="dash-actions">
        <span class="chip chip-purple">عضویت در کانال بله</span>
        <span class="chip chip-pink">عضویت در کانال ایتا</span>
        <span class="chip chip-blue">چت آنلاین (۹ الی ۱۵)</span>
        <span class="chip chip-teal">ارسال تیکت</span>
        <span class="chip chip-orange">بله / ایتا</span>
    </div>

    <div class="stats">
        <div class="stat-card blue">
            <h3>{{ number_format($stats['products']) }}</h3>
            <p>محصولات</p>
        </div>
        <div class="stat-card green">
            <h3>{{ number_format($stats['categories']) }}</h3>
            <p>دسته‌بندی‌ها</p>
        </div>
        <div class="stat-card yellow">
            <h3>{{ number_format($stats['priced']) }}</h3>
            <p>دارای قیمت</p>
        </div>
        <div class="stat-card red">
            <h3>{{ number_format($stats['users']) }}</h3>
            <p>کاربران</p>
        </div>
    </div>

    <div class="checklist">
        <div class="checklist-head">چک لیست راه‌اندازی سایت فروشگاهی</div>
        <div class="checklist-grid">
            <div class="check-card">
                <span class="num">1</span>
                <h3>ایجاد کالا</h3>
                <p>از قسمت مدیریت فروشگاه &gt; ایجاد کالا، کالاهای خود را اضافه کنید.</p>
                <a href="{{ route('admin.products.create') }}">برو به این قسمت!</a>
            </div>
            <div class="check-card">
                <span class="num">2</span>
                <h3>ساخت منو و چیدمان محصول</h3>
                <p>دسته‌بندی را مرتب بسازید و محصولات را در دسته خود بچینید.</p>
                <a href="{{ route('admin.categories.index') }}">برو به این قسمت!</a>
            </div>
            <div class="check-card">
                <span class="num">3</span>
                <h3>درج عنوان سایت</h3>
                <p>از تنظیمات سایت &gt; تنظیمات اصلی، اسم فروشگاه را به فارسی وارد کنید.</p>
                <a href="{{ route('admin.settings') }}">برو به این قسمت!</a>
            </div>
            <div class="check-card">
                <span class="num">4</span>
                <h3>درج دیسکریپشن سایت</h3>
                <p>یک جمله توضیحی درباره فعالیت فروشگاه برای سئو بنویسید.</p>
                <a href="{{ route('admin.settings') }}">برو به این قسمت!</a>
            </div>
            <div class="check-card">
                <span class="num">5</span>
                <h3>تنظیمات سایت</h3>
                <p>لوگو، آدرس، شماره تماس و توضیحات پروفایل را تکمیل کنید.</p>
                <a href="{{ route('admin.settings') }}">برو به این قسمت!</a>
            </div>
            <div class="check-card">
                <span class="num">6</span>
                <h3>نمایش سایت</h3>
                <p>برای مشاهده تغییرات اعمال‌شده روی فروشگاه کلیک کنید.</p>
                <a href="{{ route('shop.home') }}" target="_blank">برو به این قسمت!</a>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>آخرین محصولات</h2>
            <a class="btn btn-green btn-sm" href="{{ route('admin.products.create') }}">ایجاد کالا</a>
        </div>
        <div class="panel-body table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>#</th>
                    <th>تصویر</th>
                    <th>عنوان</th>
                    <th>قیمت</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($latestProducts as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img class="thumb-sm" src="{{ $product->image }}" alt="">
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($product->title, 70) }}</td>
                        <td>{{ $product->formattedPrice() }}</td>
                        <td><a class="btn btn-default btn-sm" href="{{ route('admin.products.edit', $product) }}">ویرایش</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
