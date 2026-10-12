@extends('layouts.admin')
@section('title', 'مدیریت فایل‌ها | مدیریت سایت')
@section('tab', 'مدیریت فایل‌ها')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>مدیریت فایل‌ها</h1></div>
<div class="shop-card">
@include('partials.file-drop', ['name'=>'file','title'=>'فایل را اینجا رها کنید','sub'=>'آپلود آزمایشی — ذخیره در دیتابیس مدیا'])
<div class="table-wrap" style="margin-top:16px"><table class="shop-table">
<thead><tr><th>نام</th><th>مسیر</th><th>حجم</th><th>تاریخ</th></tr></thead>
<tbody>
@forelse($files as $file)
<tr><td>{{ $file->name }}</td><td>{{ $file->path }}</td><td>{{ number_format($file->size/1024,1) }} KB</td><td>{{ optional($file->created_at)->format('Y/m/d') }}</td></tr>
@empty
<tr><td colspan="4" class="shop-empty">فایلی ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
<div class="pagination-wrap">{{ $files->links('pagination.simple') }}</div>
</div>
@endsection
