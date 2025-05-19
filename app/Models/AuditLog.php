<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class AuditLog extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'action' => $this->action,
            'description' => $this->description,
            'ip_address' => $this->ip_address
        ];
    }
}
