<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di database
    protected $table = 'campaigns';

    // Kolom-kolom yang dapat diisi melalui mass assignment
    protected $fillable = ['image', 'title', 'deskripsi', 'kategori'];

    // Accessor untuk kolom image
    public function getImageAttribute($value)
    {
        // Jika gambar ada, tambahkan URL lengkap dari direktori storage
        return $value ? asset('storage/' . $value) : null;
    }
}
