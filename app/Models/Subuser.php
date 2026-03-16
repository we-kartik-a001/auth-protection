<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Subuser extends Authenticatable
{
    protected $fillable = ['name', 'role_id', 'email', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function hasPermission(string $permissionSlug, string $table): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('slug', $permissionSlug)
            ->wherePivot('table_name', $table)
            ->exists();
    }
}
