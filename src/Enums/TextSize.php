<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum TextSize: string
{
    case Overline = 'text-overline';
    case Caption = 'text-caption';
    case Body2 = 'text-body2';
    case Body1 = 'text-body1';
    case Subtitle2 = 'text-subtitle2';
    case Subtitle1 = 'text-subtitle1';
    case H6 = 'text-h6';
    case H5 = 'text-h5';
    case H4 = 'text-h4';
    case H3 = 'text-h3';
    case H2 = 'text-h2';
    case H1 = 'text-h1';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
