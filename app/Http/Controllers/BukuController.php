<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('penulis', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'harga_asc') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort === 'harga_desc') {
                $query->orderBy('harga', 'desc');
            } elseif ($request->sort === 'judul_asc') {
                $query->orderBy('judul', 'asc');
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $buku = $query->paginate(8)->withQueryString();
        $kategoris = Kategori::withCount('buku')->orderBy('nama_kategori')->get();

        return view('buku.index', compact('buku', 'kategoris'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        $terkait = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->take(4)
            ->get();

        return view('buku.detail', compact('buku', 'terkait'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150|unique:buku,judul',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.unique' => 'Judul buku sudah terdaftar di katalog. Judul buku tidak boleh sama.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun.required' => 'Tahun terbit wajib diisi.',
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'harga.required' => 'Harga buku wajib diisi.',
            'stok.required' => 'Stok buku wajib diisi.',
            'gambar.image' => 'Berkas sampul harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/buku'), $filename);
            $validated['gambar'] = 'uploads/buku/' . $filename;
        }

        $validated['status'] = $validated['stok'] > 0 ? 'Tersedia' : 'Habis';

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku baru berhasil ditambahkan ke katalog!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:150|unique:buku,judul,' . $buku->id,
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.unique' => 'Judul buku sudah terdaftar di katalog. Judul buku tidak boleh sama.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun.required' => 'Tahun terbit wajib diisi.',
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'harga.required' => 'Harga buku wajib diisi.',
            'stok.required' => 'Stok buku wajib diisi.',
            'gambar.image' => 'Berkas sampul harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($buku->gambar && File::exists(public_path($buku->gambar))) {
                File::delete(public_path($buku->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/buku'), $filename);
            $validated['gambar'] = 'uploads/buku/' . $filename;
        }

        $validated['status'] = $validated['stok'] > 0 ? 'Tersedia' : 'Habis';

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->detailPesanan()->exists()) {
            return back()->with('error', 'Buku "' . $buku->judul . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi pemesanan. Anda dapat mengubah stoknya menjadi 0 agar tidak dapat dipesan lagi.');
        }

        if ($buku->gambar && File::exists(public_path($buku->gambar))) {
            File::delete(public_path($buku->gambar));
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus dari katalog!');
    }
}
