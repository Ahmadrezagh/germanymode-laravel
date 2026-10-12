@extends('layouts.admin')
@section('title', 'تماس با ما | مدیریت سایت')
@section('tab', 'تماس با ما')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>تماس با ما</h1></div>
<div class="shop-info">
    <p>برای انتقال فرم‌های ارسالی به بخش مشاهده‌شده، روی «دیده‌شده» کلیک کنید.</p>
</div>
<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" name="q" value="{{ $q }}" placeholder="جستجو فرم...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>#</th><th>تاریخ</th><th>نام</th><th>ایمیل</th><th>متن پیام</th><th>وضعیت</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($messages as $message)
                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ optional($message->created_at)->format('Y/m/d H:i') }}</td>
                    <td>{{ $message->name ?: '—' }}</td>
                    <td>{{ $message->email ?: '—' }}</td>
                    <td style="max-width:360px">{{ \Illuminate\Support\Str::limit($message->body, 140) }}</td>
                    <td><span class="shop-badge {{ $message->is_seen ? 'green' : 'orange' }}">{{ $message->is_seen ? 'دیده‌شده' : 'جدید' }}</span></td>
                    <td>
                        <div class="row-actions">
                            @unless($message->is_seen)
                                <form method="post" action="{{ route('admin.content.contact.seen', $message) }}">@csrf<button class="btn btn-default btn-sm" type="submit">دیده‌شده</button></form>
                            @endunless
                            <form method="post" action="{{ route('admin.content.contact.destroy', $message) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">حذف</button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="shop-empty">پیامی نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $messages->links('pagination.simple') }}</div>
</div>
@endsection
