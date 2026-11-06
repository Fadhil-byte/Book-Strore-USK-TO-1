<?php

use App\Http\Controllers\AdminPesanController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/regist', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/regist', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/about', [PageController::class, 'about'])->name('pages.about');
    Route::get('/katalog', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku', fn() => redirect()->route('buku.index'));
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
    Route::post('/contact', [PageController::class, 'storeContact'])->name('pages.store_contact');

    Route::get('/cart', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/cart/add/{bukuId}', [KeranjangController::class, 'add'])->name('keranjang.add');
    Route::post('/cart/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::post('/cart/remove/{id}', [KeranjangController::class, 'remove'])->name('keranjang.remove');

    Route::get('/checkout', [PesananController::class, 'checkout'])->name('pesanan.checkout');
    Route::post('/checkout', [PesananController::class, 'storeCheckout'])->name('pesanan.store_checkout');
    Route::get('/pesanan-saya', [PesananController::class, 'riwayat'])->name('pesanan.riwayat');
    Route::get('/pesanan/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');
    Route::post('/pesanan/{id}/bukti', [PesananController::class, 'uploadBukti'])->name('pesanan.upload_bukti');
    Route::post('/pesanan/{id}/batalkan', [PesananController::class, 'batalkan'])->name('pesanan.batalkan');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::post('/kategori/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');
        Route::post('/kategori/{id}/delete', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::post('/buku/{id}/update', [BukuController::class, 'update'])->name('buku.update');
        Route::post('/buku/{id}/delete', [BukuController::class, 'destroy'])->name('buku.destroy');

        Route::get('/pesanan', [PesananController::class, 'adminIndex'])->name('pesanan.index');
        Route::post('/pesanan/{id}/status', [PesananController::class, 'adminUpdateStatus'])->name('pesanan.update_status');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/delete', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/pesan', [AdminPesanController::class, 'index'])->name('pesan.index');
        Route::get('/pesan/{id}', [AdminPesanController::class, 'show'])->name('pesan.show');
        Route::post('/pesan/{id}/balas', [AdminPesanController::class, 'balas'])->name('pesan.balas');
        Route::post('/pesan/{id}/delete', [AdminPesanController::class, 'destroy'])->name('pesan.destroy');
    });
});
