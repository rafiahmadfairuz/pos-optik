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
            $table->foreignId('order_id')->constrained('orderans')->onDelete('cascade');

            $table->string('right_sph')->nullable();
            $table->string('right_cyl')->nullable();
            $table->string('right_axis')->nullable();
            $table->string('right_vh')->nullable();
            $table->string('right_d')->nullable();
            $table->string('right_add')->nullable();
            $table->string('right_pd')->nullable();

            $table->string('left_sph')->nullable();
            $table->string('left_cyl')->nullable();
            $table->string('left_axis')->nullable();
            $table->string('left_vh')->nullable();
            $table->string('left_d')->nullable();
            $table->string('left_add')->nullable();
            $table->string('left_pd')->nullable();

            $table->text('notes')->nullable();
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
