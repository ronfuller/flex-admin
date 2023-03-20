<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Concerns;

use Illuminate\Support\Arr;

class IndexFields
{
    /**
     * Convert an array of elements to a flatten array where keys are format [elem]_[index] where index is 1..n
     */
    public static function flatten(array $data): array
    {
        return collect($data)->map(function ($elem, $index) {
            $keys = array_keys($elem);
            $rowIndex = $index + 1;
            foreach ($keys as $key => $value) {
                data_set($elem, $value."_{$rowIndex}", $elem[$value]);
                unset($elem[$value]);
            }

            return $elem;
        })->collapse()->all();
    }

    public static function hasIndexedFields(array $fields): bool
    {
        $keys = collect(array_keys($fields));

        return $keys->contains(function ($key) {
            return self::isIndexedField(key: $key);
        });
    }

    public static function indexedFields($fields): array
    {
        return collect(array_keys($fields))
            ->filter(fn ($key) => self::isIndexedField(key: $key))
            ->map(function ($key) use ($fields) {
                return  [
                    'index' => self::fieldIndex($key),
                    'field' => (string) str($key)->beforeLast('_'),
                    'value' => $fields[$key],
                ];
            })
            ->groupBy('index')
            ->map(fn ($item) => collect($item)->mapWithKeys(fn ($item) => [$item['field'] => $item['value']])->all())
            ->values()
            ->all();
    }

    public static function isIndexedArrayField(string $key, mixed $value): bool
    {
        return \is_array($value) ? (Arr::isAssoc($value) ? self::hasIndexedFields($value) : false) : false;
    }

    protected static function isIndexedField(string $key): bool
    {
        return is_numeric(self::fieldIndex($key));
    }

    protected static function fieldIndex($field): string
    {
        return (string) str($field)->afterLast('_');
    }
}
