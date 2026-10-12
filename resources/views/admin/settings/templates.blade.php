@extends('layouts.admin')
@section('title', 'مدیریت قالب‌ها | مدیریت سایت')
@section('tab', 'مدیریت قالب ها')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>مدیریت قالب ها</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.templates.save') }}">@csrf
<div class="shop-grid-2">
@foreach($templates as $tpl)
<label class="shop-card soft" style="cursor:pointer;display:block">
<input type="radio" name="template" value="{{ $tpl['key'] }}" @checked($active === $tpl['key'])>
<strong style="margin-right:8px">{{ $tpl['name'] }}</strong>
@if($active === $tpl['key']) <span class="shop-badge green">فعال</span> @endif
</label>
@endforeach
</div>
<div class="shop-actions"><button class="btn btn-green" type="submit">فعال‌سازی قالب</button></div>
</form></div>
@endsection
