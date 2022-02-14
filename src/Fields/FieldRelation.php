<?php

namespace Psi\FlexAdmin\Fields;

trait FieldRelation
{

    /**
     * Related model that the field is on
     *
     * @var string|null
     */
    protected string|null $onModel = null;

    /**
     * Meta information on the related model
     *
     * @var array|null
     */
    protected array|null $onModelMeta = null;


    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function on(string $model): self
    {
        $this->onModel = $model;

        return $this;
    }

    protected function join(): array
    {
        $meta = $this->onModelMeta ?? [];

        return $this->onModel ? [$meta['table'], $meta['primaryKeyColumn'], '=', "{$this->modelMeta['table']}.{$meta['foreignKey']}"] : [];
    }

    protected function qualifyOnModelColumn(string $select): string
    {
        return "{$this->onModelMeta['table']}.{$select}";
    }
}
