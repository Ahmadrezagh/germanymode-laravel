@extends('layouts.admin')
@section('title', 'ویرایش کاربری | مدیریت سایت')
@section('tab', 'ویرایش کاربری')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>ویرایش کاربری</h1></div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.interactions.profile.save') }}">
        @csrf
        <div class="shop-grid-2">
            <div><label class="shop-label">نام</label><input class="shop-input" name="name" value="{{ $user->name }}" required></div>
            <div><label class="shop-label">نام خانوادگی</label><input class="shop-input" name="last_name" value="{{ $user->last_name }}"></div>
            <div><label class="shop-label">ایمیل</label><input class="shop-input" type="email" name="email" value="{{ $user->email }}" required></div>
            <div><label class="shop-label">موبایل</label><input class="shop-input" name="mobile" value="{{ $user->mobile }}"></div>
            <div class="full"><label class="shop-label">رمز جدید (اختیاری)</label><input class="shop-input" type="password" name="password"></div>
        </div>
        <div class="shop-actions"><button class="btn btn-green" type="submit">ذخیره</button></div>
    </form>
</div>
@endsection
