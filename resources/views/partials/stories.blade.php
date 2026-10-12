@php
    $storyItems = ($stories ?? collect())->values();
    $storyPayload = $storyItems->map(fn ($s) => $s->toViewerArray())->values();
@endphp

@if($storyItems->isNotEmpty())
<section class="ork-stories" id="orkStories">
    <div class="ork-stories-track">
        @foreach($storyItems as $index => $story)
            <button
                type="button"
                class="ork-story"
                data-story-open="{{ $index }}"
                aria-label="استوری {{ $story->profile_name }}"
            >
                <span class="ork-story-ring">
                    @if($story->profileImageUrl())
                        <img src="{{ $story->profileImageUrl() }}" alt="{{ $story->profile_name }}" loading="lazy">
                    @else
                        <span class="ork-story-fallback">{{ mb_substr($story->profile_name, 0, 1) }}</span>
                    @endif
                </span>
                <span class="ork-story-name">{{ $story->profile_name }}</span>
            </button>
        @endforeach
    </div>
</section>

<div class="ork-story-viewer" id="orkStoryViewer" hidden aria-hidden="true">
    <div class="ork-story-backdrop" data-story-close></div>
    <div class="ork-story-frame" role="dialog" aria-modal="true" aria-label="استوری">
        <div class="ork-story-progress" id="orkStoryProgress"></div>

        <div class="ork-story-top">
            <div class="ork-story-user">
                <img src="" alt="" id="orkStoryAvatar">
                <div>
                    <strong id="orkStoryName"></strong>
                    <small id="orkStoryCaption"></small>
                </div>
            </div>
            <button type="button" class="ork-story-close" data-story-close aria-label="بستن">×</button>
        </div>

        <div class="ork-story-media" id="orkStoryMedia">
            <video id="orkStoryVideo" playsinline webkit-playsinline></video>
            <img id="orkStoryImage" alt="">
        </div>

        <div class="ork-story-text">
            <h3 id="orkStoryTitle"></h3>
            <p id="orkStoryText"></p>
        </div>

        <a class="ork-story-cta" id="orkStoryCta" href="#" target="_blank" rel="noopener" hidden>
            <img id="orkStoryCtaImg" alt="">
            <span id="orkStoryCtaTitle"></span>
        </a>

        <button type="button" class="ork-story-nav prev" data-story-prev aria-label="قبلی"></button>
        <button type="button" class="ork-story-nav next" data-story-next aria-label="بعدی"></button>
    </div>
</div>

<script>
window.__ORK_STORIES__ = @json($storyPayload);
</script>
<script src="{{ asset('js/shop-stories.js') }}" defer></script>
@endif
