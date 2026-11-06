@extends('layouts.app')

@section('title', 'Masuk Akun - Toko Buku USK')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Masuk ke Akun Anda</h2>
    <p class="auth-subtitle">Gunakan alamat email dan password akun Anda</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Contoh: user@gmail.com" required autofocus autocomplete="email">
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password Anda" required autocomplete="current-password">
                <button type="button" class="password-toggle-btn" onclick="togglePassword('password', this)" style="font-size: 13px; font-weight: 600; color: var(--primary);">
                    Lihat
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 10px;">Masuk</button>
    </form>

    <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
        Belum memiliki akun? <a href="{{ route('register') }}" style="font-weight: 600;">Daftar di sini</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Sembunyikan';
    } else {
        input.type = 'password';
        btn.textContent = 'Lihat';
    }
}
</script>
@endsection
