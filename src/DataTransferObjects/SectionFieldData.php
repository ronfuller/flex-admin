<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionFieldData extends Data
{
    public function __construct(
        #[DataCollectionOf(SectionFieldRowData::class)]
        public DataCollection $rows,
        public ?FormFieldGroupData $group = null,
        public readonly string $component = 'fields',
    ) {
        $this->group = $this->group ?? FormFieldGroupData::from(FormFieldGroupData::empty());
    }
}
