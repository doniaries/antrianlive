<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_number',
        'name',
        'nik',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'bpjs_number',
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
     * Scope a query to only include patients that match the search term.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('medical_record_number', 'like', '%' . $search . '%');
            });
        });
    }
}
