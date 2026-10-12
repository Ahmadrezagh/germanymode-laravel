@extends('layouts.admin')

@section('title', 'تخفیف های فروشگاه | مدیریت سایت')
@section('tab', 'تخفیف های فروشگاه')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>ایجاد انبار/برند کالا</h1>
</div>

<div class="shop-dual">
    <div class="shop-card">
        <h2>ایجاد انبار</h2>
        <div class="shop-hint">
            در این بخش انبارهایی را که برای وارد کردن کالا به آن‌ها نیاز دارید ایجاد نمایید.
            <span class="must">(افزودن یک انبار اجباری می‌باشد)</span>
        </div>
        <form method="post" action="{{ route('admin.shop.warehouses.store') }}">
            @csrf
            <label class="shop-label gold">نام انبار</label>
            <input class="shop-input" type="text" name="name" placeholder="انبار نام/شماره..." required>
            <div class="shop-actions">
                <button class="btn btn-green" type="submit">✈ ارسال</button>
                <button class="btn btn-red-outline" type="reset">✕ انصراف</button>
            </div>
        </form>
        <div class="table-wrap" style="margin-top:16px">
            <table class="shop-table">
                <thead>
                <tr>
                    <th>نام انبار</th>
                    <th>ویرایش</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->name }}</td>
                        <td><span class="ico-btn" title="ویرایش">✎</span></td>
                        <td>
                            <form method="post" action="{{ route('admin.shop.warehouses.destroy', $warehouse) }}" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="ico-btn" type="submit" title="حذف">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $warehouses->links('pagination.simple') }}</div>
    </div>

    <div class="shop-card">
        <h2>ایجاد شرکت های سازنده (برندها)</h2>
        <div class="shop-hint">
            در این بخش نام برندهایی را که برای وارد کردن کالا به آن‌ها نیاز دارید ایجاد نمایید.
            <span class="must">(افزودن یک برند اجباری می‌باشد)</span>
        </div>
        <form method="post" action="{{ route('admin.shop.brands.store') }}">
            @csrf
            <label class="shop-label gold">نام برند</label>
            <input class="shop-input" type="text" name="name" placeholder="مثلاً سامسونگ" required>
            <div class="shop-actions">
                <button class="btn btn-green" type="submit">✈ ارسال</button>
                <button class="btn btn-red-outline" type="reset">✕ انصراف</button>
            </div>
        </form>
        <div class="table-wrap" style="margin-top:16px">
            <table class="shop-table">
                <thead>
                <tr>
                    <th>نام برند</th>
                    <th>ویرایش</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>{{ $brand->name }}</td>
                        <td><span class="ico-btn" title="ویرایش">✎</span></td>
                        <td>
                            <form method="post" action="{{ route('admin.shop.brands.destroy', $brand) }}" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="ico-btn" type="submit" title="حذف">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $brands->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
