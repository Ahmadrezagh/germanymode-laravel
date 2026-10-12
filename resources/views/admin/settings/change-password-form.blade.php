@extends('layouts.admin')
@section('title', 'تغییر رمز عبور | مدیریت سایت')
@section('tab', 'تغییر رمز عبور')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
<div class="shop-page-head"><h1>تغییر رمز عبور</h1></div>
<div class="shop-card"><div class="shop-grid-2">
@foreach(['رمز فعلی','رمز جدید','تکرار رمز جدید'] as $f)
<div><label class="shop-label">{{ $f }}</label><input class="shop-input" type="password" disabled></div>
@endforeach
</div></div>
@endsection
