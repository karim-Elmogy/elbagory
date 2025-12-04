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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean
            $table->string('group')->default('general'); // general, tax, payment, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // إضافة الإعدادات الافتراضية
        DB::table('settings')->insert([
            [
                'key' => 'tax_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'tax',
                'description' => 'تفعيل الضريبة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tax_rate',
                'value' => '14',
                'type' => 'number',
                'group' => 'tax',
                'description' => 'نسبة الضريبة (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_name',
                'value' => 'متجر إلكتروني',
                'type' => 'text',
                'group' => 'general',
                'description' => 'اسم المتجر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_email',
                'value' => 'info@example.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'البريد الإلكتروني للمتجر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_phone',
                'value' => '(+20) 123 456 7890',
                'type' => 'text',
                'group' => 'general',
                'description' => 'رقم الهاتف',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_logo',
                'value' => 'logo.png',
                'type' => 'text',
                'group' => 'general',
                'description' => 'لوجو المتجر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
