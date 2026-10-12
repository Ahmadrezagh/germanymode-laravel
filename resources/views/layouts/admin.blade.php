<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'مدیریت سایت')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
</head>
<body>
@php
    $tabTitle = trim($__env->yieldContent('tab', 'داشبورد'));
    $tabKey = request()->path();
    $menu = config('admin_menu.sections', []);
@endphp
<div class="admin-shell">
    <div class="main-col">
        <header class="topbar">
            <div class="user-area">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <a class="btn btn-green btn-sm" href="{{ route('admin.interactions.profile') }}" data-tab-link data-tab-title="ویرایش کاربری">ویرایش</a>
                <form action="{{ route('admin.logout') }}" method="post" style="display:inline">
                    @csrf
                    <button class="btn btn-red btn-sm" type="submit">خروج</button>
                </form>
            </div>
            <div class="top-links">
                <a href="{{ route('admin.settings.plugins') }}" data-tab-link data-tab-title="افزونه ها">افزونه‌ها</a>
                <a href="{{ route('admin.dashboard') }}" data-tab-link data-tab-title="وضعیت">وضعیت</a>
                <a href="{{ route('admin.interactions.tickets') }}" data-tab-link data-tab-title="پشتیبانی">پشتیبانی</a>
            </div>
        </header>

        <div class="tabbar" id="tabbar" data-max="5" data-current-key="{{ $tabKey }}" data-current-title="{{ $tabTitle }}" data-current-url="{{ url()->current() }}"></div>
        <div class="tab-limit-toast" id="tabLimitToast">
            حداکثر تعداد تب‌های مجاز باز شده در داخل پنل مدیریت پنج عدد می‌باشد. تعدادی از تب‌های اضافی را ببندید.
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <aside class="sidebar">
        <a class="sidebar-viewsite" href="{{ route('shop.home') }}" target="_blank">
            @include('partials.admin-icon', ['name' => 'site'])
            <span>مشاهده سایت</span>
        </a>

        @foreach($menu as $section)
            @php
                $sectionOpen = false;
                foreach ($section['items'] as $item) {
                    $url = route($item['route'], $item['params'] ?? []);
                    if (url()->current() === $url || request()->url() === $url) {
                        $sectionOpen = true;
                        break;
                    }
                    if (($item['route'] ?? '') === 'admin.products.create' && request()->routeIs('admin.products.create')) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.products.index' && request()->routeIs('admin.products.index', 'admin.products.edit')) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.shop.categories' && (request()->routeIs('admin.shop.categories') || request()->routeIs('admin.categories.*'))) $sectionOpen = true;
                    if (str_starts_with($item['route'] ?? '', 'admin.shop.') && request()->routeIs($item['route'])) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.orders.new' && request()->routeIs('admin.orders.new')) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.orders.done' && request()->routeIs('admin.orders.done')) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.users.index' && request()->routeIs('admin.users.*')) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.settings' && request()->routeIs('admin.settings')) $sectionOpen = true;
                    if (str_starts_with($item['route'] ?? '', 'admin.settings.') && request()->routeIs($item['route'])) $sectionOpen = true;
                    if (str_starts_with($item['route'] ?? '', 'admin.content.') && request()->routeIs($item['route'])) $sectionOpen = true;
                    if (str_starts_with($item['route'] ?? '', 'admin.interactions.') && request()->routeIs($item['route'])) $sectionOpen = true;
                    if (($item['route'] ?? '') === 'admin.dashboard' && request()->routeIs('admin.dashboard')) $sectionOpen = true;
                }
            @endphp
            <div class="acc-item {{ $sectionOpen ? 'open' : '' }}">
                <button type="button" class="acc-head" data-acc>
                    <span class="acc-label">
                        @include('partials.admin-icon', ['name' => $section['icon'] ?? 'dashboard'])
                        <span class="acc-title">
                            {{ $section['title'] }}
                        </span>
                    </span>
                    <span class="plus"></span>
                </button>
                <div class="acc-body">
                    @foreach($section['items'] as $item)
                        @php
                            $href = route($item['route'], $item['params'] ?? []);
                            $active = url()->current() === $href;
                            if (($item['route'] ?? '') === 'admin.products.index' && request()->routeIs('admin.products.index', 'admin.products.edit')) $active = true;
                            if (($item['route'] ?? '') === 'admin.products.create' && request()->routeIs('admin.products.create')) $active = true;
                            if (($item['route'] ?? '') === 'admin.shop.categories' && (request()->routeIs('admin.shop.categories') || request()->routeIs('admin.categories.*'))) $active = true;
                            if (str_starts_with($item['route'] ?? '', 'admin.shop.') && request()->routeIs($item['route'])) $active = true;
                            if (str_starts_with($item['route'] ?? '', 'admin.settings.') && request()->routeIs($item['route'])) $active = true;
                            if (str_starts_with($item['route'] ?? '', 'admin.content.') && request()->routeIs($item['route'])) $active = true;
                            if (str_starts_with($item['route'] ?? '', 'admin.interactions.') && request()->routeIs($item['route'])) $active = true;
                            $itemIcons = [
                                'plus' => '+',
                                'archive' => '▣',
                                'excel' => '⬇',
                                'excel-edit' => '✎',
                                'flash' => '⚡',
                                'percent' => '%',
                                'star' => '★',
                                'warehouse' => '⌂',
                                'layers' => '☰',
                                'android' => '▶',
                            ];
                        @endphp
                        <a href="{{ $href }}"
                           class="{{ $active ? 'active' : '' }} {{ !empty($item['icon']) ? 'shop-link' : '' }}"
                           data-tab-link
                           data-tab-title="{{ $item['title'] }}">
                            @if(!empty($item['icon']))
                                <span class="submenu-ico" style="background:{{ $item['color'] ?? '#3c8dbc' }}">{{ $itemIcons[$item['icon']] ?? '•' }}</span>
                            @endif
                            <span>{{ $item['title'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </aside>
</div>

<script>
(function () {
  document.querySelectorAll('[data-acc]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      btn.closest('.acc-item').classList.toggle('open');
    });
  });

  var MAX = 5;
  var bar = document.getElementById('tabbar');
  var toast = document.getElementById('tabLimitToast');
  var storageKey = 'gm_admin_tabs_v2';

  function loadTabs() {
    try { return JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch (e) { return []; }
  }
  function saveTabs(tabs) { localStorage.setItem(storageKey, JSON.stringify(tabs)); }
  function showToast() {
    toast.style.display = 'block';
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () { toast.style.display = 'none'; }, 4000);
  }
  function render() {
    var tabs = loadTabs();
    var currentKey = bar.dataset.currentKey;
    bar.innerHTML = '';
    tabs.forEach(function (tab) {
      var chip = document.createElement('a');
      chip.href = tab.url;
      chip.className = 'tab-chip' + (tab.key === currentKey ? ' active' : '');
      chip.innerHTML = '<span>' + tab.title + '</span>';
      var x = document.createElement('button');
      x.type = 'button';
      x.className = 'x';
      x.title = 'بستن تب';
      x.textContent = '×';
      x.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var next = loadTabs().filter(function (t) { return t.key !== tab.key; });
        saveTabs(next);
        if (tab.key === currentKey) {
          window.location.href = next.length ? next[next.length - 1].url : @json(route('admin.dashboard'));
        } else {
          render();
        }
      });
      chip.appendChild(x);
      bar.appendChild(chip);
    });
  }

  (function ensureCurrent() {
    var tabs = loadTabs();
    var key = bar.dataset.currentKey;
    var title = bar.dataset.currentTitle || 'تب';
    var url = bar.dataset.currentUrl;
    var exists = tabs.some(function (t) { return t.key === key; });
    if (!exists) {
      if (tabs.length >= MAX) { tabs.shift(); showToast(); }
      tabs.push({ key: key, title: title, url: url });
      saveTabs(tabs);
    } else {
      tabs = tabs.map(function (t) {
        if (t.key === key) { t.title = title; t.url = url; }
        return t;
      });
      saveTabs(tabs);
    }
    render();
  })();

  document.querySelectorAll('[data-tab-link]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var tabs = loadTabs();
      var key;
      try { key = new URL(a.href, window.location.origin).pathname.replace(/^\//, ''); }
      catch (err) { key = a.getAttribute('href'); }
      var exists = tabs.some(function (t) { return t.key === key; });
      if (!exists && tabs.length >= MAX) {
        e.preventDefault();
        showToast();
        tabs.shift();
        tabs.push({
          key: key,
          title: a.getAttribute('data-tab-title') || a.textContent.trim(),
          url: a.href
        });
        saveTabs(tabs);
        window.location.href = a.href;
      }
    });
  });
})();
</script>
<script src="{{ asset('js/admin-ui.js') }}"></script>
</body>
</html>
