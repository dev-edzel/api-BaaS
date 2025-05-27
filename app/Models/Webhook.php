<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Webhook extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'url',
        'event_type',
        'is_active',
        'last_modified_log_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): WebhookLog
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    public function toSearchableArray(): array
    {
        return [
            'url' => $this->url,
            'event_type' => $this->event_type
        ];
    }
}
