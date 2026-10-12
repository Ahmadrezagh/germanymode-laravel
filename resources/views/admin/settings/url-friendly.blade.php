@extends('layouts.admin')
@section('title', 'تنظیمات لینک‌ها | مدیریت سایت')
@section('tab', 'تنظیمات لینک‌ها')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>تنظیمات لینک‌ها (URL Friendly)</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.url-friendly.store') }}" class="shop-grid-2">@csrf
<div><label class="shop-label">عنوان</label><input class="shop-input" name="title"></div>
<div><label class="shop-label">آدرس قدیمی</label><input class="shop-input" name="old_url" required></div>
<div class="full"><label class="shop-label">آدرس جدید</label><input class="shop-input" name="new_url" required></div>
<div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">ثبت</button></div>
</form></div>
<div class="shop-card"><div class="table-wrap"><table class="shop-table">
<thead><tr><th>عنوان</th><th>قدیمی</th><th>جدید</th><th>وضعیت</th><th>حذف</th></tr></thead>
<tbody>
@forelse($items as $item)
<tr><td>{{ $item->title ?: '—' }}</td><td>{{ $item->old_url }}</td><td>{{ $item->new_url }}</td>
<td><span class="shop-badge green">فعال</span></td>
<td><form method="post" action="{{ route('admin.settings.url-friendly.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form></td></tr>
@empty
<tr><td colspan="5" class="shop-empty">موردی نیست.</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $items->links('pagination.simple') }}</div></div>
@endsection
