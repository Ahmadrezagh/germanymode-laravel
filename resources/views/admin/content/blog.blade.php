@extends('layouts.admin')
@section('title', 'بلاگ | مدیریت سایت')
@section('tab', 'بلاگ')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head">
    <h1>آرشیو بلاگ</h1>
</div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.content.posts.store') }}" class="shop-grid-2">
        @csrf
        <input type="hidden" name="type" value="blog">
        <div class="full"><label class="shop-label">عنوان</label><input class="shop-input" name="title" required></div>
        <div><label class="shop-label">نویسنده</label><input class="shop-input" name="author" value="مدیر سایت"></div>
        <div class="full"><label class="shop-label">محتوا</label><textarea class="shop-input" name="body" style="min-height:90px"></textarea></div>
        <div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">+ خبر جدید</button></div>
    </form>
</div>
<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" name="q" value="{{ $q }}" placeholder="جستجو...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>تاریخ</th><th>بازدید</th><th>عدم نمایش</th><th>عنوان</th><th>نویسنده</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ optional($post->published_at)->format('Y/m/d H:i') }}</td>
                    <td>{{ number_format($post->views) }}</td>
                    <td>{{ $post->is_hidden ? 'بله' : '—' }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->author }}</td>
                    <td>
                        <div class="row-actions">
                            <form method="post" action="{{ route('admin.content.posts.toggle', $post) }}">@csrf<button class="btn btn-default btn-sm" type="submit">نمایش/مخفی</button></form>
                            <form method="post" action="{{ route('admin.content.posts.destroy', $post) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="shop-empty">مطلبی نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $posts->links('pagination.simple') }}</div>
</div>
@endsection
