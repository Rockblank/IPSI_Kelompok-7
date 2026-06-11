<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $primaryKey = 'loan_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'book_id', 'loan_date', 'due_date', 'return_date', 'transaction_status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Hitung total buku yang sedang dipinjam user (status = 'borrowed')
     */
    public static function countActiveBorrows(int $userId): int
    {
        return self::where('user_id', $userId)
            ->where('transaction_status', 'borrowed')
            ->count();
    }

    /**
     * Cek apakah user sudah meminjam buku tertentu (status = 'borrowed')
     */
    public static function isBookAlreadyBorrowed(int $userId, int $bookId): bool
    {
        return self::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('transaction_status', 'borrowed')
            ->exists();
    }
}
