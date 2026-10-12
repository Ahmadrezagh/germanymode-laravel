@extends('layouts.admin')
@section('title', 'پاورقی موبایل | مدیریت سایت')
@section('tab', 'تنظیمات پاورقی موبایل')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>تنظیمات پاورقی موبایل</h1></div>
<div class="shop-card">
<form method="post" action="{{ route('admin.settings.mobile-footer.store') }}" class="shop-grid-2">@csrf
<div><label class="shop-label">عنوان</label><input class="shop-input" name="title" required></div>
<div><label class="shop-label">لینک</label><input class="shop-input" name="url"></div>
<div><label class="shop-label">آیکون</label><input class="shop-input" name="icon" placeholder="home / cart / user"></div>
<div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">افزودن</button></div>
</form>
</div>
<div class="shop-card"><div class="table-wrap"><table class="shop-table">
<thead><tr><th>آیکون</th><th>عنوان</th><th>لینک</th><th>ترتیب</th><th>عملیات</th></tr></thead>
<tbody>
@forelse($items as $item)
<tr><td>{{ $item->icon ?: '—' }}</td><td>{{ $item->title }}</td><td>{{ $item->url }}</td><td>{{ $item->sort_order }}</td>
<td><form method="post" action="{{ route('admin.settings.mobile-footer.destroy', $item) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form></td></tr>
@empty
<tr><td colspan="5" class="shop-empty">آیتمی نیست.</td></tr>
@endforelse
</tbody></table></div></div>
@endsection
