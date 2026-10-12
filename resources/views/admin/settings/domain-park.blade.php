@extends('layouts.admin')
@section('title', 'پارک دامنه | مدیریت سایت')
@section('tab', 'پارک دامنه')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>پارک دامنه</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.domain.save') }}">@csrf
<div class="shop-grid-2">
<div><label class="shop-label">دامنه</label><input class="shop-input" name="domain" value="{{ $domain['domain'] ?? '' }}"></div>
<div><label class="shop-label">DNS</label><input class="shop-input" name="dns" value="{{ $domain['dns'] ?? '' }}"></div>
<div><label class="shop-label">وضعیت</label>
<select class="shop-select" name="status">
@foreach(['pending'=>'در انتظار','active'=>'فعال','error'=>'خطا'] as $k=>$v)
<option value="{{ $k }}" @selected(($domain['status'] ?? '') === $k)>{{ $v }}</option>
@endforeach
</select></div>
</div>
<div class="shop-actions"><button class="btn btn-green" type="submit">ذخیره</button></div>
</form></div>
@endsection
