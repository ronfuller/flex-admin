<?php

namespace Psi\FlexAdmin\Concerns;

trait Makeable
{
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }
}
