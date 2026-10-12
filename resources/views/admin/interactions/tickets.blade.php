@extends('layouts.admin')
@section('title', 'پیام‌ها | مدیریت سایت')
@section('tab', 'پیام ها (پشتیبانی)')
@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')
<div class="shop-page-head"><h1>پیام‌ها (پشتیبانی)</h1></div>
<div class="shop-card">
    <form class="shop-toolbar" method="get">
        <input class="shop-input grow" name="q" value="{{ $q }}" placeholder="جستجو موضوع...">
        <button class="btn btn-blue" type="submit">جستجو</button>
    </form>
    @forelse($tickets as $ticket)
        <div class="shop-card soft" style="margin-bottom:12px">
            <div class="shop-subhead">
                <h3>#{{ $ticket->id }} — {{ $ticket->subject }}</h3>
                <span class="shop-badge {{ $ticket->status === 'closed' ? 'red' : ($ticket->status === 'answered' ? 'green' : 'orange') }}">{{ $ticket->status }}</span>
            </div>
            <div style="font-size:12.5px;color:#7a8a9c;margin-bottom:10px">
                {{ $ticket->user?->fullName() ?: 'کاربر' }} • {{ optional($ticket->created_at)->format('Y/m/d H:i') }}
            </div>
            @foreach($ticket->messages as $msg)
                <div style="padding:10px 12px;border-radius:10px;margin-bottom:8px;background:{{ $msg->is_staff ? '#eef6ff' : '#f7f9fc' }};border:1px solid #e6ebf2">
                    <div style="font-size:11.5px;color:#7a8a9c;margin-bottom:4px">{{ $msg->is_staff ? 'پشتیبان' : 'کاربر' }} — {{ optional($msg->created_at)->format('Y/m/d H:i') }}</div>
                    <div style="white-space:pre-wrap;line-height:1.7">{{ $msg->body }}</div>
                </div>
            @endforeach
            @if($ticket->status !== 'closed')
                <form method="post" action="{{ route('admin.interactions.tickets.reply', $ticket) }}" style="margin-top:10px">
                    @csrf
                    <textarea class="shop-input" name="body" style="min-height:70px" placeholder="پاسخ..." required></textarea>
                    <div class="shop-actions">
                        <button class="btn btn-green" type="submit">ارسال پاسخ</button>
                        <button class="btn btn-default" formaction="{{ route('admin.interactions.tickets.close', $ticket) }}" formmethod="post" type="submit">بستن تیکت</button>
                    </div>
                </form>
            @endif
        </div>
    @empty
        <div class="shop-empty">تیکتی نیست.</div>
    @endforelse
    <div class="pagination-wrap">{{ $tickets->links('pagination.simple') }}</div>
</div>
@endsection
