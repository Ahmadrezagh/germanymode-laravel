@extends('layouts.admin')
@section('title', 'خبر برگزیده | مدیریت سایت')
@section('tab', 'خبر برگزیده')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>خبر برگزیده</h1></div>
<div class="shop-card"><div class="table-wrap"><table class="shop-table">
<thead><tr><th>تاریخ</th><th>عنوان خبر</th><th>برگزیده</th><th>عملیات</th></tr></thead>
<tbody>
@forelse($posts as $post)
<tr>
<td>{{ optional($post->published_at)->format('Y/m/d') }}</td>
<td>{{ $post->title }}</td>
<td><span class="shop-badge {{ $post->is_featured ? 'green' : 'orange' }}">{{ $post->is_featured ? 'بله' : 'خیر' }}</span></td>
<td><form method="post" action="{{ route('admin.settings.featured-news.toggle', $post) }}">@csrf<button class="btn btn-blue btn-sm" type="submit">تغییر برگزیده</button></form></td>
</tr>
@empty
<tr><td colspan="4" class="shop-empty">خبری نیست.</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $posts->links('pagination.simple') }}</div>
</div>
@endsection
