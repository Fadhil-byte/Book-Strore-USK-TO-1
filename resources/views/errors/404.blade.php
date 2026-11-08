@extends('layouts.app')

@section('title', '404 Tidak Ditemukan - Toko Buku USK')

@section('content')
<div style="max-width: 650px; margin: 40px auto; text-align: center;">
    <div class="card" style="padding: 48px 32px; border: 2px solid #fed7aa; background: #ffffff;">
        <div style="display: inline-block; background: #ffedd5; color: #c2410c; padding: 6px 18px; border-radius: var(--radius-full); font-size: 13px; font-weight: 700; margin-bottom: 16px;">
            HTTP 404 &bull; HALAMAN TIDAK DITEMUKAN
        </div>

        <h1 style="font-size: 28px; font-weight: 800; color: #9a3412; margin-bottom: 12px;">
            Data atau Halaman Tidak Ditemukan
        </h1>

        <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
            {{ $exception->getMessage() ?: 'Maaf, judul buku atau tautan halaman yang Anda cari tidak tersedia di dalam basis data katalog Toko Buku USK.' }}
        </p>

        <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 28px; font-size: 13px; color: var(--text-muted); text-align: left; line-height: 1.6;">
            <strong style="color: var(--text-main); display: block; margin-bottom: 6px;">Kemungkinan Penyebab:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Nomor identitas (ID) buku yang diminta tidak terdaftar di sistem.</li>
                <li>Buku telah ditarik atau dinonaktifkan oleh administrator toko.</li>
                <li>Terdapat kesalahan penulisan alamat URL pada peramban web.</li>
            </ul>
        </div>

        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('buku.index') }}" class="btn btn-primary btn-lg">
                &larr; Lihat Katalog Buku
            </a>
            @auth
                <a href="{{ route('pages.about') }}" class="btn btn-secondary btn-lg">
                    Dashboard
                </a>
            @else
                <a href="{{ route('landing') }}" class="btn btn-secondary btn-lg">
                    Halaman Utama
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection

