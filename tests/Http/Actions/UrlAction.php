<?php

namespace Psi\FlexAdmin\Tests\Http\Actions;

use Psi\FlexAdmin\Actions\Action;

class UrlAction extends Action
{
    protected string $type = 'grouped';

    /**
     * Setting enabled will always enable the action
     */
    protected bool $enabled = true;
}
