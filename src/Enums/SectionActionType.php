<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum SectionActionType: string
{
    case Submit = 'submit';
    case Cancel = 'cancel';

    public function icon(): string
    {
        return match ($this) {
            self::Submit => 'mdi-check-circle',
            self::Cancel => 'mdi-close-circle'
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Submit => __('labels.submit'),
            self::Cancel => __('labels.cancel')
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Submit => 'secondary',
            self::Cancel => 'grey-6'
        };
    }

    public function hideLabelOnMobile(): bool
    {
        return match ($this) {
            self::Submit => false,
            self::Cancel => true
        };
    }

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
