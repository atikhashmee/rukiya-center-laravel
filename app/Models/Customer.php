<?php

namespace App\Models;

use App\Notifications\CustomerVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class Customer extends User implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use Billable, HasFactory, Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_prefix',
        'phone',
        'interests',
        'about',
        'is_active',
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
            'interests' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomerVerifyEmail);
    }
}
