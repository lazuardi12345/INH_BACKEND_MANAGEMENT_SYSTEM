<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembagaKerjasama extends Model
{
    use HasFactory;

    protected $table = 'lembaga_kerjasama';
    protected $fillable = [
        'name',
        'image',
    ];
}
