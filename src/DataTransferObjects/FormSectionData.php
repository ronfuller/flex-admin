<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class FormSectionData extends Data
{
    public function __construct(
        public readonly SectionHeadingData $heading,
        public readonly SectionActionData $actions,
        public readonly SectionFieldData $fields,
        public ?SectionAttributesData $attributes = null,
        public ?SectionContentData $content = null,
        public string $component = 'form-section',
        public string $name = '',
        public string $title = '',
    ) {
        $this->content = $this->content ?? new SectionContentData(...SectionContentData::empty());
        $this->attributes = $this->attributes ?? new SectionAttributesData(...SectionAttributesData::empty());
    }

    public function toArray(): array
    {
        $formSection = parent::toArray();

        return $formSection;
    }
}
