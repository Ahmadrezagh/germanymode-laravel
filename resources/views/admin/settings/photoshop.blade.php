@extends('layouts.admin')
@section('title', 'فتوشاپ آنلاین | مدیریت سایت')
@section('tab', 'فتوشاپ آنلاین + هوش مصنوعی')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
<div class="shop-page-head"><h1>فتوشاپ آنلاین + هوش مصنوعی</h1></div>
<div class="shop-info"><h3>ابزار ویرایش تصویر</h3><p>این بخش برای ویرایش تصاویر محصولات و بنرها آماده شده است. در نسخه فعلی به‌صورت رابط مدیریتی فعال است.</p></div>
<div class="shop-card">@include('partials.file-drop', ['name'=>'image','accept'=>'image/*','title'=>'تصویر را برای ویرایش رها کنید'])</div>
@endsection
