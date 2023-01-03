<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class FormFieldGroupData extends Data
{
    public function __construct(
        public readonly ?bool $grouped = false,
        public readonly ?int $min = 1,
        public readonly ?int $max = 3,
        public readonly ?int $fixed = 0,
        public ?string $actionLabel = '',
        public ?array $rows = []
    ) {
    }
}
