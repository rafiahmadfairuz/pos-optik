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
            $table->foreignId('orderan_id')->constrained()->onDelete('cascade');

            $table->string('right_sph_d')->nullable();
            $table->string('right_cyl_d')->nullable();
            $table->string('right_axis_d')->nullable();
            $table->string('right_va_d')->nullable();

            $table->string('left_sph_d')->nullable();
            $table->string('left_cyl_d')->nullable();
            $table->string('left_axis_d')->nullable();
            $table->string('left_va_d')->nullable();

            $table->string('add_right')->nullable();
            $table->string('add_left')->nullable();
            $table->string('pd_right')->nullable();
            $table->string('pd_left')->nullable();

            $table->text('notes')->nullable();

            $table->unsignedInteger('umur')->nullable(); 
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('alamat')->nullable();

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
