<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MedicinesModel;

class StockModel extends Model
{
    use HasFactory;

    protected $table = 'stock';

    public static function getSingleRecord($id)
    {
        return self::find($id);
    }

    public static function getRecord()
    {
        return self::select('*')
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getMedicine(): BelongsTo
    {
        return $this->belongsTo(MedicinesModel::class, 'medicine_id');
    }
}