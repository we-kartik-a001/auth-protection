<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subuser extends Model
{
    protected $fillable = ['name', 'role_id', 'email', 'password'];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
