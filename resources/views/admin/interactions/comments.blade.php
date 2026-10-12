@extends('layouts.admin')
@section('title', 'نظرات | مدیریت سایت')
@section('tab', 'نظــــرات')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>نظرات کاربران</h1></div>
<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" name="q" value="{{ $q }}" placeholder="جستجو در متن نظر...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>تاریخ</th><th>کاربر</th><th>متن</th><th>وضعیت</th><th>عملیات</th></tr></thead>
            <tbody>
            @forelse($comments as $comment)
                <tr>
                    <td>{{ optional($comment->created_at)->format('Y/m/d H:i') }}</td>
                    <td>{{ $comment->author_name ?: ($comment->user?->fullName() ?: 'مهمان') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($comment->body, 120) }}</td>
                    <td>
                        <span class="shop-badge {{ $comment->is_approved ? 'green' : 'orange' }}">
                            {{ $comment->is_approved ? 'تایید شده' : 'در انتظار' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            @unless($comment->is_approved)
                                <form method="post" action="{{ route('admin.interactions.comments.approve', $comment) }}">@csrf
                                    <button class="btn btn-green btn-sm" type="submit">تایید</button>
                                </form>
                            @endunless
                            <form method="post" action="{{ route('admin.interactions.comments.destroy', $comment) }}" onsubmit="return confirm('حذف؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="shop-empty">نظری نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $comments->links('pagination.simple') }}</div>
</div>
@endsection
