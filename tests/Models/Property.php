<?php

namespace Psi\FlexAdmin\Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Tests\Models\Builders\PropertyBuilder;
use Psi\FlexAdmin\Tests\Models\Traits\HasDateRange;

class Property extends Model
{
    use HasFactory;
    use HasDateRange;

    public const PROPERTY_TYPES = ['managed', 'private', 'portland', 'local', 'environmental', 'public', 'large', 'small', 'medium'];

    public const PROPERTY_COLORS = ['blue', 'green', 'yellow', 'orange', 'purple', 'red'];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s A',
        'updated_at' => 'datetime:m/d/Y h:i:s A',
        'contact' => AsArrayObject::class,
        'address' => AsArrayObject::class,
        'options' => AsArrayObject::class,
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function getFilterTypeAttribute()
    {
        return collect(self::PROPERTY_TYPES)->sort()->map(fn ($type) => ['label' => (string) Str::of($type)->title(), 'value' => $type])->all();
    }

    public function getFilterColorAttribute()
    {
        return collect(self::PROPERTY_COLORS)->sort()->map(fn ($color) => ['label' => (string) Str::of($color)->title(), 'value' => $color])->all();
    }

    public function getFilterCreatedAtAttribute()
    {
        return $this->getDateRanges();
    }

    public function canAct(string $slug)
    {
        return match ($slug) {
            'view-website' => false,
            default => true
        };
    }

    public function byCompany($query, mixed $value): Builder
    {
        return $query->where('companies.id', $value);
    }

    public function filterCompany($query)
    {
        $companyIds = $query->select('company_id')->orderBy('company_id')->distinct()->toBase()->get()->pluck('company_id')->all();

        return Company::select('id', 'name')->whereIn('id', $companyIds)->orderBy('name')->toBase()->get()->map(fn ($item) => (array) $item)->all();
    }

    public function scopeWithUnit($query, array $attributes)
    {
        return $query->with(['unit' => function ($query) {
            $query->select('id', 'property_id', 'title');
        }]);
    }

    public function scopeAuthorize($query, array $attributes)
    {
        return $query->where('properties.name', 'like', "{$attributes['name']}%");
    }

    public function scopeOrder($query, array $attributes)
    {
        return $query;
    }

    public function scopeFilter($query, array $attributes)
    {
        return $query;
    }

    public function scopeSearch($query, string $term)
    {
        return $query->whereIn('companies.id', Company::search($term)->toBase()->get()->pluck('id')->all());
    }

    public function scopeIndex($query, array $attributes)
    {
        return $query->where('properties.name', 'Test 1');
    }

    public function scopeDetail($query, array $attributes)
    {
        return $query;
    }

    public function scopeEdit($query, array $attributes)
    {
        return $query;
    }

    public function scopeCreate($query, array $attributes)
    {
        return $query;
    }

    public function scopeOther($query, array $attributes)
    {
        return $query->where('properties.name', 'like', "{$attributes['name']}%");
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Psi\FlexAdmin\Tests\Factories\PropertyFactory::new();
    }

    public function newEloquentBuilder($query): PropertyBuilder
    {
        return new PropertyBuilder($query);
    }

    protected static function booted()
    {
        /* === MODEL EVENTS ==== */
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
