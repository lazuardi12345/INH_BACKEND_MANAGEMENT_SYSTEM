<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang bisa diisi (fillable) melalui mass assignment.
     */
    protected $fillable = [
        'image',
        'name',
        'deskripsi',
    ];

    /**
     * Accessor untuk atribut 'image'.
     * Mengembalikan URL lengkap untuk gambar jika ada.
     *
     * @param string|null $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
