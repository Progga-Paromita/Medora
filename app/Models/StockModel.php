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

    /**
     * Get stock records with pagination, sorting, search, and status filters
     */
    public static function getRecord($request = null)
    {
        $query = self::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.generic_name as generic_name')
            ->where('stock.is_deleted', '=', 0);

        // Search: Medicine Name, Batch ID
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('medicines.name', 'like', '%' . $search . '%')
                  ->orWhere('stock.batch_id', 'like', '%' . $search . '%');
            });
        }

        // Filter by Medicine ID
        if ($request && !empty($request->get('medicine_id'))) {
            $query->where('stock.medicine_id', '=', $request->get('medicine_id'));
        }

        // Filter by Expiry status or date
        if ($request && !empty($request->get('expiry_status'))) {
            if ($request->get('expiry_status') === 'expired') {
                $query->where('stock.expiry_date', '<', date('Y-m-d'));
            } elseif ($request->get('expiry_status') === 'near_expiry') {
                // Near expiry: expiring within 90 days (3 months)
                $query->where('stock.expiry_date', '>=', date('Y-m-d'))
                      ->where('stock.expiry_date', '<=', date('Y-m-d', strtotime('+90 days')));
            }
        }

        // Filter by Low Stock
        if ($request && $request->get('low_stock') === '1') {
            $query->where('stock.quantity', '<', 10);
        }

        // Preserved sorting
        $sortBy = 'stock.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'medicine' => 'medicines.name',
                'batch' => 'stock.batch_id',
                'expiry' => 'stock.expiry_date',
                'quantity' => 'stock.quantity',
                'mrp' => 'stock.mrp',
                'rate' => 'stock.rate'
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

        $limit = 10;
        if ($request && !empty($request->get('limit'))) {
            if (in_array((int)$request->get('limit'), [10, 20, 50])) {
                $limit = (int)$request->get('limit');
            }
        }

        return $query->paginate($limit)->withQueryString();
    }

    public function getMedicine(): BelongsTo
    {
        return $this->belongsTo(MedicinesModel::class, 'medicine_id');
    }
}