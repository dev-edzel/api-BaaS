<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Transaction extends Model
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = [
        'reference',
        'from_account_id',
        'to_account_id',
        'amount',
        'type',
        'status',
        'description',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }

    public function accountFee(): HasOne
    {
        return $this->hasOne(AccountFee::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

}
