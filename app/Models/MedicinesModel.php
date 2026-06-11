<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicinesModel extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    // Get single record
    static public function getSingleRecord($id)
    {
        return self::find($id);
    }



    // Get all records (not deleted)
    public static function getRecord()
{
    return self::select('medicines.*')
        ->where('is_deleted', 0)
        ->orderBy('id', 'desc')
        ->get();
}
    public function getProfileImage(): string
{
    if (!empty($this->profile_image) &&
        file_exists('uploads/medicines/' . $this->profile_image)) {

        return url('uploads/medicines/' . $this->profile_image);
    }

    return "https://ui-avatars.com/api/?name=" .
        urlencode($this->name . " " . ($this->last_name ?? '')) .
        "&color=7F9CF5&background=EBF4FF";
}
}