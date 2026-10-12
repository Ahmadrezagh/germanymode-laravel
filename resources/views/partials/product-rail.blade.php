@php
    $title = $title ?? 'محصولات';
    $items = $items ?? collect();
    $moreUrl = $moreUrl ?? route('shop.search');
@endphp
@if($items->isNotEmpty())
<section class="ork-rail">
    <div class="ork-rail-head">
        <h2>{{ $title }}</h2>
        <a href="{{ $moreUrl }}">کالای بیشتر</a>
    </div>
    <div class="ork-rail-track" data-rail>
        <button class="ork-rail-nav prev" type="button" data-rail-prev aria-label="قبلی">‹</button>
        <div class="ork-rail-scroller" data-rail-scroller>
            @foreach($items as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <button class="ork-rail-nav next" type="button" data-rail-next aria-label="بعدی">›</button>
    </div>
</section>
@endif
