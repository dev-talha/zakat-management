<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'name_bn', 'email', 'mobile', 'password', 'avatar', 'branch_id', 'user_type', 'locale', 'status'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'mobile', 'status', 'user_type'])
            ->logOnlyDirty();
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    // Helpers
    public function isDonor(): bool
    {
        return $this->user_type === 'donor';
    }

    public function isBeneficiary(): bool
    {
        return $this->user_type === 'beneficiary';
    }

    public function isStaff(): bool
    {
        return $this->user_type === 'staff';
    }

    public function isAgent(): bool
    {
        return $this->user_type === 'agent';
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'bn' && $this->name_bn) {
            return $this->name_bn;
        }
        return $this->name;
    }
}
