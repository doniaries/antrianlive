<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Relasi dengan layanan untuk petugas
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'user_services');
    }

    /**
     * Cek apakah user adalah superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Cek apakah user adalah petugas
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Cek apakah user memiliki akses ke layanan tertentu
     */
    public function hasServiceAccess(int $serviceId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->services()->where('service_id', $serviceId)->exists();
    }

    public function canBeImpersonated(): bool
    {
        return ! $this->is_admin();
    }
}
