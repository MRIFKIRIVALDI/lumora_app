<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'phone',
        'is_active',
        'identity_number', 'gender', 'birth_place', 'birth_date', 'address', 'bio', 'occupation',
        'emergency_contact_name', 'emergency_contact_phone', 'photo',
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
            'is_active' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'birth_date' => 'date',
        ];
    }

    public function isRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'parent_student', 'student_id', 'parent_id');
    }
}
