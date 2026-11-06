<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\PesanKontak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function landing()
    {
        $bukuTerbaru = Buku::with('kategori')->orderBy('id', 'desc')->take(8)->get();
        $kategoris = Kategori::withCount('buku')->orderBy('nama_kategori')->get();
        $totalBuku = Buku::count();
        $totalUser = User::where('role', 'user')->count();

        return view('pages.landing', compact('bukuTerbaru', 'kategoris', 'totalBuku', 'totalUser'));
    }

    public function about()
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalUser = User::where('role', 'user')->count();

        if (Auth::user()->role === 'admin') {
            $totalPesanan = Pesanan::count();
            $pesananBaru = Pesanan::where('status_pesanan', 'Menunggu Konfirmasi')->count();
            $pesanBaru = PesanKontak::where('status', 'Belum Dibaca')->count();

            return view('pages.about', compact(
                'totalBuku',
                'totalKategori',
                'totalUser',
                'totalPesanan',
                'pesananBaru',
                'pesanBaru'
            ));
        }

        return view('pages.about', compact('totalBuku', 'totalKategori', 'totalUser'));
    }

    public function contact()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.pesan.index');
        }

        $user = Auth::user();
        $riwayatPesan = PesanKontak::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.contact', compact('user', 'riwayatPesan'));
    }

    public function storeContact(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.pesan.index');
        }

        $validated = $request->validate([
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string|max:2000',
        ]);

        $validated['nama'] = Auth::user()->nama;
        $validated['email'] = Auth::user()->email;
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Belum Dibaca';

        PesanKontak::create($validated);

        return back()->with('success', 'Pesan Anda berhasil dikirim ke Administrator! Admin akan segera membalas pesan Anda.');
    }
}
