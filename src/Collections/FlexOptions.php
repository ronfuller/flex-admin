<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Collections;

trait FlexOptions
{
    /**
     * Inertia Page Component
     */
    public ?string $page = null;

    /**
     * Determines if we load default filter values from the resource
     */
    protected bool $defaultFilters = true;

    /**
     * Determines if we build filter options immediately
     */
    protected bool $deferFilters = false;

    /**
     * Include filters
     */
    protected bool $withFilters = true;

    /**
     * Determines if we paginate
     */
    protected bool $paginate = true;

    /**
     * Include Actions with the resource
     */
    protected bool $withActions = true;

    /**
     * Include resource relations
     */
    protected bool $withRelations = true;

    /**
     * Convert Fields to Associative Array Object
     */
    protected bool $fieldsAsObject = false;

    /**
     * Send output to ray debugger
     */
    protected bool $sendToRay = false;

    /**
     * Callback function to transform data on converting to array
     *
     * @var callable|null
     */
    protected mixed $transformer = null;

    public function transform(callable $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Fields on panels will be created to objects
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function fieldsAsObject(): self
    {
        $this->fieldsAsObject = true;

        return $this;
    }

    /**
     * Set the Inertia Page Component
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function page(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function ray(): self
    {
        $this->sendToRay = true;

        return $this;
    }

    /**
     * Without pagination
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutPagination(): self
    {
        $this->paginate = false;

        return $this;
    }

    /**
     * Without default filters
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutDefaultFilters(): self
    {
        $this->defaultFilters = false;

        return $this;
    }

    /**
     * Without deferred filters
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutDeferredFilters(): self
    {
        $this->deferFilters = false;

        return $this;
    }

    public function withoutFilters(): self
    {
        $this->withFilters = false;

        return $this;
    }

    /**
     * Creates a resource without actions
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutActions(): self
    {
        $this->withActions = false;

        return $this;
    }

    public function withoutRelations(): self
    {
        $this->withRelations = false;

        return $this;
    }
}
