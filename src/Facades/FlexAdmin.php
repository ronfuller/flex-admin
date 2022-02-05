<?php

namespace Psi\FlexAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Psi\FlexAdmin\FlexAdmin
 */
class FlexAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'flex-admin';
    }
}
