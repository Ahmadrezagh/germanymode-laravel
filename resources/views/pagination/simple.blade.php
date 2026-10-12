@if ($paginator->hasPages())
    <nav>
        @if ($paginator->onFirstPage())
            <span>قبلی</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">قبلی</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">بعدی</a>
        @else
            <span>بعدی</span>
        @endif
    </nav>
@endif
