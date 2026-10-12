@extends('layouts.admin')
@section('title', 'فرم ساز | مدیریت سایت')
@section('tab', 'فرم ساز')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>فرم ساز</h1></div>
<div class="shop-card"><div class="table-wrap"><table class="shop-table">
<thead><tr><th>عنوان</th><th>تاریخ</th><th>ویرایش</th></tr></thead>
<tbody>
@foreach($forms as $form)
<tr><td>{{ $form['title'] }}</td><td>{{ $form['created_at'] }}</td><td><button class="btn btn-default btn-sm" type="button">ویرایش</button></td></tr>
@endforeach
</tbody></table></div></div>
@endsection
