<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@tokobuku.usk'],
            [
                'password' => Hash::make('admin123'),
                'nama' => 'Administrator Toko Buku',
                'role' => 'admin',
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'password' => Hash::make('user1234'),
                'nama' => 'Muhammad Fadhil',
                'role' => 'user',
            ]
        );

        $katSains = Kategori::firstOrCreate(
            ['nama_kategori' => 'Sains & Teknologi'],
            ['deskripsi' => 'Buku-buku seputar sains, komputasi, dan teknik.']
        );

        $katSastra = Kategori::firstOrCreate(
            ['nama_kategori' => 'Sastra & Fiksi'],
            ['deskripsi' => 'Novel, cerpen, dan karya sastra pilihan.']
        );

        $katPengembangan = Kategori::firstOrCreate(
            ['nama_kategori' => 'Pengembangan Diri'],
            ['deskripsi' => 'Buku motivasi, produktivitas, dan psikologi praktis.']
        );

        Buku::firstOrCreate(
            ['judul' => 'Laskar Pelangi'],
            [
                'kategori_id' => $katSastra->id,
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun' => 2005,
                'harga' => 85000,
                'stok' => 15,
                'deskripsi' => 'Kisah inspiratif tentang sepuluh anak di Belitung yang berjuang menggapai cita-cita.',
                'status' => 'Tersedia',
            ]
        );

        Buku::firstOrCreate(
            ['judul' => 'Bumi Manusia'],
            [
                'kategori_id' => $katSastra->id,
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun' => 1980,
                'harga' => 95000,
                'stok' => 10,
                'deskripsi' => 'Mahakarya roman tetralogi Pulau Buru berlatar kebangkitan nasional awal abad 20.',
                'status' => 'Tersedia',
            ]
        );

        Buku::firstOrCreate(
            ['judul' => 'Filosofi Teras'],
            [
                'kategori_id' => $katPengembangan->id,
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Kompas',
                'tahun' => 2018,
                'harga' => 98000,
                'stok' => 8,
                'deskripsi' => 'Penerapan filsafat Stoisisme kuno untuk mengatasi kecemasan dan stres mental modern.',
                'status' => 'Tersedia',
            ]
        );
    }
}
