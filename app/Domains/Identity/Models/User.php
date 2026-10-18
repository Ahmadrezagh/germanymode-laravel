<?php

namespace App\Domains\Identity\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'portal_id', 'name', 'last_name', 'email', 'password', 'is_admin', 'role',
    'access_level_id', 'admin_rank', 'page_link', 'avatar', 'mobile',
    'phone', 'workplace_phone', 'fax', 'national_code', 'postal_code',
    'birth_date', 'province', 'city', 'address', 'notes', 'is_blocked',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_blocked' => 'boolean',
            'birth_date' => 'date',
            'admin_rank' => 'integer',
        ];
    }

    public function accessLevel(): BelongsTo
    {
        return $this->belongsTo(AdminAccessLevel::class, 'access_level_id');
    }

    public function fullName(): string
    {
        return trim(($this->name ?? '').' '.($this->last_name ?? ''));
    }

    public function rankLabel(): string
    {
        return match ((int) $this->admin_rank) {
            1 => 'یک ستاره',
            2 => 'دو ستاره',
            3 => 'سه ستاره',
            4 => 'چهار ستاره',
            5 => 'پنج ستاره',
            default => 'بدون رتبه',
        };
    }
}
