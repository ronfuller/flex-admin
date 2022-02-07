<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Database\Eloquent\Model;
use Psi\FlexAdmin\Lib\FlexInspect;

trait FieldModel
{
    public function modelMeta(Model $model): array
    {
        return (new FlexInspect($model))->meta;
    }
}
