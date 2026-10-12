@extends('layouts.shop')

@section('title', 'ثبت نام | جرمنی مد')

@section('content')
<nav class="ork-breadcrumb">
    <a href="{{ route('shop.home') }}">خانه</a>
    <span>/</span>
    <span>ثبت نام</span>
</nav>

<section class="ork-auth">
    <div class="ork-auth-card wide">
        <div class="ork-auth-head">
            <h1>ایجاد حساب کاربری</h1>
            <p>عضویت در فروشگاه اینترنتی جرمنی مد</p>
        </div>

        @if($errors->any())
            <div class="ork-alert error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('shop.register.submit') }}" class="ork-auth-form grid">
            @csrf
            @if(!empty($fields['name']))
                <label class="ork-field">
                    <span>نام</span>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
            @endif
            @if(!empty($fields['last_name']))
                <label class="ork-field">
                    <span>نام خانوادگی</span>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                </label>
            @endif
            @if(!empty($fields['mobile']))
                <label class="ork-field">
                    <span>موبایل</span>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" required dir="ltr" placeholder="09xxxxxxxxx">
                </label>
            @endif
            <label class="ork-field">
                <span>ایمیل</span>
                <input type="email" name="email" value="{{ old('email') }}" required dir="ltr">
            </label>
            @if(!empty($fields['national_code']))
                <label class="ork-field">
                    <span>کد ملی</span>
                    <input type="text" name="national_code" value="{{ old('national_code') }}" required dir="ltr">
                </label>
            @endif
            @if(!empty($fields['address']))
                <label class="ork-field full">
                    <span>آدرس</span>
                    <textarea name="address" required rows="3">{{ old('address') }}</textarea>
                </label>
            @endif
            <label class="ork-field">
                <span>رمز عبور</span>
                <input type="password" name="password" required minlength="6">
            </label>
            <label class="ork-field">
                <span>تکرار رمز عبور</span>
                <input type="password" name="password_confirmation" required minlength="6">
            </label>
            <div class="full">
                <button class="ork-btn-buy" type="submit">ثبت نام</button>
            </div>
        </form>

        <div class="ork-auth-foot">
            قبلاً ثبت‌نام کرده‌اید؟
            <a href="{{ route('shop.login') }}">وارد شوید</a>
        </div>
    </div>
</section>
@endsection
