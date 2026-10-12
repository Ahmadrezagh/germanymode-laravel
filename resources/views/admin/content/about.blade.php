@extends('layouts.admin')
@section('title', 'درباره ما | مدیریت سایت')
@section('tab', 'درباره ما')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>درباره ما</h1></div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.content.about.save') }}">
        @csrf
        <div class="shop-grid-2">
            <div class="full"><label class="shop-label">عنوان</label><input class="shop-input" name="title" value="{{ $page->title }}" required></div>
            <div class="full"><label class="shop-label">محتوای صفحه</label><textarea class="shop-input" name="body" style="min-height:220px">{{ $page->body }}</textarea></div>
            <label style="display:flex;gap:8px;align-items:center;font-weight:700;color:#314356">
                <input type="checkbox" name="is_hidden" value="1" @checked($page->is_hidden)> عدم نمایش
            </label>
        </div>
        <div class="shop-actions">
            <button class="btn btn-green" type="submit">ارسال</button>
        </div>
    </form>
</div>
@endsection
