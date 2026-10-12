@php
    $href = $product->url();
    $img = $product->image;
@endphp
<a href="{{ $href }}" class="ork-card" title="{{ $product->title }}">
    <span class="ork-card-img">
        @if($img)
            <img loading="lazy" src="{{ $img }}" alt="{{ $product->title }}">
        @else
            <span class="ork-card-empty">بدون تصویر</span>
        @endif
    </span>
    <h2 class="ork-card-title">{{ $product->title }}</h2>
    <span class="ork-card-price">
        @if($product->price > 0)
            @if($product->oldPriceNumber())
                <span class="ork-price-old">{{ $product->oldPriceNumber() }} تومان</span>
            @endif
            <span class="ork-price-now">{{ $product->priceNumber() }} <small>تومان</small></span>
        @else
            <span class="ork-price-now">تماس بگیرید</span>
        @endif
    </span>
</a>
