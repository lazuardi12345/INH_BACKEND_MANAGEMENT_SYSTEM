<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistribusiProgramTable extends Migration
{
    public function up()
    {
        Schema::create('distribusi-program', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title', 255); // Nama program
            $table->text('deskripsi')->nullable(); // Deskripsi program
            $table->string('image')->nullable(); // File gambar program
            $table->string('author', 255); // Penulis atau pembuat program
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('distribusi_program');
    }
}
