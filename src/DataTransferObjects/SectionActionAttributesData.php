<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionActionAttributesData extends Data
{
    public function __construct(
        public readonly string $label,
        public readonly string $icon = 'mdi-check-circle',
        public readonly string $color = 'secondary',
        public ?ActionAttributesRouteData $route = null
    ) {
        $this->route = $this->route ?? ActionAttributesRouteData::from(ActionAttributesRouteData::empty());
    }
}
