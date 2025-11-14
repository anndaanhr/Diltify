# Diltify — Aplikasi Web Pemutar Musik

**Diltify** adalah aplikasi web pemutar musik sederhana yang terinspirasi dari Spotify dan SoundCloud.  
Aplikasi ini dibangun menggunakan **Laravel 10** dan **PostgreSQL** (melalui Laragon) dengan integrasi **iTunes Search API** sebagai sumber data lagu dan preview berdurasi 30 detik.


## Teknologi yang Digunakan
- Laravel 10  
- PostgreSQL  
- Blade Template + TailwindCSS  
- iTunes Search API  


## Fitur Utama
1. **Autentikasi Manual**  
   Register, login, dan logout tanpa Laravel Breeze atau Jetstream.  
   Password di-hash menggunakan `Hash::make()` dan halaman tertentu dilindungi oleh middleware `auth`.

2. **CRUD Utama**  
   - **Playlist:** tambah, tampilkan, ubah, dan hapus playlist.  
   - **Favorite Lagu:** simpan lagu ke daftar favorit, ubah catatan, atau hapus lagu.  
   - **Lagu dalam Playlist:** tambahkan lagu dari hasil pencarian ke playlist, tampilkan daftar lagu dalam playlist, dan hapus lagu dari playlist.

3. **Integrasi API iTunes**  
   Halaman pencarian lagu menampilkan judul, artis, cover album, dan tombol untuk memutar preview 30 detik.  
   Lagu dapat ditambahkan ke playlist atau daftar favorit.

4. **Enkripsi dan Error Handling**  
   Data sensitif seperti nama playlist dan email user dienkripsi dengan  
   `Crypt::encryptString()` dan `Crypt::decryptString()`.  
   Semua error ditangani dengan `try...catch` dan diarahkan ke halaman error custom yang ramah pengguna.


## Tujuan Proyek
Proyek ini dikembangkan untuk memahami konsep autentikasi manual, implementasi CRUD relasional antar tabel, integrasi API eksternal, serta enkripsi data menggunakan Laravel dan PostgreSQL.  
