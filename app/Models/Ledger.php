<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Ledger extends Model
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = [
        'transaction_id',
        'account_id',
        'direction',
        'amount',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }
}
