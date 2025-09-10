<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Mendefinisikan relasi Many-to-Many ke model Service.
     * Sebuah loket bisa melayani banyak jenis layanan.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'counter_layanans');
    }

    /**
     * Mendefinisikan relasi One-to-Many ke model Antrian.
     * Sebuah loket bisa memiliki banyak antrian.
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}
