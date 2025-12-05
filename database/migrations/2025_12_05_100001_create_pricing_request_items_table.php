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
        Schema::create('pricing_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_request_id');
            $table->foreign('pricing_request_id')->references('id')->on('pricing_requests')->onDelete('cascade');
            $table->string('product_name'); // اسم المنتج المدخل من العميل
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable(); // ملاحظات خاصة بالمنتج
            $table->decimal('price', 15, 2)->nullable(); // السعر الذي يحدده الإدمن
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_request_items');
    }
};

