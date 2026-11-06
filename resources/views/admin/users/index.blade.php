@extends('layouts.app')

@section('title', 'Daftar User Terdaftar - Admin Toko Buku USK')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">List Pengguna Terdaftar</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Daftar seluruh akun user dan administrator yang terdaftar di sistem</p>
    </div>
</div>

<div class="card" style="padding: 18px 24px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 12px; align-items: center;">
        <input type="text" name="q" class="form-control" placeholder="Cari nama lengkap atau email..." value="{{ request('q') }}">

        <select name="role" class="form-control" style="width: 200px;">
            <option value="">-- Semua Role --</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User (Pembeli)</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
        </select>

        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Nama Lengkap</th>
                <th>Alamat Email</th>
                <th style="text-align: center;">Role</th>
                <th style="text-align: center;">Total Transaksi</th>
                <th>Tanggal Bergabung</th>
                <th style="width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $u)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>
                        <strong style="font-size: 15px;">{{ $u->nama }}</strong>
                        @if ($u->id === Auth::id())
                            <span class="status-badge badge-info" style="font-size: 10px; margin-left: 6px;">Akun Anda</span>
                        @endif
                    </td>
                    <td>{{ $u->email }}</td>
                    <td style="text-align: center;">
                        <span class="role-pill {{ $u->role }}">{{ $u->role }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="status-badge badge-secondary">{{ $u->pesanan_count }} pesanan</span>
                    </td>
                    <td style="font-size: 13px; color: var(--text-muted);">
                        {{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if ($u->id !== Auth::id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @else
                            <span style="font-size: 12px; color: var(--text-muted);">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 32px; color: var(--text-muted);">
                        Tidak ada user yang sesuai dengan pencarian.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 24px; display: flex; justify-content: center;">
    {{ $users->links() }}
</div>
@endsection

