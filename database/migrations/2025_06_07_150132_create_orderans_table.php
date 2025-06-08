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
            $table->decimal('total', 15, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('order_status', ['pending', 'complete'])->default('pending');
            $table->enum('payment_type', ['DP', 'pelunasan', 'asuransi']);
            $table->string('optometrist_name');
            $table->string('customer_paying');
            $table->enum('payment_method', ['cash', 'card']);
            $table->unsignedBigInteger('asuransi_id')->nullable();
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
