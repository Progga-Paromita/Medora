<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItemsModel extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'medicine_id',
        'stock_id',
        'quantity',
        'selling_price',
        'discount',
        'tax',
        'subtotal',
    ];

    /**
     * Relationship with InvoicesModel
     */
    public function getInvoice(): BelongsTo
    {
        return $this->belongsTo(InvoicesModel::class, 'invoice_id');
    }

    /**
     * Relationship with MedicinesModel
     */
    public function getMedicine(): BelongsTo
    {
        return $this->belongsTo(MedicinesModel::class, 'medicine_id');
    }

    /**
     * Relationship with StockModel
     */
    public function getStock(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'stock_id');
    }
}
