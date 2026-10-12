@extends('layouts.admin')
@section('title', 'استوری سایت | مدیریت سایت')
@section('tab', 'تنظیمات استوری سایت')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>تنظیمات استوری سایت</h1><span class="shop-badge blue">بازدید استوری‌ها {{ number_format($totalViews) }}</span></div>
<div class="shop-card">
<form method="post" action="{{ route('admin.settings.stories.store') }}" class="shop-grid-2">@csrf
<div><label class="shop-label">نام پروفایل</label><input class="shop-input" name="profile_name" required></div>
<div><label class="shop-label">عنوان محتوای استوری</label><input class="shop-input" name="title"></div>
<div class="full"><label class="shop-label">توضیح کوتاه</label><input class="shop-input" name="short_text"></div>
<div><label class="shop-label">عنوان لینک</label><input class="shop-input" name="link_title"></div>
<div><label class="shop-label">لینک استوری</label><input class="shop-input" name="link_url"></div>
<div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">افزودن</button></div>
</form>
</div>
<div class="shop-card">
<div class="table-wrap"><table class="shop-table">
<thead><tr><th>#</th><th>پروفایل</th><th>عنوان</th><th>بازدید</th><th>وضعیت</th><th>عملیات</th></tr></thead>
<tbody>
@forelse($stories as $story)
<tr>
<td>{{ $story->id }}</td><td>{{ $story->profile_name }}</td><td>{{ $story->title ?: '—' }}</td>
<td>{{ number_format($story->views) }}</td>
<td><span class="shop-badge {{ $story->is_active ? 'green' : 'red' }}">{{ $story->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
<td><form method="post" action="{{ route('admin.settings.stories.destroy', $story) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form></td>
</tr>
@empty
<tr><td colspan="6" class="shop-empty">استوری نیست.</td></tr>
@endforelse
</tbody></table></div>
</div>
@endsection
