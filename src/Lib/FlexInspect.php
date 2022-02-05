<?php

namespace Psi\FlexAdmin\Lib;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FlexInspect
{
    public array|null $meta = null;

    public function __construct(Model $model)
    {
        $this->meta = $this->getMeta($model);
    }

    protected function getMeta(Model $model): array
    {

        return [
            'class' => \get_class($model),
            'name' =>  $model->joiningTableSegment(),
            'pluralName' => $this->pluralName($model),
            'filterFunctions' => $this->filterFunctions($model),
            'filterAttributes' => $this->filterAttributes($model),
            'table' => $model->getTable(),
            'created_at' => $model->getQualifiedCreatedAtColumn(),
            'updated_at' => $model->getQualifiedUpdatedAtColumn(),
            'tableSegment' => $model->joiningTableSegment(),
            'columns' => Schema::getColumnListing($model->getTable()),
            'casts' => $model->getCasts(),
            'relations' => $model->relationsToArray(),
            'globalScopes' => $model->getGlobalScopes(),
            'primaryKey' => $model->getKeyName(),
            'primaryKeyColumn' => $model->getQualifiedKeyName(),
            'routeKey' => $model->getRouteKeyName(),
            'foreignKey' => $model->getForeignKey(),
            'perPage' => $model->getPerPage()
        ];
    }

    protected function filterFunctions($model): array
    {
        $methods = \get_class_methods($model);

        return collect($methods)
            ->filter(fn ($method) => Str::of($method)->startsWith("filter"))
            ->values()->map(fn ($function) => (string) Str::of($function)->replace('filter', '')->lower())
            ->all();
    }
    protected function filterAttributes($model): array
    {
        $methods = \get_class_methods($model);

        return collect($methods)
            ->filter(fn ($method) => Str::of($method)->startsWith("getFilter") && Str::of($method)->endsWith("Attribute"))
            ->values()->map(fn ($function) => (string) Str::of($function)->replace('getFilter', '')->replace('Attribute', '')->lower())
            ->all();
    }

    protected function pluralName($model): string
    {
        return (string) Str::of($model->getQualifiedKeyName())->before('.')->replace("_", "-");
    }
}
