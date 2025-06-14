<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lensa_finishes', function (Blueprint $table) {
            $table->id();
            $table->string('merk', 100);
            $table->string('desain', 50);
            $table->string('tipe', 50);
            $table->decimal('sph', 5, 2);
            $table->decimal('cyl', 5, 2);
            $table->decimal('add', 5, 2);
            $table->decimal('harga', 15, 2);
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('laba', 15, 2);
            $table->unsignedInteger('stok');
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lensa_finishes');
    }
};
