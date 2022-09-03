<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;

interface Flexible
{
    /**
     * Generates resource fields
     *
     * @return array
     */
    public function fields(array|null $keys = null): array;

    /**
     * Builds Resource Actions
     *
     * @return array
     */
    public function actions(): array;

    /**
     * Builds resource panels
     *
     * @return array
     */
    public function panels(): array;

    /**
     * Builds resource relations
     *
     * @return array
     */
    public function relations(Request $request): array;

    /**
     * Builds resource filters
     *
     * @return array
     */
    // public function filters(): array;
}
