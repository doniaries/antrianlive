<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'profils';
    protected $fillable = [
        'nama_instansi',
        'alamat',
        'no_telepon',
        'email',
        'logo',
        'favicon',
    ];

    public function getLogoUrlAttribute()
    {
        return asset('storage/' . $this->logo);
    }

    public function getFaviconUrlAttribute()
    {
        return asset('storage/' . $this->favicon);
    }

    public function getNamaInstansiAttribute()
    {
        return $this->attributes['nama_instansi'];
    }

    public function getAlamatAttribute()
    {
        return $this->attributes['alamat'];
    }
}
