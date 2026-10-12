@extends('layouts.shop')

@section('title', 'جستجو | جرمنی مد')

@section('content')
    <nav class="ork-breadcrumb">
        <a href="{{ route('shop.home') }}">خانه</a>
        <span>/</span>
        <span>جستجو</span>
    </nav>

    <section class="ork-section">
        <div class="ork-rail-head">
            <h2>نتایج جستجو @if($q) برای «{{ $q }}» @endif</h2>
            <span>{{ number_format($products->total()) }} مورد</span>
        </div>
        <div class="ork-grid">
            @forelse($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="ork-empty">نتیجه‌ای پیدا نشد.</p>
            @endforelse
        </div>
        <div class="pagination-wrap">
            {{ $products->links('pagination.simple') }}
        </div>
    </section>
@endsection
