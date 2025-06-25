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
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('merk', 100);
            $table->string('tipe', 50);
            $table->string('warna', 50);
            $table->bigInteger('harga');
            $table->bigInteger('harga_beli');
            $table->bigInteger('laba');
            $table->unsignedInteger('stok');
            $table->foreignId('cabang_id')->nullable()->constrained('cabangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
