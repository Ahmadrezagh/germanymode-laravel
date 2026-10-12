@extends('layouts.admin')

@section('title', 'چیدمان تخفیف ویژه | مدیریت سایت')
@section('tab', 'چیدمان تخفیف ویژه')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>مرتب سازی کالای تخفیف ویژه</h1>
</div>

<div class="shop-hint">
    ✓ کاربری گرامی، برای مرتب‌سازی کالای تخفیف ویژه می‌توانید از طریق درگ کردن ردیف‌ها اقدام کنید.
    پس از جابجایی دکمه ثبت جابجایی ظاهر شده، در پایان روی آن کلیک نمایید.
    برای تغییر تصاویر اسلایدر، از بخش ویرایش کالا اقدام کنید.
</div>

<div class="shop-card">
    <div class="shop-subhead">
        <h3>تخفیف های تصویر دار</h3>
        <button class="btn btn-green btn-sm" type="button">☰ ذخیره ترتیب</button>
    </div>
    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>چیدمان</th>
                <th>تصویر</th>
                <th>طبقه فروشگاهی</th>
                <th>عنوان فروشگاهی</th>
                <th>تنظیمات</th>
                <th>انقضا</th>
                <th>وضعیت</th>
                <th>پیشنویس</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $i => $product)
                <tr>
                    <td>{{ $product->sort_order ?: ($products->firstItem() + $i) }}</td>
                    <td>
                        @if($product->image)
                            <img class="shop-mini-thumb" src="{{ $product->image }}" alt="">
                        @endif
                    </td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($product->title, 70) }}</td>
                    <td>
                        <span class="shop-badge orange">تخفیف ویژه</span>
                        <span class="shop-badge blue">پیشنهاد فروشگاه</span>
                        <span class="shop-badge green">ارسال رایگان</span>
                    </td>
                    <td>—</td>
                    <td><span class="shop-badge green">{{ $product->status ?: 'موجود' }}</span></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.products.edit', $product) }}">ویرایش</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="shop-empty">کالای تخفیف‌داری نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $products->links('pagination.simple') }}</div>
</div>
@endsection
