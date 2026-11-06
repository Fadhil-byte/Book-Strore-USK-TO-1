@extends('layouts.app')

@section('title', 'Detail Pesanan: ' . $pesanan->kode_pesanan . ' - Toko Buku USK')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar Semua Pesanan</a>
        @else
            <a href="{{ route('pesanan.riwayat') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Riwayat Pesanan</a>
        @endif
        <h1 style="font-size: 24px; font-weight: 800; margin-top: 12px;">Pesanan #{{ $pesanan->kode_pesanan }}</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Dibuat pada {{ $pesanan->created_at->format('d F Y, H:i') }} WIB</p>
    </div>

    <div style="display: flex; gap: 8px;">
        @if ($pesanan->status_pembayaran === 'Sudah Dibayar')
            <span class="status-badge badge-success" style="font-size: 14px; padding: 6px 14px;">Pembayaran: Lunas</span>
        @elseif ($pesanan->status_pembayaran === 'Dikembalikan')
            <span class="status-badge badge-secondary" style="font-size: 14px; padding: 6px 14px;">Pembayaran: Dikembalikan</span>
        @else
            <span class="status-badge badge-warning" style="font-size: 14px; padding: 6px 14px;">Pembayaran: Menunggu</span>
        @endif

        @if ($pesanan->status_pesanan === 'Dibatalkan')
            <span class="status-badge badge-danger" style="font-size: 14px; padding: 6px 14px;">Status: Dibatalkan</span>
        @elseif ($pesanan->status_pesanan === 'Selesai')
            <span class="status-badge badge-success" style="font-size: 14px; padding: 6px 14px;">Status: Selesai</span>
        @else
            <span class="status-badge badge-info" style="font-size: 14px; padding: 6px 14px;">Status: {{ $pesanan->status_pesanan }}</span>
        @endif
    </div>
</div>

