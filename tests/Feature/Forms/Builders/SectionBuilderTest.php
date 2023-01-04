<?php

use Psi\FlexAdmin\Builders\SectionBuilder;
use Psi\FlexAdmin\DataTransferObjects\SectionActionData;
use Psi\FlexAdmin\DataTransferObjects\SectionContentData;
use Psi\FlexAdmin\DataTransferObjects\SectionFieldRowData;
use Psi\FlexAdmin\DataTransferObjects\SectionHeadingData;

// use Illuminate\Support\Str;
// use Illuminate\Support\Arr;

/**
 * Pest Expectations: https://pestphp.com/docs/expectations#available-expectations
 *
 * Mail Intercept: https://github.com/kirschbaum-development/mail-intercept
 */
beforeEach(function () {
});

it('should create a builder', function () {
    expect(SectionBuilder::make())->toBeInstanceOf(SectionBuilder::class);
})->group('forms');

it('should create a heading', function () {
    expect(SectionBuilder::make()->heading(['title' => 'Heading'])->heading)->toBeInstanceOf(SectionHeadingData::class);
})->group('forms');

it('should create actions', function () {
    expect(SectionBuilder::make()->actions(['label' => 'Submit'])->actions)->toBeInstanceOf(SectionActionData::class);
})->group('forms');

it('should create content', function () {
    expect(SectionBuilder::make()->content(['message' => fake()->sentence()])->content)->toBeInstanceOf(SectionContentData::class);
})->group('forms');

it('should create rows', function () {
    expect(
        SectionBuilder::make()->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
            ->rows
            ->first()
    )
        ->toBeInstanceOf(SectionFieldRowData::class);
})->group('forms');

it('should build a form section', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->toArray();
    expect($section)->toBeArray()->toHaveKeys(['heading', 'actions', 'fields', 'content']);
})->group('forms');

it('should build a form section with values', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                    'value' => 'John Smith',
                ],
            ],
        ])
        ->toArray();
    expect($section)->toBeArray()
        ->toHaveKeys(['heading', 'actions', 'fields', 'content', 'values'])
        ->values->toBe(['first_name' => 'John Smith']);
})->group('forms');

it('should build a form section with a hidden field', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->row([
            [
                'class' => 'hidden',
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->toArray();
    expect(data_get($section, 'fields.rows.0.attr.hidden'))->toBeTrue();
})->group('forms');

it('should build a form section without a hidden field', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->row([
            [
                'class' => 'col col-xs-12',
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->toArray();
    expect(data_get($section, 'fields.rows.0.attr.hidden'))->toBeFalse();
})->group('forms');

it('should build a form section with grouped fields', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->group([
            'grouped' => true,
            'min' => 1,
            'max' => 2,
            'fixed' => 1,
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.last-name'),
                        'name' => 'last_name',
                    ],
                ],
            ],
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.phone'),
                        'name' => 'phone',
                    ],
                ],
            ],
        ])
        ->toArray();
    expect(data_get($section, 'fields.rows.4.columns.0.field.attr.name'))->toBe('last_name_2');
    expect(data_get($section, 'fields.rows.0.columns.0.field.attr.name'))->toBe('first_name_1');
})->group('forms');

it('should transform field values from input data', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.last-name'),
                        'name' => 'last_name',
                    ],
                ],
            ],
        ])
        ->toArray([
            'first_name' => 'Ron',
            'last_name' => 'Fuller',
        ]);
    expect(data_get($section, 'fields.rows.0.columns.0.field.value'))->toBe('Ron');
    expect(data_get($section, 'fields.rows.1.columns.0.field.value'))->toBe('Fuller');
})->group('forms');

it('should transform field values from input data for grouped rows', function () {
    $section = SectionBuilder::make()
        ->heading(['title' => __('labels.contact-information')])
        ->actions([])
        ->group([
            'grouped' => true,
            'min' => 1,
            'max' => 2,
            'fixed' => 1,
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.first-name'),
                        'name' => 'first_name',
                    ],
                ],
            ],
        ])
        ->row([
            [
                'field' => [
                    'attr' => [
                        'label' => __('labels.last-name'),
                        'name' => 'last_name',
                    ],
                ],
            ],
        ])
        ->toArray(
            [
                [
                    'first_name' => 'Ron',
                    'last_name' => 'Fuller',
                ],
                [
                    'first_name' => 'John',
                    'last_name' => 'Smith',
                ],
            ]
        );
    expect(data_get($section, 'fields.rows.0.columns.0.field.value'))->toBe('Ron');
    expect(data_get($section, 'fields.rows.1.columns.0.field.value'))->toBe('Fuller');
    expect(data_get($section, 'fields.rows.2.columns.0.field.value'))->toBe('John');
    expect(data_get($section, 'fields.rows.3.columns.0.field.value'))->toBe('Smith');
})->group('forms');
