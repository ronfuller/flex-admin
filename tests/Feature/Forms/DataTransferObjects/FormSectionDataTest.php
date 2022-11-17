<?php

use Psi\FlexAdmin\DataTransferObjects\FormColumnData;
use Psi\FlexAdmin\DataTransferObjects\FormFieldData;
use Psi\FlexAdmin\DataTransferObjects\FormSectionData;
use Psi\FlexAdmin\DataTransferObjects\SectionActionData;
use Psi\FlexAdmin\DataTransferObjects\SectionFieldData;
use Psi\FlexAdmin\DataTransferObjects\SectionFieldRowData;
use Psi\FlexAdmin\DataTransferObjects\SectionHeadingAttributesData;
use Psi\FlexAdmin\DataTransferObjects\SectionHeadingData;
use Psi\FlexAdmin\Enums\FieldComponent;
use Psi\FlexAdmin\Enums\TextWeight;

// use Illuminate\Support\Str;
// use Illuminate\Support\Arr;

/**
 * Pest Expectations: https://pestphp.com/docs/expectations#available-expectations
 *
 * Mail Intercept: https://github.com/kirschbaum-development/mail-intercept
 */
beforeEach(function () {
});

it('should create an empty Section Heading Attributes Transfer Object', function () {
    expect(SectionHeadingAttributesData::empty())->toBeArray()->toHaveKeys(['textColor', 'backgroundColor', 'textSize', 'textWeight']);
})->group('forms');

it('should create a Section Heading Attributes Transfer Object', function () {
    expect((new SectionHeadingAttributesData(
        backgroundColor: 'primary',
        textSize: 'text-h6'
    ))->toArray())->toBeArray()->toHaveKey('textWeight', TextWeight::Regular->value);
})->group('forms');

it('should create a section heading data object')
    ->expect(fn () => (new SectionHeadingData(title: fake()->sentence()))->toArray())
    ->toBeArray()
    ->group('forms');

it('should create a section heading data object from an array')
    ->expect(fn () => SectionHeadingData::from(['title' => fake()->sentence()])->toArray())
    ->toBeArray()
    ->group('forms');

it('should create a section heading data object from attributes')
    ->expect(fn () => SectionHeadingData::from(['title' => fake()->sentence(), 'attr' => ['textColor' => 'secondary']])->toArray())
    ->toBeArray()
    ->group('forms');

it('should create a section action data object')
    ->expect(fn () => SectionActionData::from()->toArray())
    ->toBeArray()
    ->toHaveKeys(['component', 'controls', 'hide', 'defaults'])
    ->component->toBe('actions')
    ->group('forms');

it('should create a form field data object')
    ->expect(fn () => FormFieldData::from(
        [
            'component' => FieldComponent::Text,
            'attr' => [
                'label' => 'First Name',
                'name' => 'first_name',
                'format' => 'text',
            ],
        ]
    )->toArray())
    ->toBeArray()
    ->component->toBe('text-field')
    ->attr->format->toBe('text')
    ->attr->name->toBe('first_name')
    ->group('forms');

it('should create a form column data object')
    ->expect(fn () => FormColumnData::from([
        'class' => 'col-6 col-xs-12',
        'field' => [
            'attr' => [
                'label' => 'First Name',
                'name' => 'first_name',
            ],
        ],
    ])->toArray())
    ->toBeArray()
    ->field->component->toBe('text-field')
    ->field->attr->name->toBe('first_name')
    ->class->toBe('col-6 col-xs-12')
    ->group('forms');

it('should create a field row data object')
    ->expect(fn () => SectionFieldRowData::from(
        [
            'columns' => [
                [
                    'field' => [
                        'attr' => [
                            'label' => 'First Name',
                            'name' => 'first_name',
                        ],
                    ],
                ],
            ],
        ]
    )->toArray())
    ->toBeArray()
    ->group('forms');

it('should create a field row data object with a columns collection')
    ->expect(fn () => SectionFieldRowData::from(
        [
            'columns' => FormColumnData::collection([
                FormColumnData::from([
                    'field' => [
                        'attr' => [
                            'label' => 'First Name',
                            'name' => 'first_name',
                        ],
                    ],
                ]),
            ]),
        ]
    )->toArray())
    ->toBeArray()
    ->group('forms');

it('should create a form section data object')
    ->expect(fn () => FormSectionData::from(
        [
            'heading' => SectionHeadingData::from([
                'title' => 'Heading',
            ]),
            'actions' => SectionActionData::from([
                'label' => 'Submit',
            ]),
            'fields' => SectionFieldData::from([
                'rows' => SectionFieldRowData::collection(
                    [
                        [
                            'columns' => FormColumnData::collection([
                                FormColumnData::from([
                                    'field' => [
                                        'attr' => [
                                            'label' => 'First Name',
                                            'name' => 'firstname',
                                        ],
                                    ],
                                ]),
                                FormColumnData::from([
                                    'field' => [
                                        'attr' => [
                                            'label' => 'Last Name',
                                            'name' => 'lastname',
                                        ],
                                    ],
                                ]),
                            ]),
                        ],
                    ]
                ),
            ]),
        ]
    )->toArray())
    ->toBeArray()
    ->group('forms');
