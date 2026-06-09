# E-Library Project Documentation

## Ringkasan Proyek
Aplikasi E-Library ini adalah sistem perpustakaan berbasis web yang dibangun dengan Laravel. Aplikasi memungkinkan:
- user mendaftar, login, melihat katalog buku, meminjam buku, dan melihat riwayat peminjaman
- admin mengelola data buku, melihat status peminjaman seluruh user, dan menandai buku sebagai dikembalikan
- notifikasi dikirim ke user untuk buku yang kembali tersedia dan untuk denda keterlambatan

## Fitur Utama

### User
- registrasi dan login
- melihat dashboard buku
- mencari buku berdasarkan judul dan penulis
- melihat detail buku
- menambahkan buku ke keranjang pinjam
- mengantri notifikasi jika buku habis
- konfirmasi peminjaman buku dari keranjang
- melihat riwayat pinjaman berdasarkan status `borrowed` dan `returned`
- melihat notifikasi

### Admin
- akses panel admin untuk mengelola buku
- CRUD buku (buat, baca, update, hapus)
- melihat semua peminjaman user di halaman `admin/loans`
- menandai pinjaman sebagai dikembalikan
- stok buku otomatis diperbarui ketika buku kembali
- pemberitahuan queue otomatis dikirim setelah buku kembali tersedia

## Arsitektur dan Alur

### Auth dan Middleware
- `AuthController` menangani login, register, logout, dan redirect pengguna berdasarkan role
- `CheckAuth` memastikan user sudah login sebelum akses route yang dilindungi
- `CheckRole` memeriksa role admin untuk route admin, dan menolak akses jika role tidak sesuai
- role dibaca dari session: `user_id`, `user_name`, `user_email`, `role`

### Routing Utama
- Guest routes:
  - `GET /login` → halaman login
  - `POST /login` → proses login
  - `GET /register` → halaman register
  - `POST /register` → proses registrasi
- User routes (`auth.check`):
  - `GET /dashboard` → halaman utama user
  - `GET /dashboard/search` → hasil pencarian buku
  - `GET /books/{book}` → detail buku
  - `GET /cart` → halaman keranjang dan antrean notifikasi
  - `POST /cart/add/{book}` → tambah buku ke keranjang
  - `POST /cart/queue/{book}` → aktifkan notifikasi antrean ketika buku habis
  - `DELETE /cart/{item}` → hapus item dari keranjang/antrean
  - `GET /loans/confirm` → halaman konfirmasi peminjaman
  - `POST /loans` → proses peminjaman
  - `GET /history` → riwayat peminjaman user
  - `GET /notifications` → daftar notifikasi user
- Admin routes (`auth.check`, `role:admin`, prefix `admin`):
  - `resource('books')` → CRUD buku admin
  - `GET /admin/loans` → daftar semua peminjaman
  - `POST /admin/loans/{loan_id}/return` → tandai pinjaman kembali

## Model dan Relasi

### `User`
- primary key: `user_id`
- kolom: `full_name`, `email`, `password`, `role`
- relasi:
  - `hasMany(Loan::class)`
  - `hasMany(CartQueue::class)`
  - `hasMany(Notification::class)`

### `Book`
- primary key: `book_id`
- kolom: `book_title`, `author`, `publisher`, `available_stock`, `book_status`
- relasi:
  - `hasMany(Loan::class)`
  - `hasMany(CartQueue::class)`

### `Loan`
- primary key: `loan_id`
- kolom: `user_id`, `book_id`, `loan_date`, `due_date`, `return_date`, `transaction_status`
- relasi:
  - `belongsTo(User::class)`
  - `belongsTo(Book::class)`
- status: `borrowed` atau `returned`

### `CartQueue`
- table: `carts_queues`
- kolom: `user_id`, `book_id`, `type`, `is_reminder_active`
- `type` digunakan untuk membedakan item `cart` dan `queue`

### `Notification`
- primary key: `notification_id`
- kolom: `user_id`, `message`, `sent_at`, `is_read`
- relasi: `belongsTo(User::class)`

## Alur Peminjaman

