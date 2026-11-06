@extends('layouts.app')

@section('title', 'Edit Buku: ' . $buku->judul . ' - Admin Toko Buku USK')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800;">Edit Data Buku</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Perbarui informasi, harga, atau stok buku</p>
        </div>
        <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
    </div>

    <div class="card" style="padding: 32px;">
        <form method="POST" action="{{ route('admin.buku.update', $buku->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label" for="judul">Judul Buku *</label>
                <input type="text" id="judul" name="judul" class="form-control" value="{{ old('judul', $buku->judul) }}" required>
                @error('judul')
                    <span style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="penulis">Nama Penulis *</label>
                    <input type="text" id="penulis" name="penulis" class="form-control" value="{{ old('penulis', $buku->penulis) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="penerbit">Penerbit *</label>
                    <input type="text" id="penerbit" name="penerbit" class="form-control" value="{{ old('penerbit', $buku->penerbit) }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="kategori_id">Kategori Buku *</label>
                    <select id="kategori_id" name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $buku->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tahun">Tahun Terbit *</label>
                    <input type="number" id="tahun" name="tahun" class="form-control" value="{{ old('tahun', $buku->tahun) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="stok">Stok Buku *</label>
                    <input type="number" id="stok" name="stok" class="form-control" value="{{ old('stok', $buku->stok) }}" min="0" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="harga">Harga (Rp) *</label>
                    <input type="number" id="harga" name="harga" class="form-control" value="{{ old('harga', $buku->harga) }}" min="0" step="1000" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="gambar">Ganti Cover Buku (Opsional)</label>
                    <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                    @if ($buku->gambar && file_exists(public_path($buku->gambar)))
                        <span style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;">Cover saat ini: {{ basename($buku->gambar) }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi / Sinopsis Buku</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary btn-lg">Perbarui Data Buku</button>
            </div>
        </form>
    </div>
</div>
@endsection
