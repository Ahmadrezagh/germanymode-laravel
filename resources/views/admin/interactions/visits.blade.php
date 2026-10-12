@extends('layouts.admin')
@section('title', 'آمار بازدید | مدیریت سایت')
@section('tab', 'آمار بازدید')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>آمار بازدید</h1></div>
<div class="shop-grid-2" style="margin-bottom:16px">
    <div class="shop-card"><div style="color:#7a8a9c;font-size:12px">کل بازدید</div><div style="font-size:28px;font-weight:800;color:#1a5f9e">{{ number_format($totals['hits']) }}</div></div>
    <div class="shop-card"><div style="color:#7a8a9c;font-size:12px">بازدیدکننده یکتا</div><div style="font-size:28px;font-weight:800;color:#2e7d32">{{ number_format($totals['unique']) }}</div></div>
    <div class="shop-card"><div style="color:#7a8a9c;font-size:12px">امروز</div><div style="font-size:28px;font-weight:800;color:#e65100">{{ number_format($totals['today']) }}</div></div>
</div>
<div class="shop-card">
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>تاریخ</th><th>صفحه</th><th>بازدید</th><th>یکتا</th></tr></thead>
            <tbody>
            @forelse($days as $day)
                <tr>
                    <td>{{ $day->visited_on }}</td>
                    <td>{{ $day->path ?: '/' }}</td>
                    <td>{{ number_format($day->hits) }}</td>
                    <td>{{ number_format($day->unique_hits) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="shop-empty">آماری ثبت نشده.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
