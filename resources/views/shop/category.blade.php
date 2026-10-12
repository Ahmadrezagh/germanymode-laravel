@extends('layouts.shop')

@section('title', $category->name.' | جرمنی مد')

@section('content')
    <nav class="ork-breadcrumb">
        <a href="{{ route('shop.home') }}">خانه</a>
        <span>/</span>
        <span>{{ $category->name }}</span>
    </nav>

    <section class="ork-section">
        <div class="ork-rail-head">
            <h2>{{ $category->name }}</h2>
            <span>{{ number_format($products->total()) }} کالا</span>
        </div>
        <div class="ork-grid">
            @forelse($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="ork-empty">در این دسته محصولی نیست.</p>
            @endforelse
        </div>
        <div class="pagination-wrap">
            {{ $products->links('pagination.simple') }}
        </div>
    </section>
@endsection
