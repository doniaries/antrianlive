<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'counter_id',
        'queue_number',
        'formatted_number',
        'status',
        'called_at',
        'finished_at',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     * Ini penting agar kolom tanggal bisa dimanipulasi sebagai objek Carbon.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi "milik dari" (belongs to) ke model Service.
     * Setiap antrian pasti milik satu layanan.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Mendefinisikan relasi "milik dari" (belongs to) ke model Counter.
     * Setiap antrian akan dipanggil oleh satu loket.
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }
}
