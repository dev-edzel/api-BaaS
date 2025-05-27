<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Account extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'account_number',
        'account_type',
        'status',
        'balance',
        'currency',
        'last_modified_log_id'
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

    public function transactionsFrom(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(AccountFee::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'linked_account_id');
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    public function toSearchableArray(): array
    {
        return [
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'status' => $this->status,
            'balance' => $this->balance,
            'currency' => $this->currency,
        ];
    }
}
