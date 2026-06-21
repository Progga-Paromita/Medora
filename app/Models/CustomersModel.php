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
    public static function getRecord(): Collection
    {
        return self::where('is_deleted', '=', 0)->get();
    }
}
