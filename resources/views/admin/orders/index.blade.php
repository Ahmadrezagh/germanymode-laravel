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
                <input type="text" name="q" placeholder="جستجو فاکتور / کاربر" style="width:220px">
                <button class="btn btn-default" type="submit">جستجو</button>
                <button class="btn btn-default" type="button">پرینت فاکتور</button>
                <button class="btn btn-default" type="button">پرینت آدرس</button>
            </form>
        </div>
        <div class="panel-body table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>شماره</th>
                    <th>فاکتور</th>
                    <th>تراکنش‌ها</th>
                    <th>صورت حساب</th>
                    <th>کاربری-خریدار</th>
                    @if($mode === 'new')
                        <th>تایید</th>
                        <th>ارسال</th>
                        <th>عدم تایید</th>
                        <th>حذف</th>
                        <th>ویرایش</th>
                    @else
                        <th>عدم تایید</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order['id'] ?? '—' }}</td>
                        <td>{{ $order['invoice'] ?? '—' }}</td>
                        <td>—</td>
                        <td>—</td>
                        <td>{{ $order['user'] ?? '—' }}</td>
                        <td colspan="5">—</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;color:#888;padding:28px">
                            سفارشی ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
