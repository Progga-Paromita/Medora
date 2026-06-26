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
     * Get all purchase records
     */
    public static function getRecord()
    {
        return self::where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
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
