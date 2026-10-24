@extends('layouts.admin')

@section('title', $title.' | مدیریت سایت')
@section('tab', $title)

@section('content')
    <h1 class="page-title">
        {{ $title }}
    </h1>

    <div class="alert alert-warn">
        این بخش آرشیو سفارشات کاربران است. بعد از تایید تراکنش و ارسال کالا، سفارش به «سفارشات انجام شده» منتقل می‌شود.
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $subtitle }}</h2>
            <form class="form-inline" method="get">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="جستجو فاکتور / کاربر" style="width:220px">
                <button class="btn btn-default" type="submit">جستجو</button>
            </form>
        </div>
        <div class="panel-body table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>شماره</th>
                    <th>فاکتور</th>
                    <th>تاریخ</th>
                    <th>صورت حساب</th>
                    <th>کاربری-خریدار</th>
                    <th>وضعیت</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="order-row-link" onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor:pointer">
                        <td>{{ $order->id }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" onclick="event.stopPropagation()" style="color:#0b5ed7;font-weight:600">
                                {{ $order->factor_no }}
                            </a>
                        </td>
                        <td>{{ $order->purchased_at ?: '—' }}</td>
                        <td>{{ number_format((int) $order->total_amount) }} تومان</td>
                        <td>
                            {{ $order->buyer_name ?: ($order->user?->fullName() ?: '—') }}
                            @if($order->portal_id)
                                <div style="font-size:11px;color:#888">{{ $order->portal_id }}</div>
                            @endif
                        </td>
                        <td>
                            @if($order->is_shipped) ارسال‌شده
                            @elseif($order->is_confirmed) تایید‌شده
                            @else جدید
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#888;padding:28px">
                            سفارشی ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($orders, 'links'))
            <div class="pagination-wrap" style="padding:12px">{{ $orders->links('pagination.simple') }}</div>
        @endif
    </div>
@endsection
