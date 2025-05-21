<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Card extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'linked_account_id',
        'card_number',
        'status',
        'expiry_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'expiry_date'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'linked_account_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'card_number' => $this->card_number
        ];
    }
}
