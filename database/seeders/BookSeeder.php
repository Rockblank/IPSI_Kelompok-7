<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 1. PASTIKAN IMPORT INI ADA

class BookSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Book::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $books = [
            [
                'book_title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'available_stock' => 2,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Kisah Kapal Karam Laut Jawa',
                'author' => 'Aditya Nugroho',
                'publisher' => 'UB Press',
                'available_stock' => 1,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Meniti Ombak Selat Sunda',
                'author' => 'Siti Aminah',
                'publisher' => 'Erlangga',
                'available_stock' => 0,
                'book_status' => 'not_available'
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}