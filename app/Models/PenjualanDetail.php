<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'obat_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
