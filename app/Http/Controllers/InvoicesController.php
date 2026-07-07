<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\InvoicesModel;
use App\Models\InvoiceItemsModel;
use App\Models\CustomersModel;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function list(Request $request): View
    {
        $data['getRecord'] = InvoicesModel::getRecord($request);
        $data['header_title'] = 'Invoices List';

        // Prepopulate customers for filter dropdown
        $data['getCustomers'] = CustomersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        // Calculate statistics
        $data['totalInvoices'] = InvoicesModel::where('is_deleted', 0)->count();

        $data['todaySales'] = InvoicesModel::where('is_deleted', 0)
            ->whereDate('invoice_date', today())
            ->sum('net_total');

        $data['monthlySales'] = InvoicesModel::where('is_deleted', 0)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('net_total');

        $data['totalRevenue'] = InvoicesModel::where('is_deleted', 0)->sum('net_total');

        $data['avgInvoiceValue'] = InvoicesModel::where('is_deleted', 0)->avg('net_total') ?? 0.00;

        // Medicines sold today (sum of quantities in invoice items)
        $data['medicinesSoldToday'] = InvoiceItemsModel::whereHas('getInvoice', function($q) {
            $q->where('is_deleted', 0)->whereDate('invoice_date', today());
        })->sum('quantity');

        return view('admin.invoices.list', $data);
    }

    /**
     * Autocomplete endpoint to search active medicines and get stock levels.
     */
    public function searchMedicine(Request $request): JsonResponse
    {
        $search = trim($request->get('q'));
        if (empty($search)) {
            return response()->json([]);
        }

        // Search medicines by name or generic name
        $medicines = MedicinesModel::where('is_deleted', 0)
            ->where(function($q) use($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('generic_name', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        $results = [];
        foreach ($medicines as $med) {
            // Find active non-expired stock batches
            $stocks = StockModel::where('medicine_id', $med->id)
                ->where('is_deleted', 0)
                ->where('expiry_date', '>', date('Y-m-d'))
                ->where('quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->get();

            $availableQty = $stocks->sum('quantity');

            // Retrieve selling price (MRP) of the earliest expiring batch
            $mrp = 0.00;
            if ($stocks->isNotEmpty()) {
                $mrp = $stocks->first()->mrp;
            }

            $results[] = [
                'id' => $med->id,
                'name' => $med->name,
                'generic_name' => $med->generic_name,
                'packaging' => $med->packaging,
                'available_qty' => $availableQty,
                'mrp' => $mrp
            ];
        }

        return response()->json($results);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function add(): View
    {
        $data['getCustomer'] = CustomersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Add New Invoice';

        return view('admin.invoices.add', $data);
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Server-side validation
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'invoice_date'   => 'required|date',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'tax'            => 'required|numeric|min:0|max:100',
            'medicines'      => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity'    => 'required|integer|min:1',
        ], [
            'medicines.required' => 'At least one medicine item must be added to the invoice.',
        ]);

        // Verify stock levels across all medicines first
        foreach ($request->medicines as $item) {
            $medicine = MedicinesModel::find($item['medicine_id']);
            $availableQty = StockModel::where('medicine_id', $item['medicine_id'])
                ->where('is_deleted', 0)
                ->where('expiry_date', '>', date('Y-m-d'))
                ->sum('quantity');

            if ($availableQty < (int)$item['quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Insufficient stock for medicine: " . $medicine->name . " (Available: " . $availableQty . ")");
            }
        }

        // DB Transaction for atomicity
        DB::transaction(function () use ($request) {
            // Generate unique invoice number
            $count = InvoicesModel::whereDate('created_at', today())->count();
            $invoiceNo = 'INV-' . date('Ymd') . '-' . sprintf('%04d', $count + 1);
            while (InvoicesModel::where('invoice_number', $invoiceNo)->exists()) {
                $count++;
                $invoiceNo = 'INV-' . date('Ymd') . '-' . sprintf('%04d', $count + 1);
            }

            $invoice = new InvoicesModel();
            $invoice->invoice_number = $invoiceNo;
            $invoice->customer_id = $request->customer_id;
            $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
            $invoice->total_amount = 0.00;
            $invoice->total_discount = 0.00;
            $invoice->tax = $request->tax;
            $invoice->net_total = 0.00;
            $invoice->is_deleted = 0;
            $invoice->save();

            $subtotalSum = 0.00;

            foreach ($request->medicines as $item) {
                $qtyNeeded = (int)$item['quantity'];

                // Get active non-expired stock batches sorted by expiry date (FEFO)
                $batches = StockModel::where('medicine_id', $item['medicine_id'])
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) {
                        break;
                    }

                    $qtyToTake = min($qtyNeeded, $batch->quantity);
                    $subtotal = $qtyToTake * $batch->mrp;
                    $subtotalSum += $subtotal;

                    // Create Invoice Item
                    $invoiceItem = new InvoiceItemsModel();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->medicine_id = $item['medicine_id'];
                    $invoiceItem->stock_id = $batch->id;
                    $invoiceItem->quantity = $qtyToTake;
                    $invoiceItem->selling_price = $batch->mrp;
                    $invoiceItem->subtotal = $subtotal;
                    $invoiceItem->save();

                    // Deduct from stock batch
                    $batch->quantity -= $qtyToTake;
                    $batch->save();

                    $qtyNeeded -= $qtyToTake;
                }
            }

            // Calculations
            $discountType = $request->discount_type;
            $discountVal = (float)$request->discount_value;
            $taxRate = (float)$request->tax;

            if ($discountType === 'percentage') {
                $totalDiscount = ($discountVal / 100) * $subtotalSum;
            } else {
                $totalDiscount = min($discountVal, $subtotalSum);
            }

            $afterDiscount = $subtotalSum - $totalDiscount;
            $taxAmount = ($taxRate / 100) * $afterDiscount;
            $netTotal = $afterDiscount + $taxAmount;

            // Save totals in header
            $invoice->total_amount = $subtotalSum;
            $invoice->total_discount = $totalDiscount;
            $invoice->net_total = $netTotal;
            $invoice->save();
        });

        return redirect('admin/invoices')->with('success', 'Invoice generated and stock updated successfully.');
    }

    /**
     * Display the specified invoice details.
     */
    public function show($id): View
    {
        $data['invoice'] = InvoicesModel::findOrFail($id);
        $data['invoiceItems'] = InvoiceItemsModel::where('invoice_id', $id)
            ->with('getMedicine', 'getStock')
            ->get();
        $data['header_title'] = 'Invoice Details';

        return view('admin.invoices.show', $data);
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id): View
    {
        $invoice = InvoicesModel::findOrFail($id);
        $data['getRecord'] = $invoice;
        $data['getCustomer'] = CustomersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Edit Invoice';

        // Load items and group them by medicine ID to populate row values correctly
        $invoiceItems = InvoiceItemsModel::where('invoice_id', $id)->get();
        
        $itemsGrouped = [];
        foreach ($invoiceItems as $item) {
            if (!isset($itemsGrouped[$item->medicine_id])) {
                $stockMatch = StockModel::where('medicine_id', $item->medicine_id)
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->sum('quantity');

                $itemsGrouped[$item->medicine_id] = [
                    'medicine_id' => $item->medicine_id,
                    'name' => optional($item->getMedicine)->name,
                    'generic_name' => optional($item->getMedicine)->generic_name,
                    'packaging' => optional($item->getMedicine)->packaging,
                    'quantity' => 0,
                    'mrp' => $item->selling_price,
                    // Available quantity includes the quantity already deducted for this invoice
                    'available_qty' => $stockMatch
                ];
            }
            $itemsGrouped[$item->medicine_id]['quantity'] += $item->quantity;
            $itemsGrouped[$item->medicine_id]['available_qty'] += $item->quantity;
        }

        $data['invoiceItems'] = array_values($itemsGrouped);

        return view('admin.invoices.edit', $data);
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $invoice = InvoicesModel::findOrFail($id);

        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'invoice_date'   => 'required|date',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'tax'            => 'required|numeric|min:0|max:100',
            'medicines'      => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity'    => 'required|integer|min:1',
        ], [
            'medicines.required' => 'At least one medicine item must be added to the invoice.',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // 1. Revert previous stock deductions
            $oldItems = InvoiceItemsModel::where('invoice_id', $invoice->id)->get();
            foreach ($oldItems as $oldItem) {
                if ($oldItem->stock_id) {
                    $stock = StockModel::find($oldItem->stock_id);
                    if ($stock) {
                        $stock->quantity += $oldItem->quantity;
                        $stock->save();
                    }
                }
            }

            // 2. Delete old invoice items
            InvoiceItemsModel::where('invoice_id', $invoice->id)->delete();

            // 3. Verify stock availability for new items
            foreach ($request->medicines as $item) {
                $availableQty = StockModel::where('medicine_id', $item['medicine_id'])
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->sum('quantity');

                if ($availableQty < (int)$item['quantity']) {
                    throw new \Exception("Insufficient stock for medicine: " . MedicinesModel::find($item['medicine_id'])->name);
                }
            }

            // 4. Deduct stock using FEFO and save new items
            $subtotalSum = 0.00;
            foreach ($request->medicines as $item) {
                $qtyNeeded = (int)$item['quantity'];

                $batches = StockModel::where('medicine_id', $item['medicine_id'])
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) {
                        break;
                    }

                    $qtyToTake = min($qtyNeeded, $batch->quantity);
                    $subtotal = $qtyToTake * $batch->mrp;
                    $subtotalSum += $subtotal;

                    $invoiceItem = new InvoiceItemsModel();
                    $invoiceItem->invoice_id = $invoice->id;
                    $invoiceItem->medicine_id = $item['medicine_id'];
                    $invoiceItem->stock_id = $batch->id;
                    $invoiceItem->quantity = $qtyToTake;
                    $invoiceItem->selling_price = $batch->mrp;
                    $invoiceItem->subtotal = $subtotal;
                    $invoiceItem->save();

                    $batch->quantity -= $qtyToTake;
                    $batch->save();

                    $qtyNeeded -= $qtyToTake;
                }
            }

            // Calculations
            $discountType = $request->discount_type;
            $discountVal = (float)$request->discount_value;
            $taxRate = (float)$request->tax;

            if ($discountType === 'percentage') {
                $totalDiscount = ($discountVal / 100) * $subtotalSum;
            } else {
                $totalDiscount = min($discountVal, $subtotalSum);
            }

            $afterDiscount = $subtotalSum - $totalDiscount;
            $taxAmount = ($taxRate / 100) * $afterDiscount;
            $netTotal = $afterDiscount + $taxAmount;

            // 5. Update invoice header
            $invoice->customer_id = $request->customer_id;
            $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
            $invoice->total_amount = $subtotalSum;
            $invoice->total_discount = $totalDiscount;
            $invoice->tax = $taxRate;
            $invoice->net_total = $netTotal;
            $invoice->save();
        });

        return redirect('admin/invoices')->with('success', 'Invoice updated and stock recalculated successfully.');
    }

    /**
     * Soft delete the specified invoice and revert stock.
     */
    public function delete($id): RedirectResponse
    {
        $invoice = InvoicesModel::findOrFail($id);

        DB::transaction(function () use ($invoice) {
            // Revert stock deductions
            $items = InvoiceItemsModel::where('invoice_id', $invoice->id)->get();
            foreach ($items as $item) {
                if ($item->stock_id) {
                    $stock = StockModel::find($item->stock_id);
                    if ($stock) {
                        $stock->quantity += $item->quantity;
                        $stock->save();
                    }
                }
            }

            // Soft delete header
            $invoice->is_deleted = 1;
            $invoice->save();
        });

        return redirect('admin/invoices')->with('success', 'Invoice softly deleted and inventory stock reverted.');
    }

    /**
     * Restore the soft-deleted invoice and deduct stock using FEFO.
     */
    public function restore($id): RedirectResponse
    {
        $invoice = InvoicesModel::findOrFail($id);

        DB::transaction(function () use ($invoice) {
            // Deduct stock for all items
            $items = InvoiceItemsModel::where('invoice_id', $invoice->id)->get();
            foreach ($items as $item) {
                // Re-deduct from stock
                $availableQty = StockModel::where('medicine_id', $item->medicine_id)
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->sum('quantity');

                if ($availableQty < $item->quantity) {
                    throw new \Exception("Cannot restore invoice. Insufficient stock for medicine: " . optional($item->getMedicine)->name);
                }

                $qtyNeeded = $item->quantity;
                $batches = StockModel::where('medicine_id', $item->medicine_id)
                    ->where('is_deleted', 0)
                    ->where('expiry_date', '>', date('Y-m-d'))
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) {
                        break;
                    }
                    $qtyToTake = min($qtyNeeded, $batch->quantity);
                    $batch->quantity -= $qtyToTake;
                    $batch->save();

                    // Update stock_id reference
                    $item->stock_id = $batch->id;
                    $item->save();

                    $qtyNeeded -= $qtyToTake;
                }
            }

            $invoice->is_deleted = 0;
            $invoice->save();
        });

        return redirect('admin/invoices?status=deleted')->with('success', 'Invoice restored and stock recalculated.');
    }

    /**
     * Printable invoice layout view.
     */
    public function printInvoice($id): View
    {
        $data['invoice'] = InvoicesModel::findOrFail($id);
        $data['invoiceItems'] = InvoiceItemsModel::where('invoice_id', $id)
            ->with('getMedicine')
            ->get();
        $data['header_title'] = 'Print Invoice';

        return view('admin.invoices.print', $data);
    }
}
