<?php

namespace App\Http\Controllers;

use App\Models\PesanKontak;
use Illuminate\Http\Request;

class AdminPesanController extends Controller
{
    public function index(Request $request)
    {
        $query = PesanKontak::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesans = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $unreadCount = PesanKontak::where('status', 'Belum Dibaca')->count();

        return view('admin.pesan.index', compact('pesans', 'unreadCount'));
    }

    public function show($id)
    {
        $pesan = PesanKontak::with('user')->findOrFail($id);

        if ($pesan->status === 'Belum Dibaca') {
            $pesan->update(['status' => 'Sudah Dibaca']);
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    public function balas(Request $request, $id)
    {
        $pesan = PesanKontak::findOrFail($id);

        $validated = $request->validate([
            'balasan' => 'required|string|max:3000',
        ]);

        $pesan->update([
            'balasan' => $validated['balasan'],
            'tanggal_balasan' => now(),
            'status' => 'Sudah Dibaca',
        ]);

        return back()->with('success', 'Balasan berhasil dikirim kepada ' . $pesan->nama . '!');
    }

    public function destroy($id)
    {
        $pesan = PesanKontak::findOrFail($id);
        $pesan->delete();

        return redirect()->route('admin.pesan.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