<div class="cart-layout">
    <div>
        <div class="card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Item Buku yang Dipesan</h3>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th style="width: 130px; text-align: center;">Harga</th>
                            <th style="width: 80px; text-align: center;">Jumlah</th>
                            <th style="width: 140px; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan->detailPesanan as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">{{ $item->buku->judul }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">Penulis: {{ $item->buku->penulis }}</div>
                                </td>
                                <td style="text-align: center;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td style="text-align: center; font-weight: 600;">{{ $item->jumlah }}</td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                <div style="width: 280px;">
                    <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 6px;">
                        <span>Total Tagihan:</span>
                        <strong style="font-size: 18px; color: var(--primary);">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Detail Pengiriman</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Penerima</span>
                    <strong>{{ $pesanan->nama_penerima }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Telepon / WhatsApp</span>
                    <strong>{{ $pesanan->telepon }}</strong>
                </div>
                <div style="grid-column: span 2;">
                    <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Alamat Pengiriman</span>
                    <p style="margin-top: 2px;">{{ $pesanan->alamat_pengiriman }}</p>
                </div>
                @if ($pesanan->catatan)
                    <div style="grid-column: span 2;">
                        <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Catatan</span>
                        <p style="font-style: italic; color: var(--text-muted);">"{{ $pesanan->catatan }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Metode & Pembayaran</h3>

            <div style="margin-bottom: 16px;">
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Metode Dipilih</span>
                <strong>{{ $pesanan->metode_pembayaran }}</strong>
            </div>

            @if ($pesanan->metode_pembayaran === 'Payment at Delivery' || $pesanan->metode_pembayaran === 'Transfer Bank')
                <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 20px; font-size: 13px;">
                    <strong style="display: block; margin-bottom: 6px; color: var(--primary);">Instruksi Pembayaran:</strong>
                    Silakan transfer pembayaran senilai <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong> ke salah satu rekening berikut sebelum buku dikirim:
                    <ul style="margin: 8px 0 0 18px; line-height: 1.6;">
                        <li><strong>Bank BSI:</strong> 7123456789 (a.n Toko Buku USK)</li>
                        <li><strong>Bank BRI:</strong> 0012-01-001234-50-1 (a.n Toko Buku USK)</li>
                    </ul>
                </div>

                @if ($pesanan->bukti_pembayaran && file_exists(public_path($pesanan->bukti_pembayaran)))
                    <div style="margin-bottom: 16px;">
                        <span style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 6px;">Bukti Pembayaran Terunggah:</span>
                        <a href="{{ asset($pesanan->bukti_pembayaran) }}" target="_blank">
                            <img src="{{ asset($pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 200px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        </a>
                        @if ($pesanan->status_pesanan === 'Menunggu Konfirmasi')
                            <div style="margin-top: 8px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-sm); padding: 10px 12px; font-size: 12px; color: #1e40af;">
                                <strong>Status: Menunggu Konfirmasi</strong><br>
                                Bukti transfer berhasil dikirim. Menunggu verifikasi dan konfirmasi dari Administrator sebelum pesanan diproses.
                            </div>
                        @endif
                    </div>
                @endif

                @if (Auth::user()->role === 'user' && $pesanan->status_pembayaran === 'Belum Dibayar' && $pesanan->status_pesanan !== 'Dibatalkan')
                    <form method="POST" action="{{ route('pesanan.upload_bukti', $pesanan->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="bukti_pembayaran" style="font-size: 13px;">
                                {{ $pesanan->bukti_pembayaran ? 'Unggah Ulang Bukti Transfer' : 'Unggah Bukti Transfer' }}
                            </label>
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ $pesanan->bukti_pembayaran ? 'Kirim Ulang Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                        </button>
                    </form>
                @endif
            @else
                <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; font-size: 13px;">
                    Pembayaran tunai (Cash on Delivery) disiapkan saat kurir mengantarkan buku ke alamat Anda.
                </div>
            @endif
        </div>

        @if ($pesanan->status_pesanan === 'Dibatalkan')
            <div class="card" style="padding: 20px; border: 1px solid #fecaca; background: #fef2f2; margin-bottom: 24px;">
                <strong style="color: #991b1b; font-size: 14px; display: block; margin-bottom: 4px;">Pesanan Dibatalkan</strong>
                <p style="font-size: 13px; color: #7f1d1d; line-height: 1.5;">
                    Transaksi pemesanan ini telah dibatalkan. Stok buku telah dikembalikan ke katalog.
                </p>
            </div>
        @endif

        @if (Auth::user()->role === 'user' && $pesanan->status_pesanan === 'Menunggu Konfirmasi')
            <div class="card" style="padding: 24px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 8px; color: var(--danger);">Batalkan Pesanan</h4>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5;">
                    Pesanan dapat dibatalkan selama masih menunggu konfirmasi. Stok buku akan dikembalikan secara otomatis.
                </p>
                <form method="POST" action="{{ route('pesanan.batalkan', $pesanan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block">Batalkan Pesanan Ini</button>
                </form>
            </div>
        @endif

        @if (Auth::user()->role === 'admin')
            <div class="card" style="padding: 24px; border: 2px solid var(--primary-border);">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--primary);">Pembaruan Status Transaksi (Admin)</h3>

                <form method="POST" action="{{ route('admin.pesanan.update_status', $pesanan->id) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="status_pembayaran">Status Pembayaran</label>
                        <select id="status_pembayaran" name="status_pembayaran" class="form-control">
                            <option value="Belum Dibayar" {{ $pesanan->status_pembayaran === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="Sudah Dibayar" {{ $pesanan->status_pembayaran === 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                            <option value="Dikembalikan" {{ $pesanan->status_pembayaran === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status_pesanan">Status Pesanan</label>
                        <select id="status_pesanan" name="status_pesanan" class="form-control">
                            <option value="Menunggu Konfirmasi" {{ $pesanan->status_pesanan === 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="Diproses" {{ $pesanan->status_pesanan === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Dikirim" {{ $pesanan->status_pesanan === 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="Selesai" {{ $pesanan->status_pesanan === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ $pesanan->status_pesanan === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Perbarui Status Pesanan</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

