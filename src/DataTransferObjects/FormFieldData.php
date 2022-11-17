<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Psi\FlexAdmin\Enums\FieldComponent;
use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class FormFieldData extends Data
{
    public function __construct(
        public FormFieldAttributesData $attr,
        public mixed $value = '',
        public readonly FieldComponent|string $component = 'text-field',
    ) {
    }
}
