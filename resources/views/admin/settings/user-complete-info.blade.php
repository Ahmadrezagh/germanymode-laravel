@extends('layouts.admin')
@section('title', 'تکمیل اطلاعات کاربران | مدیریت سایت')
@section('tab', 'تکمیل اطلاعات کاربران')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
<div class="shop-page-head"><h1>تکمیل اطلاعات کاربران</h1></div>
<div class="shop-card"><div class="shop-grid-2">
@foreach($fields as $field)
<div><label class="shop-label">{{ $field }}</label><input class="shop-input" disabled placeholder="فیلد فرم کاربری"></div>
@endforeach
</div>
<p style="color:#7a8a9c;font-size:13px;margin-top:12px">این صفحه چیدمان فیلدهای سمت کاربر را نشان می‌دهد.</p>
</div>
@endsection
