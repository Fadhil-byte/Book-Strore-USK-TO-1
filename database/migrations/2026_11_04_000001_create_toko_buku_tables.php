<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategoris')) {
            Schema::create('kategoris', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 100)->unique();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('buku', function (Blueprint $table) {
            if (!Schema::hasColumn('buku', 'kategori_id')) {
                $table->foreignId('kategori_id')->nullable()->after('id')->constrained('kategoris')->nullOnDelete();
            }
            if (!Schema::hasColumn('buku', 'harga')) {
                $table->decimal('harga', 12, 2)->default(50000)->after('tahun');
            }
            if (!Schema::hasColumn('buku', 'stok')) {
                $table->integer('stok')->default(10)->after('harga');
            }
            if (!Schema::hasColumn('buku', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('stok');
            }
            if (!Schema::hasColumn('buku', 'gambar')) {
                $table->string('gambar', 255)->nullable()->after('deskripsi');
            }
        });

        if (!Schema::hasTable('keranjangs')) {
            Schema::create('keranjangs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('buku_id')->constrained('buku')->cascadeOnDelete();
                $table->integer('jumlah')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pesanans')) {
            Schema::create('pesanans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pesanan', 50)->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('nama_penerima', 100);
                $table->string('telepon', 30);
                $table->text('alamat_pengiriman');
                $table->string('metode_pembayaran', 50)->default('Payment at Delivery');
                $table->enum('status_pembayaran', ['Belum Dibayar', 'Sudah Dibayar', 'Dikembalikan'])->default('Belum Dibayar');
                $table->enum('status_pesanan', ['Menunggu Konfirmasi', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'])->default('Menunggu Konfirmasi');
                $table->decimal('total_harga', 14, 2)->default(0);
                $table->text('catatan')->nullable();
                $table->string('bukti_pembayaran', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('detail_pesanans')) {
            Schema::create('detail_pesanans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
                $table->foreignId('buku_id')->constrained('buku')->cascadeOnDelete();
                $table->integer('jumlah')->default(1);
                $table->decimal('harga', 12, 2)->default(0);
                $table->decimal('subtotal', 14, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pesan_kontaks')) {
            Schema::create('pesan_kontaks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('nama', 100);
                $table->string('email', 100);
                $table->string('subjek', 150);
                $table->text('pesan');
                $table->enum('status', ['Belum Dibaca', 'Sudah Dibaca'])->default('Belum Dibaca');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_kontaks');
        Schema::dropIfExists('detail_pesanans');
        Schema::dropIfExists('pesanans');
        Schema::dropIfExists('keranjangs');

        Schema::table('buku', function (Blueprint $table) {
            if (Schema::hasColumn('buku', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
            if (Schema::hasColumn('buku', 'harga')) {
                $table->dropColumn('harga');
            }
            if (Schema::hasColumn('buku', 'stok')) {
                $table->dropColumn('stok');
            }
            if (Schema::hasColumn('buku', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('buku', 'gambar')) {
                $table->dropColumn('gambar');
            }
        });

        Schema::dropIfExists('kategoris');
    }
};

