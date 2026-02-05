<div class="row">
    <div class="col-12">
        <nav aria-label="...">
        <ul class="pagination">
            @if ($paginator->onFirstPage())

            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
                </li>
            @endif

            @foreach ($elements as $element)
    @if (is_array($element)) 
        @foreach ($element as $key => $el)
            @if ($key == $paginator->currentPage())
                <li class="page-item active"><a class="page-link" href="javascript:void(0)">{{ $key }}</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $el }}">{{ $key }}</a></li>
            @endif
        @endforeach
    @endif
@endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @endif


        </ul>
    </nav>
</div>
</div>

