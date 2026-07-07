<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomersModel;
use App\Models\SuppliersModel;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use App\Models\InvoicesModel;
use App\Models\InvoiceItemsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customer;
    protected $supplier;
    protected $medicine;
    protected $stockBatch1;
    protected $stockBatch2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $user = new User();
        $user->name = 'Admin Test';
        $user->email = 'admin_test@example.com';
        $user->password = bcrypt('password123');
        $user->is_role = 1;
        $user->status = 1;
        $user->is_deleted = 0;
        $user->save();
        $this->adminUser = $user;

        // 2. Create Customer
        $customer = new CustomersModel();
        $customer->name = 'John Doe';
        $customer->phone = '0199999999';
        $customer->email = 'john@example.com';
        $customer->address = 'Dhaka';
        $customer->is_deleted = 0;
        $customer->save();
        $this->customer = $customer;

        // 3. Create Supplier
        $supplier = new SuppliersModel();
        $supplier->name = 'Square Pharma';
        $supplier->phone = '123456';
        $supplier->address = 'Dhaka';
        $supplier->is_deleted = 0;
        $supplier->save();
        $this->supplier = $supplier;

        // 4. Create Medicine
        $medicine = new MedicinesModel();
        $medicine->name = 'Napa 500mg';
        $medicine->generic_name = 'Paracetamol';
        $medicine->packaging = '10s';
        $medicine->supplier_id = $supplier->id;
        $medicine->is_deleted = 0;
        $medicine->save();
        $this->medicine = $medicine;

        // 5. Create two stock batches with different expiry dates (FEFO verification)
        // Batch 1 expires sooner (Earliest Expiry First)
        $batch1 = new StockModel();
        $batch1->medicine_id = $medicine->id;
        $batch1->batch_id = 'BATCH-EARLY';
        $batch1->expiry_date = date('Y-m-d', strtotime('+3 months'));
        $batch1->quantity = 30; // 30 available
        $batch1->rate = 1.00;
        $batch1->mrp = 2.00;
        $batch1->is_deleted = 0;
        $batch1->save();
        $this->stockBatch1 = $batch1;

        // Batch 2 expires later
        $batch2 = new StockModel();
        $batch2->medicine_id = $medicine->id;
        $batch2->batch_id = 'BATCH-LATER';
        $batch2->expiry_date = date('Y-m-d', strtotime('+6 months'));
        $batch2->quantity = 50; // 50 available
        $batch2->rate = 1.00;
        $batch2->mrp = 2.50;
        $batch2->is_deleted = 0;
        $batch2->save();
        $this->stockBatch2 = $batch2;
    }

    /**
     * Test FEFO stock deduction:
     * Requesting 40 units should take:
     * - 30 units from BATCH-EARLY (reducing it to 0)
     * - 10 units from BATCH-LATER (reducing it to 40)
     */
    public function test_invoice_creation_deducts_fefo_stock()
    {
        $postData = [
            'customer_id' => $this->customer->id,
            'invoice_date' => date('Y-m-d'),
            'discount_type' => 'fixed',
            'discount_value' => 5.00,
            'tax' => 10.00, // 10% tax
            'medicines' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 40
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/invoices/create', $postData);

        $response->assertRedirect('/admin/invoices');

        // Verify stock batch 1 is fully depleted
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch1->id,
            'quantity' => 0
        ]);

        // Verify stock batch 2 has 40 units remaining
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch2->id,
            'quantity' => 40
        ]);

        // Verify invoice was created
        $this->assertDatabaseHas('invoices', [
            'customer_id' => $this->customer->id,
            // Subtotal amount = (30 * $2.00) + (10 * $2.50) = $60 + $25 = $85.00
            'total_amount' => 85.00,
            // Discount = $5.00
            'total_discount' => 5.00,
            // Tax = 10% of (85 - 5) = $8.00
            // Net Total = 80 + 8 = $88.00
            'net_total' => 88.00
        ]);

        // Verify invoice items records
        $this->assertDatabaseHas('invoice_items', [
            'stock_id' => $this->stockBatch1->id,
            'quantity' => 30
        ]);
        $this->assertDatabaseHas('invoice_items', [
            'stock_id' => $this->stockBatch2->id,
            'quantity' => 10
        ]);
    }

    /**
     * Test validation checks fail if quantity requested is more than aggregate stock.
     */
    public function test_insufficient_stock_prevents_invoice_generation()
    {
        $postData = [
            'customer_id' => $this->customer->id,
            'invoice_date' => date('Y-m-d'),
            'discount_type' => 'fixed',
            'discount_value' => 0.00,
            'tax' => 0.00,
            'medicines' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 100 // exceeds aggregate stock of 80 (30 + 50)
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->from('/admin/invoices/create')
            ->post('/admin/invoices/create', $postData);

        $response->assertRedirect('/admin/invoices/create');
        $response->assertSessionHas('error');

        // Confirm stock remains untouched
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch1->id,
            'quantity' => 30
        ]);
    }

    /**
     * Test updating invoice adjusts stock properly.
     */
    public function test_invoice_update_adjusts_stock()
    {
        // 1. Create invoice header manually
        $invoice = new InvoicesModel();
        $invoice->invoice_number = 'INV-999';
        $invoice->customer_id = $this->customer->id;
        $invoice->invoice_date = date('Y-m-d');
        $invoice->total_amount = 60.00;
        $invoice->total_discount = 0.00;
        $invoice->tax = 0.00;
        $invoice->net_total = 60.00;
        $invoice->is_deleted = 0;
        $invoice->save();

        // 2. Create invoice item (deducted 30 from batch 1)
        $item = new InvoiceItemsModel();
        $item->invoice_id = $invoice->id;
        $item->medicine_id = $this->medicine->id;
        $item->stock_id = $this->stockBatch1->id;
        $item->quantity = 30;
        $item->selling_price = 2.00;
        $item->subtotal = 60.00;
        $item->save();

        // Set batch 1 stock to 0 as if it was sold
        $this->stockBatch1->quantity = 0;
        $this->stockBatch1->save();

        // 3. Update invoice: Request quantity of 10 instead of 30
        $updateData = [
            'customer_id' => $this->customer->id,
            'invoice_date' => date('Y-m-d'),
            'discount_type' => 'fixed',
            'discount_value' => 0.00,
            'tax' => 0.00,
            'medicines' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 10
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/invoices/edit/{$invoice->id}", $updateData);

        $response->assertRedirect('/admin/invoices');

        // Stock batch 1 should be restored (30 back) then deducted (10 taken) => 20 left
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch1->id,
            'quantity' => 20
        ]);
    }

    /**
     * Test delete reverts stock level.
     */
    public function test_invoice_delete_reverts_stock()
    {
        // 1. Setup invoice
        $invoice = new InvoicesModel();
        $invoice->invoice_number = 'INV-888';
        $invoice->customer_id = $this->customer->id;
        $invoice->invoice_date = date('Y-m-d');
        $invoice->total_amount = 60.00;
        $invoice->total_discount = 0.00;
        $invoice->tax = 0.00;
        $invoice->net_total = 60.00;
        $invoice->is_deleted = 0;
        $invoice->save();

        $item = new InvoiceItemsModel();
        $item->invoice_id = $invoice->id;
        $item->medicine_id = $this->medicine->id;
        $item->stock_id = $this->stockBatch1->id;
        $item->quantity = 30;
        $item->selling_price = 2.00;
        $item->subtotal = 60.00;
        $item->save();

        $this->stockBatch1->quantity = 0;
        $this->stockBatch1->save();

        // 2. Delete invoice
        $response = $this->actingAs($this->adminUser)
            ->get("/admin/invoices/delete/{$invoice->id}");

        $response->assertRedirect('/admin/invoices');

        // Stock should be reverted back to 30
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch1->id,
            'quantity' => 30
        ]);

        // Invoice should be soft-deleted
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'is_deleted' => 1
        ]);
    }
}
