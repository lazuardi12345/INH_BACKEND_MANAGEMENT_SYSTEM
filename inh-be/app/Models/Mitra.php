<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';

    // Kolom-kolom yang bisa diisi (fillable)
    protected $fillable = ['image', 'name'];

    /**
     * Accessor untuk atribut 'image'
     * Mengembalikan URL lengkap gambar jika ada
     */
    public function getImageAttribute($value)
    {
        // Jika gambar ada, tambahkan URL storage lengkap
        return $value ? asset('storage/' . $value) : null;
    }
}
