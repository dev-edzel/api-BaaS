<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class UserInfo extends Model
{
    use SoftDeletes, Searchable;

    protected $table = 'user_info';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'avatar',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
