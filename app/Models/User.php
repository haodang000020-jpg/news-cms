<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function posts()
    {
        return $this->hasMany(
            Post::class,
            'author_id'
        );
    }

    public function hasRole($roleCode)
    {
        return $this->role?->code === $roleCode;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role?->code, $roles);
    }

    public function hasPermission($permissionCode)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions()
            ->where('code', $permissionCode)
            ->exists();
    }
}