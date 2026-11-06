@extends('layouts.app')

@section('title', 'Baca Pesan: ' . $pesan->subjek . ' - Admin Toko Buku USK')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.pesan.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar Pesan</a>
    </div>

    <div class="card" style="padding: 32px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
            <div>
                @if ($pesan->balasan)
                    <span class="status-badge badge-success" style="margin-bottom: 8px;">Pesan Telah Dibalas</span>
                @else
                    <span class="status-badge badge-warning" style="margin-bottom: 8px;">Menunggu Balasan Admin</span>
                @endif
                <h1 style="font-size: 22px; font-weight: 800; margin-top: 6px;">{{ $pesan->subjek }}</h1>
                <div style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                    Dari: <strong style="color: var(--text-main);">{{ $pesan->nama }}</strong> ({{ $pesan->email }})
                    @if ($pesan->user)
                        &bull; Akun Terdaftar: <a href="{{ route('admin.users.index', ['q' => $pesan->user->email]) }}">{{ $pesan->user->email }}</a>
                    @endif
                </div>
            </div>

            <div style="font-size: 13px; color: var(--text-muted); text-align: right;">
                {{ $pesan->created_at->format('d F Y, H:i') }} WIB
            </div>
        </div>

        <div style="font-size: 15px; line-height: 1.8; color: var(--text-main); white-space: pre-line; background: var(--background); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border-color); margin-bottom: 24px;">
{{ $pesan->pesan }}
        </div>

        @if ($pesan->balasan)
            <div style="background: #eff6ff; border: 1px solid var(--primary-border); border-radius: var(--radius); padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong style="color: var(--primary); font-size: 14px;">Balasan Anda yang Terkirim ke User:</strong>
                    <span style="font-size: 12px; color: var(--text-muted);">{{ $pesan->tanggal_balasan ? $pesan->tanggal_balasan->format('d M Y, H:i') : '' }} WIB</span>
                </div>
                <div style="font-size: 14px; color: #1e3a8a; line-height: 1.6; white-space: pre-line;">
{{ $pesan->balasan }}
                </div>
            </div>
        @endif

        <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">{{ $pesan->balasan ? 'Perbarui Balasan ke User' : 'Kirimkan Balasan ke User' }}</h3>
            <form method="POST" action="{{ route('admin.pesan.balas', $pesan->id) }}">
                @csrf
                <div class="form-group">
                    <textarea name="balasan" class="form-control" rows="4" placeholder="Tuliskan jawaban atau konfirmasi untuk user ini..." required>{{ old('balasan', $pesan->balasan) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Balasan ke User</button>
            </form>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="mailto:{{ $pesan->email }}?subject=Re: {{ rawurlencode($pesan->subjek) }}" class="btn btn-secondary btn-sm">
                Buka di Aplikasi Email
            </a>

            <form method="POST" action="{{ route('admin.pesan.destroy', $pesan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Hapus Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection
