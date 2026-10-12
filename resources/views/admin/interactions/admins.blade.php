@extends('layouts.admin')
@section('title', 'مدیران | مدیریت سایت')
@section('tab', 'مدیران')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')

@php
    $formUser = $editing;
    $action = $formUser
        ? route('admin.interactions.admins.update', $formUser)
        : route('admin.interactions.admins.store');
@endphp

<div class="shop-page-head">
    <h1>مدیریت مدیران سایت</h1>
    <a class="btn-archive" href="{{ route('admin.interactions.admins') }}">All logs</a>
</div>

<div class="shop-info">
    <p style="margin:0">
        برای ایجاد مدیران سایت شما باید حداقل یک سطح دسترسی تعیین نمایید.
        برای این کار می‌توانید روی <a href="{{ route('admin.interactions.access-levels') }}">(ایجاد سطح دسترسی جدید)</a> در کنار سطح دسترسی کلیک نمایید.
        در صورتی که رمز عبور را خالی رد نمایید رمز عبور کاربر تغییر نمی‌کند.
    </p>
</div>

<div class="shop-card">
    <form method="post" action="{{ $action }}">
        @csrf
        @if($formUser) @method('PUT') @endif

        <div class="admin-form-3col">
            {{-- ستون راست --}}
            <div class="admin-form-col">
                <div>
                    <label class="shop-label">نام کاربری (پست الکترونیکی)</label>
                    <input class="shop-input" type="email" name="email" value="{{ old('email', $formUser?->email) }}" placeholder="Name@Maile.com" required>
                </div>
                <div>
                    <label class="shop-label">رمز عبور <span class="hint">(درصورتی که خالی باشد تغییر نمی‌کند)</span></label>
                    <input class="shop-input" type="password" name="password" placeholder="**********" autocomplete="new-password">
                </div>
                <div>
                    <label class="shop-label" id="levels">
                        سطح دسترسی
                        <a href="{{ route('admin.interactions.access-levels') }}" data-tab-link data-tab-title="سطوح دسترسی">(ایجاد سطح دسترسی جدید)</a>
                    </label>
                    <select class="shop-select" name="access_level_id" required>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" @selected((int) old('access_level_id', $formUser?->access_level_id) === (int) $level->id)>
                                {{ $level->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="shop-label">درجه مدیر <span class="hint">(فقط یک امتیاز به مدیر است)</span></label>
                    <select class="shop-select" name="admin_rank">
                        @foreach([0=>'بدون رتبه',1=>'یک ستاره',2=>'دو ستاره',3=>'سه ستاره',4=>'چهار ستاره',5=>'پنج ستاره'] as $val => $label)
                            <option value="{{ $val }}" @selected((int) old('admin_rank', $formUser?->admin_rank ?? 0) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ستون وسط --}}
            <div class="admin-form-col">
                <div>
                    <label class="shop-label">نام</label>
                    <input class="shop-input" name="name" value="{{ old('name', $formUser?->name) }}" placeholder="نام" required>
                </div>
                <div>
                    <label class="shop-label">تلفن منزل</label>
                    <input class="shop-input" name="phone" value="{{ old('phone', $formUser?->phone) }}" placeholder="تلفن منزل">
                </div>
                <div>
                    <label class="shop-label">لینک صفحه</label>
                    <input class="shop-input" name="page_link" value="{{ old('page_link', $formUser?->page_link) }}" placeholder="http://">
                </div>
            </div>

            {{-- ستون چپ --}}
            <div class="admin-form-col">
                <div>
                    <label class="shop-label">نام خانوادگی</label>
                    <input class="shop-input" name="last_name" value="{{ old('last_name', $formUser?->last_name) }}" placeholder="نام خانوادگی">
                </div>
                <div>
                    <label class="shop-label">تلفن همراه</label>
                    <input class="shop-input" name="mobile" value="{{ old('mobile', $formUser?->mobile) }}" placeholder="تلفن همراه">
                </div>
                <div>
                    <label class="shop-label">تصویر</label>
                    @include('partials.file-upload-mini', [
                        'name' => 'avatar_file',
                        'urlName' => 'avatar',
                        'value' => old('avatar', $formUser?->avatar),
                        'accept' => 'image/*',
                        'placeholder' => 'http://',
                    ])
                </div>
                <div>
                    <label class="shop-label">آدرس</label>
                    <input class="shop-input" name="address" value="{{ old('address', $formUser?->address) }}" placeholder="آدرس">
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <button class="btn btn-green" type="submit">{{ $formUser ? 'ویرایش مدیر' : 'ارسال' }}</button>
            <label style="display:inline-flex;gap:8px;align-items:center;font-weight:700;color:#c62828;margin-right:8px">
                <input type="checkbox" name="is_blocked" value="1" @checked(old('is_blocked', $formUser?->is_blocked))>
                مسدود کردن کاربر
            </label>
            @if($formUser)
                <a class="btn btn-default" href="{{ route('admin.interactions.admins') }}">انصراف</a>
            @endif
        </div>
    </form>
</div>

<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" type="search" name="q" value="{{ $q }}" placeholder="جستجو کد / نام / ایمیل...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>

    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>کدکاربری</th>
                <th>سطح دسترسی</th>
                <th>نام نام خانوادگی</th>
                <th>نام کاربری</th>
                <th>آدرس ها</th>
                <th>مسدود</th>
                <th>ویرایش</th>
                <th>حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($admins as $admin)
                <tr @if($editing && $editing->id === $admin->id) style="outline:2px solid #3b82c4;outline-offset:-2px" @endif>
                    <td>
                        <div style="font-weight:800">{{ $admin->portal_id ?: $admin->id }}</div>
                        <div style="font-size:11px;color:#8a97a8">{{ optional($admin->created_at)->format('Y/m/d H:i') }}</div>
                    </td>
                    <td>
                        <div>({{ $admin->accessLevel?->name ?: '—' }})</div>
                        <a href="#" style="font-size:11px;color:#0d6efd" onclick="alert('لاگ ورود مدیر — به‌زودی');return false;">(LOG)</a>
                    </td>
                    <td>{{ $admin->fullName() }}</td>
                    <td>{{ $admin->email }}</td>
                    <td style="max-width:180px;font-size:12px;color:#667788">{{ $admin->address ? \Illuminate\Support\Str::limit($admin->address, 40) : '—' }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.interactions.admins.block', $admin) }}">
                            @csrf
                            <button class="btn btn-sm {{ $admin->is_blocked ? 'btn-danger' : 'btn-default' }}" type="submit">
                                {{ $admin->is_blocked ? 'مسدود' : '—' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.interactions.admins', ['edit' => $admin->id, 'q' => $q]) }}">ویرایش</a>
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.interactions.admins.destroy', $admin) }}" onsubmit="return confirm('مدیر حذف شود؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="shop-empty">مدیری ثبت نشده.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $admins->links('pagination.simple') }}</div>
</div>

<div class="shop-card soft">
    <div class="shop-subhead">
        <h3 style="margin:0;color:#1a5f9e;font-size:14px">سطح‌های دسترسی تعریف‌شده</h3>
        <a class="btn btn-blue btn-sm" href="{{ route('admin.interactions.access-levels') }}" data-tab-link data-tab-title="سطوح دسترسی">ایجاد / ویرایش سطوح</a>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:8px">
        @forelse($levels as $level)
            <span class="shop-badge {{ $level->is_blocked ? 'red' : 'blue' }}">{{ $level->label() }}</span>
        @empty
            <span style="color:#8a97a8;font-size:13px">هنوز سطحی تعریف نشده.</span>
        @endforelse
    </div>
</div>
@endsection
