@extends('layouts.admin')
@section('title', 'فرم ساز عضویت | مدیریت سایت')
@section('tab', 'فرم ساز عضویت')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>فرم ساز عضویت</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.signup-form.save') }}">@csrf
<div class="shop-grid-2">
@foreach(['name'=>'نام','last_name'=>'نام خانوادگی','mobile'=>'موبایل','email'=>'ایمیل','password'=>'رمز عبور','national_code'=>'کد ملی','address'=>'آدرس'] as $k=>$label)
<label style="display:flex;gap:8px;align-items:center;font-weight:700"><input type="checkbox" name="{{ $k }}" value="1" @checked(!empty($fields[$k]))> {{ $label }}</label>
@endforeach
</div>
<div class="shop-actions"><button class="btn btn-green" type="submit">ذخیره</button></div>
</form></div>
@endsection
