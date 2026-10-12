@extends('layouts.shop')

@section('title', 'سبد خرید | جرمنی مد')

@section('content')
<nav class="ork-breadcrumb">
    <a href="{{ route('shop.home') }}">خانه</a>
    <span>/</span>
    <span>سبد خرید</span>
</nav>

<section class="ork-cart-page">
    <div class="ork-cart-head">
        <h1>سبد خرید</h1>
        <p>@if($count) {{ number_format($count) }} کالا در سبد شماست @else سبد خرید شما خالی است @endif</p>
    </div>

    @if(session('ok'))
        <div class="ork-alert ok">{{ session('ok') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="ork-cart-empty">
            <strong>هنوز کالایی انتخاب نکرده‌اید</strong>
            <p>از میان محصولات اورجینال آلمان و اروپا انتخاب کنید.</p>
            <a class="ork-btn-buy" href="{{ route('shop.search') }}">مشاهده کالاها</a>
        </div>
    @else
        <div class="ork-cart-layout">
            <div class="ork-cart-list">
                @foreach($items as $item)
                    <article class="ork-cart-row">
                        <a class="ork-cart-thumb" href="{{ $item['url'] }}">
                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">
                            @else
                                <span>بدون تصویر</span>
                            @endif
                        </a>
                        <div class="ork-cart-meta">
                            <a href="{{ $item['url'] }}" class="ork-cart-title">{{ $item['title'] }}</a>
                            <div class="ork-cart-price">
                                @if($item['price'] > 0)
                                    {{ number_format($item['price']) }} <small>تومان</small>
                                @else
                                    تماس بگیرید
                                @endif
                            </div>
                            <div class="ork-cart-actions">
                                <form method="post" action="{{ route('shop.cart.update') }}" class="ork-cart-qty">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <button type="submit" name="qty" value="{{ max(0, $item['qty'] - 1) }}" aria-label="کاهش">−</button>
                                    <span>{{ $item['qty'] }}</span>
                                    <button type="submit" name="qty" value="{{ min(99, $item['qty'] + 1) }}" aria-label="افزایش">+</button>
                                </form>
                                <form method="post" action="{{ route('shop.cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <button class="ork-cart-remove" type="submit">حذف</button>
                                </form>
                            </div>
                        </div>
                        <div class="ork-cart-line">
                            @if($item['price'] > 0)
                                {{ number_format($item['price'] * $item['qty']) }} <small>تومان</small>
                            @else
                                —
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="ork-cart-summary">
                <h2>خلاصه سفارش</h2>
                <div class="ork-cart-sum-row">
                    <span>تعداد کالا</span>
                    <strong>{{ number_format($count) }}</strong>
                </div>
                <div class="ork-cart-sum-row">
                    <span>جمع جزء</span>
                    <strong>
                        @if($subtotal > 0)
                            {{ number_format($subtotal) }} <small>تومان</small>
                        @else
                            پس از استعلام
                        @endif
                    </strong>
                </div>
                <p class="ork-cart-hint">پس از ثبت سفارش، هماهنگی ارسال و پرداخت انجام می‌شود.</p>
                @auth
                    <button class="ork-btn-buy" type="button" onclick="alert('پرداخت به‌زودی فعال می‌شود')">ادامه خرید / پرداخت</button>
                @else
                    <a class="ork-btn-buy" href="{{ route('shop.login') }}">برای ادامه وارد شوید</a>
                    <a class="ork-btn-ghost" href="{{ route('shop.register') }}">ایجاد حساب کاربری</a>
                @endauth
                <a class="ork-cart-continue" href="{{ route('shop.search') }}">ادامه خرید از فروشگاه</a>
            </aside>
        </div>
    @endif
</section>
@endsection
