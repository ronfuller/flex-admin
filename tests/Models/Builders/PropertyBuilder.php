<?php
namespace Psi\FlexAdmin\Tests\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Psi\FlexAdmin\Builders\FlexQueryBuilder;
use Psi\FlexAdmin\Concerns\HasDateRange;

class PropertyBuilder extends Builder implements FlexQueryBuilder
{
    use HasDateRange;

    public function index(array $attributes): FlexQueryBuilder
    {
        return $this->whereNotNull('id');
    }

    public function search(string $term): FlexQueryBuilder
    {
        return $this->where('name', 'like', "%{$term}%")
            ->orWhereHas('company', function (Builder $query) use ($term) {
                return $query->where('name', 'like', "%{$term}%");
            })
            ->orWhereRaw('LOWER(options->"$.color") like ?', "%{$term}%")
            ->orWhere('status', '=', $term)
            ->orWhere('type', 'like', "%{$term}%");
    }

    public function filter(array $filter): FlexQueryBuilder
    {
        return $this->when($filter['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        })
            ->when($filter['color'] ?? null, function ($query, $color) {
                $query->where('options->color', $color);
            })
            ->when($filter['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filter['created_at'] ?? null, function ($query, $dateRange) {
                return $query->where('created_at', '>=', $this->getStartDateTime($dateRange))->where('created_at', '<=', $this->getEndDateTime($dateRange));
            });
    }

    public function sortBy(string $sort, string $sortDir): FlexQueryBuilder
    {
        return $this->orderBy($sort, $sortDir);
    }
}
