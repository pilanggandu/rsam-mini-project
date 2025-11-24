<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'resep_id',
        'apoteker_id',
        'total_harga',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class);
    }

    public function apoteker()
    {
        return $this->belongsTo(User::class, 'apoteker_id');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
