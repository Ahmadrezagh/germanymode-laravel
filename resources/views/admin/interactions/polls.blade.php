@extends('layouts.admin')
@section('title', 'نظرسنجی | مدیریت سایت')
@section('tab', 'نظرسنجی')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>نظرسنجی</h1></div>
<div class="shop-card">
    <form method="post" action="{{ route('admin.interactions.polls.store') }}">
        @csrf
        <div class="shop-grid-2">
            <div class="full"><label class="shop-label">عنوان نظرسنجی</label><input class="shop-input" name="title" required></div>
            <div class="full"><label class="shop-label">گزینه‌ها (هر خط یک گزینه)</label><textarea class="shop-input" name="options" style="min-height:100px" required>عالی
خوب
متوسط
ضعیف</textarea></div>
        </div>
        <div class="shop-actions"><button class="btn btn-green" type="submit">+ نظرسنجی جدید</button></div>
    </form>
</div>
@foreach($polls as $poll)
<div class="shop-card">
    <div class="shop-subhead">
        <h3>{{ $poll->title }}</h3>
        <form method="post" action="{{ route('admin.interactions.polls.destroy', $poll) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="shop-table">
            <thead><tr><th>گزینه</th><th>رای</th></tr></thead>
            <tbody>
            @foreach($poll->options as $opt)
                <tr><td>{{ $opt->title }}</td><td>{{ number_format($opt->votes) }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection
