<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('buku.kategori')
            ->where('user_id', Auth::id())
            ->get();

        $total = $items->sum(function ($item) {
            return $item->jumlah * $item->buku->harga;
        });

        return view('keranjang.index', compact('items', 'total'));
    }

    public function add(Request $request, $bukuId)
    {
        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        $jumlah = max(1, intval($request->input('jumlah', 1)));

        $item = Keranjang::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->first();

        if ($item) {
            $totalJumlah = $item->jumlah + $jumlah;
            if ($totalJumlah > $buku->stok) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia (sisa: ' . $buku->stok . ').');
            }
            $item->update(['jumlah' => $totalJumlah]);
        } else {
            if ($jumlah > $buku->stok) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia (sisa: ' . $buku->stok . ').');
            }
            Keranjang::create([
                'user_id' => Auth::id(),
                'buku_id' => $bukuId,
                'jumlah' => $jumlah,
            ]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Buku "' . $buku->judul . '" berhasil dimasukkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $item = Keranjang::with('buku')->where('user_id', Auth::id())->findOrFail($id);
        $jumlah = intval($request->input('jumlah', 1));

        if ($jumlah <= 0) {
            $item->delete();
            return back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        if ($jumlah > $item->buku->stok) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia (sisa: ' . $item->buku->stok . ').');
        }

        $item->update(['jumlah' => $jumlah]);

        return back()->with('success', 'Jumlah buku berhasil diperbarui.');
    }

    public function remove($id)
    {
        $item = Keranjang::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}

