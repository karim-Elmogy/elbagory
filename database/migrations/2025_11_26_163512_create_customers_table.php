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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // ربط بحساب المستخدم
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->enum('type', ['retail', 'wholesale'])->default('retail'); // قطاعي أو جملة
            $table->string('company_name')->nullable(); // للجملة
            $table->string('tax_number')->nullable(); // الرقم الضريبي
            $table->text('address')->nullable();
            $table->text('detailed_address')->nullable(); // للجملة
            $table->enum('preferred_payment_method', ['cash', 'bank_transfer', 'credit'])->default('cash');
            $table->decimal('credit_limit', 15, 2)->default(0); // حد الائتمان للجملة
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->string('customer_code')->unique()->nullable(); // كود العميل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
