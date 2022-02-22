<?php

namespace Psi\FlexAdmin\Fields\Enums;

enum DisplayContext: string
{
    case INDEX = 'index';
    case DETAIL = 'detail';
    case EDIT = 'edit';
    case CREATE = 'create';

    public function permission()
    {
        return match ($this) {
            self::INDEX => '{entity}.view-any',
            self::DETAIL => '{entity}.view',
            self::EDIT => '{entity}.edit',
            self::CREATE => '{entity}.create',
        };
    }
    static public function values(): array
    {
        return collect(self::cases())->map(fn ($enum) => $enum->value)->all();
    }
}
