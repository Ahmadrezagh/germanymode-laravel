@php
    $slides = ($banners ?? collect())->values();
@endphp
@if($slides->isNotEmpty())
<section class="ork-hero" id="orkHero" aria-label="بنرهای سایت">
    <div class="ork-hero-track" data-hero-track>
        @foreach($slides as $i => $banner)
            <article class="ork-hero-slide {{ $i === 0 ? 'is-active' : '' }}" data-hero-slide @if($i !== 0) hidden @endif>
                @if(filled($banner->button_url) && ! $banner->hasButton())
                    <a class="ork-hero-media" href="{{ $banner->button_url }}" draggable="false">
                        <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title ?: 'بنر' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" draggable="false">
                    </a>
                @else
                    <div class="ork-hero-media">
                        <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title ?: 'بنر' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" draggable="false">
                    </div>
                @endif

                @if(filled($banner->title) || $banner->hasButton())
                    <div class="ork-hero-overlay">
                        @if(filled($banner->title))
                            <h2 class="ork-hero-title">{{ $banner->title }}</h2>
                        @endif
                        @if($banner->hasButton())
                            <a class="ork-hero-cta" href="{{ $banner->button_url }}">{{ $banner->button_title }}</a>
                        @endif
                    </div>
                @endif
            </article>
        @endforeach
    </div>

    @if($slides->count() > 1)
        <div class="ork-hero-controls">
            <div class="ork-hero-dots" data-hero-dots>
                @foreach($slides as $i => $banner)
                    <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-hero-dot="{{ $i }}" aria-label="اسلاید {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
        <div class="ork-hero-progress" aria-hidden="true"><i data-hero-progress></i></div>
    @endif
</section>
<script src="{{ asset('js/shop-hero.js') }}" defer></script>
@endif
