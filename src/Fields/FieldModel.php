<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Database\Eloquent\Model;
use Psi\FlexAdmin\Lib\FlexInspect;

trait FieldModel
{
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected Model $model;

    /**
     * Meta information for the model
     *
     * @var array
     */
    protected array $modelMeta;

    public function modelMeta(Model $model): array
    {
        return (new FlexInspect($model))->meta;
    }
}
