<?php

namespace Psi\FlexAdmin\Resources;

trait ResourcePagination
{

    /**
     *
     * @var int - pagination option to use for default pagination per page
     */
    protected int|null $perPage;

    /**
     *
     * @var array
     */
    protected array|null $perPageOptions;

    /**
     * Determines if we should paginate the resource
     *
     * @var bool
     */
    protected bool $paginate = true;


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
            // Quasar Specific Fields
            'page' => $this->resource->currentPage(),
            'rowsPerPage' => $this->resource->perPage(),
            'rowsNumber' => $this->resource->total(),

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
            'rowsPerPageOptions' => $this->perPageOptions()
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
