<?php

namespace Psi\FlexAdmin\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationGroup extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s A',
        'updated_at' => 'datetime:m/d/Y h:i:s A',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Psi\FlexAdmin\Tests\Factories\ApplicationGroupFactory::new();
    }
}
