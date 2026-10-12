<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود | مدیریت سایت</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="head">
            <h1>مدیریت سایت</h1>
            <p>ورود به پنل ادمین جرمنی مد</p>
        </div>
        <div class="body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            <form method="post" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input id="email" type="email" name="email" value="{{ old('email', 'admin@germanymode.com') }}" required autofocus>
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <input id="password" type="password" name="password"  required>
                    @error('password')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="actions">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1"> مرا به خاطر بسپار
                    </label>
                    <button class="btn btn-primary" type="submit">ورود</button>
                </div>
            </form>
            <p style="margin:16px 0 0;font-size:12px;color:#888;text-align:center">
                <a href="{{ route('shop.home') }}">بازگشت به فروشگاه</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
