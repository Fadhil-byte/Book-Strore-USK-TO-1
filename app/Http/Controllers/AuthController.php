<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('pages.about');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = trim($request->input('email', ''));
        $password = trim($request->input('password', ''));

        if (empty($email) || empty($password)) {
            return back()->with('error', 'Silakan masukkan alamat email dan password!')->withInput();
        }

        $throttleKey = 'login|' . Str::transliterate(Str::lower($email)) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', 'Terlalu banyak percobaan login gagal. Demi keamanan, silakan coba lagi dalam ' . $seconds . ' detik.')->withInput();
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            return back()->with('error', 'Kombinasi Alamat Email dan Password yang Anda masukkan tidak valid!')->withInput();
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pages.about')->with('success', 'Selamat datang kembali, ' . $user->nama . '!');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('pages.about');
        }

        return view('auth.regist');
    }

    public function register(Request $request)
    {
        $regThrottleKey = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($regThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($regThrottleKey);
            return back()->with('error', 'Terlalu banyak percobaan pendaftaran dari perangkat Anda. Silakan coba lagi dalam ' . $seconds . ' detik.')->withInput();
        }

        RateLimiter::hit($regThrottleKey, 300);

        $request->merge([
            'nama' => strip_tags(trim($request->input('nama', ''))),
            'email' => trim($request->input('email', '')),
        ]);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\,\'\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:100', 'ends_with:@gmail.com', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', Password::min(8)->letters()->numbers()],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.regex' => 'Nama lengkap hanya boleh memuat huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.ends_with' => 'Pendaftaran akun wajib menggunakan alamat email @gmail.com.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.letters' => 'Password harus memuat kombinasi huruf.',
            'password.numbers' => 'Password harus memuat kombinasi angka.',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan alamat email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
