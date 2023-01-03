<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionFieldRowAttributesData extends Data
{
    public function __construct(
        public readonly bool $hidden = false,
        public readonly string $fieldName = ''          // we store hidden field name here to easily handle faked row data and not replace hidden
    ) {
    }
}
