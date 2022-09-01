<?php
namespace Psi\FlexAdmin\Resources;

trait ResourcePagination
{
    /**
     * @var int - pagination option to use for default pagination per page
     */
    protected int|null $perPage;

    /**
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

    public function perPage()
    {
        return $this->perPage ?? $this->model->getPerPage();
    }

    public function perPageOptions()
    {
        return $this->perPageOptions ?? config('flex-admin.pagination.per_page_options');
    }
}
