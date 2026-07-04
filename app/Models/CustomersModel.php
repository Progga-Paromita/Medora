<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;

class CustomersModel extends Model
{
    use HasFactory;

    protected $table = 'customers';

    /**
     * Get all active (not deleted) customer records.
     */
    /**
     * Get all active customer records.
     */
    public static function getSingleRecord($id)
    {
        return self::find($id);
    }

    public static function getRecord($request = null)
    {
        $query = self::where('is_deleted', 0);

        // Toggle deleted customers
        if ($request && $request->get('status') === 'deleted') {
            $query = self::where('is_deleted', 1);
        }

        // Search: Name, Phone, Email
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('customers.name', 'like', '%' . $search . '%')
                  ->orWhere('customers.phone', 'like', '%' . $search . '%')
                  ->orWhere('customers.email', 'like', '%' . $search . '%');
            });
        }

        // Filter by Date (Registration Date range)
        if ($request && !empty($request->get('start_date'))) {
            $query->whereDate('customers.created_at', '>=', $request->get('start_date'));
        }
        if ($request && !empty($request->get('end_date'))) {
            $query->whereDate('customers.created_at', '<=', $request->get('end_date'));
        }

        // Sorting
        $sortBy = 'customers.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'name' => 'customers.name',
                'created_at' => 'customers.created_at'
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
}
