<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';
    // Tentukan kolom-kolom yang bisa diisi (fillable)
    protected $fillable = ['image', 'name'];
}
