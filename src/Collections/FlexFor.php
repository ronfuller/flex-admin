<?php

namespace Psi\FlexAdmin\Collections;

use Psi\FlexAdmin\Fields\Field;

trait FlexFor
{
    public static function for(...$arguments)
    {
        return new static(...$arguments);
    }

    public static function forDetail(mixed $model)
    {
        return new static($model, Field::CONTEXT_DETAIL);
    }

    public static function forIndex(mixed $model)
    {
        return new static($model, Field::CONTEXT_INDEX);
    }

    public static function forEdit(mixed $model)
    {
        return new static($model, Field::CONTEXT_EDIT);
    }

    public static function forCreate(mixed $model)
    {
        return new static($model, Field::CONTEXT_CREATE);
    }
}
