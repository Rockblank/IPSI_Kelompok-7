<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartQueue extends Model
{
    protected $primaryKey = 'item_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'book_id', 'type', 'is_reminder_active'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
