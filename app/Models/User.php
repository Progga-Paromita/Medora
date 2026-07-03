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
        'last_name',
        'email',
        'profile_image',
        'phone',
        'password',
        'is_role',
        'status',
        'is_deleted',
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

    static public function getSingleRecord($id)
    {
    return self::find($id);
    }


    static public function getRecord($request = null)
    {
        $query = self::select('users.*');

        // Toggle deleted users
        if ($request && $request->get('status') === 'deleted') {
            $query->where('is_deleted', '=', 1);
        } else {
            $query->where('is_deleted', '=', 0);
        }

        // Search Name, Last Name, Email, Phone
        if ($request && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                  ->orWhere('users.last_name', 'like', '%' . $search . '%')
                  ->orWhere('users.email', 'like', '%' . $search . '%')
                  ->orWhere('users.phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by Role
        if ($request && $request->get('role') !== null && $request->get('role') !== '') {
            $query->where('users.is_role', '=', $request->get('role'));
        }

        // Filter by Status (Active/Inactive)
        if ($request && $request->get('status') !== null && $request->get('status') !== '' && $request->get('status') !== 'deleted') {
            $query->where('users.status', '=', $request->get('status'));
        }

        // Filter by Registration Date
        if ($request && !empty($request->get('start_date'))) {
            $query->whereDate('users.created_at', '>=', $request->get('start_date'));
        }
        if ($request && !empty($request->get('end_date'))) {
            $query->whereDate('users.created_at', '<=', $request->get('end_date'));
        }

        // Sorting
        $sortBy = 'users.id';
        $sortOrder = 'desc';

        if ($request && !empty($request->get('sort_by'))) {
            $allowedSorts = [
                'name' => 'users.name',
                'email' => 'users.email',
                'role' => 'users.is_role',
                'created_at' => 'users.created_at'
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

        // Order query
        if ($sortBy === 'users.name') {
            $query->orderBy('users.name', $sortOrder)->orderBy('users.last_name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination limit
        $limit = 10;
        if ($request && !empty($request->get('limit'))) {
            if (in_array((int)$request->get('limit'), [10, 20, 50])) {
                $limit = (int)$request->get('limit');
            }
        }

        return $query->paginate($limit)->withQueryString();
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
