@extends('layouts.app')

@section('title', 'Pesan Masuk (Contact Admin) - Admin Toko Buku USK')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Pesan Masuk dari Pengguna</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Daftar pesan dan pertanyaan yang dikirimkan user melalui halaman Contact to Admin</p>
    </div>

    @if ($unreadCount > 0)
        <span class="status-badge badge-warning" style="font-size: 14px; padding: 6px 14px;">{{ $unreadCount }} Pesan Belum Dibaca</span>
    @endif
</div>

<div class="card" style="padding: 16px 24px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.pesan.index') }}" style="display: flex; gap: 12px; align-items: center;">
        <select name="status" class="form-control" style="width: 220px;" onchange="this.form.submit()">
            <option value="">-- Semua Status Pesan --</option>
            <option value="Belum Dibaca" {{ request('status') == 'Belum Dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
            <option value="Sudah Dibaca" {{ request('status') == 'Sudah Dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
        </select>
        @if (request('status'))
            <a href="{{ route('admin.pesan.index') }}" class="btn btn-secondary btn-sm">Reset Filter</a>
        @endif
    </form>
</div>

@if ($pesans->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">Tidak Ada Pesan Masuk</h3>
        <p style="color: var(--text-muted);">Kotak pesan Anda saat ini sedang kosong.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Pengirim</th>
                    <th>Subjek & Cuplikan Pesan</th>
                    <th>Waktu Dikirim</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 140px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesans as $index => $p)
                    <tr style="{{ $p->status === 'Belum Dibaca' ? 'background: #eff6ff; font-weight: 500;' : '' }}">
                        <td>{{ $pesans->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $p->nama }}</strong>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $p->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $p->subjek }}</div>
                            <div style="font-size: 13px; color: var(--text-muted); max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $p->pesan }}</div>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $p->created_at->format('d M Y, H:i') }}
                        </td>
                        <td style="text-align: center;">
                            @if ($p->status === 'Belum Dibaca')
                                <span class="status-badge badge-warning">Baru</span>
                            @else
                                <span class="status-badge badge-secondary">Dibaca</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="{{ route('admin.pesan.show', $p->id) }}" class="btn btn-primary btn-sm">Buka</a>
                                <form method="POST" action="{{ route('admin.pesan.destroy', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $pesans->links() }}
    </div>
@endif
@endsection

