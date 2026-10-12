@extends('layouts.admin')
@section('title', 'افزونه سبد خرید | مدیریت سایت')
@section('tab', 'افزونه سبد خرید')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>افزونه سبد خرید</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.cart.save') }}">@csrf
<div class="shop-grid-2">
<div><label class="shop-label">قالب سبد خرید</label>
<select class="shop-select" name="template">
@foreach(['default'=>'پیش‌فرض','modern'=>'مدرن','compact'=>'فشرده'] as $k=>$v)
<option value="{{ $k }}" @selected(($cart['template'] ?? '') === $k)>{{ $v }}</option>
@endforeach
</select></div>
<label style="display:flex;gap:8px;align-items:center;font-weight:700"><input type="checkbox" name="auto_post" value="1" @checked(!empty($cart['auto_post']))> انتخاب اتوماتیک اولین اولویت پست</label>
</div>
<div class="shop-actions"><button class="btn btn-green" type="submit">ذخیره</button></div>
</form></div>
@endsection
