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
        // 1. suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });

        // 2. customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('doctor_name')->nullable();
            $table->text('doctor_address')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });

        // 3. medicines
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('packaging')->nullable();
            $table->string('generic_name')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('profile_image')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });

        // 4. invoices (Sales)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('invoice_date');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('tax', 5, 2)->default(0);
            $table->decimal('net_total', 12, 2)->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        // 5. stock
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('mrp', 12, 2)->default(0);
            $table->decimal('rate', 12, 2)->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('set null');
        });

        // 6. purchases
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('voucher_number')->nullable();
            $table->date('purchase_date');
            $table->decimal('net_total', 12, 2)->default(0);
            $table->tinyInteger('payment_status')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('stock');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('suppliers');
    }
};
