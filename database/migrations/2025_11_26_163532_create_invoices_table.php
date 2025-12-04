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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // WH-2025-000001
            $table->date('invoice_date');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit'])->default('cash');
            $table->integer('credit_days')->nullable(); // مدة السداد لو أجل
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('total_after_discount', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['draft', 'final', 'cancelled'])->default('draft');
            $table->text('cancellation_reason')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->unsignedBigInteger('order_id')->nullable(); // ربط بالطلب إن وجد
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
