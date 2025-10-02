<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    use HasFactory;

    /**
     * Status antrian yang tersedia.
     */
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPANGGIL = 'dipanggil';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_BATAL = 'batal';

    /**
     * Tipe pasien yang tersedia.
     */
    public const TYPE_UMUM = 'umum';
    public const TYPE_BPJS = 'bpjs';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'counter_id',
        'patient_id',
        'queue_number',
        'formatted_number',
        'patient_type',
        'bpjs_number',
        'status',
        'called_at',
        'finished_at',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Relasi ke model Service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relasi ke model Counter.
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    /**
     * Relasi ke model Patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope untuk antrian aktif (belum selesai atau dibatalkan).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DIPANGGIL]);
    }

    /**
     * Scope untuk antrian berdasarkan tipe pasien.
     */
    public function scopePatientType($query, $type)
    {
        return $query->where('patient_type', $type);
    }

    /**
     * Scope untuk antrian berdasarkan nomor BPJS.
     */
    public function scopeBpjsNumber($query, $bpjsNumber)
    {
        return $query->where('bpjs_number', $bpjsNumber);
    }

    /**
     * Cek apakah antrian milik pasien BPJS.
     */
    public function isBpjs(): bool
    {
        return $this->patient_type === self::TYPE_BPJS;
    }

    /**
     * Cek apakah antrian milik pasien umum.
     */
    public function isUmum(): bool
    {
        return $this->patient_type === self::TYPE_UMUM;
    }
}