1. User menambahkan buku ke keranjang (`type = cart`)
2. User konfirmasi peminjaman di `/loans/confirm`
3. Aplikasi membuat entri `Loan` untuk setiap buku dalam keranjang
4. `available_stock` buku dikurangi
5. Jika stok habis, `book_status` diubah menjadi `habis`
6. Keranjang dihapus setelah peminjaman berhasil
7. User dapat melihat status pinjaman di halaman `/history`

## Alur Pengembalian Admin

1. Admin membuka `/admin/loans`
2. Admin menandai pinjaman sebagai `Kembali`
3. Sistem memperbarui `transaction_status` menjadi `returned` dan mengisi `return_date`
4. Stok buku (`available_stock`) ditambah
5. Jika buku sebelumnya `habis`, `book_status` diubah menjadi `tersedia`
6. `CartController::notifyQueue($bookId)` dipanggil untuk mengirim notifikasi antrean

## Fitur Notifikasi dan Queue

- User dapat mengantri notifikasi dengan `POST /cart/queue/{book}` jika buku sedang habis
- Ketika buku kembali tersedia melalui update stok admin atau pengembalian admin, sistem:
  - membuat entri `Notification`
  - mengubah item antrean dari `type = queue` menjadi `type = cart`
  - nonaktifkan reminder agar item siap diproses peminjaman
- Ada command artisan:
  - `php artisan notifications:overdue`
  - mengirim notifikasi denda keterlambatan untuk pinjaman yang sudah melewati `due_date`
  - denda dihitung Rp2.000 per hari terlambat

## Struktur View

### Halaman User
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/dashboard/index.blade.php`
- `resources/views/dashboard/search.blade.php`
- `resources/views/dashboard/show.blade.php`
- `resources/views/cart/index.blade.php`
- `resources/views/loans/confirm.blade.php`
- `resources/views/history/index.blade.php`
- `resources/views/notifications/index.blade.php`

### Halaman Admin
- `resources/views/layouts/admin.blade.php`
- `resources/views/admin/books/index.blade.php`
- `resources/views/admin/books/create.blade.php`
- `resources/views/admin/books/edit.blade.php`
- `resources/views/admin/loans/index.blade.php`

## Catatan Penggunaan

- Akses admin hanya untuk user dengan role `admin`
- Default redirect admin setelah login ke `admin.books.index`
- User default diarahkan ke `dashboard`
- Pastikan session auth aktif untuk akses halaman user/admin

## File Konfigurasi Penting
- `routes/web.php` — mendefinisikan semua route aplikasi
- `app/Http/Controllers/AuthController.php` — login/register/logout, role redirect
- `app/Http/Controllers/CartController.php` — keranjang, antrean notifikasi, dan helper `notifyQueue`
- `app/Http/Controllers/LoanController.php` — proses peminjaman user
- `app/Http/Controllers/Admin/LoanController.php` — kelola status pengembalian
- `app/Http/Controllers/Admin/BookController.php` — CRUD buku admin
- `app/Http/Middleware/CheckAuth.php` / `CheckRole.php` — proteksi akses

## Pengembangan dan Perbaikan
- Halaman `dashboard`, pencarian, dan detail buku kini dapat diakses oleh guest tanpa login
- Aksi perubahan data (`tambah ke keranjang`, `daftar notifikasi`, `konfirmasi pinjam`) tetap dilindungi oleh login
- Jika guest klik `Tambah ke Keranjang` atau akses halaman peminjaman, intent disimpan dan setelah login sistem akan melanjutkan proses
- Batas maksimal 10 buku di keranjang dipastikan di `CartController::addBookToCart`
- Navbar publik sekarang menampilkan tombol `Masuk` dan `Daftar` untuk guest
- Navbar admin sekarang memiliki tombol `Buku` dan `Peminjaman` untuk akses cepat
- Admin bisa langsung memantau dan menyelesaikan peminjaman dari panel admin
- Notifikasi antrean otomatis saat buku kembali tersedia

## Cara Menjalankan
1. `composer install`
2. `cp .env.example .env` lalu atur database
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `php artisan serve`

---

Dokumentasi ini dibuat untuk memberikan gambaran lengkap tentang fitur, alur, dan struktur aplikasi E-Library di repositori ini.
