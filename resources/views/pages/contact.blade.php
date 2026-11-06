@extends('layouts.app')

@section('title', 'Contact to Admin - Toko Buku USK')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800;">Contact to Admin</h1>
    <p style="color: var(--text-muted); font-size: 14px;">Kirimkan pertanyaan atau informasi seputar pemesanan dan ketersediaan buku langsung kepada admin</p>
</div>

<div class="contact-layout">
    <div>
        <div class="card" style="padding: 28px;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 18px;">Kirim Pesan ke Admin</h2>

            <div style="background: var(--background); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 20px; font-size: 13px;">
                <div style="color: var(--text-muted); margin-bottom: 2px;">Identitas Pengirim:</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ Auth::user()->nama }}</div>
                <div style="color: var(--primary); font-size: 13px;">{{ Auth::user()->email }}</div>
            </div>

            <form method="POST" action="{{ route('pages.store_contact') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="subjek">Subjek Pesan *</label>
                    <input type="text" id="subjek" name="subjek" class="form-control" value="{{ old('subjek') }}" placeholder="Contoh: Pertanyaan Stok Buku atau Status Pembayaran" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pesan">Isi Pesan *</label>
                    <textarea id="pesan" name="pesan" class="form-control" rows="5" placeholder="Tuliskan pesan Anda secara jelas dan lengkap..." required>{{ old('pesan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Kirim Pesan</button>
            </form>
        </div>
    </div>

    <div>
        <div class="card" style="padding: 28px;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Riwayat Pesan & Balasan Admin</h2>

            @if ($riwayatPesan && $riwayatPesan->isNotEmpty())
                <div style="display: flex; flex-direction: column; gap: 16px; max-height: 520px; overflow-y: auto; padding-right: 6px;">
                    @foreach ($riwayatPesan as $rp)
                        <div style="background: var(--background); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <div>
                                    <strong style="font-size: 14px; color: var(--text-main); display: block;">{{ $rp->subjek }}</strong>
                                    <span style="font-size: 11px; color: var(--text-muted);">{{ $rp->created_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                                @if ($rp->balasan)
                                    <span class="status-badge badge-success" style="font-size: 11px;">Sudah Dibalas</span>
                                @else
                                    <span class="status-badge badge-warning" style="font-size: 11px;">Menunggu Balasan</span>
                                @endif
                            </div>

                            <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 10px; background: #ffffff; padding: 10px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0;">
                                "{{ $rp->pesan }}"
                            </div>

                            @if ($rp->balasan)
                                <div style="background: #eff6ff; border-left: 3px solid var(--primary); padding: 12px; border-radius: 4px;">
                                    <div style="font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 4px;">
                                        Balasan Administrator ({{ $rp->tanggal_balasan ? $rp->tanggal_balasan->format('d M Y, H:i') : '' }} WIB):
                                    </div>
                                    <div style="font-size: 13px; color: #1e3a8a; line-height: 1.6; white-space: pre-line;">
                                        {{ $rp->balasan }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 32px 16px; color: var(--text-muted);">
                    <p style="font-size: 14px;">Belum ada riwayat pesan yang Anda kirimkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
