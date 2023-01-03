<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Psi\FlexAdmin\Enums\SectionActionType;
use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionActionControlData extends Data
{
    public function __construct(
        public SectionActionAttributesData $attr,
        public readonly bool $hideLabelOnMobile = false,
        public readonly SectionActionType|string $type = SectionActionType::Submit,
    ) {
    }

    public static function empty(array $extra = []): array
    {
        $data = parent::empty($extra);

        return [
            ...$data,
            'type' => data_get($data, 'type')->value,
        ];
    }
}
