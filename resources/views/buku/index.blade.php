@extends('layouts.app')

@section('title', 'Katalog Buku - Toko Buku USK')

@section('content')
<div style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 800;">Katalog Buku</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Klik pada kartu buku untuk melihat informasi lengkap dan memasukkan ke keranjang belanja</p>
        </div>

        <form method="GET" action="{{ route('buku.index') }}" style="display: flex; gap: 8px; align-items: center;">
            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            @if (request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <select name="sort" class="form-control" style="width: auto; padding: 8px 14px; font-size: 13px;" onchange="this.form.submit()">
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga: Termahal</option>
                <option value="judul_asc" {{ request('sort') == 'judul_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
            </select>
        </form>
    </div>
</div>

<div class="card" style="padding: 16px 20px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('buku.index') }}" style="display: flex; gap: 10px;">
        @if (request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <input type="text" name="q" class="form-control" placeholder="Cari buku berdasarkan judul, nama penulis, atau penerbit..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Cari Buku</button>
        @if (request('q') || request('kategori') || request('sort'))
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="filter-pills" style="margin-bottom: 24px;">
    <a href="{{ route('buku.index', array_merge(request()->except('kategori', 'page'))) }}" class="filter-pill {{ !request('kategori') ? 'active' : '' }}">
        Semua Kategori
    </a>
    @foreach ($kategoris as $kat)
        <a href="{{ route('buku.index', array_merge(request()->except('page'), ['kategori' => $kat->id])) }}" class="filter-pill {{ request('kategori') == $kat->id ? 'active' : '' }}">
            {{ $kat->nama_kategori }} ({{ $kat->buku_count }})
        </a>
    @endforeach
</div>

@if ($buku->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">Tidak Ada Buku Ditemukan</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">Kata kunci atau kategori yang Anda pilih belum memiliki data buku.</p>
        <a href="{{ route('buku.index') }}" class="btn btn-secondary">Reset Pencarian</a>
    </div>
@else
    <div class="tokopedia-grid">
        @foreach ($buku as $item)
            <a href="{{ route('buku.show', $item->id) }}" class="tokopedia-card" title="Klik untuk melihat detail & pesan">
                <div class="tokopedia-thumb">
                    @if ($item->gambar && file_exists(public_path($item->gambar)))
                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->judul }}">
                    @else
                        <div style="text-align: center; color: var(--text-muted); padding: 16px; font-weight: 600; font-size: 13px;">
                            BukuKita USK
                        </div>
                    @endif
                </div>

                <div class="tokopedia-body">
                    <div class="tokopedia-title" title="{{ $item->judul }}">{{ $item->judul }}</div>
                    <div class="tokopedia-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="tokopedia-meta">Penulis: {{ $item->penulis }}</div>
                    <div class="tokopedia-meta">{{ $item->kategori ? $item->kategori->nama_kategori : 'Umum' }} &bull; {{ $item->tahun }}</div>

                    <div class="tokopedia-footer">
                        <span>Banda Aceh</span>
                        @if ($item->stok > 0)
                            <span class="tokopedia-badge-stock in">Sisa {{ $item->stok }}</span>
                        @else
                            <span class="tokopedia-badge-stock out">Habis</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $buku->links() }}
    </div>
@endif
@endsection
