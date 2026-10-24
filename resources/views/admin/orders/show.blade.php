@extends('layouts.admin')

@section('title', $title.' | مدیریت سایت')
@section('tab', $title)

@section('content')
    <div class="shop-page-head" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
        <h1 class="page-title" style="margin:0">{{ $title }}</h1>
        <a class="btn btn-default" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.orders.new') }}">بازگشت</a>
    </div>

    <div class="panel" style="margin-top:14px">
        <div class="panel-head">
            <h2>اطلاعات فاکتور</h2>
        </div>
        <div class="panel-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px 24px;font-size:13.5px">
                <div><strong>شماره فاکتور:</strong> {{ $order->factor_no }}</div>
                <div><strong>خریدار:</strong> {{ $order->buyer_name ?: ($order->user?->fullName() ?: '—') }}</div>
                <div><strong>شناسه کاربری:</strong> {{ $order->portal_id ?: '—' }}</div>
                <div><strong>تاریخ خرید:</strong> {{ $order->purchased_at ?: '—' }}</div>
                <div><strong>تاریخ صدور:</strong> {{ $order->issued_at ?: '—' }}</div>
                <div><strong>تلفن:</strong> {{ $order->phone ?: '—' }}</div>
                <div><strong>فکس:</strong> {{ $order->fax ?: '—' }}</div>
                <div><strong>کدپستی:</strong> {{ $order->postal_code ?: '—' }}</div>
                <div>
                    <strong>وضعیت:</strong>
                    @if($order->is_shipped) ارسال‌شده
                    @elseif($order->is_confirmed) تایید‌شده
                    @else جدید
                    @endif
                </div>
                <div><strong>تایید:</strong> {{ $order->is_confirmed ? 'بله' : 'خیر' }}</div>
                <div><strong>آماده‌سازی:</strong> {{ $order->is_prepared ? 'بله' : 'خیر' }}</div>
                <div><strong>خروج از انبار:</strong> {{ $order->is_left_warehouse ? 'بله' : 'خیر' }}</div>
            </div>

            @if($order->shipping_address)
                <div style="margin-top:18px;padding-top:14px;border-top:1px solid #eee">
                    <strong>آدرس ارسال کالا:</strong>
                    <p style="margin:8px 0 0;line-height:1.7">{{ $order->shipping_address }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="panel" style="margin-top:14px">
        <div class="panel-head">
            <h2>اقلام فاکتور</h2>
        </div>
        <div class="panel-body table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>شماره</th>
                    <th>نام کالا</th>
                    <th>مشخصات</th>
                    <th>تعداد</th>
                    <th>قیمت واحد</th>
                    <th>جمع (تومان)</th>
                </tr>
                </thead>
                <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ $item->line_no }}</td>
                        <td>{{ $item->title ?: '—' }}</td>
                        <td style="max-width:280px;white-space:normal">{{ $item->specs ?: '—' }}</td>
                        <td>{{ $item->qty ?: '—' }}</td>
                        <td>{{ number_format((int) $item->unit_price) }}</td>
                        <td>{{ number_format((int) $item->line_total) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#888;padding:24px">آیتمی ثبت نشده است.</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" style="text-align:left;font-weight:700">هزینه ارسال</td>
                    <td style="font-weight:700">{{ number_format((int) $order->shipping_amount) }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:left;font-weight:700">جمع کل فاکتور</td>
                    <td style="font-weight:700">{{ number_format((int) $order->total_amount) }} تومان</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
