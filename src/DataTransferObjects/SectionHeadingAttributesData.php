<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Psi\FlexAdmin\Enums\TextSize;
use Psi\FlexAdmin\Enums\TextWeight;
use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionHeadingAttributesData extends Data
{
    public function __construct(
        public readonly string $textColor = 'primary',
        public readonly string $backgroundColor = 'white',
        public readonly TextSize|string|null $textSize = TextSize::Body2,
        public readonly TextWeight|string|null $textWeight = TextWeight::Regular
    ) {
    }

    public static function empty(array $extra = []): array
    {
        $data = parent::empty($extra);

        return [
            ...$data,
            'textWeight' => data_get($data, 'textWeight')->value,
            'textSize' => data_get($data, 'textSize')->value,
        ];
    }
}
