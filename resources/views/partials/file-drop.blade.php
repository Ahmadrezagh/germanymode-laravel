@php
    $name = $name ?? 'file';
    $accept = $accept ?? null;
    $required = $required ?? false;
    $multiple = $multiple ?? str_ends_with((string) $name, '[]');
    $title = $title ?? 'فایل را بکشید و اینجا رها کنید';
    $sub = $sub ?? 'یا روی دکمه انتخاب فایل کلیک کنید';
@endphp
<label class="file-drop" data-file-drop>
    <input type="file" name="{{ $name }}" @if($accept) accept="{{ $accept }}" @endif @if($required) required @endif @if($multiple) multiple @endif>
    <span class="file-drop-ico" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M12 16V6M12 6l-3.5 3.5M12 6l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 16.5V18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1.5" stroke-linecap="round"/>
        </svg>
    </span>
    <span class="file-drop-title">{{ $title }}</span>
    <span class="file-drop-sub">{{ $sub }}</span>
    <span class="file-drop-browse">انتخاب فایل</span>
    <span class="file-drop-name"></span>
</label>
