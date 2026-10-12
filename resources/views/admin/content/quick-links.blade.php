@extends('layouts.admin')
@section('title', 'دسترسی سریع | مدیریت سایت')
@section('tab', 'دسترسی سریع')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>بخش پیوند دسترسی سریع</h1></div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.content.quick-links.store') }}" class="shop-grid-2">
        @csrf
        <div><label class="shop-label">عنوان</label><input class="shop-input" name="title" required></div>
        <div><label class="shop-label">آدرس url</label><input class="shop-input" name="url" placeholder="https://"></div>
        <div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">ارسال</button></div>
    </form>
</div>
<div class="shop-card">
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>#</th><th>عنوان</th><th>لینک</th><th>ترتیب</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($links as $link)
                <tr>
                    <td>{{ $link->id }}</td>
                    <td>{{ $link->title }}</td>
                    <td>{{ $link->url ?: '—' }}</td>
                    <td>{{ $link->sort_order }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.content.quick-links.destroy', $link) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="shop-empty">لینکی نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
