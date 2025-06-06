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
        Schema::create('softlens', function (Blueprint $table) {
              $table->id();
            $table->string('merk', 100);
            $table->string('tipe', 50);
            $table->string('warna', 50);
            $table->decimal('harga', 15, 2);
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
        Schema::dropIfExists('softlens');
    }
};
