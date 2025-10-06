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
        Schema::create('produk_cabangs', function (Blueprint $table) {
            $table->id();
            $table->morphs('itemable');
            $table->foreignId('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
            $table->integer('stok');
            // $table->bigInteger('harga_jual')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_cabangs');
    }
};
