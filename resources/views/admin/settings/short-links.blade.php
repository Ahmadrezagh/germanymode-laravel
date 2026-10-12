@extends('layouts.admin')
@section('title', 'کوتاه کننده لینک | مدیریت سایت')
@section('tab', 'کوتاه کننده لینک')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>کوتاه کننده لینک</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.short-links.store') }}" class="shop-grid-2">@csrf
<div class="full"><label class="shop-label">لینک اصلی</label><input class="shop-input" name="original_url" required></div>
<div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">+ ساخت لینک</button></div>
</form></div>
<div class="shop-card"><div class="table-wrap"><table class="shop-table">
<thead><tr><th>لینک اصلی</th><th>لینک کوتاه</th><th>بازدید</th><th>حذف</th></tr></thead>
<tbody>
@forelse($links as $link)
<tr><td>{{ \Illuminate\Support\Str::limit($link->original_url, 60) }}</td><td>/s/{{ $link->code }}</td><td>{{ $link->visits }}</td>
<td><form method="post" action="{{ route('admin.settings.short-links.destroy', $link) }}">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form></td></tr>
@empty
<tr><td colspan="4" class="shop-empty">لینکی نیست.</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $links->links('pagination.simple') }}</div></div>
@endsection
