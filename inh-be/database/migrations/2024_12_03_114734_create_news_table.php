<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('news', function (Blueprint $table) {
        $table->id();
        $table->string('vidio');
        $table->enum('kategory', ['kepedulian', 'kemanusiaan']);
        $table->string('name');
        $table->text('deskripsi');
        $table->time('time_vidio');
        $table->date('date');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
