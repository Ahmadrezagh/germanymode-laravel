@php
    $meta = $meta ?? [];
    $price = $meta['price'] ?? null;
    $priceValue = $meta['price_value'] ?? null;
    $url = $meta['url'] ?? null;
@endphp
@if($price || $url)
    <div class="ork-amazon">
        <div class="ork-amazon-brand">
            <span class="ork-amazon-mark">a</span>
            <div>
                <strong>منبع خرید آمازون</strong>
                <small>قیمت و لینک اصلی کالا در آلمان</small>
            </div>
        </div>
        <div class="ork-amazon-meta">
            @if($priceValue || $price)
                <div class="ork-amazon-price">
                    <span>قیمت آمازون</span>
                    <strong>{{ $priceValue ?: $price }}</strong>
                </div>
            @endif
            @if($url)
                <a class="ork-amazon-link" href="{{ $url }}" target="_blank" rel="noopener noreferrer nofollow">
                    مشاهده در آمازون
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                </a>
            @endif
        </div>
    </div>
@endif
