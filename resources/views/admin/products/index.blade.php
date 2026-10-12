@extends('layouts.admin')

@section('title', 'آرشیو کالاها | مدیریت سایت')
@section('tab', 'آرشیو کالاها')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">

<div class="shop-page-head">
    <h1>آرشیو کالا</h1>
    <a class="btn btn-green btn-sm" href="{{ route('admin.products.create') }}">+ کالای جدید</a>
</div>

<div class="shop-card">
    <div class="shop-hint" style="margin-bottom:12px">
        در لیست زیر فقط ۲۰۰۰ کالای آخر درج‌شده نمایش داده می‌شود؛ برای یافتن کالا می‌توانید آن را جستجو نمایید.
    </div>

    <form class="shop-toolbar" method="get">
        <select class="shop-select" name="warehouse_id" style="max-width:220px" onchange="this.form.submit()">
            <option value="">همه انبارها</option>
            @foreach(($warehouses ?? []) as $w)
                <option value="{{ $w->id }}" @selected(request('warehouse_id') == $w->id)>{{ $w->name }}</option>
            @endforeach
        </select>
        <select class="shop-select" name="per_page" style="max-width:160px" onchange="this.form.submit()">
            @foreach([10,50,100,200,300,500] as $n)
                <option value="{{ $n }}" @selected((int)request('per_page', 20) === $n)>نمایش {{ $n }} ردیف</option>
            @endforeach
        </select>
        <input class="shop-input grow" type="search" name="q" value="{{ $q }}" placeholder="جستجو...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>

    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>عنوان</th>
                <th>دسته</th>
                <th>انبار</th>
                <th>قیمت</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->image)
                            <img class="shop-mini-thumb" src="{{ $product->image }}" alt="">
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($product->title, 80) }}</td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ $product->warehouse?->name ?? '—' }}</td>
                    <td>{{ $product->formattedPrice() }}</td>
                    <td>
                        <span class="shop-badge {{ ($product->status ?? 'موجود') === 'موجود' ? 'green' : 'red' }}">
                            {{ $product->status ?? 'موجود' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.products.edit', $product) }}">ویرایش</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="shop-empty">محصولی نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $products->links('pagination.simple') }}</div>
</div>
@endsection
