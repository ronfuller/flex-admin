<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;

interface Flexible
{
    /**
     * Generates resource fields
     */
    public function fields(array|null $keys = null): array;

    /**
     * Builds Resource Actions
     */
    public function actions(): array;

    /**
     * Builds resource panels
     */
    public function panels(): array;

    /**
     * Builds resource relations
     */
    public function relations(Request $request): array;

    /**
     * Builds resource filters
     *
     * @return array
     */
    // public function filters(): array;
}
