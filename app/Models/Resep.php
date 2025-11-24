<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_resep',
        'pasien_id',
        'dokter_id',
        'status',
        'catatan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function details()
    {
        return $this->hasMany(ResepDetail::class);
    }

    public function penjualan()
    {
        return $this->hasOne(Penjualan::class);
    }
}
