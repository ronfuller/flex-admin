<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum ElementDesign: string
{
    case Standard = '';
    case Filled = 'filled';
    case Outlined = 'outlined';
    case Borderless = 'borderless';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
