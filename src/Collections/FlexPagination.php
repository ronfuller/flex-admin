<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Collections;

trait FlexPagination
{
    protected function perPageOptions(): array
    {
        return $this->resource->perPageOptions();
    }

    protected function toPaginationMeta(array $sort, mixed $resource): void
    {
        $this->paginationMeta = $this->paginate ? [...$sort, ...[
            // Quasar Specific Fields
            'page' => $resource->currentPage(),
            'rowsPerPage' => $resource->perPage(),
            'rowsNumber' => $resource->total(),

            'currentPage' => $resource->currentPage(),
            'from' => $resource->firstItem(),
            'lastPage' => $resource->lastPage(),
            'path' => $resource->path(),
            'perPage' => $resource->perPage(),
            'total' => $resource->total(),
            'to' => $resource->lastItem(),
            'nextUrl' => $resource->nextPageUrl(),
            'previousUrl' => $resource->previousPageUrl(),
            'previous' => $resource->onFirstPage(),
            'next' => $resource->hasMorePages(),
            'rowsPerPageOptions' => $this->perPageOptions(),
        ]] : [];
    }
}
