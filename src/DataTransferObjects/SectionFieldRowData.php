<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionFieldRowData extends Data
{
    public function __construct(
        #[DataCollectionOf(FormColumnData::class)]
        public DataCollection $columns,
        public ?string $id = null,
        public ?int $index = null,
        public ?SectionFieldRowAttributesData $attr = null,
        public bool $visible = true
    ) {
        $this->id = $this->id ?? (string) Str::uuid();
        $this->attr = $this->attr ?? new SectionFieldRowAttributesData(...SectionFieldRowAttributesData::empty());
    }
}
