<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'فروشگاه اینترنتی جرمنی مد')</title>
    <meta name="description" content="@yield('meta_description', 'فروشگاه اینترنتی جرمنی مد — خرید مستقیم از آلمان و اروپا')">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>
@php
    $megaCategories = $megaCategories ?? \App\Models\Category::megaMenuTree();
    $siteStories = isset($stories) ? $stories : \App\Models\Story::query()->where('is_active', true)->orderBy('sort_order')->get();
    $cartCount = \App\Support\Cart::count();
@endphp

<div class="announce">
    💥 تمامی اجناس اورجینال و مستقیماً از آلمان ارسال می‌شوند درب منزل شما. 💥
</div>

<header class="ork-header">
    <div class="ork-header-inner">
        <a class="ork-logo" href="{{ route('shop.home') }}" title="فروشگاه اینترنتی جرمنی مد">
            <img class="ork-logo-img" src="{{ asset('images/logo-germany-mode.png') }}?v=4" alt="جرمنی مد | Germany mode" width="118" height="66" loading="eager">
            <span class="ork-logo-text">
                <strong>جرمنی مد</strong>
                <small>خرید مستقیم از آلمان</small>
            </span>
        </a>

        <form class="ork-search" action="{{ route('shop.search') }}" method="get" data-suggest-url="{{ route('shop.search.suggest') }}" autocomplete="off">
            <div class="ork-search-bar">
                <input type="search" name="q" value="{{ $q ?? request('q') }}" placeholder="کالای مورد نظرتان را جستجو کنید ..." autocomplete="off" aria-autocomplete="list" aria-controls="orkSearchSuggest" aria-expanded="false">
                <button type="submit" aria-label="جستجو">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                </button>
            </div>
            <div class="ork-search-suggest" id="orkSearchSuggest" hidden role="listbox"></div>
        </form>
        <script src="{{ asset('js/shop-search.js') }}" defer></script>

        <div class="ork-actions">
            @auth
                <span class="ork-user">{{ auth()->user()->fullName() ?: auth()->user()->email }}</span>
                <form method="post" action="{{ route('shop.logout') }}" class="ork-logout-form">
                    @csrf
                    <button type="submit" class="ork-link">خروج</button>
                </form>
            @else
                <a href="{{ route('shop.login') }}" class="ork-link {{ request()->routeIs('shop.login') ? 'active' : '' }}">وارد شوید</a>
                <a href="{{ route('shop.register') }}" class="ork-link {{ request()->routeIs('shop.register') ? 'active' : '' }}">ثبت نام کنید</a>
            @endauth
            <a href="{{ route('shop.cart') }}" class="ork-cart {{ request()->routeIs('shop.cart') ? 'active' : '' }}">
                <span class="ork-cart-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6L5 3H2"/><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/></svg>
                </span>
                <span>سبد خرید</span>
                @if($cartCount > 0)
                    <span class="ork-cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
</header>

<nav class="ork-menu" id="orkMenu">
    <div class="ork-menu-inner">
        <a href="{{ route('shop.home') }}" class="{{ request()->routeIs('shop.home') ? 'active' : '' }}">خانه</a>
        @include('partials.mega-menu', ['megaCategories' => $megaCategories, 'megaPart' => 'trigger'])
        <a href="{{ route('shop.search') }}">جستجو</a>
    </div>
    @include('partials.mega-menu', ['megaCategories' => $megaCategories, 'megaPart' => 'panel'])
</nav>

@if(($siteStories ?? collect())->isNotEmpty() && request()->routeIs('shop.home'))
    @include('partials.stories', ['stories' => $siteStories])
@endif

<main class="ork-main">
    @yield('content')
</main>

@include('partials.footer')
</body>
</html>
