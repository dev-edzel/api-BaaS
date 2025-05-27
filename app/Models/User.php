<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\Authentication\AccessControlService;
use App\Traits\HasJWT;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, Searchable, HasJWT;

    protected AccessControlService $accessControlService;

    protected $fillable = [
        'username',
        'email',
        'phone_number',
        'password',
        'kyc_status',
        'two_factor_enabled',
        'is_verified',
        'is_active',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed'
        ];
    }

    public function role(): HasOneThrough
    {
        return $this->hasOneThrough(
            Role::class,
            RoleUser::class,
            'user_id',
            'id',
            'id',
            'role_id'
        );
    }

    public function userInfo(): HasOne

    {
        return $this->hasOne(UserInfo::class)->withTrashed();
    }

    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class, 'user_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function kycDocuments(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function userLogs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->accessControlService = app(AccessControlService::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->accessControlService->isSuperAdmin($this);
    }

    public function permitted(array $permissions = [], bool $any = false): bool
    {
        return $this->accessControlService->permitted($this, $permissions, $any);
    }

    public function toSearchableArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'phone_number' => $this->phone_number
        ];
    }
}
