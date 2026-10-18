@extends('layouts.shop')

@section('title', $product->title.' | جرمنی مد')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->short_description ?: $product->title), 160))

@section('content')
@php
    $gallery = $product->galleryUrlsResolved();
    $main = $gallery[0] ?? $product->image;
    $shortMeta = \App\Domains\Catalog\Models\Product::extractAmazonMeta($product->short_description);
    $specsMeta = \App\Domains\Catalog\Models\Product::extractAmazonMeta($product->specifications);
    $specsHasAmazon = $specsMeta['price'] || $specsMeta['url'];
    $shortHasAmazon = $shortMeta['price'] || $shortMeta['url'];
    // If specs already shows the Amazon card, don't repeat it above.
    $showShortAmazon = $shortHasAmazon && ! $specsHasAmazon;
@endphp

<nav class="ork-breadcrumb">
    <a href="{{ route('shop.home') }}">خانه</a>
    <span>/</span>
    @if($product->category)
        <a href="{{ route('shop.category', $product->category) }}">{{ $product->category->name }}</a>
        <span>/</span>
    @endif
    <span>{{ \Illuminate\Support\Str::limit($product->title, 48) }}</span>
</nav>

@php
    $lightboxImages = count($gallery) ? $gallery : array_values(array_filter([$main]));
