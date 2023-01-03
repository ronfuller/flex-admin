<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionHeadingData extends Data
{
    public function __construct(
        public readonly ?string $title = '',
        public readonly ?string $icon = '',
        public ?SectionHeadingAttributesData $attr = null,
        public readonly ?string $component = 'sidebar',
    ) {
        if (is_null($this->attr)) {
            $this->attr = new SectionHeadingAttributesData(...SectionHeadingAttributesData::empty());
        }
    }
}
