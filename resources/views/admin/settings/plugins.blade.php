@extends('layouts.admin')
@section('title', 'افزونه‌ها | مدیریت سایت')
@section('tab', 'افزونه ها')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>افزونه ها</h1></div>
<div class="shop-card"><form method="post" action="{{ route('admin.settings.plugins.save') }}">@csrf
<div class="table-wrap"><table class="shop-table">
<thead><tr><th>نام افزونه</th><th>وضعیت</th><th>فعال‌سازی</th></tr></thead>
<tbody>
@foreach($plugins as $plugin)
<tr>
<td>{{ $plugin['name'] }}</td>
<td><span class="shop-badge {{ !empty($plugin['enabled']) ? 'green' : 'red' }}">{{ !empty($plugin['enabled']) ? 'فعال' : 'غیرفعال' }}</span></td>
<td><input type="checkbox" name="enabled[]" value="{{ $plugin['key'] }}" @checked(!empty($plugin['enabled']))></td>
</tr>
@endforeach
</tbody></table></div>
<div class="shop-actions"><button class="btn btn-green" type="submit">ذخیره</button></div>
</form></div>
@endsection
