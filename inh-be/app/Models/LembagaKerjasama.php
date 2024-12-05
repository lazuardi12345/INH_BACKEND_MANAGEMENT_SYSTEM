<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LembagaKerjasama extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan
    protected $table = 'lembaga_kerjasama';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'name',
        'image',
    ];

    // Accessor untuk mengambil URL gambar
    public function getImageAttribute($value)
    {
        // Cek apakah gambar ada, jika ada, kembalikan URL lengkap
        return url(Storage::url($value));
    }
}
