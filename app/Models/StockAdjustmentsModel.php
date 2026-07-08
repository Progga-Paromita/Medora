<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentsModel extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustments';

    protected $fillable = [
        'medicine_id',
        'stock_id',
        'user_id',
        'adjustment_type',
        'quantity',
        'reason',
    ];

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

    /**
     * Relationship with User
     */
    public function getUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