@endphp
<article class="ork-product">
    <div class="ork-product-gallery" data-gallery-images='@json($lightboxImages)'>
        <div class="ork-product-main {{ $main ? 'is-zoomable' : '' }}" id="orkMainZoom" @if($main) role="button" tabindex="0" aria-label="بزرگ‌نمایی تصویر محصول" @endif>
            @if($main)
                <img src="{{ $main }}" alt="{{ $product->title }}" id="orkMainImage">
            @else
                <span class="ork-card-empty">بدون تصویر</span>
            @endif
        </div>
        @if(count($gallery) > 1)
            <div class="ork-product-thumbs">
                @foreach($gallery as $url)
                    <button type="button" class="ork-thumb {{ $loop->first ? 'active' : '' }}" data-src="{{ $url }}" data-index="{{ $loop->index }}">
                        <img src="{{ $url }}" alt="" loading="lazy">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="ork-product-info">
        @if($product->brand)
            <div class="ork-brand">{{ $product->brand->name }}</div>
        @endif
        <h1>{{ $product->title }}</h1>

        <div class="ork-product-pricebox">
            @if($product->price > 0)
                <div class="ork-price-now big">{{ $product->priceNumber() }} <small>تومان</small></div>
                @if($product->oldPriceNumber())
                    <div class="ork-price-old">{{ $product->oldPriceNumber() }} تومان</div>
                    <div class="ork-discount-badge">
                        {{ round((1 - $product->price / max((int) $product->regular_price, 1)) * 100) }}٪ تخفیف
                    </div>
                @endif
            @else
                <div class="ork-price-now big">تماس بگیرید</div>
            @endif
        </div>

        @if($product->models->isNotEmpty())
            <div class="ork-models">
                <div class="ork-label">انتخاب مدل / رنگ</div>
                <div class="ork-model-list">
                    @foreach($product->models as $model)
                        <label class="ork-model-item">
                            <input type="radio" name="model" value="{{ $model->id }}" @checked($loop->first)>
                            @if($model->color)
                                <i style="background:{{ $model->color }}"></i>
                            @endif
                            <span>{{ $model->model ?: 'مدل' }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="ork-qty-row">
            <div class="ork-label">تعداد</div>
            <div class="ork-qty">
                <button type="button" data-qty="-">−</button>
                <input type="number" id="orkQty" value="1" min="1" max="99">
                <button type="button" data-qty="+">+</button>
            </div>
        </div>

        @if(session('ok'))
            <div class="ork-alert ok">{{ session('ok') }}</div>
        @endif
        <form method="post" action="{{ route('shop.cart.add') }}" class="ork-buy-row">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="qty" id="orkQtyHidden" value="1">
            <button class="ork-btn-buy" type="submit">افزودن به سبد خرید</button>
            <a class="ork-btn-ghost" href="#ork-specs">مشخصات کالا</a>
        </form>

        @if($showShortAmazon)
            @include('partials.amazon-source', ['meta' => $shortMeta])
        @endif
        @if($shortMeta['body'] !== '')
            <div class="ork-short">{{ $shortMeta['body'] }}</div>
        @elseif($product->short_description && ! ($shortMeta['price'] || $shortMeta['url']))
            <div class="ork-short">{{ $product->short_description }}</div>
        @endif

        <ul class="ork-trust">
            <li>ارسال مستقیم از آلمان</li>
            <li>ضمانت اصالت کالا</li>
            <li>پشتیبانی خرید</li>
        </ul>
    </div>
</article>

@if($product->specifications)
    <section class="ork-specs" id="ork-specs">
        <h2>مشخصات کالا</h2>
        @if($specsMeta['price'] || $specsMeta['url'])
            @include('partials.amazon-source', ['meta' => $specsMeta])
        @endif
        @if($specsMeta['body'] !== '')
            <div class="ork-specs-body">{{ $specsMeta['body'] }}</div>
        @elseif(! ($specsMeta['price'] || $specsMeta['url']))
            <div class="ork-specs-body">{{ $product->specifications }}</div>
        @endif
    </section>
@endif

@if($related->isNotEmpty())
    @include('partials.product-rail', [
        'title' => 'محصولات مرتبط',
        'items' => $related,
        'moreUrl' => $product->category ? route('shop.category', $product->category) : route('shop.search'),
    ])
@endif

@if(count($lightboxImages))
    <div class="ork-lightbox" id="orkLightbox" hidden>
        <button type="button" class="ork-lightbox-close" data-lightbox-close aria-label="بستن">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
        @if(count($lightboxImages) > 1)
            <button type="button" class="ork-lightbox-nav prev" data-lightbox-prev aria-label="قبلی">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
            </button>
            <button type="button" class="ork-lightbox-nav next" data-lightbox-next aria-label="بعدی">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 5l-7 7 7 7"/></svg>
            </button>
        @endif
        <figure class="ork-lightbox-figure">
            <img src="{{ $lightboxImages[0] }}" alt="{{ $product->title }}" id="orkLightboxImage">
            <figcaption class="ork-lightbox-caption">
                <span id="orkLightboxCounter">1 / {{ count($lightboxImages) }}</span>
            </figcaption>
        </figure>
    </div>
@endif

<script>
(function () {
    var galleryRoot = document.querySelector('.ork-product-gallery');
    var thumbs = document.querySelector('.ork-product-thumbs');
    var mainImg = document.getElementById('orkMainImage');
    var mainZoom = document.getElementById('orkMainZoom');
    var lightbox = document.getElementById('orkLightbox');
    var lightboxImg = document.getElementById('orkLightboxImage');
    var lightboxCounter = document.getElementById('orkLightboxCounter');
    var images = [];
    var index = 0;

    try {
        images = JSON.parse(galleryRoot && galleryRoot.getAttribute('data-gallery-images') || '[]');
    } catch (_) {
        images = [];
    }
    if (!images.length && mainImg && mainImg.src) images = [mainImg.src];

    function syncThumbs() {
        document.querySelectorAll('.ork-thumb').forEach(function (b, i) {
            var btnIndex = parseInt(b.getAttribute('data-index') || String(i), 10);
            b.classList.toggle('active', btnIndex === index);
        });
    }

    function showIndex(nextIndex, updateMain) {
        if (!images.length) return;
        index = (nextIndex + images.length) % images.length;
        var src = images[index];
        if (updateMain !== false && mainImg) mainImg.src = src;
        if (lightboxImg) lightboxImg.src = src;
        if (lightboxCounter) lightboxCounter.textContent = (index + 1) + ' / ' + images.length;
        syncThumbs();
    }

    function currentIndexFromMain() {
        if (!mainImg) return 0;
        var src = mainImg.getAttribute('src') || '';
        var found = images.indexOf(src);
        return found >= 0 ? found : 0;
    }

    function openLightbox(atIndex) {
        if (!lightbox || !images.length) return;
        showIndex(typeof atIndex === 'number' ? atIndex : currentIndexFromMain(), false);
        lightbox.hidden = false;
        document.body.classList.add('ork-lightbox-open');
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.hidden = true;
        document.body.classList.remove('ork-lightbox-open');
    }

    function selectThumb(btn) {
        if (!btn || !mainImg) return;
        var src = btn.getAttribute('data-src');
        if (!src) return;
        var btnIndex = parseInt(btn.getAttribute('data-index') || '-1', 10);
        if (btnIndex < 0) btnIndex = images.indexOf(src);
        if (btnIndex < 0) btnIndex = 0;
        showIndex(btnIndex, true);
    }

    if (mainZoom && mainImg) {
        mainZoom.addEventListener('click', function () {
            openLightbox(currentIndexFromMain());
        });
        mainZoom.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openLightbox(currentIndexFromMain());
            }
        });
    }

    if (lightbox) {
        lightbox.querySelectorAll('[data-lightbox-close]').forEach(function (btn) {
            btn.addEventListener('click', closeLightbox);
        });
        var prevBtn = lightbox.querySelector('[data-lightbox-prev]');
        var nextBtn = lightbox.querySelector('[data-lightbox-next]');
        if (prevBtn) prevBtn.addEventListener('click', function (e) { e.stopPropagation(); showIndex(index - 1, true); });
        if (nextBtn) nextBtn.addEventListener('click', function (e) { e.stopPropagation(); showIndex(index + 1, true); });

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });

        document.addEventListener('keydown', function (e) {
            if (lightbox.hidden) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showIndex(index + 1, true); // RTL: left = next
            if (e.key === 'ArrowRight') showIndex(index - 1, true);
        });
    }

    if (thumbs) {
        var drag = { active: false, dragging: false, startX: 0, scrollLeft: 0, suppressClick: false };

        thumbs.addEventListener('pointerdown', function (e) {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            drag.active = true;
            drag.dragging = false;
            drag.suppressClick = false;
            drag.startX = e.clientX;
            drag.scrollLeft = thumbs.scrollLeft;
        });

        thumbs.addEventListener('pointermove', function (e) {
            if (!drag.active) return;
            var dx = e.clientX - drag.startX;
            if (!drag.dragging && Math.abs(dx) > 6) {
                drag.dragging = true;
                drag.suppressClick = true;
                thumbs.classList.add('is-dragging');
                try { thumbs.setPointerCapture(e.pointerId); } catch (_) {}
            }
            if (drag.dragging) {
                thumbs.scrollLeft = drag.scrollLeft - dx;
            }
        });

        function endDrag(e) {
            if (!drag.active) return;
            var wasDragging = drag.dragging;
            drag.active = false;
            drag.dragging = false;
            thumbs.classList.remove('is-dragging');
            try { thumbs.releasePointerCapture(e.pointerId); } catch (_) {}

            if (!wasDragging) {
                var btn = e.target && e.target.closest ? e.target.closest('.ork-thumb') : null;
                if (btn && thumbs.contains(btn)) selectThumb(btn);
            }
        }

        thumbs.addEventListener('pointerup', endDrag);
        thumbs.addEventListener('pointercancel', function () {
            drag.active = false;
            drag.dragging = false;
            thumbs.classList.remove('is-dragging');
        });

        thumbs.addEventListener('click', function (e) {
            if (!drag.suppressClick) return;
            e.preventDefault();
            e.stopPropagation();
            drag.suppressClick = false;
        }, true);

        thumbs.addEventListener('wheel', function (e) {
            if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) return;
            if (thumbs.scrollWidth <= thumbs.clientWidth) return;
            e.preventDefault();
            thumbs.scrollLeft += e.deltaY;
        }, { passive: false });
    }

    var qty = document.getElementById('orkQty');
    var qtyHidden = document.getElementById('orkQtyHidden');
    function syncQty(n) {
        n = Math.max(1, Math.min(99, n));
        if (qty) qty.value = n;
        if (qtyHidden) qtyHidden.value = n;
    }
    document.querySelectorAll('[data-qty]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var n = parseInt((qty && qty.value) || '1', 10);
            n = btn.getAttribute('data-qty') === '+' ? n + 1 : n - 1;
            syncQty(n);
        });
    });
    if (qty) {
        qty.addEventListener('change', function () {
            syncQty(parseInt(qty.value || '1', 10));
        });
    }
    document.querySelectorAll('[data-rail]').forEach(function (rail) {
        var scroller = rail.querySelector('[data-rail-scroller]');
        var prev = rail.querySelector('[data-rail-prev]');
        var next = rail.querySelector('[data-rail-next]');
        if (!scroller) return;
        function go(dir) {
            scroller.scrollBy({ left: dir * Math.min(scroller.clientWidth * 0.8, 520), behavior: 'smooth' });
        }
        if (prev) prev.addEventListener('click', function () { go(1); });
        if (next) next.addEventListener('click', function () { go(-1); });
    });
})();
</script>
@endsection
