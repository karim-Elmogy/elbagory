<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعداد رقم الواتساب إذا لم يكن موجوداً
        $exists = DB::table('settings')->where('key', 'whatsapp_number')->exists();
        
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'whatsapp_number',
                'value' => '201234567890',
                'type' => 'text',
                'group' => 'general',
                'description' => 'رقم الواتساب',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف إعداد رقم الواتساب
        DB::table('settings')->where('key', 'whatsapp_number')->delete();
    }
};
