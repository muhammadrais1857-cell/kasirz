@if ($paginator->hasPages())
<nav class="halaman">
    @if ($paginator->onFirstPage())<span class="off">&lsaquo; Sebelumnya</span>@else<a href="{{ $paginator->previousPageUrl() }}">&lsaquo; Sebelumnya</a>@endif
    <span class="mu">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">Berikutnya &rsaquo;</a>@else<span class="off">Berikutnya &rsaquo;</span>@endif
</nav>
@endif
