@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Buku USK')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800;">Keranjang Belanja</h1>
    <p style="color: var(--text-muted); font-size: 14px;">Kelola buku yang akan Anda beli sebelum melanjutkan ke pembayaran</p>
</div>

@if ($items->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">Keranjang Belanja Anda Kosong</h3>
        <p style="color: var(--text-muted); margin-bottom: 24px;">Temukan buku-buku menarik dari katalog kami dan tambahkan ke keranjang.</p>
        <a href="{{ route('buku.index') }}" class="btn btn-primary btn-lg">Jelajahi Katalog Buku</a>
    </div>
@else
    <div class="cart-layout">
        <div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th style="width: 140px; text-align: center;">Harga</th>
                            <th style="width: 130px; text-align: center;">Jumlah</th>
                            <th style="width: 140px; text-align: right;">Subtotal</th>
                            <th style="width: 60px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; gap: 14px; align-items: center;">
                                        <div style="width: 50px; height: 65px; border-radius: var(--radius-sm); overflow: hidden; background: #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                            @if ($item->buku->gambar && file_exists(public_path($item->buku->gambar)))
                                                <img src="{{ asset($item->buku->gambar) }}" alt="{{ $item->buku->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted);">USK</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('buku.show', $item->buku_id) }}" style="font-weight: 700; color: var(--text-main); font-size: 15px; display: block;">{{ $item->buku->judul }}</a>
                                            <span style="font-size: 12px; color: var(--text-muted);">Penulis: {{ $item->buku->penulis }}</span>
                                            <div style="font-size: 11px; color: #10b981; margin-top: 2px;">Stok tersedia: {{ $item->buku->stok }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    Rp {{ number_format($item->buku->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('keranjang.update', $item->id) }}" style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        @csrf
                                        <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" max="{{ $item->buku->stok }}" class="qty-input" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td style="text-align: right; font-weight: 800; color: var(--primary);">
                                    Rp {{ number_format($item->jumlah * $item->buku->harga, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center;">
                                    <form method="POST" action="{{ route('keranjang.remove', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger); padding: 4px 8px;" title="Hapus Item">&times;</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Tambah Buku Lainnya</a>
            </div>
        </div>

        <div>
            <div class="card" style="padding: 24px; position: sticky; top: 80px;">
                <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Ringkasan Belanja</h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-muted);">
                    <span>Total Item</span>
                    <strong style="color: var(--text-main);">{{ $items->sum('jumlah') }} pcs</strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 16px; border-top: 1px dashed var(--border-color); padding-top: 16px;">
                    <span style="font-weight: 700;">Total Tagihan</span>
                    <strong style="font-size: 22px; font-weight: 800; color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <a href="{{ route('pesanan.checkout') }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                    Lanjut ke Pembayaran &rarr;
                </a>

                <div style="font-size: 12px; color: var(--text-muted); text-align: center;">
                    Pembayaran saat pengiriman (Payment at Delivery / COD)
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

