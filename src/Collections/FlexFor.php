<?php
namespace Psi\FlexAdmin\Collections;

use Psi\FlexAdmin\Fields\Field;

trait FlexFor
{
    public static function forDetail(mixed $model)
    {
        /**
         * @var Flex
         */
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_DETAIL
        );
        return $flex->setResultModel($model);
    }

    public static function forIndex(mixed $model)
    {
        return new static(
            model: $model,
            context: Field::CONTEXT_INDEX
        );
    }

    public static function forEdit(mixed $model)
    {
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_EDIT
        );
        return $flex->setResultModel($model);
    }

    public static function forCreate(mixed $model)
    {
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_CREATE
        );
        return $flex->setResultModel($model);
    }
}
