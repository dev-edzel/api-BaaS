<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountFee extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'account_id',
        'fee_id',
        'transaction_id',
        'charged_at',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'datetime' => 'charged_at'
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }
}
