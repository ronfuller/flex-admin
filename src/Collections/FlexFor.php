<?php

namespace Psi\FlexAdmin\Collections;

use Psi\FlexAdmin\Fields\Field;

trait FlexFor
{
    public static function forDetail(mixed $model, string $resourceClassName = null)
    {
        /**
         * @var Flex
         */
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_DETAIL,
            resourceClassName: $resourceClassName
        );

        return $flex->setResultModel($model);
    }

    public static function forIndex(mixed $model, string $resourceClassName = null): Flex
    {
        return new static(
            model: $model,
            context: Field::CONTEXT_INDEX,
            resourceClassName: $resourceClassName
        );
    }

    public static function forEdit(mixed $model, string $resourceClassName = null)
    {
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_EDIT,
            resourceClassName: $resourceClassName
        );

        return $flex->setResultModel($model);
    }

    public static function forCreate(mixed $model, string $resourceClassName = null)
    {
        $flex = new static(
            model: get_class($model),
            context: Field::CONTEXT_CREATE,
            resourceClassName: $resourceClassName
        );

        return $flex->setResultModel($model);
    }
}
