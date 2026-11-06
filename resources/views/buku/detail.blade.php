@extends('layouts.app')

@section('title', $buku->judul . ' - Toko Buku USK')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Katalog</a>
</div>

<div class="card" style="padding: 32px;">
    <div class="book-detail-layout">
        <div>
            <div style="width: 100%; height: 380px; border-radius: var(--radius); overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                @if ($buku->gambar && file_exists(public_path($buku->gambar)))
                    <img src="{{ asset($buku->gambar) }}" alt="{{ $buku->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="text-align: center; color: var(--text-muted); font-weight: 600;">
                        <strong>{{ $buku->judul }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="book-category" style="font-size: 14px; margin-bottom: 8px;">{{ $buku->kategori ? $buku->kategori->nama_kategori : 'Umum' }}</div>
            <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 12px; line-height: 1.3;">{{ $buku->judul }}</h1>

            <div style="display: flex; gap: 16px; margin-bottom: 20px; font-size: 14px; color: var(--text-muted); flex-wrap: wrap;">
                <div>Penulis: <strong style="color: var(--text-main);">{{ $buku->penulis }}</strong></div>
                <div>&bull;</div>
                <div>Penerbit: <strong style="color: var(--text-main);">{{ $buku->penerbit }}</strong></div>
                <div>&bull;</div>
                <div>Tahun: <strong style="color: var(--text-main);">{{ $buku->tahun }}</strong></div>
            </div>

            <div style="background: var(--background); padding: 18px 24px; border-radius: var(--radius); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--border-color);">
                <div>
                    <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Harga Buku</span>
                    <div style="font-size: 26px; font-weight: 800; color: var(--primary);">Rp {{ number_format($buku->harga, 0, ',', '.') }}</div>
                </div>

                <div>
                    <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Status Stok</span>
                    <div>
                        @if ($buku->stok > 0)
                            <span class="status-badge badge-success">Tersedia ({{ $buku->stok }} pcs)</span>
                        @else
                            <span class="status-badge badge-danger">Stok Habis</span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 28px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 8px;">Deskripsi / Sinopsis</h3>
                <p style="color: var(--text-muted); font-size: 15px; line-height: 1.7;">
                    {{ $buku->deskripsi ?: 'Belum ada deskripsi untuk buku ini.' }}
                </p>
            </div>

            <div>
                @auth
                    @if (Auth::user()->role === 'user')
                        @if ($buku->stok > 0)
                            <form method="POST" action="{{ route('keranjang.add', $buku->id) }}" style="display: flex; gap: 12px; align-items: center;">
                                @csrf
                                <div style="width: 100px;">
                                    <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">Jumlah</label>
                                    <input type="number" name="jumlah" value="1" min="1" max="{{ $buku->stok }}" class="form-control" style="text-align: center;">
                                </div>
                                <div style="flex: 1; padding-top: 18px;">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Masukkan ke Keranjang</button>
                                </div>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg btn-block" disabled>Maaf, Stok Buku Ini Sedang Habis</button>
                        @endif
                    @else
                        <div style="display: flex; gap: 12px;">
                            <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-primary btn-lg">Edit Data Buku Ini</a>
                            <form method="POST" action="{{ route('admin.buku.destroy', $buku->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg">Hapus Buku</button>
                            </form>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">Masuk untuk Membeli Buku Ini</a>
                @endauth
            </div>
        </div>
    </div>
</div>

@if ($terkait->isNotEmpty())
    <div style="margin-top: 40px;">
        <h3 class="section-title" style="margin-bottom: 20px;">Buku Terkait Lainnya</h3>
        <div class="tokopedia-grid">
            @foreach ($terkait as $t)
                <a href="{{ route('buku.show', $t->id) }}" class="tokopedia-card">
                    <div class="tokopedia-thumb">
                        @if ($t->gambar && file_exists(public_path($t->gambar)))
                            <img src="{{ asset($t->gambar) }}" alt="{{ $t->judul }}">
                        @else
                            <div style="text-align: center; color: var(--text-muted); padding: 12px; font-weight: 600; font-size: 13px;">
                                BukuKita USK
                            </div>
                        @endif
                    </div>
                    <div class="tokopedia-body">
                        <div class="tokopedia-title">{{ $t->judul }}</div>
                        <div class="tokopedia-price">Rp {{ number_format($t->harga, 0, ',', '.') }}</div>
                        <div class="tokopedia-meta">Penulis: {{ $t->penulis }}</div>
                        <div class="tokopedia-footer">
                            <span>Banda Aceh</span>
                            @if ($t->stok > 0)
                                <span class="tokopedia-badge-stock in">Sisa {{ $t->stok }}</span>
                            @else
                                <span class="tokopedia-badge-stock out">Habis</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection

