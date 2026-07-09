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

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $staffUser;
    protected $customer;
    protected $supplier;
    protected $medicine;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin_rep@example.com';
        $admin->password = bcrypt('password123');
        $admin->is_role = 1;
        $admin->status = 1;
        $admin->is_deleted = 0;
        $admin->save();
        $this->adminUser = $admin;

        // 2. Create Staff
        $staff = new User();
        $staff->name = 'Staff';
        $staff->email = 'staff_rep@example.com';
        $staff->password = bcrypt('password123');
        $staff->is_role = 2;
        $staff->status = 1;
        $staff->is_deleted = 0;
        $staff->save();
        $this->staffUser = $staff;

        // 3. Create Customer
        $customer = new CustomersModel();
        $customer->name = 'Doe';
        $customer->phone = '555555';
        $customer->is_deleted = 0;
        $customer->save();
        $this->customer = $customer;

        // 4. Create Supplier
        $supplier = new SuppliersModel();
        $supplier->name = 'Acme Corp';
        $supplier->phone = '888888';
        $supplier->is_deleted = 0;
        $supplier->save();
        $this->supplier = $supplier;

        // 5. Create Medicine
        $medicine = new MedicinesModel();
        $medicine->name = 'Panadol Extra';
        $medicine->generic_name = 'Paracetamol';
        $medicine->packaging = '10s';
        $medicine->supplier_id = $supplier->id;
        $medicine->is_deleted = 0;
        $medicine->save();
        $this->medicine = $medicine;

        // 6. Create Stock batch
        $stock = new StockModel();
        $stock->medicine_id = $medicine->id;
        $stock->batch_id = 'B-111';
        $stock->expiry_date = date('Y-m-d', strtotime('+3 months'));
        $stock->quantity = 100;
        $stock->rate = 1.00; // Cost = 1.00
        $stock->mrp = 2.00;  // Price = 2.00
        $stock->is_deleted = 0;
        $stock->save();

        // 7. Create Invoice manually
        $invoice = new InvoicesModel();
        $invoice->invoice_number = 'INV-101';
        $invoice->customer_id = $customer->id;
        $invoice->invoice_date = date('Y-m-d');
        $invoice->total_amount = 40.00;
        $invoice->total_discount = 0.00;
        $invoice->tax = 0.00;
        $invoice->net_total = 40.00;
        $invoice->is_deleted = 0;
        $invoice->save();

        // 8. Create Invoice Item (Qty = 20 units)
        $item = new InvoiceItemsModel();
        $item->invoice_id = $invoice->id;
        $item->medicine_id = $medicine->id;
        $item->stock_id = $stock->id;
        $item->quantity = 20;
        $item->selling_price = 2.00;
        $item->subtotal = 40.00;
        $item->save();
    }

    /**
     * Test admin can access dashboard reports and verify profit calculations.
     */
    public function test_admin_can_access_dashboard_and_profit_is_correct()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/reports/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Business Intelligence Dashboard');
        $response->assertSee('$40.00'); // Total Revenue
        $response->assertSee('$20.00'); // Gross Profit: Qty 20 * (selling $2.00 - cost $1.00) = $20.00
    }

    /**
     * Test staff cannot access reports routes.
     */
    public function test_staff_is_denied_reports_access()
    {
        $response = $this->actingAs($this->staffUser)
            ->get('/admin/reports/dashboard');

        $response->assertRedirect('admin/dashboard');
    }

    /**
     * Test sales report filter preset and Excel generation.
     */
    public function test_sales_report_filter_and_excel_export()
    {
        // Check filter preset today
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/reports/sales?date_preset=today');

        $response->assertStatus(200);
        $response->assertSee('INV-101');

        // Check Excel export returns correct spreadsheet header
        $excelResponse = $this->actingAs($this->adminUser)
            ->get('/admin/reports/sales/excel?date_preset=today');

        $excelResponse->assertStatus(200);
        $excelResponse->assertHeader('Content-Type', 'application/vnd.ms-excel');
    }
}
