<?php

// use Illuminate\Support\Str;
// use Illuminate\Support\Arr;

/**
 * Pest Expectations: https://pestphp.com/docs/expectations#available-expectations
 *
 * Mail Intercept: https://github.com/kirschbaum-development/mail-intercept
 */

use Psi\FlexAdmin\Builders\FormBuilder;
use Psi\FlexAdmin\Tests\Feature\Forms\Mock\Services\FormSectionService;

beforeEach(function () {
});

it('should render a form builder instance', function () {
    expect(FormBuilder::make(new FormSectionService()))->toBeInstanceOf(FormBuilder::class);
})->group('forms');

it('should build a form from sections', function () {
    $sections = [
        [
            'slug' => 'Contact',
            'data' => [
                'contact' => true,
            ],
        ],
        [
            'slug' => 'Employment',
            'data' => [
                'employment' => true,
            ],
        ],
    ];
    expect(FormBuilder::make(new FormSectionService())->build(
        sections: $sections
    ))
        ->toMatchArray([
            [
                'contact' => true,
            ],
            [
                'employment' => true,
            ],
        ]);
})->group('forms');
