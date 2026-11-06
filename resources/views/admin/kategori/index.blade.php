@extends('layouts.app')

@section('title', 'Kelola Kategori Buku - Admin Toko Buku USK')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800;">Kelola Kategori Buku</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Tambah, ubah, dan hapus kategori buku untuk memudahkan pengelompokan produk</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 360px 1fr; gap: 28px;">
    <div>
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Tambah Kategori Baru</h3>
            <form method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_kategori">Nama Kategori *</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" placeholder="Contoh: Komik & Manga" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat kategori...">{{ old('deskripsi') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">+ Simpan Kategori</button>
            </form>
        </div>
    </div>

    <div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="width: 110px; text-align: center;">Jumlah Buku</th>
                        <th style="width: 140px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong style="font-size: 15px;">{{ $k->nama_kategori }}</strong>
                            </td>
                            <td style="color: var(--text-muted);">{{ $k->deskripsi ?: '-' }}</td>
                            <td style="text-align: center;">
                                <span class="status-badge badge-info">{{ $k->buku_count }} buku</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="editKategori({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->deskripsi ?? '') }}')">Edit</button>

                                    <form method="POST" action="{{ route('admin.kategori.destroy', $k->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                Belum ada kategori buku. Silakan tambahkan melalui form di samping.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalEdit" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 500px; width: 100%; margin: 0; padding: 28px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Edit Kategori Buku</h3>
        <form id="formEditKategori" method="POST" action="">
            @csrf
            <div class="form-group">
                <label class="form-label" for="edit_nama_kategori">Nama Kategori *</label>
                <input type="text" id="edit_nama_kategori" name="nama_kategori" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_deskripsi">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editKategori(id, nama, deskripsi) {
    document.getElementById('edit_nama_kategori').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('formEditKategori').action = '{{ url("admin/kategori") }}/' + id + '/update';
    document.getElementById('modalEdit').style.display = 'flex';
}
function closeModal() {
    document.getElementById('modalEdit').style.display = 'none';
}
</script>
@endsection

