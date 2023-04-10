<?php

namespace Psi\FlexAdmin\Tests\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Psi\FlexAdmin\Builders\FlexQueryBuilder;
use Psi\FlexAdmin\Concerns\HasDateRange;

class UnitBuilder extends Builder implements FlexQueryBuilder
{
    use HasDateRange;

    public function index(array $attributes): self
    {
        return $this->whereNotNull('id');
    }

    public function search(string $term, array $attributes = []): self
    {
        return $this;
    }

    public function filter(array $filter, array $attributes = []): self
    {
        return $this;
    }

    public function sortBy(string $sort, string $sortDir): self
    {
        return $this->orderBy($sort, $sortDir);
    }
}
