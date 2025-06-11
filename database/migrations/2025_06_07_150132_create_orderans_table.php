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
        Schema::create('orderans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->date('order_date');
            $table->date('complete_date')->nullable();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->enum('payment_type', ['DP', 'pelunasan', 'asuransi']);
            $table->enum('order_status', ['pending', 'complete'])->default('pending');
            $table->enum('payment_method', ['cash', 'card']);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('customer_paying');
            $table->string('perlu_dibayar');
            $table->string('kembalian')->nullable();
            $table->foreignId('asuransi_id')->nullable()->constrained('asuransis')->onDelete('cascade');
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderans');
    }
};
