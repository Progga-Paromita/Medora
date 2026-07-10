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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings values
        $defaults = [
            ['key' => 'pharmacy_name', 'value' => 'Medora Pharmacy'],
            ['key' => 'address', 'value' => '123 Health Care Avenue, Dhaka, Bangladesh'],
            ['key' => 'phone', 'value' => '+880 2-9876543'],
            ['key' => 'email', 'value' => 'contact@medora.com'],
            ['key' => 'website', 'value' => 'www.medora.com'],
            ['key' => 'currency', 'value' => '$'],
            ['key' => 'tax_percentage', 'value' => '10'],
            ['key' => 'invoice_prefix', 'value' => 'INV-'],
            ['key' => 'purchase_prefix', 'value' => 'PUR-'],
            ['key' => 'low_stock_threshold', 'value' => '20'],
            ['key' => 'expiry_alert_days', 'value' => '30'],
            ['key' => 'theme', 'value' => 'dark'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
