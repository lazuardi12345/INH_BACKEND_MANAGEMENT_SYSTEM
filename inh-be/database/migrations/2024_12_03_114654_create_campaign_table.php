<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) { // Gunakan nama jamak
            $table->id();
            $table->string('image');
            $table->string('title');
            $table->text('deskripsi');
            $table->enum('kategori', ['sedekah umum', 'palestina', 'nasional', 'internasional']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};
