@extends('layouts.admin')

@section('title', 'مشتریان | مدیریت سایت')
@section('tab', 'مشتریان|کاربران')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')

<div class="shop-page-head"><h1>مدیریت کاربران سایت</h1></div>

<div class="shop-card">
    <form method="post" action="{{ route('admin.users.store') }}" class="shop-grid-2">
        @csrf
        <div><label class="shop-label">نام</label><input class="shop-input" name="name" required></div>
        <div><label class="shop-label">نام خانوادگی</label><input class="shop-input" name="last_name"></div>
        <div><label class="shop-label">نام کاربری (ایمیل)</label><input class="shop-input" type="email" name="email" required></div>
        <div><label class="shop-label">موبایل</label><input class="shop-input" name="mobile"></div>
        <div><label class="shop-label">رمز عبور</label><input class="shop-input" type="password" name="password" placeholder="خالی = 123456"></div>
        <div><label class="shop-label">استان</label><input class="shop-input" name="province"></div>
        <div class="full"><label class="shop-label">آدرس</label><textarea class="shop-input" name="address" style="min-height:70px"></textarea></div>
        <div class="full shop-actions" style="margin:0"><button class="btn btn-green" type="submit">ارسال</button></div>
    </form>
</div>

<div class="shop-card">
    <div class="shop-hint">برای انتقال به حساب کاربری روی ویرایش کلیک کنید. می‌توانید کاربر را مسدود کنید.</div>
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" type="search" name="q" value="{{ $q }}" placeholder="نام / ایمیل / موبایل">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>
    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>کدکاربری</th>
                <th>تاریخ</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>نام کاربری</th>
                <th>موبایل</th>
                <th>مسدود</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                        <td>{{ $user->portal_id ?: $user->id }}</td>
                        <td>{{ optional($user->created_at)->format('Y/m/d H:i') }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->last_name ?: '—' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile ?: '—' }}</td>
                    <td>
                        <span class="shop-badge {{ $user->is_blocked ? 'red' : 'green' }}">
                            {{ $user->is_blocked ? 'مسدود' : 'فعال' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <form method="post" action="{{ route('admin.users.update', $user) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="mobile" value="{{ $user->mobile }}">
                                <input type="hidden" name="is_blocked" value="{{ $user->is_blocked ? 0 : 1 }}">
                                <button class="btn btn-default btn-sm" type="submit">{{ $user->is_blocked ? 'رفع مسدود' : 'مسدود' }}</button>
                            </form>
                            <form method="post" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('حذف شود؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="shop-empty">کاربری نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $users->links('pagination.simple') }}</div>
</div>
@endsection
