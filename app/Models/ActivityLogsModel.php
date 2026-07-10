<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogsModel extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent'
    ];

    /**
     * Log user activity trail.
     */
    public static function log($action)
    {
        self::create([
            'user_id' => optional(auth()->user())->id,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Relationship to User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
