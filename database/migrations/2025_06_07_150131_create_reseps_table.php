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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            // --- DATA UTAMA RESEP (OD = Kanan, OS = Kiri) ---

            // Mata Kanan (OD)
            $table->string('od_sph')->nullable();
            $table->string('od_cyl')->nullable();
            $table->string('od_axis')->nullable();
            $table->string('od_add')->nullable();
            $table->string('od_prisma')->nullable();
            $table->string('od_base')->nullable();
            $table->string('od_va')->nullable();

            // Mata Kiri (OS)
            $table->string('os_sph')->nullable();
            $table->string('os_cyl')->nullable();
            $table->string('os_axis')->nullable();
            $table->string('os_add')->nullable();
            $table->string('os_prisma')->nullable();
            $table->string('os_base')->nullable();
            $table->string('os_va')->nullable();

            // --- NOTE & TANGGAL ---
            $table->date('tanggal_pemeriksaan')->nullable();
            $table->text('notes')->nullable();

            // --- DATA PRECAL / FRAME ---
            $table->string('pdr')->nullable();       // Pupillary Distance Right
            $table->string('pdl')->nullable();       // Pupillary Distance Left
            $table->string('pv')->nullable();        // Pantoscopic Tilt / Vertex Distance
            $table->string('frame_a')->nullable();   // Box System A (Lebar Lensa)
            $table->string('frame_b')->nullable();   // Box System B (Tinggi Lensa)
            $table->string('frame_d')->nullable();   // DBL (Distance Between Lenses)
            $table->string('frame_diag')->nullable(); // Effective Diameter / Diagonal
            $table->string('frame')->nullable();     // Nama/Kode Frame

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
