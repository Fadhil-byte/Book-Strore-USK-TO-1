<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['pesanan', 'keranjang']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);

        if ($user->pesanan()->whereIn('status_pesanan', ['Menunggu Konfirmasi', 'Diproses', 'Dikirim'])->exists()) {
            return back()->with('error', 'Pengguna "' . $user->nama . '" memiliki transaksi pesanan aktif yang sedang berjalan dan tidak dapat dihapus.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus dari sistem.');
    }
}

