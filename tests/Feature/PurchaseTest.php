<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SuppliersModel;
use App\Models\MedicinesModel;
use App\Models\PurchasesModel;
use App\Models\PurchaseItemsModel;
use App\Models\StockModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supplier;
    protected $medicine;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin user manually to avoid mass assignment issues
        $user = new User();
        $user->name = 'Admin Test';
        $user->email = 'admin_test@example.com';
        $user->password = bcrypt('password123');
        $user->is_role = 1;
        $user->status = 1;
        $user->is_deleted = 0;
        $user->save();
        $this->adminUser = $user;

        // 2. Create supplier manually
        $supplier = new SuppliersModel();
        $supplier->name = 'Test Supplier LLC';
        $supplier->phone = '1234567890';
        $supplier->address = 'Supplier Lane 123';
        $supplier->is_deleted = 0;
        $supplier->save();
        $this->supplier = $supplier;

        // 3. Create medicine manually
        $medicine = new MedicinesModel();
        $medicine->name = 'Panadol 500mg';
        $medicine->generic_name = 'Paracetamol';
        $medicine->packaging = 'Tablet';
        $medicine->supplier_id = $supplier->id;
        $medicine->is_deleted = 0;
        $medicine->save();
        $this->medicine = $medicine;
    }

    /**
     * Test purchase creation automatically increases stock.
     */
    public function test_purchase_creation_increases_stock()
    {
        $voucherNum = 'VOUCH-' . uniqid();
        $postData = [
            'supplier_id' => $this->supplier->id,
            'voucher_number' => $voucherNum,
            'purchase_date' => date('Y-m-d'),
            'payment_status' => 2, // Accepted
            'medicines' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'batch_id' => 'BATCH-A1',
                    'expiry_date' => date('Y-m-d', strtotime('+1 year')),
                    'quantity' => 100,
                    'purchase_rate' => 5.00,
                    'mrp' => 8.00
                ]
            ]
        ];

        // Perform request as admin
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/purchases/add', $postData);

        $response->assertRedirect('/admin/purchases');

        // Check if purchase is saved
        $this->assertDatabaseHas('purchases', [
            'voucher_number' => $voucherNum,
            'net_total' => 500.00
        ]);

        // Check if stock is updated
        $this->assertDatabaseHas('stock', [
            'medicine_id' => $this->medicine->id,
            'batch_id' => 'BATCH-A1',
            'quantity' => 100,
            'rate' => 5.00,
            'mrp' => 8.00
        ]);
    }

    /**
     * Test purchase update adjusts stock quantities.
     */
    public function test_purchase_update_recalculates_stock()
    {
        // 1. Create a purchase manually
        $purchase = new PurchasesModel();
        $purchase->supplier_id = $this->supplier->id;
        $purchase->voucher_number = 'VOUCH-' . uniqid();
        $purchase->purchase_date = date('Y-m-d');
        $purchase->payment_status = 1;
        $purchase->net_total = 500.00;
        $purchase->is_deleted = 0;
        $purchase->save();

        $item = new PurchaseItemsModel();
        $item->purchase_id = $purchase->id;
        $item->medicine_id = $this->medicine->id;
        $item->quantity = 100;
        $item->purchase_rate = 5.00;
        $item->subtotal = 500.00;
        $item->save();

        $stock = new StockModel();
        $stock->medicine_id = $this->medicine->id;
        $stock->batch_id = 'BATCH-A1';
        $stock->expiry_date = date('Y-m-d', strtotime('+1 year'));
        $stock->quantity = 100;
        $stock->rate = 5.00;
        $stock->mrp = 8.00;
        $stock->is_deleted = 0;
        $stock->save();

        // 2. Update purchase: Change quantity to 150
        $updateData = [
            'supplier_id' => $this->supplier->id,
            'voucher_number' => $purchase->voucher_number,
            'purchase_date' => date('Y-m-d'),
            'payment_status' => 2,
            'medicines' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'batch_id' => 'BATCH-A1',
                    'expiry_date' => date('Y-m-d', strtotime('+1 year')),
                    'quantity' => 150,
                    'purchase_rate' => 5.00,
                    'mrp' => 8.00
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/purchases/edit/{$purchase->id}", $updateData);

        $response->assertRedirect('/admin/purchases');

        // Check if stock has been updated correctly to 150
        $this->assertDatabaseHas('stock', [
            'medicine_id' => $this->medicine->id,
            'batch_id' => 'BATCH-A1',
            'quantity' => 150
        ]);
    }

    /**
     * Test purchase delete reverts stock.
     */
    public function test_purchase_delete_reverts_stock()
    {
        // 1. Create a purchase manually
        $purchase = new PurchasesModel();
        $purchase->supplier_id = $this->supplier->id;
        $purchase->voucher_number = 'VOUCH-' . uniqid();
        $purchase->purchase_date = date('Y-m-d');
        $purchase->payment_status = 2;
        $purchase->net_total = 500.00;
        $purchase->is_deleted = 0;
        $purchase->save();

        $item = new PurchaseItemsModel();
        $item->purchase_id = $purchase->id;
        $item->medicine_id = $this->medicine->id;
        $item->quantity = 100;
        $item->purchase_rate = 5.00;
        $item->subtotal = 500.00;
        $item->save();

        $stock = new StockModel();
        $stock->medicine_id = $this->medicine->id;
        $stock->batch_id = 'BATCH-A1';
        $stock->expiry_date = date('Y-m-d', strtotime('+1 year'));
        $stock->quantity = 100;
        $stock->rate = 5.00;
        $stock->mrp = 8.00;
        $stock->is_deleted = 0;
        $stock->save();

        // 2. Perform delete
        $response = $this->actingAs($this->adminUser)
            ->get("/admin/purchases/delete/{$purchase->id}");

        $response->assertRedirect('/admin/purchases');

        // 3. Verify stock is reverted back to 0
        $this->assertDatabaseHas('stock', [
            'medicine_id' => $this->medicine->id,
            'batch_id' => 'BATCH-A1',
            'quantity' => 0
        ]);

        // Verify purchase is soft deleted
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'is_deleted' => 1
        ]);
    }
}
