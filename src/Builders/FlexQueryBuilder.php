<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Builders;

interface FlexQueryBuilder
{
    public function index(array $attributes): self;

    public function sortBy(string $sort, string $sortDir): self;

    public function search(string $term, array $attributes = []): self;

    public function filter(array $filter, array $attributes = []): self;
}
