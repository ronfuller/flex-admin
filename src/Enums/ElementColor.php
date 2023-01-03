<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum ElementColor: string
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Accent = 'accent';
    case Alternate = 'alternate';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
