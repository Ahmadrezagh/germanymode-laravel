@extends('layouts.shop')

@section('title', 'فروشگاه اینترنتی جرمنی مد')
@section('meta_description', 'فروشگاه اینترنتی جرمنی مد — خرید مستقیم از آلمان و اروپا، لوازم خانگی و برندهای اروپایی')

@section('content')
    <section class="ork-hero-note">
        فروشگاه اینترنتی جرمنی مد ، عرضه کننده محصولات اصل و اورجینال از آلمان و اروپا
    </section>

    @include('partials.hero-banner', ['banners' => $banners ?? collect()])

    @include('partials.product-rail', [
        'title' => 'کالای برگزیده',
        'items' => $featured,
        'moreUrl' => route('shop.search'),
    ])

    @include('partials.product-rail', [
        'title' => 'تخفیف ویژه',
        'items' => $specials,
        'moreUrl' => route('shop.search'),
    ])

    @include('partials.product-rail', [
        'title' => 'جدیدترین کالاها',
        'items' => $newest,
        'moreUrl' => route('shop.search'),
    ])

    @foreach($categoryRails as $rail)
        @include('partials.product-rail', [
            'title' => $rail->name,
            'items' => $rail->railProducts,
            'moreUrl' => route('shop.category', $rail),
        ])
    @endforeach

    <section class="ork-section">
        <div class="ork-rail-head">
            <h2>همه محصولات</h2>
            <span>{{ number_format($products->total()) }} کالا</span>
        </div>
        <div class="ork-grid">
            @forelse($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="ork-empty">محصولی یافت نشد.</p>
            @endforelse
        </div>
        <div class="pagination-wrap">
            {{ $products->links('pagination.simple') }}
        </div>
    </section>

    <script>
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
    </script>
@endsection
