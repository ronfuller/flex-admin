<?php

namespace Psi\FlexAdmin\Fields;

trait Makeable
{
    public static function make(array|null $cols, ...$arguments)
    {
        $cols = $cols ?? [$arguments[0]];

        return in_array($arguments[0], $cols) ? new static(...$arguments) : null;
    }
}
