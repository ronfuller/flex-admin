<?php

namespace Psi\FlexAdmin\Tests\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psi\FlexAdmin\Tests\Models\Builders\CompanyBuilder;

class Company extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s A',
        'updated_at' => 'datetime:m/d/Y h:i:s A',
        'settings' => AsArrayObject::class,
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('name', 'like', "%{$term}");
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Psi\FlexAdmin\Tests\Factories\CompanyFactory::new();
    }

    public function newEloquentBuilder($query): CompanyBuilder
    {
        return new CompanyBuilder($query);
    }
}
