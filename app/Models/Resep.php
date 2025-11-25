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

    /**
     * Generate nomor resep dengan format RXYYYYMMDDxxx
     * contoh: RX20251124001
     */
    protected function generateNomorResep(): string
    {
        $prefix = 'RX' . now()->format('Ymd');

        $last = static::where('nomor_resep', 'like', $prefix . '%')
            ->orderBy('nomor_resep', 'desc')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $lastRunning = (int) substr($last->nomor_resep, -3);
            $nextNumber  = $lastRunning + 1;
        }

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
