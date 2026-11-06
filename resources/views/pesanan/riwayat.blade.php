@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - Toko Buku USK')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800;">Riwayat Pesanan Buku Saya</h1>
    <p style="color: var(--text-muted); font-size: 14px;">Pantau status konfirmasi, pembayaran, dan pengiriman buku Anda</p>
</div>

@if ($pesanans->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">Belum Ada Riwayat Pesanan</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">Anda belum melakukan pemesanan buku apa pun.</p>
        <a href="{{ route('buku.index') }}" class="btn btn-primary">Mulai Belanja Buku</a>
    </div>
@else
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Tanggal</th>
                    <th>Penerima</th>
                    <th>Total Tagihan</th>
                    <th style="text-align: center;">Status Bayar</th>
                    <th style="text-align: center;">Status Pesanan</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanans as $p)
                    <tr>
                        <td>
                            <strong style="color: var(--primary);">{{ $p->kode_pesanan }}</strong>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $p->detailPesanan->count() }} jenis buku</div>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $p->created_at->format('d M Y, H:i') }}
                        </td>
                        <td>
                            <div>{{ $p->nama_penerima }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $p->telepon }}</div>
                        </td>
                        <td>
                            <strong style="font-size: 15px;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->metode_pembayaran }}</div>
                        </td>
                        <td style="text-align: center;">
                            @if ($p->status_pembayaran === 'Sudah Dibayar')
                                <span class="status-badge badge-success">Lunas</span>
                            @elseif ($p->status_pembayaran === 'Dikembalikan')
                                <span class="status-badge badge-secondary">Dikembalikan</span>
                            @else
                                <span class="status-badge badge-warning">Belum Lunas</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($p->status_pesanan === 'Selesai')
                                <span class="status-badge badge-success">Selesai</span>
                            @elseif ($p->status_pesanan === 'Dikirim')
                                <span class="status-badge badge-info">Sedang Dikirim</span>
                            @elseif ($p->status_pesanan === 'Diproses')
                                <span class="status-badge badge-info">Diproses</span>
                            @elseif ($p->status_pesanan === 'Dibatalkan')
                                <span class="status-badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="status-badge badge-warning">Menunggu Konfirmasi</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('pesanan.detail', $p->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                                @if ($p->status_pesanan === 'Menunggu Konfirmasi')
                                    <form method="POST" action="{{ route('pesanan.batalkan', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $pesanans->links() }}
    </div>
@endif
@endsection

