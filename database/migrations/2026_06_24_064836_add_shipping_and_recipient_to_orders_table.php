<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address', 1000)->nullable()->after('total_amount');
            $table->string('recipient_no_hp', 30)->nullable()->after('shipping_address');
            $table->string('recipient_name', 255)->nullable()->after('recipient_no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'recipient_no_hp', 'recipient_name']);
        });
    }
};
