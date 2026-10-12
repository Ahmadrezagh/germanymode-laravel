@extends('layouts.admin')
@section('title', 'ویرایش اطلاعات کاربری | مدیریت سایت')
@section('tab', 'ویرایش اطلاعات کاربری')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
<div class="shop-page-head"><h1>ویرایش اطلاعات کاربری</h1></div>
<div class="shop-card"><div class="shop-grid-2">
@foreach(['نام','نام خانوادگی','موبایل','آدرس','کدپستی'] as $f)
<div class="{{ $f === 'آدرس' ? 'full' : '' }}"><label class="shop-label">{{ $f }}</label>@if($f==='آدرس')<textarea class="shop-input" disabled></textarea>@else<input class="shop-input" disabled>@endif</div>
@endforeach
</div></div>
@endsection
