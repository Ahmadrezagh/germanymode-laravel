@extends('layouts.admin')
@section('title', 'تولبار | مدیریت سایت')
@section('tab', 'تنظیمات تولبار')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>تنظیمات تولبار</h1></div>
<div class="shop-card">
<form method="post" action="{{ route('admin.settings.toolbar.save') }}">@csrf
<div class="shop-grid-2">
<div class="full"><label class="shop-label">متن تولبار</label><input class="shop-input" name="text" value="{{ $toolbar['text'] ?? '' }}"></div>
<div><label class="shop-label">رنگ پس‌زمینه</label><input class="shop-input" type="color" name="bg" value="{{ $toolbar['bg'] ?? '#dd4b39' }}"></div>
<div><label class="shop-label">رنگ متن</label><input class="shop-input" type="color" name="color" value="{{ $toolbar['color'] ?? '#ffffff' }}"></div>
<div><label class="shop-label">اندازه متن</label>
<select class="shop-select" name="size">
@foreach(['14px','16px','18px','20px','22px'] as $s)
<option value="{{ $s }}" @selected(($toolbar['size'] ?? '') === $s)>{{ $s }}</option>
@endforeach
</select></div>
<div><label class="shop-label">لینک</label><input class="shop-input" name="link" value="{{ $toolbar['link'] ?? '' }}"></div>
<div><label class="shop-label">باز شدن لینک</label>
<select class="shop-select" name="target">
<option value="_self" @selected(($toolbar['target'] ?? '') === '_self')>همان صفحه</option>
<option value="_blank" @selected(($toolbar['target'] ?? '') === '_blank')>تب جدید</option>
</select></div>
<div><label class="shop-label">افکت متن</label>
<select class="shop-select" name="text_effect">
@foreach([''=>'بدون افکت','typing'=>'typing','marquee'=>'marquee','Glow'=>'Glow','Shadow'=>'Shadow','Bounce'=>'Bounce','Flash'=>'Flash','Wave'=>'Wave','Zoom'=>'Zoom'] as $k=>$v)
<option value="{{ $k }}" @selected(($toolbar['text_effect'] ?? '') === $k)>{{ $v }}</option>
@endforeach
</select></div>
<div><label class="shop-label">افکت بک‌گراند</label>
<select class="shop-select" name="bg_effect">
@foreach([''=>'بدون افکت','gradient'=>'Gradient متحرک','Pulse'=>'Pulse','Rainbow'=>'Rainbow','Dark Move'=>'Dark Move','Shine'=>'Shine'] as $k=>$v)
<option value="{{ $k }}" @selected(($toolbar['bg_effect'] ?? '') === $k)>{{ $v }}</option>
@endforeach
</select></div>
<label style="display:flex;gap:8px;align-items:center;font-weight:700"><input type="checkbox" name="enabled" value="1" @checked(!empty($toolbar['enabled']))> فعال</label>
</div>
<div style="margin:16px 0;padding:14px;border-radius:10px;text-align:center;background:{{ $toolbar['bg'] ?? '#dd4b39' }};color:{{ $toolbar['color'] ?? '#fff' }};font-size:{{ $toolbar['size'] ?? '18px' }}">{{ $toolbar['text'] ?? '' }}</div>
<div class="shop-actions"><button class="btn btn-green" type="submit">ثبت و فعال سازی</button></div>
</form>
</div>
@endsection
