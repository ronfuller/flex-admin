<?php

namespace Psi\FlexAdmin\Resources;

trait ResourcePagination
{
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setPerPageOptions(array $perPageOptions): self
    {
        $this->perPageOptions = $perPageOptions;

        return $this;
    }

    public function toPagination(): array
    {

        // TODO: Handle transform , append
        return $this->paginate ? [
            'currentPage' => $this->resource->currentPage(),
            'from' => $this->resource->firstItem(),
            'lastPage' => $this->resource->lastPage(),
            'path' => $this->resource->path(),
            'perPage' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'to' => $this->resource->lastItem(),
            'nextUrl' => $this->resource->nextPageUrl(),
            'previousUrl' => $this->resource->previousPageUrl(),
            'previous' => $this->resource->onFirstPage(),
            'next' => $this->resource->hasMorePages(),
        ] : [];
    }

    protected function perPage()
    {
        return $this->perPage ?? $this->model->getPerPage();
    }

    protected function perPageOptions()
    {
        return $this->perPageOptions ?? config('flex-admin.pagination.per_page_options');
    }
}
