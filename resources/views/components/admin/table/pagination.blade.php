@if ($paginator->hasPages())
    <nav class="@stylex('pagFooter')" role="navigation" aria-label="Pagination navigation">
        <p class="@stylex('pagInfo')">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>

        <ul class="@stylex('pagNav')">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="@stylex('pageNum', 'pageNumDisabled')" aria-disabled="true">
                        <x-lucide-chevron-left class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </span>
                @else
                    <button
                        type="button"
                        class="@stylex('pageNum')"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        aria-label="Previous page"
                    >
                        <x-lucide-chevron-left class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </button>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li wire:key="paginator-{{ $paginator->getPageName() }}-{{ $element }}">
                        <span class="@stylex('pageNumDots')" aria-hidden="true">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                            @if ($page == $paginator->currentPage())
                                <span class="@stylex('pageNum', 'pageNumActive')" aria-current="page">{{ $page }}</span>
                            @else
                                <button
                                    type="button"
                                    class="@stylex('pageNum')"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    wire:loading.attr="disabled"
                                    aria-label="Go to page {{ $page }}"
                                >
                                    {{ $page }}
                                </button>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <button
                        type="button"
                        class="@stylex('pageNum')"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        aria-label="Next page"
                    >
                        <x-lucide-chevron-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </button>
                @else
                    <span class="@stylex('pageNum', 'pageNumDisabled')" aria-disabled="true">
                        <x-lucide-chevron-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif