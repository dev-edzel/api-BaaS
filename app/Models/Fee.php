<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Fee extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'amount',
        'is_percentage',
        'applies_to',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function accountFees(): HasMany
    {
        return $this->hasMany(AccountFee::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
