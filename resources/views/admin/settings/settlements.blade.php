@extends('layouts.admin')
@section('title', 'واریزی کاربران | مدیریت سایت')
@section('tab', 'واریزی کاربران')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>واریزی کاربران</h1></div>
<div class="shop-card">
<form class="shop-toolbar" method="get"><input class="shop-input grow" name="q" value="{{ $q }}" placeholder="جستجو فاکتور / خریدار"><button class="btn btn-blue" type="submit">جستجو</button></form>
<div class="table-wrap"><table class="shop-table">
<thead><tr><th>تاریخ</th><th>ش فاکتور</th><th>خریدار</th><th>مبلغ</th><th>تایید</th><th>عملیات</th></tr></thead>
<tbody>
@forelse($items as $item)
<tr>
<td>{{ optional($item->paid_at ?: $item->created_at)->format('Y/m/d H:i') }}</td>
<td>{{ $item->invoice_no }}</td>
<td>{{ $item->buyer_name ?: ($item->user?->fullName() ?: '—') }}</td>
<td>{{ number_format($item->amount) }} تومان</td>
<td><span class="shop-badge {{ $item->is_confirmed ? 'green' : 'orange' }}">{{ $item->is_confirmed ? 'تایید شده' : 'در انتظار' }}</span></td>
<td>@unless($item->is_confirmed)<form method="post" action="{{ route('admin.settings.settlements.confirm', $item) }}">@csrf<button class="btn btn-green btn-sm" type="submit">تایید</button></form>@endunless</td>
</tr>
@empty
<tr><td colspan="6" class="shop-empty">واریزی نیست.</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $items->links('pagination.simple') }}</div>
</div>
@endsection
