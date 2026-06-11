<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    static public function getRecord()
   {
    return User::select('users.*')
        ->where('is_deleted', '=', 0)
        ->where('status', '=', 1)
        ->get();
   }
   public function getProfileImage()
{
    if (!empty($this->profile_image) && file_exists('uploads/profile/' . $this->profile_image)) {
        return url('uploads/profile/' . $this->profile_image);
    } else {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name . " " . $this->last_name) . "&color=7F9CF5&background=EBF4FF";
    }
}
}
