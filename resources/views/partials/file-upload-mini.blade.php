@php
    $name = $name ?? 'file';
    $urlName = $urlName ?? null; // optional companion text field name
    $value = $value ?? '';
    $accept = $accept ?? 'image/*';
    $placeholder = $placeholder ?? 'http://';
@endphp
<div class="file-upload-mini" data-file-upload-mini>
    @if($urlName)
        <input
            class="file-upload-mini-url shop-input"
            type="text"
            name="{{ $urlName }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
        >
    @else
        <input
            class="file-upload-mini-url shop-input"
            type="text"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            readonly
            tabindex="-1"
        >
    @endif
    <label class="file-upload-mini-btn" title="انتخاب فایل">
        <input type="file" name="{{ $name }}" accept="{{ $accept }}" data-skip-upgrade hidden>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 16V6M12 6l-3.5 3.5M12 6l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 16.5V18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1.5" stroke-linecap="round"/>
        </svg>
    </label>
</div>
