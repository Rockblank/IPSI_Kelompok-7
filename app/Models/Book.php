<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{
    protected $primaryKey = 'book_id';
    protected $fillable = [
        'book_title',
        'author',
        'publisher',
        'available_stock',
        'book_status',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id');
    }
    public function cartQueues()
    {
        return $this->hasMany(CartQueue::class, 'book_id');
    }
}