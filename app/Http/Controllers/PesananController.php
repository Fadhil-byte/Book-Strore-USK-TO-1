<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Keranjang;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function checkout()
    {
        $items = Keranjang::with('buku')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda masih kosong. Silakan pilih buku terlebih dahulu.');
        }

        foreach ($items as $item) {
            if ($item->jumlah > $item->buku->stok) {
                return redirect()->route('keranjang.index')->with('error', 'Stok buku "' . $item->buku->judul . '" tidak mencukupi (tersedia: ' . $item->buku->stok . ').');
            }
        }

        $total = $items->sum(function ($item) {
            return $item->jumlah * $item->buku->harga;
        });

        $user = Auth::user();

        return view('pesanan.checkout', compact('items', 'total', 'user'));
    }

    public function storeCheckout(Request $request)
    {
        $validated = $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:30',
            'alamat_pengiriman' => 'required|string',
            'metode_pembayaran' => 'required|string|in:Payment at Delivery,COD',
            'catatan' => 'nullable|string',
        ]);

        $items = Keranjang::with('buku')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('buku.index')->with('error', 'Keranjang kosong.');
        }

        return DB::transaction(function () use ($validated, $items) {
            $totalHarga = 0;

            foreach ($items as $item) {
                if ($item->jumlah > $item->buku->stok) {
                    throw new \Exception('Stok untuk buku ' . $item->buku->judul . ' tidak mencukupi.');
                }
                $totalHarga += ($item->jumlah * $item->buku->harga);
            }

            $kode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $pesanan = Pesanan::create([
                'kode_pesanan' => $kode,
                'user_id' => Auth::id(),
                'nama_penerima' => $validated['nama_penerima'],
                'telepon' => $validated['telepon'],
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'Belum Dibayar',
                'status_pesanan' => 'Menunggu Konfirmasi',
                'total_harga' => $totalHarga,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($items as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'buku_id' => $item->buku_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->buku->harga,
                    'subtotal' => $item->jumlah * $item->buku->harga,
                ]);

                $item->buku->decrement('stok', $item->jumlah);
                if ($item->buku->fresh()->stok <= 0) {
                    $item->buku->update(['status' => 'Habis']);
                }
            }

            Keranjang::where('user_id', Auth::id())->delete();

            return redirect()->route('pesanan.detail', $pesanan->id)->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran sebelum buku dikirim.');
        });
    }

    public function riwayat()
    {
        $pesanans = Pesanan::with('detailPesanan.buku')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('pesanan.riwayat', compact('pesanans'));
    }

    public function detail($id)
    {
        $query = Pesanan::with(['detailPesanan.buku.kategori', 'user']);

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $pesanan = $query->findOrFail($id);

        return view('pesanan.detail', compact('pesanan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);

        if ($pesanan->status_pesanan === 'Dibatalkan') {
            return back()->with('error', 'Pesanan yang telah dibatalkan tidak dapat diproses pembayarannya.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . $pesanan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti'), $filename);

            $pesanan->update([
                'bukti_pembayaran' => 'uploads/bukti/' . $filename,
                'status_pesanan' => 'Menunggu Konfirmasi',
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Status pesanan Anda saat ini Menunggu Konfirmasi dari Administrator.');
    }

    public function batalkan($id)
    {
        $pesanan = Pesanan::with('detailPesanan.buku')->where('user_id', Auth::id())->findOrFail($id);

        if ($pesanan->status_pesanan !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau telah selesai.');
        }

        DB::transaction(function () use ($pesanan) {
            foreach ($pesanan->detailPesanan as $item) {
                if ($item->buku) {
                    $item->buku->increment('stok', $item->jumlah);
                    if ($item->buku->fresh()->stok > 0) {
                        $item->buku->update(['status' => 'Tersedia']);
                    }
                }
            }

            $pesanan->update([
                'status_pesanan' => 'Dibatalkan',
                'status_pembayaran' => 'Dikembalikan',
            ]);
        });

        return back()->with('success', 'Pesanan ' . $pesanan->kode_pesanan . ' berhasil dibatalkan.');
    }

    public function adminIndex(Request $request)
    {
        $query = Pesanan::with(['user', 'detailPesanan.buku']);

        if ($request->filled('status_pesanan')) {
            $query->where('status_pesanan', $request->status_pesanan);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pesanan', 'like', "%{$keyword}%")
                  ->orWhere('nama_penerima', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($u) use ($keyword) {
                      $u->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                  });
            });
        }

        $pesanans = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();
        $pendingOrdersCount = Pesanan::where('status_pesanan', 'Menunggu Konfirmasi')->count();

        return view('admin.pesanan.index', compact('pesanans', 'pendingOrdersCount'));
    }

    public function adminUpdateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::with('detailPesanan.buku')->findOrFail($id);

        $validated = $request->validate([
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar,Dikembalikan',
            'status_pesanan' => 'required|in:Menunggu Konfirmasi,Diproses,Dikirim,Selesai,Dibatalkan',
        ]);

        if ($validated['status_pesanan'] === 'Dibatalkan') {
            $validated['status_pembayaran'] = 'Dikembalikan';
        }

        DB::transaction(function () use ($pesanan, $validated) {
            if ($pesanan->status_pesanan !== 'Dibatalkan' && $validated['status_pesanan'] === 'Dibatalkan') {
                foreach ($pesanan->detailPesanan as $item) {
                    if ($item->buku) {
                        $item->buku->increment('stok', $item->jumlah);
                        if ($item->buku->fresh()->stok > 0) {
                            $item->buku->update(['status' => 'Tersedia']);
                        }
                    }
                }
            } elseif ($pesanan->status_pesanan === 'Dibatalkan' && $validated['status_pesanan'] !== 'Dibatalkan') {
                foreach ($pesanan->detailPesanan as $item) {
                    if ($item->buku) {
                        $item->buku->decrement('stok', $item->jumlah);
                        if ($item->buku->fresh()->stok <= 0) {
                            $item->buku->update(['status' => 'Habis']);
                        }
                    }
                }
            }

            $pesanan->update($validated);
        });

        return back()->with('success', 'Status pesanan ' . $pesanan->kode_pesanan . ' berhasil diperbarui!');
    }
}

