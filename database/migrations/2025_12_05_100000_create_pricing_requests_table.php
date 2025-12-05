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
        Schema::create('pricing_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('request_number')->unique();
            $table->enum('status', ['pending', 'priced', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // ملاحظات من العميل
            $table->text('admin_notes')->nullable(); // ملاحظات من الإدمن
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_requests');
    }
};

