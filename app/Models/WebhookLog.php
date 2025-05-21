<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class WebhookLog extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'webhook_id',
        'payload',
        'response_code',
        'success',
        'sent_at'
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

    public function toSearchableArray(): array
    {
        return [
            'payload' => $this->payload,
            'response_code ' => $this->response_code
        ];
    }
}
