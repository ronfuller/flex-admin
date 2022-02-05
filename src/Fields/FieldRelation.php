<?php

namespace Psi\FlexAdmin\Fields;

trait FieldRelation
{

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
        return $this->onModel ? array($meta['table'], $meta['primaryKeyColumn'], '=', "{$this->modelMeta['table']}.{$meta['foreignKey']}") : [];
    }

    protected function qualifyOnModelColumn(string $select): string
    {
        return "{$this->onModelMeta['table']}.{$select}";
    }
}
