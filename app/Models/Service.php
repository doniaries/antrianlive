<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Mendefinisikan relasi Many-to-Many ke model Counter.
     * Sebuah layanan bisa dilayani oleh banyak loket.
     */
    public function counters(): BelongsToMany
    {
        return $this->belongsToMany(Counter::class, 'counter_layanans')
            ->withTimestamps();
    }

    /**
     * Get all of the antrians for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}
