@extends('layouts.admin')
@section('title', 'گالری | مدیریت سایت')
@section('tab', 'گالری موزیک و کلیپ')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>گالری موزیک و کلیپ</h1></div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.content.posts.store') }}" class="shop-grid-2">
        @csrf
        <input type="hidden" name="type" value="gallery">
        <div class="full"><label class="shop-label">عنوان</label><input class="shop-input" name="title" required></div>
        <div class="full"><label class="shop-label">توضیح</label><textarea class="shop-input" name="body"></textarea></div>
        <div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">+ جدید</button></div>
    </form>
</div>
<div class="shop-card">
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>تاریخ</th><th>عنوان</th><th>بازدید</th><th>عدم نمایش</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ optional($item->created_at)->format('Y/m/d') }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ number_format($item->views) }}</td>
                    <td>{{ $item->is_hidden ? 'بله' : '—' }}</td>
                    <td>
                        <div class="row-actions">
                            <form method="post" action="{{ route('admin.content.posts.toggle', $item) }}">@csrf<button class="btn btn-default btn-sm" type="submit">نمایش/مخفی</button></form>
                            <form method="post" action="{{ route('admin.content.posts.destroy', $item) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="shop-empty">موردی نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $items->links('pagination.simple') }}</div>
</div>
@endsection
