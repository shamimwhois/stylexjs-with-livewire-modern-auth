<?php

namespace App\Models;

use App\Models\Concerns\HasRoles;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'email', 'phone', 'country_code', 'password'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'blocked_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Whether the account is temporarily suspended.
     */
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /**
     * Whether the account is permanently blocked.
     */
    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /**
     * Whether sign in should be denied for this account.
     */
    public function isRestricted(): bool
    {
        return $this->isSuspended() || $this->isBlocked();
    }

    /**
     * Temporarily suspend the account, lifting any permanent block.
     */
    public function suspend(): void
    {
        $this->forceFill(['suspended_at' => now(), 'blocked_at' => null])->save();
    }

    /**
     * Permanently block the account, lifting any suspension.
     */
    public function block(): void
    {
        $this->forceFill(['blocked_at' => now(), 'suspended_at' => null])->save();
    }

    /**
     * Restore the account to a fully active state.
     */
    public function restoreAccount(): void
    {
        $this->forceFill(['suspended_at' => null, 'blocked_at' => null])->save();
    }

    /**
     * The social accounts linked to this user.
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * The device this account is currently bound to.
     */
    public function loginDevice(): HasOne
    {
        return $this->hasOne(LoginDevice::class);
    }

    /**
     * The social profiles linked to this user.
     */
    public function socialProfiles(): HasMany
    {
        return $this->hasMany(SocialProfile::class);
    }

    /**
     * The addresses linked to this user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * The sellers linked to this user.
     */
    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }

    /**
     * The wallet owned by this user.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * The licences owned by this user.
     */
    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }

    /**
     * The feature requests created by this user.
     */
    public function featureRequests(): HasMany
    {
        return $this->hasMany(FeatureRequest::class);
    }
}
