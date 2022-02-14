<?php

namespace Psi\FlexAdmin\Tests\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => AsCollection::class,
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function givePermissionTo(string $permission)
    {
        $this->permissions = $this->permissions ? $this->permissions->concat([$permission])->all() : [$permission];
        $this->save();
    }

    public function revokePermissionTo(string $permission)
    {
        $this->permissions = $this->permissions ? collect($this->permissions)->filter(function ($item) use ($permission) {
            return $item !== $permission;
        })->values()->all() : [];
        $this->save();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Psi\FlexAdmin\Tests\Factories\UserFactory::new();
    }
}
