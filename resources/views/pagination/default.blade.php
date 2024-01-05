<style>
.page-item{
border:1px solid #eceff4;background:white!important;
}

</style>@if ($paginator->lastPage() > 1)
<nav aria-label="Page navigation">
        <ul class="pagination">
            
            <li class="page-item  {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}"><i class="tf-icon bx bx-chevrons-left"></i></a>
            </li>
             <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()-1) }}"><i class="tf-icon bx bx-chevron-left"></i></a>
            </li>
            @if ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}" ><i class="tf-icon bx bx-chevron-right"></i></a>
            </li>
            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" ><i class="tf-icon bx bx-chevrons-right"></i></a>
            </li>
        </ul>
</nav>
@endif

