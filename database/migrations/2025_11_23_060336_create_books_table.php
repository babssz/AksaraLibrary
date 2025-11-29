<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->string('kategori');
            $table->string('isbn')->unique()->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('max_peminjaman_hari')->default(7);
            $table->decimal('denda_per_hari', 8, 2)->default(5000);
            $table->description('deskrisi')->nullable();
            $table->string('cover')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};