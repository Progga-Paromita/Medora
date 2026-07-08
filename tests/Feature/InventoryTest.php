<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SuppliersModel;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use App\Models\StockAdjustmentsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $staffUser;
    protected $supplier;
    protected $medicine;
    protected $stockBatch;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $admin = new User();
        $admin->name = 'Admin User';
        $admin->email = 'admin_inv@example.com';
        $admin->password = bcrypt('password123');
        $admin->is_role = 1;
        $admin->status = 1;
        $admin->is_deleted = 0;
        $admin->save();
        $this->adminUser = $admin;

        // 2. Create Staff User
        $staff = new User();
        $staff->name = 'Staff User';
        $staff->email = 'staff_inv@example.com';
        $staff->password = bcrypt('password123');
        $staff->is_role = 2; // Staff
        $staff->status = 1;
        $staff->is_deleted = 0;
        $staff->save();
        $this->staffUser = $staff;

        // 3. Create Supplier
        $supplier = new SuppliersModel();
        $supplier->name = 'Incepta Pharma';
        $supplier->phone = '777888';
        $supplier->address = 'Dhaka';
        $supplier->is_deleted = 0;
        $supplier->save();
        $this->supplier = $supplier;

        // 4. Create Medicine
        $medicine = new MedicinesModel();
        $medicine->name = 'Napa Extra';
        $medicine->generic_name = 'Paracetamol + Caffeine';
        $medicine->packaging = '10s';
        $medicine->supplier_id = $supplier->id;
        $medicine->is_deleted = 0;
        $medicine->save();
        $this->medicine = $medicine;

        // 5. Create Stock Batch
        $batch = new StockModel();
        $batch->medicine_id = $medicine->id;
        $batch->batch_id = 'BATCH-X';
        $batch->expiry_date = date('Y-m-d', strtotime('+4 months'));
        $batch->quantity = 100;
        $batch->rate = 1.20;
        $batch->mrp = 2.00;
        $batch->is_deleted = 0;
        $batch->save();
        $this->stockBatch = $batch;
    }

    /**
     * Test inventory dashboard loads correctly with correct KPIs.
     */
    public function test_inventory_dashboard_renders_with_kpis()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/inventory/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Inventory Dashboard');
        $response->assertSee('100'); // Total stock units
        $response->assertSee('$120.00'); // Valuation (100 * $1.20)
    }

    /**
     * Test staff cannot access stock adjust routes.
     */
    public function test_staff_cannot_access_adjustment_routes()
    {
        // Try getting adjustment page as staff
        $response1 = $this->actingAs($this->staffUser)
            ->get('/admin/inventory/adjust');
        $response1->assertRedirect('admin/dashboard');

        // Try posting adjustment as staff
        $response2 = $this->actingAs($this->staffUser)
            ->post('/admin/inventory/adjust', [
                'medicine_id' => $this->medicine->id,
                'stock_id' => $this->stockBatch->id,
                'adjustment_type' => 'increase',
                'quantity' => 10,
                'reason' => 'Staff trying to adjust'
            ]);
        $response2->assertRedirect('admin/dashboard');
    }

    /**
     * Test manual stock increase.
     */
    public function test_manual_stock_increase()
    {
        $postData = [
            'medicine_id' => $this->medicine->id,
            'stock_id' => $this->stockBatch->id,
            'adjustment_type' => 'increase',
            'quantity' => 50,
            'reason' => 'Physical audit discrepancy'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/inventory/adjust', $postData);

        $response->assertRedirect('/admin/inventory/dashboard');

        // Check stock quantity increased to 150
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch->id,
            'quantity' => 150
        ]);

        // Check adjustment logged
        $this->assertDatabaseHas('stock_adjustments', [
            'stock_id' => $this->stockBatch->id,
            'adjustment_type' => 'increase',
            'quantity' => 50,
            'user_id' => $this->adminUser->id
        ]);
    }

    /**
     * Test manual stock decrease.
     */
    public function test_manual_stock_decrease()
    {
        $postData = [
            'medicine_id' => $this->medicine->id,
            'stock_id' => $this->stockBatch->id,
            'adjustment_type' => 'decrease',
            'quantity' => 40,
            'reason' => 'Damaged during relocation'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/inventory/adjust', $postData);

        $response->assertRedirect('/admin/inventory/dashboard');

        // Check stock quantity decreased to 60
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch->id,
            'quantity' => 60
        ]);
    }

    /**
     * Test manual stock decrease rejects negative results.
     */
    public function test_manual_stock_decrease_rejects_negative_results()
    {
        $postData = [
            'medicine_id' => $this->medicine->id,
            'stock_id' => $this->stockBatch->id,
            'adjustment_type' => 'decrease',
            'quantity' => 200, // exceeds current 100
            'reason' => 'Typo decrement'
        ];

        $response = $this->actingAs($this->adminUser)
            ->from('/admin/inventory/adjust')
            ->post('/admin/inventory/adjust', $postData);

        $response->assertRedirect('/admin/inventory/adjust');
        $response->assertSessionHas('error', 'Adjustment failed. Stock quantity cannot become negative.');

        // Stock remains 100
        $this->assertDatabaseHas('stock', [
            'id' => $this->stockBatch->id,
            'quantity' => 100
        ]);
    }
}
