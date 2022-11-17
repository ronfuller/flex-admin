<?php
namespace Psi\FlexAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psi\FlexAdmin\Concerns\IndexFields;

class GetIndexedFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $fields = $request->all();

        if (IndexFields::hasIndexedFields($fields)) {
            $indexedFields = IndexFields::indexedFields($fields);
            $request->merge(['indexedFields' => $indexedFields]);
        }
        foreach (array_keys($fields) as $key => $value) {
            if (IndexFields::isIndexedArrayField(key: $value, value: $fields[$value])) {
                $request->merge([$value => IndexFields::indexedFields($fields[$value])]);
            }
        }

        return $next($request);
    }
}
