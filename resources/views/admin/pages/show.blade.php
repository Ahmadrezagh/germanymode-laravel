@extends('layouts.admin')

@section('title', ($meta['title'] ?? 'صفحه').' | مدیریت سایت')
@section('tab', $meta['title'] ?? 'صفحه')

@section('content')
    <h1 class="page-title">
        {{ $meta['title'] }}
    </h1>

    @if(!empty($meta['slow']))
        <div class="alert alert-warn">
            این بخش در پنل اصلی (<code>{{ $meta['source'] ?? '' }}</code>) معمولاً خیلی کند لود می‌شود؛ اینجا نسخه سبک آن آماده است.
        </div>
    @endif

    @if(!empty($meta['description']))
        <p style="color:#666;margin:0 0 14px;font-size:13.5px;line-height:1.8">{{ $meta['description'] }}</p>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $meta['title'] }}</h2>
            <div class="toolbar">
                @foreach(($meta['actions'] ?? []) as $action)
                    <button class="btn btn-default btn-sm" type="button">{{ $action }}</button>
                @endforeach
            </div>
        </div>
        <div class="panel-body">
            @if(!empty($meta['fields']))
                <div class="form-grid">
                    @foreach($meta['fields'] as $field)
                        <div class="form-group {{ count($meta['fields']) < 4 ? 'full' : '' }}">
                            <label>{{ $field }}</label>
                            @if(str_contains($field, 'محتوا') || str_contains($field, 'آدرس') || str_contains($field, 'توضیح'))
                                <textarea></textarea>
                            @elseif(str_contains($field, 'رمز'))
                                <input type="password">
                            @elseif(str_contains($field, 'فایل') || str_contains($field, 'تصویر') || str_contains($field, 'آیکون'))
                                @include('partials.file-drop', [
                                    'name' => 'file_'.md5($field),
                                    'accept' => str_contains($field, 'تصویر') || str_contains($field, 'آیکون') ? 'image/*' : null,
                                    'title' => $field.' را اینجا رها کنید',
                                ])
                            @elseif(str_contains($field, 'وضعیت') || str_contains($field, 'انتخاب') || str_contains($field, 'نوع'))
                                <select class="shop-select">
                                    <option value="">— انتخاب کنید —</option>
                                    <option>فعال</option>
                                    <option>غیرفعال</option>
                                </select>
                            @else
                                <input type="text">
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="form-actions">
                    <button class="btn btn-green" type="button">ارسال</button>
                    <a class="btn btn-red-outline" href="{{ route('admin.dashboard') }}">انصراف</a>
                </div>
            @elseif(!empty($meta['columns']))
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                        <tr>
                            @foreach($meta['columns'] as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="{{ count($meta['columns']) }}" style="text-align:center;color:#888;padding:28px">
                                موردی برای نمایش نیست.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warn" style="margin:0">
                    صفحه «{{ $meta['title'] }}» آماده است. محتوا و عملیات بعدی را می‌توانیم دقیق‌تر روی همین مسیر پیاده کنیم.
                    @if(!empty($meta['source']))
                        <div style="margin-top:6px;font-size:12px;opacity:.85">منبع پنل اصلی: {{ $meta['source'] }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
