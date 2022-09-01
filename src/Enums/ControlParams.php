<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Enums;

enum ControlParams: string
{
    case WithActions = 'withActions';
    case WithRelations = 'withRelations';
    case FilterRelations = 'filterRelations';
    case DefaultActions = 'defaultActions';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
