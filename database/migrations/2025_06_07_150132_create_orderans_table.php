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
            $table->enum('payment_status', ['unpaid', 'DP', 'paid'])->default('unpaid');
            $table->bigInteger('customer_paying');
            $table->bigInteger('diskon');
            $table->bigInteger('perlu_dibayar');
            $table->bigInteger('kurang_bayar');
            $table->bigInteger('kembalian')->nullable();
            $table->foreignId('asuransi_id')->nullable()->constrained('asuransis')->onDelete('cascade');
            $table->bigInteger('total');
            $table->bigInteger('laba_total')->default(0);
            $table->boolean('is_returned')->default(false);
            $table->timestamp('returned_at')->nullable();
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
