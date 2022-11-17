<?php
namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum FieldLabelPosition: string
{
    case Top = 'top';
    case Inline = 'inline';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
