<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogsModel extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'name',
        'email',
        'role'
    ];

    /**
     * Log user activity trail.
     */
    public static function log($action = null)
    {
        $user = auth()->user();
        if ($user) {
            self::create([
                'name' => trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')),
                'email' => $user->email,
                'role' => $user->is_role == 1 ? 'Administrator' : 'Pharmacy Staff'
            ]);
        }
    }
}
