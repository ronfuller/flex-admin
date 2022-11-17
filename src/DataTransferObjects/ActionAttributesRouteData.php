<?php
namespace Psi\FlexAdmin\DataTransferObjects;

// use Spatie\LaravelData\DataCollection;
// use Spatie\LaravelData\Attributes\DataCollectionOf;
// use Spatie\LaravelData\Optional;
// use Spatie\LaravelData\Attributes\WithTransformer;
// use Spatie\LaravelData\Attributes\WithCast;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class ActionAttributesRouteData extends Data
{
    public function __construct(
        public readonly string $name = '',
        public readonly array $params = []
    ) {
    }
}
