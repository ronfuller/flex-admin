<?php
namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum TextWeight: string
{
    case Bolder = 'text-weight-bolder';
    case Bold = 'text-weight-bold';
    case Medium = 'text-weight-medium';
    case Regular = 'text-weight-regular';
    case Light = 'text-weight-light';
    case Thin = 'text-weight-thin';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
