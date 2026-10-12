@extends('layouts.admin')
@section('title', 'بنرهای صفحه اصلی | مدیریت سایت')
@section('tab', 'بنرهای صفحه اصلی')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head">
    <h1>بنرهای صفحه اصلی</h1>
    <span class="shop-badge blue">نسبت تصویر ۱۶:۹ — ثابت</span>
</div>

<div class="shop-card">
    <form method="post" action="{{ route('admin.settings.banners.store') }}" enctype="multipart/form-data" class="shop-grid-2">
        @csrf
        <div class="full">
            <label class="shop-label">تصویر بنر (۱۶:۹) *</label>
            <input class="shop-input" type="file" name="image_file" accept="image/*">
            <div style="margin-top:8px">
                <label class="shop-label">یا مسیر / آدرس تصویر</label>
                <input class="shop-input" name="image" placeholder="/images/banners/banner-1.svg">
            </div>
        </div>
        <div>
            <label class="shop-label">تایتل (اختیاری)</label>
            <input class="shop-input" name="title" value="{{ old('title') }}">
        </div>
        <div>
            <label class="shop-label">عنوان دکمه (اختیاری)</label>
            <input class="shop-input" name="button_title" value="{{ old('button_title') }}">
        </div>
        <div class="full">
            <label class="shop-label">لینک دکمه (اختیاری)</label>
            <input class="shop-input" name="button_url" value="{{ old('button_url') }}" placeholder="/search یا https://...">
        </div>
        <div class="full shop-actions" style="margin:0">
            <button class="btn btn-green" type="submit">افزودن بنر</button>
        </div>
    </form>
</div>

<div class="shop-card">
    <div class="table-wrap">
        <table class="shop-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تصویر</th>
                    <th>تایتل</th>
                    <th>دکمه</th>
                    <th>لینک</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>
                        <img src="{{ $banner->imageUrl() }}" alt="" style="width:120px;aspect-ratio:16/9;object-fit:cover;border-radius:6px;display:block;background:#eee">
                    </td>
                    <td>{{ $banner->title ?: '—' }}</td>
                    <td>{{ $banner->button_title ?: '—' }}</td>
                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $banner->button_url ?: '—' }}</td>
                    <td><span class="shop-badge {{ $banner->is_active ? 'green' : 'red' }}">{{ $banner->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
                    <td>
                        <form method="post" action="{{ route('admin.settings.banners.destroy', $banner) }}" onsubmit="return confirm('حذف بنر؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="shop-empty">بنری ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
