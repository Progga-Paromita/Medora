<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicesModel extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    /**
     * Get a single invoice record by ID.
     */
    public static function getSingleRecord($id): ?InvoicesModel
    {
        return self::find($id);
    }

    /**
     * Get invoice records with filters, search, and sorting
     */
    public static function getRecord($request = null)
    {
        $query = self::select('invoices.*')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select('invoices.*', 'customers.name as customer_name')
            ->where('invoices.is_deleted', '=', 0);

        // Toggle deleted invoices
        if ($request && $request->get('status') === 'deleted') {
            $query = self::select('invoices.*')
                ->join('customers', 'invoices.customer_id', '=', 'customers.id')
                ->select('invoices.*', 'customers.name as customer_name')
                ->where('invoices.is_deleted', '=', 1);
        }

        // Search: Invoice Number, Customer Name
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('invoices.invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customers.name', 'like', '%' . $search . '%');
            });
        }

        // Filter by Customer
        if ($request && !empty($request->get('customer_id'))) {
            $query->where('invoices.customer_id', '=', $request->get('customer_id'));
        }

        // Filter by Date Range
        if ($request && !empty($request->get('start_date'))) {
            $query->whereDate('invoices.invoice_date', '>=', $request->get('start_date'));
        }
        if ($request && !empty($request->get('end_date'))) {
            $query->whereDate('invoices.invoice_date', '<=', $request->get('end_date'));
        }

        // Filter by Net Total Range
        if ($request && !empty($request->get('min_amount'))) {
            $query->where('invoices.net_total', '>=', $request->get('min_amount'));
        }
        if ($request && !empty($request->get('max_amount'))) {
            $query->where('invoices.net_total', '<=', $request->get('max_amount'));
        }

        // Sorting
        $sortBy = 'invoices.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'invoice_no' => 'invoices.invoice_number',
                'customer' => 'customers.name',
                'invoice_date' => 'invoices.invoice_date',
                'net_total' => 'invoices.net_total',
                'created_at' => 'invoices.created_at'
            ];
            if (array_key_exists($request->get('sort_by'), $allowedSorts)) {
                $sortBy = $allowedSorts[$request->get('sort_by')];
            }
        }

        if ($request && !empty($request->get('sort_order'))) {
            if (in_array(strtolower($request->get('sort_order')), ['asc', 'desc'])) {
                $sortOrder = strtolower($request->get('sort_order'));
            }
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination limit
        $limit = 10;
        if ($request && !empty($request->get('limit'))) {
            if (in_array((int)$request->get('limit'), [10, 20, 50])) {
                $limit = (int)$request->get('limit');
            }
        }

        return $query->paginate($limit)->withQueryString();
    }

    /**
     * Relationship with InvoiceItemsModel
     */
    public function getInvoiceItems()
    {
        return $this->hasMany(InvoiceItemsModel::class, 'invoice_id');
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function getCustomerName(): BelongsTo
    {
        return $this->belongsTo(CustomersModel::class, 'customer_id', 'id');
    }
}
