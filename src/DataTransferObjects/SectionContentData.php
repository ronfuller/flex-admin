<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionContentData extends Data
{
    public function __construct(
        public readonly string $message = '',
        public readonly string $component = 'form-section-content',
        public ?ContentAttributesData $attr = null
    ) {
        $this->attr = $this->attr ?? new ContentAttributesData(...ContentAttributesData::empty());
    }
}
