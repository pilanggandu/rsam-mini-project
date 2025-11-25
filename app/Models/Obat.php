<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'stok',
        'harga_jual',
    ];

    protected $casts = [
        'stok'       => 'integer',
        'harga_jual' => 'decimal:2',
    ];

    public function resepDetails()
    {
        return $this->hasMany(ResepDetail::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
