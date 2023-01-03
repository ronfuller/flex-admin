<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class ContentAttributesData extends Data
{
    public function __construct(
        public readonly string $color = 'accent',
        public readonly string $icon = 'mdi-information',
        public readonly string $border = 'top',
        public readonly bool $outlined = true,
        public readonly bool $prominent = true,
        public readonly bool $dense = false,
        public readonly array $blocks = [],
        public readonly ?string $classInfo = ''
    ) {
    }
}
