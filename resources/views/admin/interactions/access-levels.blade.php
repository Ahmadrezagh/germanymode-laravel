@extends('layouts.admin')
@section('title', 'ایجاد سطح دسترسی | مدیریت سایت')
@section('tab', 'سطوح دسترسی')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop-admin.css') }}">
@include('partials.admin-flash')

@php
    $formLevel = $editing;
    $selected = collect(old('permissions', $formLevel?->permissions ?? []));
    $action = $formLevel
        ? route('admin.interactions.access-levels.update', $formLevel)
        : route('admin.interactions.access-levels.store');
@endphp

<div class="shop-page-head">
    <h1>ایجاد سطح دسترسی</h1>
    <a class="btn-archive" href="{{ route('admin.interactions.admins') }}">بازگشت به مدیران ←</a>
</div>

<div class="shop-info">
    <p style="margin:0 0 8px">
        از طریق این بخش می‌توانید سطوح دسترسی مختلف با عنوان‌های خاص ایجاد نموده و در بخش مدیران سایت به مدیران خود اختصاص دهید.
    </p>
    <p style="margin:0 0 8px">
        ویژگی این امر این است که در صورتی که از این قسمت سطوح دسترسی را ویرایش نمایید، سطح دسترسی تمام کاربرانی که به این بخش مرتبط هستند تغییر می‌کند.
    </p>
    <p style="margin:0">
        برای درج سطح دسترسی جدید ابتدا یک عنوان وارد کرده سپس روی «تغییر دسترسی +» کلیک نمایید، دسترسی بخش‌های مختلف را انتخاب کرده و گزینه اعمال را بزنید. سپس روی دکمه ارسال سبز رنگ کلیک نمایید.
    </p>
</div>

<div class="shop-card">
    <form method="post" action="{{ $action }}" id="access-level-form">
        @csrf
        @if($formLevel) @method('PUT') @endif

        <div class="shop-grid-2">
            <div class="full" style="display:flex;gap:0;align-items:stretch">
                <div style="flex:1">
                    <label class="shop-label">عنوان سطح دسترسی</label>
                    <input class="shop-input" name="name" id="level-name" value="{{ old('name', $formLevel?->name) }}" required placeholder="مثلاً مدیر ۱۱">
                </div>
                <div style="display:flex;align-items:flex-end">
                    <button class="btn btn-blue" type="button" id="open-perm-modal" style="border-radius:0 0 0 0;height:44px;margin-bottom:0;border-top-left-radius:11px;border-bottom-left-radius:11px">
                        تغییر دسترسی +
                    </button>
                </div>
            </div>

            <div class="full">
                <div id="perm-summary" class="shop-hint" style="margin:0">
                    @if($selected->isNotEmpty())
                        {{ $selected->count() }} دسترسی انتخاب شده
                    @else
                        هنوز دسترسی‌ای انتخاب نشده — روی «تغییر دسترسی +» کلیک کنید.
                    @endif
                </div>
                <div id="perm-hidden-inputs">
                    @foreach($selected as $perm)
                        <input type="hidden" name="permissions[]" value="{{ $perm }}">
                    @endforeach
                </div>
            </div>

            <label style="display:flex;gap:8px;align-items:center;font-weight:700;color:#c62828">
                <input type="checkbox" name="is_blocked" value="1" @checked(old('is_blocked', $formLevel?->is_blocked))>
                مسدود کردن دسترسی
            </label>
        </div>

        <div class="shop-actions">
            <button class="btn btn-green" type="submit">ارسال</button>
            @if($formLevel)
                <a class="btn btn-default" href="{{ route('admin.interactions.access-levels') }}">انصراف</a>
            @endif
        </div>
    </form>
</div>

