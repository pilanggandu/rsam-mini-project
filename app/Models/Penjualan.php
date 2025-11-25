<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $fillable = [
        'nomor_transaksi',
        'resep_id',
        'apoteker_id',
        'total_harga',
        'status',
        'finalized_at',
    ];

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class);
    }

    public function apoteker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'apoteker_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public static function generateNomorTransaksi(): string
    {
        $prefix = 'TRX-' . now()->format('Ymd');

        $last = static::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderByDesc('nomor_transaksi')
            ->value('nomor_transaksi');

        if (! $last) {
            return $prefix . '-0001';
        }

        $lastNumber = (int) substr($last, -4);

        return $prefix . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
