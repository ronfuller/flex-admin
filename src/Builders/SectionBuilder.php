<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Builders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psi\FlexAdmin\Concerns\IndexFields;
use Psi\FlexAdmin\Concerns\Makeable;
use Psi\FlexAdmin\DataTransferObjects\FormColumnData;
use Psi\FlexAdmin\DataTransferObjects\FormFieldGroupData;
use Psi\FlexAdmin\DataTransferObjects\FormSectionData;
use Psi\FlexAdmin\DataTransferObjects\SectionActionData;
use Psi\FlexAdmin\DataTransferObjects\SectionAttributesData;
use Psi\FlexAdmin\DataTransferObjects\SectionContentData;
use Psi\FlexAdmin\DataTransferObjects\SectionFieldData;
use Psi\FlexAdmin\DataTransferObjects\SectionFieldRowData;
use Psi\FlexAdmin\DataTransferObjects\SectionHeadingData;
use Spatie\LaravelData\Optional;

class SectionBuilder
{
    use Makeable;

    public SectionHeadingData $heading;

    public SectionActionData $actions;

    public SectionContentData $content;

    public FormFieldGroupData $group;

    public SectionAttributesData $attributes;

    public Collection $rows;

    public string $component = 'form-section';

    public string $title = '';

    public string $name = '';

    public string $slug = '';

    public array $fields = [
        'component' => 'fields',
    ];

    final public function __construct()
    {
        $this->rows = collect([]);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function component(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function heading(array $heading): self
    {
        $this->heading = SectionHeadingData::from($heading);

        return $this;
    }

    public function actions(array $actions = []): self
    {
        $this->actions = SectionActionData::from($actions);

        return $this;
    }

    public function content(array $content): self
    {
        $this->content = SectionContentData::from($content);

        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = SectionAttributesData::from($attributes);

        return $this;
    }

    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function row(array $cols): self
    {
        $hidden = collect($cols)->contains(fn ($col) => data_get($col, 'class', '') === 'hidden');
        $fieldName = $hidden ? data_get($cols[0], 'field.attr.name', '') : '';

        $row = SectionFieldRowData::from([
            'attr' => [
                'hidden' => collect($cols)->contains(fn ($col) => data_get($col, 'class', '') === 'hidden'),
                'fieldName' => $fieldName,
            ],
            'columns' => $cols,
        ]);
        $this->rows->push($row);

        return $this;
    }

    public function group(array $group): self
    {
        $this->group = FormFieldGroupData::from($group);

        return $this;
    }

    public function slug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function transform(SectionFieldData $fieldData, array $data): SectionFieldData
    {
        $data = Arr::isAssoc($data) ? $data : IndexFields::flatten($data);
        $fieldData->rows->each(function (SectionFieldRowData $row) use ($data) {
            $row->columns->each(function (FormColumnData $col) use ($data, $row) {
                $key = $col->field->attr->name;
                if (Arr::has($data, $key)) {
                    $col->field->value = data_get($data, $key);
                }
                // Special Case Age based conditional fields

                if (
                    in_array((string) str($col->field->attr->name)->beforeLast('_'), ['ssn', 'drivers_license', 'student'])
                    && $col->field->attr->conditional
                    && ! ($col->field->attr->conditional instanceof Optional)

                ) {
                    // set the hidden property based on values
                    $dateCol = $row->columns->first(fn (FormColumnData $col) => (string) str($col->field->attr->name)->beforeLast('_') === 'birthdate');
                    if (! empty($dateCol?->field?->value)) {
                        $col->field->attr->hidden = Carbon::parse($dateCol->field->value)->diffInYears(now()) > 18;
                    }
                }
            });
        });

        return $fieldData;
    }

    public function toArray(array $data = []): array
    {
        /**
         * @var SectionFieldData
         */
        $fieldData = $this->group ?? null ? $this->toGroupedFields() : $this->toFields();

        $fields = empty($data) ? $fieldData : $this->transform($fieldData, $data);

        $responseData =
            [
                'slug' => $this->slug ?? '',
                ...FormSectionData::from([
                    'heading' => $this->heading ?? [],
                    'actions' => $this->actions ?? SectionActionData::from([]),
                    'content' => $this->content ?? null,
                    'attributes' => $this->attributes ?? null,
                    'component' => $this->component,
                    'title' => $this->title,
                    'name' => $this->name,
                    'fields' => $fields,
                    'values' => $this->values($fields),
                ])->toArray(),
            ];

        return $responseData;
    }

    protected function values(SectionFieldData $fields): array
    {
        $values = [];
        $fields->rows->each(function (SectionFieldRowData $row) use (&$values) {
            $row->columns->each(function (FormColumnData $col) use (&$values) {
                $values[$col->field->attr->name] = $col->field->value;
            });
        });

        return $values;
    }

    protected function toFields(): SectionFieldData
    {
        return SectionFieldData::from([
            ...$this->fields,
            'rows' => SectionFieldRowData::collection($this->rows->all()),
        ]);
    }

    protected function toGroupedFields(): SectionFieldData
    {
        $rows = collect([]);
        $groupedRows = collect([]);
        for ($index = 1; $index <= $this->group->max; $index++) {
            $groupedRows->push(collect([]));

            $this->rows->each(function (SectionFieldRowData $row, $key) use ($index, &$rows, &$groupedRows) {
                $clone = SectionFieldRowData::from($row->toArray());
                $clone->columns->each(function (FormColumnData $col) use ($index) {
                    if (Arr::has($col->field->attr->toArray(), 'conditional')) {
                        if (! Arr::has($col->field->attr->toArray(), 'conditionField')) {
                            throw new \Exception("Conditional field not specified for field {$col->field->attr->name}", 1);
                        }
                        // Special Case Occupant Fields
                        if (in_array($col->field->attr->name, ['ssn', 'drivers_license', 'student'])) {
                            // The Primary Occupant will be the first index, can't make these fields conditional
                            if ($index === 1) {
                                $col->field->attr->conditional = false;
                                $col->field->attr->hidden = false;
                            }
                        }
                        $col->field->attr->conditionField .= "_{$index}";
                    }
                    $col->field->attr->name .= "_{$index}";
                });
                $clone->index = $index;
                $rows->push($clone);
                $groupedRows[$index - 1]->push($key + (($index - 1) * $this->rows->count()));
            });
        }
        $this->group->rows = $groupedRows->map(fn ($item) => $item->all())->all();

        return SectionFieldData::from([
            ...$this->fields,
            'group' => $this->group,
            'rows' => SectionFieldRowData::collection($rows),
        ]);
    }
}
