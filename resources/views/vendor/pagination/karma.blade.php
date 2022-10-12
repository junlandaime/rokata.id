@if ($paginator->hasPages())
    <div class="pagination">

        @if ($paginator->onFirstPage())
            {{-- <a class="prev-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> --}}
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="prev-arrow"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif



            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- <a href="#" class="active">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
        <a href="#">6</a> --}}

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="next-arrow"><i class="fa fa-arrow-right"
                    aria-hidden="true"></i></a>
        @else
        @endif
    </div>
@endif
