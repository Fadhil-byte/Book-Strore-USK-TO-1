@extends('layouts.app')

@section('title', 'List Pesanan Masuk (Semua User) - Admin Toko Buku USK')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Daftar Pesanan Buku (Semua User)</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Monitor seluruh transaksi pemesanan buku dari pengguna yang berbeda-beda</p>
    </div>

    @if ($pendingOrdersCount > 0)
        <span class="status-badge badge-danger" style="font-size: 14px; padding: 6px 14px;">{{ $pendingOrdersCount }} Pesanan Baru Perlu Konfirmasi</span>
    @endif
</div>

<div class="card" style="padding: 18px 24px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.pesanan.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: center;">
        <input type="text" name="q" class="form-control" placeholder="Cari kode pesanan atau nama user/penerima..." value="{{ request('q') }}">

        <select name="status_pesanan" class="form-control">
            <option value="">-- Semua Status Pesanan --</option>
            <option value="Menunggu Konfirmasi" {{ request('status_pesanan') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
            <option value="Diproses" {{ request('status_pesanan') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="Dikirim" {{ request('status_pesanan') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="Selesai" {{ request('status_pesanan') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Dibatalkan" {{ request('status_pesanan') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <select name="status_pembayaran" class="form-control">
            <option value="">-- Semua Status Bayar --</option>
            <option value="Belum Dibayar" {{ request('status_pembayaran') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
            <option value="Sudah Dibayar" {{ request('status_pembayaran') == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
            <option value="Dikembalikan" {{ request('status_pembayaran') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
        </select>

        <div style="display: flex; gap: 6px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

@if ($pesanans->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">Tidak Ada Pesanan Ditemukan</h3>
        <p style="color: var(--text-muted);">Belum ada transaksi pemesanan yang sesuai dengan filter pencarian Anda.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>User Pemesan</th>
                    <th>Penerima & Alamat</th>
                    <th>Total Tagihan</th>
                    <th style="text-align: center;">Status Bayar</th>
                    <th style="text-align: center;">Status Pesanan</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanans as $p)
                    <tr style="{{ $p->status_pesanan === 'Menunggu Konfirmasi' ? 'background: #eff6ff; font-weight: 500;' : '' }}">
                        <td>
                            <strong style="color: var(--primary); font-size: 15px;">{{ $p->kode_pesanan }}</strong>
                            @if ($p->status_pesanan === 'Menunggu Konfirmasi')
                                <span class="status-badge badge-danger" style="font-size: 10px; margin-left: 4px;">Baru</span>
                            @endif
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->detailPesanan->count() }} item buku</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $p->user ? $p->user->nama : 'User Dihapus' }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $p->user ? $p->user->email : '-' }}</div>
                        </td>
                        <td>
                            <div><strong>{{ $p->nama_penerima }}</strong> ({{ $p->telepon }})</div>
                            <div style="font-size: 12px; color: var(--text-muted); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $p->alamat_pengiriman }}</div>
                        </td>
                        <td>
                            <strong style="font-size: 15px;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->metode_pembayaran }}</div>
                        </td>
                        <td style="text-align: center;">
                            @if ($p->status_pembayaran === 'Sudah Dibayar')
                                <span class="status-badge badge-success">Sudah Bayar</span>
                            @elseif ($p->status_pembayaran === 'Dikembalikan')
                                <span class="status-badge badge-secondary">Dikembalikan</span>
                            @else
                                <span class="status-badge badge-warning">Belum Bayar</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($p->status_pesanan === 'Selesai')
                                <span class="status-badge badge-success">Selesai</span>
                            @elseif ($p->status_pesanan === 'Dikirim')
                                <span class="status-badge badge-info">Dikirim</span>
                            @elseif ($p->status_pesanan === 'Diproses')
                                <span class="status-badge badge-info">Diproses</span>
                            @elseif ($p->status_pesanan === 'Dibatalkan')
                                <span class="status-badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="status-badge badge-warning">Menunggu Konfirmasi</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('pesanan.detail', $p->id) }}" class="btn btn-primary btn-sm">Kelola</a>
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

