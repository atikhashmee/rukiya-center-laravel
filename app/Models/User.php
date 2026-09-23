<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'instructor_id',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'permissions' => 'array',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /** Set when this account belongs to an instructor; their records are scoped to it. */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->role?->isSuperAdmin();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission)
            || in_array($permission, $this->extraPermissions(), true);
    }

    /** Permissions granted to this user directly, on top of their role. */
    public function extraPermissions(): array
    {
        return array_values($this->permissions ?? []);
    }

    /** Everything this user holds - role permissions plus direct grants - for the frontend. */
    public function allPermissions(): array
    {
        if ($this->role?->isSuperAdmin()) {
            return \App\Support\Permissions::all();
        }

        return array_values(array_unique([
            ...($this->role->permissions ?? []),
            ...$this->extraPermissions(),
        ]));
    }
}
