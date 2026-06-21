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
     * Get all active (not deleted) invoice records.
     */
    public static function getRecord()
    {
        return self::select('invoices.*')
            ->where('is_deleted', '=', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function getCustomerName(): BelongsTo
    {
        return $this->belongsTo(CustomersModel::class, 'customer_id', 'id');
    }
}
