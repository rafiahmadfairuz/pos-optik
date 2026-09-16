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
      Schema::create('transfers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('from_cabang_id');
    $table->unsignedBigInteger('to_cabang_id');
    $table->date('tanggal')->default(now());
    $table->string('kode')->unique();

    // UBAH DARI BOOLEAN RETUR MENJADI STRING STATUS
    // Nilai status: 'completed', 'returned', 'transferred_out'
    $table->string('status')->default('completed');

    $table->timestamps();

    $table->foreign('from_cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
    $table->foreign('to_cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
