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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // كود الخصم
            $table->string('name'); // اسم الخصم
            $table->text('description')->nullable(); // وصف الخصم
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // نوع الخصم: نسبة مئوية أو مبلغ ثابت
            $table->decimal('value', 10, 2); // قيمة الخصم
            $table->decimal('minimum_amount', 10, 2)->nullable(); // الحد الأدنى للطلب
            $table->decimal('maximum_discount', 10, 2)->nullable(); // الحد الأقصى للخصم (للنسبة المئوية)
            $table->integer('usage_limit')->nullable(); // حد الاستخدام (null = غير محدود)
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام
            $table->integer('usage_limit_per_user')->nullable(); // حد الاستخدام لكل مستخدم
            $table->date('starts_at')->nullable(); // تاريخ بداية الخصم
            $table->date('expires_at')->nullable(); // تاريخ انتهاء الخصم
            $table->boolean('is_active')->default(true); // حالة الخصم
            $table->enum('customer_type', ['all', 'retail', 'wholesale'])->default('all'); // نوع العملاء المسموح لهم
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
