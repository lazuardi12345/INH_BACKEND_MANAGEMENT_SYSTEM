<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategory extends Model
{
    use HasFactory;

    protected $table = 'kategory'; // Nama tabel
    protected $fillable = ['name',]; // Kolom yang dapat diisi
}
