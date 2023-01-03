<?php

namespace Psi\FlexAdmin\Tests\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Psi\FlexAdmin\Builders\FlexQueryBuilder;

class CompanyBuilder extends Builder implements FlexQueryBuilder
{
    public function index(array $attributes): FlexQueryBuilder
    {
        return $this->whereNotNull('id');
    }

    public function search(string $term, array $attributes = []): FlexQueryBuilder
    {
        return $this->where('name', 'like', "%{$term}%");
    }

    public function filter(array $filter, array $attributes = []): FlexQueryBuilder
    {
        return $this;
    }

    public function sortBy(string $sort, string $sortDir): FlexQueryBuilder
    {
        return $this->orderBy($sort, $sortDir);
    }
}
