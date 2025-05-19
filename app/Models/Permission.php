<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Permission extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function roles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Role::class,
            PermissionRole::class,
            'permission_id',
            'id',
            'id',
            'role_id'
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
