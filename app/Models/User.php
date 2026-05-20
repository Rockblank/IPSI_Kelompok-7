<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['full_name', 'email', 'password', 'role'];
    protected $hidden = ['password'];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'user_id');
    }
    public function cartQueues()
    {
        return $this->hasMany(CartQueue::class, 'user_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
