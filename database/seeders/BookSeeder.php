<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

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
            ],
            [
                'book_title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'available_stock' => 5,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'available_stock' => 3,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Kompas Penerbit Buku',
                'available_stock' => 4,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'publisher' => 'Gramedia Pustaka Utama',
                'available_stock' => 2,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'available_stock' => 6,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Pulang',
                'author' => 'Tere Liye',
                'publisher' => 'Sabak Grip Nusantara',
                'available_stock' => 0,
                'book_status' => 'not_available'
            ],
            [
                'book_title' => 'Dilan: Dia adalah Dilanku Tahun 1990',
                'author' => 'Pidi Baiq',
                'publisher' => 'Pastel Books',
                'available_stock' => 3,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'available_stock' => 1,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Sebuah Seni untuk Bersikap Bodo Amat',
                'author' => 'Mark Manson',
                'publisher' => 'Grasindo',
                'available_stock' => 4,
                'book_status' => 'available'
            ],
            [
                'book_title' => 'Negeri 5 Menara',
                'author' => 'A. Fuadi',
                'publisher' => 'Gramedia Pustaka Utama',
                'available_stock' => 0,
                'book_status' => 'not_available'
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}