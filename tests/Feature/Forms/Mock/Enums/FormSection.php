<?php

namespace Psi\FlexAdmin\Tests\Feature\Forms\Mock\Enums;

use Psi\FlexAdmin\Resources\FormSections\ResourceFormSection;
use Psi\FlexAdmin\Tests\Feature\Forms\Mock\Resources\FormSections\ContactSection;
use Psi\FlexAdmin\Tests\Feature\Forms\Mock\Resources\FormSections\EmploymentSection;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum FormSection: string
{
    case Contact = 'Contact';
    case Employment = 'Employment';

    public function section(...$args): ResourceFormSection
    {
        return match ($this) {
            self::Contact => ContactSection::make(...$args),
            self::Employment => EmploymentSection::make(...$args)
        };
    }

    public function fake(int $count): mixed
    {
        return match ($this) {
            self::ContactInformation => []
        };
    }

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
