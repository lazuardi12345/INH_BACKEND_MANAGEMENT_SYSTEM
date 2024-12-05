<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns'; // Pastikan nama tabel
    protected $fillable = ['image', 'name', 'deskripsi']; // Kolom yang bisa diisi

    public function getImageAttribute($value)
    {
        // Jika gambar ada, tambahkan URL storage lengkap
        return $value ? asset('storage/' . $value) : null;
    }
}
