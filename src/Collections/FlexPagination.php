<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Collections;

trait FlexPagination
{
    protected function perPageOptions(): array
    {
        return $this->resource->perPageOptions();
    }

    protected function toPaginationMeta(array $defaultSort, string $indexRoute, array $sort, mixed $resource): void
    {
        $replacePath = '';
        if ($this->paginate) {
            // We need the path for the indexed resource, if this is related it will be a replacement, if not related nothing gets replaced
            $replacePath = str($resource->path())->contains('?') ? (string) str($resource->path())->before('?') : $resource->path();
        }

        $this->paginationMeta = $this->paginate ? [...$sort, ...[
            // Quasar Specific Fields
            'page' => $resource->currentPage(),
            'rowsPerPage' => $resource->perPage(),
            'rowsNumber' => $resource->total(),

            'defaultSort' => $defaultSort,

            'nextUrl' => (string) str($resource->nextPageUrl())->replace($replacePath, $indexRoute),
            'previousUrl' => (string) str($resource->previousPageUrl())->replace($replacePath, $indexRoute),
            'path' => (string) str($resource->path())->replace($replacePath, $indexRoute),

            'currentPage' => $resource->currentPage(),
            'from' => $resource->firstItem(),
            'lastPage' => $resource->lastPage(),

            'perPage' => $resource->perPage(),
            'total' => $resource->total(),
            'to' => $resource->lastItem(),

            'previous' => $resource->onFirstPage(),
            'next' => $resource->hasMorePages(),
            'rowsPerPageOptions' => $this->perPageOptions(),
        ]] : [];
    }
}
