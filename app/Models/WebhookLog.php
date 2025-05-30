<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class WebhookLog extends Model
{
    use SoftDeletes, Searchable, HasFactory;

    protected $fillable = [
        'webhook_id',
        'payload',
        'response_code',
        'success',
        'sent_at',
        'last_modified_log_id'
    ];

    protected function casts(): array
    {
        return [
            'datetime' => 'sent_at'
        ];
    }

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    public function toSearchableArray(): array
    {
        return [
            'payload' => $this->payload,
            'response_code ' => $this->response_code
        ];
    }
}
