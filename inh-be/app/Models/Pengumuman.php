<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman'; // Nama tabel
    protected $fillable = ['image']; // Kolom yang dapat diisi

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
