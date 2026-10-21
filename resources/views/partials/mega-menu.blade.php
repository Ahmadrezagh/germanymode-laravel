@php
    $megaCategories = $megaCategories ?? collect();
    $megaPart = $megaPart ?? 'all';
@endphp

@if($megaCategories->isNotEmpty())
    @if($megaPart === 'trigger' || $megaPart === 'all')
        <div class="ork-mega" id="orkMega">
            <button type="button" class="ork-mega-trigger {{ request()->routeIs('shop.category') ? 'active' : '' }}" id="orkMegaTrigger" aria-expanded="false" aria-controls="orkMegaPanel">
                دسته بندی کالا
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </button>
        </div>
    @endif

    @if($megaPart === 'panel' || $megaPart === 'all')
        <div class="ork-mega-panel" id="orkMegaPanel" hidden>
            <div class="ork-mega-inner">
                <aside class="ork-mega-l1" role="tablist" aria-label="دسته‌بندی‌ها">
                    <a class="ork-mega-all" href="{{ route('shop.search') }}">مشاهده همه کالاها</a>
                    @foreach($megaCategories as $i => $cat)
                        <button
                            type="button"
                            class="ork-mega-l1-item {{ $i === 0 ? 'active' : '' }}"
                            role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            data-mega-tab="{{ $cat->id }}"
                        >
                            <span>{{ $cat->name }}</span>
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
                        </button>
                    @endforeach
                </aside>

                <div class="ork-mega-l2">
                    @foreach($megaCategories as $i => $cat)
                        <div
                            class="ork-mega-pane {{ $i === 0 ? 'active' : '' }}"
                            data-mega-pane="{{ $cat->id }}"
                            @if($i !== 0) hidden @endif
                        >
                            <div class="ork-mega-pane-head">
                                <h3>{{ $cat->name }}</h3>
                                <a href="{{ route('shop.category', $cat) }}">همه این دسته</a>
                            </div>

                            @if($cat->children->isNotEmpty())
                                <div class="ork-mega-groups">
                                    @foreach($cat->children as $child)
                                        <div class="ork-mega-group">
                                            <a class="ork-mega-group-title" href="{{ route('shop.category', $child) }}">{{ $child->name }}</a>
                                            @if($child->children->isNotEmpty())
                                                <div class="ork-mega-links">
                                                    @foreach($child->children as $grand)
                                                        <a href="{{ route('shop.category', $grand) }}">{{ $grand->name }}</a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <a class="ork-mega-empty-link" href="{{ route('shop.category', $cat) }}">رفتن به دسته {{ $cat->name }}</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script src="{{ asset('js/shop-mega.js') }}" defer></script>
    @endif
@elseif($megaPart === 'trigger' || $megaPart === 'all')
    <a href="{{ route('shop.search') }}">دسته بندی کالا</a>
@endif
