<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItemsModel extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'medicine_id',
        'quantity',
        'purchase_rate',
        'subtotal',
    ];

    /**
     * Relationship with PurchasesModel
     */
    public function getPurchase(): BelongsTo
    {
        return $this->belongsTo(PurchasesModel::class, 'purchase_id');
    }

    /**
     * Relationship with MedicinesModel
     */
    public function getMedicine(): BelongsTo
    {
        return $this->belongsTo(MedicinesModel::class, 'medicine_id');
    }
}
