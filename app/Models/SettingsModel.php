<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Retrieve a setting value or fallback.
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Store or update a setting key-value pair.
     */
    public static function setValue($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
