<?php

namespace Psi\FlexAdmin\Lib;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FlexUtils
{
    protected array $meta;

    public function __construct(Model $model)
    {
        $this->meta = (new FlexInspect($model))->meta;
    }

    /**
     * Return the title for the given slug (i.e. Edit Property)
     */
    public function title(string $slug): string
    {
        $modelKey = $this->meta['name'];
        $modelTitle = (string) Str::of($modelKey)->replace('_', ' ')->title();

        return (string) Str::of($slug)->title()->append(" {$modelTitle}");
    }

    /**
     * Return the permissions for the given slug (i.e. properties.edit)
     */
    public function permission(string $slug): string
    {
        $pluralModelName = $this->meta['pluralName'];

        return "{$pluralModelName}.{$slug}";
    }

    /**
     * Build the route for the given slug (i.e. application-groups.show)
     */
    public function route(string $slug, mixed $model): array
    {
        $routeKeyName = $this->meta['routeKey'];
        $pluralModel = $this->meta['pluralName'];
        $modelKey = $this->meta['name'];

        $slugResourceRoutes = [
            'view' => 'show',
            'edit' => 'edit',
            'create' => 'create',
            'delete' => 'destroy',
        ];
        $slugRouteMethods = [
            'view' => 'get',
            'edit' => 'get',
            'create' => 'get',
            'delete' => 'delete',
        ];
        $routeMethod = $slugRouteMethods[$slug];
        $routeName = $pluralModel.'.'.$slugResourceRoutes[$slug];

        $routeParam = in_array($slug, ['view', 'edit', 'delete']) ? [$modelKey => $model->getAttribute($routeKeyName)] : [];

        return [$routeName, $routeMethod, $routeParam];
    }
}
