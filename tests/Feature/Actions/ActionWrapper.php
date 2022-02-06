<?php

namespace Psi\FlexAdmin\Tests\Feature\Actions;

use Psi\FlexAdmin\Actions\Action;

class ActionWrapper extends Action
{

    public function getWithDisabled()
    {
        return $this->withDisabled;
    }
    public function getWithPermissions()
    {
        return $this->withPermissions;
    }
    public function getContexts()
    {
        return $this->contexts;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
    public function getDividers()
    {
        return $this->dividers;
    }
}
