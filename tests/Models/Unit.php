<?php
namespace Psi\FlexAdmin\Tests\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];
    public $perPage = 5;

    protected $casts = [
        'available' => 'boolean',
        'available_at' => 'date:m/d/Y',
        'garage' => 'boolean'
    ];

    /* === ELOQUENT RELATIONSHIPS === */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // We need to assign company since unmapped units will have a company but no property
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /** === MODEL FUNCTIONS */

    /* === ACCESSORS, MUTATORS === */

    public function size(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? $value . ' sq ft' : null
        );
    }

    protected static function booted()
    {
        /* === MODEL EVENTS ==== */
        static::creating(function ($model) {
            $model->status = $model->status ?? 'Accepting';
        });

        static::saving(function ($model) {
        });

        static::created(function ($model) {
        });

        /* === GLOBAL SCOPES ==== */
    }
}
