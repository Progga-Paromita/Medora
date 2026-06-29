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
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('strength')->nullable()->after('generic_name');
            $table->string('category')->nullable()->after('strength');
            $table->string('sku')->nullable()->after('category');
            $table->string('temperature_control')->nullable()->after('sku');
            $table->tinyInteger('prescription_required')->default(0)->after('temperature_control');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['strength', 'category', 'sku', 'temperature_control', 'prescription_required']);
        });
    }
};
