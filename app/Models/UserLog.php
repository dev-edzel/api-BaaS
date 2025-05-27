<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class UserLog extends Model
{
    use SoftDeletes, Searchable, HasFactory;

    protected $fillable = [
        'initiator_id',
        'initiator_username',
        'initiator_role',
        'action',
        'details',
        'ip_address',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'initiator_username' => $this->initiator_username,
            'initiator_role' => $this->initiator_role,
            'action' => $this->action,
            'description' => $this->description,
            'ip_address' => $this->ip_address
        ];
    }
}
