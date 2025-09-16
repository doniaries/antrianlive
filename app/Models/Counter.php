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
        'status',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
    ];

    const STATUS_BUKA = 'buka';
    const STATUS_TUTUP = 'tutup';
    const STATUS_ISTIRAHAT = 'istirahat';

    public static function getStatuses()
    {
        return [
            self::STATUS_BUKA => 'Buka',
            self::STATUS_TUTUP => 'Tutup',
            self::STATUS_ISTIRAHAT => 'Istirahat',
        ];
    }

    public function isAvailable()
    {
        if ($this->status === self::STATUS_TUTUP) {
            return false;
        }

        if ($this->open_time && $this->close_time) {
            $now = now();
            $openTime = now()->setTimeFromTimeString($this->open_time);
            $closeTime = now()->setTimeFromTimeString($this->close_time);
            
            return $now->between($openTime, $closeTime);
        }

        return $this->status === self::STATUS_BUKA;
    }

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
