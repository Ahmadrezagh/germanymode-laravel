@extends('layouts.admin')

@section('title', 'کالای برگزیده | مدیریت سایت')
@section('tab', 'کالای برگزیده')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>مرتب سازی کالای برگزیده</h1>
</div>

<div class="shop-hint">
    ✓ کاربری گرامی، برای مرتب‌سازی کالای برگزیده می‌توانید از طریق درگ کردن ردیف‌ها اقدام کنید.
    پس از جابجایی دکمه ثبت جابجایی ظاهر شده، در پایان روی آن کلیک نمایید.
</div>

<div class="shop-card">
    <div class="shop-subhead">
        <h3>کالاهای برگزیده</h3>
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
                        <span class="shop-badge blue">کالای برگزیده</span>
                        @if($product->has_discount)
                            <span class="shop-badge orange">تخفیف ویژه</span>
                        @endif
                        <span class="shop-badge green">ارسال رایگان</span>
                    </td>
                    <td><span class="shop-badge green">{{ $product->status ?: 'موجود' }}</span></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.products.edit', $product) }}">ویرایش</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="shop-empty">کالای برگزیده‌ای نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $products->links('pagination.simple') }}</div>
</div>
@endsection
