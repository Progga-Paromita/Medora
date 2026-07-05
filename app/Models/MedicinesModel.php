<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SuppliersModel;
use App\Models\StockModel;


class MedicinesModel extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    public static function getSingleRecord($id)
    {
        return self::find($id);
    }

    public static function getRecord($request = null)
    {
        $query = self::select('medicines.*')
            ->join('suppliers', 'medicines.supplier_id', '=', 'suppliers.id')
            ->select('medicines.*', 'suppliers.name as supplier_name')
            ->where('medicines.is_deleted', 0);

        // Toggle deleted medicines
        if ($request && $request->get('status') === 'deleted') {
            $query = self::select('medicines.*')
                ->join('suppliers', 'medicines.supplier_id', '=', 'suppliers.id')
                ->select('medicines.*', 'suppliers.name as supplier_name')
                ->where('medicines.is_deleted', 1);
        }

        // Search: Medicine Name, Generic Name, Supplier Name
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('medicines.name', 'like', '%' . $search . '%')
                  ->orWhere('medicines.generic_name', 'like', '%' . $search . '%')
                  ->orWhere('suppliers.name', 'like', '%' . $search . '%');
            });
        }

        // Filter by Packaging
        if ($request && !empty($request->get('packaging'))) {
            $query->where('medicines.packaging', '=', $request->get('packaging'));
        }

        // Filter by Supplier
        if ($request && !empty($request->get('supplier_id'))) {
            $query->where('medicines.supplier_id', '=', $request->get('supplier_id'));
        }

        // Filter by Date Added (Registration range)
        if ($request && !empty($request->get('start_date'))) {
            $query->whereDate('medicines.created_at', '>=', $request->get('start_date'));
        }
        if ($request && !empty($request->get('end_date'))) {
            $query->whereDate('medicines.created_at', '<=', $request->get('end_date'));
        }

        // Sorting
        $sortBy = 'medicines.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'name' => 'medicines.name',
                'generic_name' => 'medicines.generic_name',
                'supplier' => 'suppliers.name',
                'created_at' => 'medicines.created_at'
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

        // Apply sorting
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

    public function getProfileImage(): string
    {
        if (!empty($this->profile_image) && file_exists('uploads/medicines/' . $this->profile_image)) {
            return url('uploads/medicines/' . $this->profile_image);
        } else {
            return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=7F9CF5";
        }
    }

    public function getSupplierName()
    {
        return $this->belongsTo(SuppliersModel::class, 'supplier_id',"id");
    }
}
