@extends('layouts.admin')

@section('title', 'ویرایش سریع کالاها | مدیریت سایت')
@section('tab', 'ویرایش سریع کالاها')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>ویرایش سریع کالا ها</h1>
    <a class="btn btn-default btn-sm" href="{{ route('admin.shop.export-excel') }}">خروجی excel</a>
</div>

<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <select class="shop-select" name="warehouse_id" style="max-width:220px" onchange="this.form.submit()">
            <option value="">کلیه‌ انبارها</option>
            @foreach($warehouses as $w)
                <option value="{{ $w->id }}" @selected(request('warehouse_id') == $w->id)>انبار › {{ $w->name }}</option>
            @endforeach
        </select>
        <select class="shop-select" name="scope" style="max-width:200px" onchange="this.form.submit()">
            <option value="">نمایش همه کالاها</option>
            <option value="placed" @selected(request('scope') === 'placed')>نمایش کالاهای چیده شده</option>
        </select>
        <select class="shop-select" name="per_page" style="max-width:160px" onchange="this.form.submit()">
            @foreach([10,50,100,200,300,500] as $n)
                <option value="{{ $n }}" @selected($perPage === $n)>نمایش {{ $n }} ردیف</option>
            @endforeach
        </select>
        <input class="shop-input grow" type="search" name="q" value="{{ $q }}" placeholder="جستجوی عنوان کالا...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>

    <form method="post" action="{{ route('admin.shop.quick-edit.save') }}">
        @csrf
        <div class="table-wrap">
            <table class="shop-table">
                <thead>
                <tr>
                    <th>تصویر</th>
                    <th>عنوان</th>
                    <th>قیمت تخفیف‌خورده</th>
                    <th>قیمت عادی</th>
                    <th>وضعیت</th>
                    <th>دسته</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img class="shop-mini-thumb" src="{{ $product->image }}" alt="">
                            @endif
                        </td>
                        <td>
                            <input class="shop-input" type="text" name="products[{{ $product->id }}][title]" value="{{ $product->title }}">
                        </td>
                        <td style="min-width:120px">
                            <input class="shop-input" type="number" name="products[{{ $product->id }}][discounted_price]" value="{{ $product->discounted_price }}">
                        </td>
                        <td style="min-width:120px">
                            <input class="shop-input" type="number" name="products[{{ $product->id }}][regular_price]" value="{{ $product->regular_price }}">
                        </td>
                        <td style="min-width:110px">
                            <select class="shop-select" name="products[{{ $product->id }}][status]">
                                <option @selected($product->status === 'موجود')>موجود</option>
                                <option @selected($product->status === 'ناموجود')>ناموجود</option>
                            </select>
                        </td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="shop-empty">کالایی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="shop-actions">
            <button class="btn btn-green" type="submit">ذخیره همه</button>
        </div>
    </form>
    <div class="pagination-wrap">{{ $products->links('pagination.simple') }}</div>
</div>
@endsection
