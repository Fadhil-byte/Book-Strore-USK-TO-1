@extends('layouts.app')

@section('title', '403 Unauthorized - Akses Ditolak')

@section('content')
<div style="max-width: 650px; margin: 40px auto; text-align: center;">
    <div class="card" style="padding: 48px 32px; border: 2px solid #fecaca; background: #ffffff;">
        <div style="display: inline-block; background: #fee2e2; color: #dc2626; padding: 6px 18px; border-radius: var(--radius-full); font-size: 13px; font-weight: 700; margin-bottom: 16px;">
            HTTP 403 &bull; UNAUTHORIZED / FORBIDDEN
        </div>

        <h1 style="font-size: 28px; font-weight: 800; color: #991b1b; margin-bottom: 12px;">
            Akses Ditolak (Unauthorized)
        </h1>

        <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses Administrator untuk membuka halaman ini.' }}
        </p>

        <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 28px; font-size: 13px; color: var(--text-muted);">
            <div>Status Akun Anda Saat Ini: <strong>{{ Auth::check() ? Auth::user()->nama : 'Tamu (Belum Login)' }}</strong></div>
            <div style="margin-top: 4px;">Tingkat Akses (Role): <strong style="color: var(--primary);">{{ Auth::check() ? strtoupper(Auth::user()->role) : 'GUEST' }}</strong></div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('pages.about') }}" class="btn btn-primary btn-lg">
                &larr; Kembali ke Dashboard
            </a>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-lg">
                Katalog Buku
            </a>
        </div>
    </div>
</div>
@endsection