<div class="shop-card">
    <div class="table-wrap">
        <table class="shop-table">
            <thead>
            <tr>
                <th>تاریخ</th>
                <th>تعداد مدیران</th>
                <th>عنوان دسترسی</th>
                <th>نمایش دسترسی</th>
                <th>مسدود</th>
                <th>ویرایش</th>
                <th>حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($levels as $level)
                <tr @if($editing && $editing->id === $level->id) style="outline:2px solid #3b82c4;outline-offset:-2px" @endif>
                    <td>{{ optional($level->created_at)->format('Y/m/d H:i') }}</td>
                    <td>{{ number_format($level->users_count) }}</td>
                    <td>
                        <strong>{{ $level->name }}</strong>
                        @if($level->is_blocked)
                            <span class="shop-badge red">دسترسی مسدود</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-default btn-sm" type="button"
                                data-view-perms='@json($level->permissionTitles(), JSON_UNESCAPED_UNICODE)'
                                data-view-title="{{ $level->name }}">
                            نمایش
                        </button>
                        <span style="font-size:11px;color:#8a97a8;margin-right:6px">{{ count($level->permissions ?? []) }} مورد</span>
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.interactions.access-levels.block', $level) }}">
                            @csrf
                            <button class="btn btn-sm {{ $level->is_blocked ? 'btn-danger' : 'btn-default' }}" type="submit">
                                {{ $level->is_blocked ? 'مسدود' : '—' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.interactions.access-levels', ['edit' => $level->id]) }}">ویرایش</a>
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.interactions.access-levels.destroy', $level) }}" onsubmit="return confirm('سطح دسترسی حذف شود؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="shop-empty">سطح دسترسی‌ای نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Permission picker modal (matches creat_roles.aspx popup) --}}
<div id="perm-modal" style="display:none;position:fixed;inset:0;background:rgba(20,35,55,.45);z-index:1000;padding:24px;overflow:auto">
    <div class="shop-card" style="max-width:920px;margin:20px auto;max-height:calc(100vh - 48px);display:flex;flex-direction:column">
        <div class="shop-subhead">
            <h3 style="margin:0;color:#1a5f9e">انتخاب دسترسی بخش‌ها</h3>
            <div style="display:flex;gap:8px">
                <button class="btn btn-default btn-sm" type="button" id="perm-select-all">دسترسی کامل</button>
                <button class="btn btn-default btn-sm" type="button" id="perm-clear-all">انصراف انتخاب</button>
            </div>
        </div>
        <div style="overflow:auto;flex:1;padding:4px 2px 12px;border:1px solid #e6ebf2;border-radius:12px;background:#fbfcfe">
            @foreach($permissionGroups as $group)
                <div style="padding:12px 14px;border-bottom:1px solid #eef2f7">
                    <div style="font-weight:800;color:#24415f;margin-bottom:8px;display:flex;justify-content:space-between;gap:8px;align-items:center">
                        <span>{{ $group['title'] }}</span>
                        <button class="btn btn-default btn-sm perm-group-all" type="button" data-group="{{ $group['key'] }}">انتخاب گروه</button>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:6px 12px">
                        @foreach(($group['permissions'] ?? []) as $perm)
                            <label style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;font-weight:600;color:#314356;cursor:pointer">
                                <input type="checkbox" class="perm-check" data-group="{{ $group['key'] }}" value="{{ $perm['key'] }}"
                                    @checked($selected->contains($perm['key']))>
                                <span>{{ $perm['title'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="shop-actions" style="margin-top:12px;justify-content:flex-end">
            <button class="btn btn-default" type="button" id="perm-cancel">انصراف</button>
            <button class="btn btn-green" type="button" id="perm-apply">اعمال</button>
        </div>
    </div>
</div>

{{-- View permissions modal --}}
<div id="view-perm-modal" style="display:none;position:fixed;inset:0;background:rgba(20,35,55,.45);z-index:1001;padding:24px;overflow:auto">
    <div class="shop-card" style="max-width:560px;margin:40px auto">
        <div class="shop-subhead">
            <h3 id="view-perm-title" style="margin:0;color:#1a5f9e">نمایش دسترسی</h3>
            <button class="btn btn-default btn-sm" type="button" id="view-perm-close">بستن</button>
        </div>
        <ul id="view-perm-list" style="margin:0;padding:0 18px;line-height:2;color:#314356"></ul>
    </div>
</div>

<script>
(function () {
  var modal = document.getElementById('perm-modal');
  var openBtn = document.getElementById('open-perm-modal');
  var applyBtn = document.getElementById('perm-apply');
  var cancelBtn = document.getElementById('perm-cancel');
  var hiddenBox = document.getElementById('perm-hidden-inputs');
  var summary = document.getElementById('perm-summary');

  function selectedValues() {
    return Array.prototype.slice.call(document.querySelectorAll('.perm-check:checked')).map(function (el) { return el.value; });
  }

  openBtn.addEventListener('click', function () { modal.style.display = 'block'; });
  cancelBtn.addEventListener('click', function () { modal.style.display = 'none'; });
  modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });

  document.getElementById('perm-select-all').addEventListener('click', function () {
    document.querySelectorAll('.perm-check').forEach(function (el) { el.checked = true; });
  });
  document.getElementById('perm-clear-all').addEventListener('click', function () {
    document.querySelectorAll('.perm-check').forEach(function (el) { el.checked = false; });
  });
  document.querySelectorAll('.perm-group-all').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var g = btn.getAttribute('data-group');
      document.querySelectorAll('.perm-check[data-group="' + g + '"]').forEach(function (el) { el.checked = true; });
    });
  });

  applyBtn.addEventListener('click', function () {
    var vals = selectedValues();
    hiddenBox.innerHTML = '';
    vals.forEach(function (v) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'permissions[]';
      input.value = v;
      hiddenBox.appendChild(input);
    });
    summary.textContent = vals.length
      ? (vals.length + ' دسترسی انتخاب شده')
      : 'هنوز دسترسی‌ای انتخاب نشده — روی «تغییر دسترسی +» کلیک کنید.';
    modal.style.display = 'none';
  });

  var viewModal = document.getElementById('view-perm-modal');
  var viewList = document.getElementById('view-perm-list');
  var viewTitle = document.getElementById('view-perm-title');
  document.querySelectorAll('[data-view-perms]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var items = [];
      try { items = JSON.parse(btn.getAttribute('data-view-perms') || '[]'); } catch (e) {}
      viewTitle.textContent = 'نمایش دسترسی — ' + (btn.getAttribute('data-view-title') || '');
      viewList.innerHTML = items.length
        ? items.map(function (t) { return '<li>' + t + '</li>'; }).join('')
        : '<li style="color:#8a97a8">دسترسی‌ای ثبت نشده.</li>';
      viewModal.style.display = 'block';
    });
  });
  document.getElementById('view-perm-close').addEventListener('click', function () { viewModal.style.display = 'none'; });
  viewModal.addEventListener('click', function (e) { if (e.target === viewModal) viewModal.style.display = 'none'; });
})();
</script>
@endsection
