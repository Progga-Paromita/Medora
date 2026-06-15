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

    public static function getRecord()
    {
        return self::select('medicines.*')
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
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