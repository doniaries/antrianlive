<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'bpjs_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    protected $dates = [
        'date_of_birth',
    ];

    /**
     * Get the antrians for the patient.
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }

    /**
     * Get the user's initials.
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->name;
        $initials = '';
        
        $words = explode(' ', $name);
        
        // Get the first letter of each word
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            
            // Limit to 2 characters for initials
            if (strlen($initials) >= 2) {
                break;
            }
        }
        
        return $initials;
    }

    /**
     * Scope a query to only include patients that match the search term.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('bpjs_number', 'like', '%' . $search . '%');
            });
        });
    }
}
