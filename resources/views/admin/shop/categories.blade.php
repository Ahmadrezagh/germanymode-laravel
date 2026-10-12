@extends('layouts.admin')

@section('title', 'دسته بندی کالاها | مدیریت سایت')
@section('tab', 'دسته بندی کالاها')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>
        چیدمان طبقات فروشگاهی (دسته بندی کالاها)
        <small style="display:block;font-size:12px;color:#777;font-weight:500;margin-top:4px">این بخش سرمنو می‌باشد — آرشیوی از محصولات زیر منو</small>
    </h1>
    <a class="btn-archive" href="{{ route('admin.products.create') }}">+ ایجاد کالا</a>
</div>

<div class="shop-card" style="padding-bottom:10px">
    <div class="shop-subhead" style="margin-top:0">
        <h3 style="color:#333">توضیحات صفحه آرشیو</h3>
        <button class="btn btn-blue btn-sm" type="button">نمایش محتوای آرشیو</button>
    </div>
</div>

<div class="shop-card">
    <div class="shop-hint">
        ✓ کاربری گرامی، برای مرتب‌سازی طبقات فروشگاهی می‌توانید از طریق درگ کردن ردیف‌ها (با موس بگیرید و بکشید) اقدام نمایید.
        پس از جابجایی دکمه ثبت جابجایی ظاهر شده، در پایان روی آن کلیک نمایید.
    </div>

    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" type="search" name="q" value="{{ $q }}" placeholder="شرکت سازنده یا گروه کالا یا مدل یا عنوان فروشگاهی">
        <button class="btn btn-blue" type="submit">جستجو</button>
        <a class="btn btn-red-outline" href="{{ route('admin.shop.categories') }}">انصراف</a>
    </form>

    <div class="shop-subhead">
        <h3>کالاهای چیده شده در طبقه (دسته بندی کالاها)</h3>
        <div style="display:flex;gap:8px">
            <a class="btn btn-green btn-sm" href="{{ route('admin.categories.create') }}">☰ چیدمان / دسته جدید</a>
            <button class="btn btn-default btn-sm" type="button">ویرایش قیمت</button>
        </div>
    </div>

    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>#</th>
                <th>نام دسته</th>
                <th>والد</th>
                <th>تعداد محصول</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent?->name ?? '—' }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td style="white-space:nowrap">
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.categories.edit', $category) }}">ویرایش</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="post" style="display:inline" onsubmit="return confirm('حذف شود؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="shop-empty">دسته‌ای یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $categories->links('pagination.simple') }}</div>
</div>
@endsection
