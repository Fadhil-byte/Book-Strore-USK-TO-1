<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'buku_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'buku_id');
    }
}
