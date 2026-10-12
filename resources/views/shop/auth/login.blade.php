@extends('layouts.shop')

@section('title', 'ورود | جرمنی مد')

@section('content')
<nav class="ork-breadcrumb">
    <a href="{{ route('shop.home') }}">خانه</a>
    <span>/</span>
    <span>ورود</span>
</nav>

<section class="ork-auth">
    <div class="ork-auth-card">
        <div class="ork-auth-head">
            <h1>ورود به حساب کاربری</h1>
            <p>برای پیگیری سفارش و تکمیل خرید وارد شوید</p>
        </div>

        @if($errors->any())
            <div class="ork-alert error">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('shop.login.submit') }}" class="ork-auth-form">
            @csrf
            <label class="ork-field">
                <span>ایمیل</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="example@email.com">
            </label>
            <label class="ork-field">
                <span>رمز عبور</span>
                <input type="password" name="password" required placeholder="••••••••">
            </label>
            <label class="ork-check">
                <input type="checkbox" name="remember" value="1">
                <span>مرا به خاطر بسپار</span>
            </label>
            <button class="ork-btn-buy" type="submit">ورود</button>
        </form>

        <div class="ork-auth-foot">
            حساب کاربری ندارید؟
            <a href="{{ route('shop.register') }}">ثبت نام کنید</a>
        </div>
    </div>
</section>
@endsection
