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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->string('unit')->default('قطعة'); // وحدة القياس
            $table->decimal('retail_price', 15, 2); // سعر قطاعي
            $table->decimal('wholesale_price', 15, 2); // سعر جملة
            $table->integer('min_wholesale_quantity')->default(1); // حد أدنى للكمية للجملة
            $table->integer('stock_quantity')->default(0); // الكمية المتاحة
            $table->integer('reorder_level')->default(10); // حد إعادة الطلب
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('main_image')->nullable();
            $table->json('images')->nullable(); // صور إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
