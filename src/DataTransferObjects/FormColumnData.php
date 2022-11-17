<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class FormColumnData extends Data
{
    public function __construct(
        public FormFieldData $field,
        public readonly string $class = 'col-6 col-xs-12',
        public ?FormColumnAttributesData $attr = null,
    ) {
        $this->attr = $this->attr ?? new FormColumnAttributesData(...FormColumnAttributesData::empty());
    }
}
