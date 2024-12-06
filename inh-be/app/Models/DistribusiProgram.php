<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiProgram extends Model
{
    use HasFactory;

    protected $table = 'distribusi-program';

    protected $fillable = [
        'title',
        'deskripsi',
        'image',
        'author',
    ];

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
