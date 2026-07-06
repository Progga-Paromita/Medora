<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesModel extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'supplier_id',
        'invoice_id',
        'voucher_number',
        'purchase_date',
        'net_total',
        'payment_status',
        'is_deleted',
    ];

    /**
     * Get a single purchase record
     */
    public static function getSingleRecord($id)
    {
        return self::find($id);
    }

    /**
     * Get all purchase records with filters and sorting
     */
    public static function getRecord($request = null)
    {
        $query = self::select('purchases.*')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('purchases.*', 'suppliers.name as supplier_name')
            ->where('purchases.is_deleted', 0);

        // Toggle deleted purchases
        if ($request && $request->get('status') === 'deleted') {
            $query = self::select('purchases.*')
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->select('purchases.*', 'suppliers.name as supplier_name')
                ->where('purchases.is_deleted', 1);
        }

        // Search: Voucher Number, Supplier Name
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('purchases.voucher_number', 'like', '%' . $search . '%')
                  ->orWhere('suppliers.name', 'like', '%' . $search . '%');
            });
        }

        // Filter by Supplier
        if ($request && !empty($request->get('supplier_id'))) {
            $query->where('purchases.supplier_id', '=', $request->get('supplier_id'));
        }

        // Filter by Payment Status
        if ($request && !empty($request->get('payment_status'))) {
            $query->where('purchases.payment_status', '=', $request->get('payment_status'));
        }

        // Filter by Date Range
        if ($request && !empty($request->get('start_date'))) {
            $query->whereDate('purchases.purchase_date', '>=', $request->get('start_date'));
        }
        if ($request && !empty($request->get('end_date'))) {
            $query->whereDate('purchases.purchase_date', '<=', $request->get('end_date'));
        }

        // Sorting
        $sortBy = 'purchases.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'voucher' => 'purchases.voucher_number',
                'supplier' => 'suppliers.name',
                'purchase_date' => 'purchases.purchase_date',
                'net_total' => 'purchases.net_total',
                'payment_status' => 'purchases.payment_status',
                'created_at' => 'purchases.created_at'
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
     * Relationship with PurchaseItemsModel
     */
    public function getPurchaseItems()
    {
        return $this->hasMany(PurchaseItemsModel::class, 'purchase_id');
    }

    /**
     * Supplier Relationship
     */
    public function getSupplierName()
    {
        return $this->belongsTo(SuppliersModel::class, 'supplier_id', 'id');
    }

    /**
     * Invoice Relationship
     */
    public function getInvoiceNo()
    {
        return $this->belongsTo(InvoicesModel::class, 'invoice_id', 'id');
    }
}
