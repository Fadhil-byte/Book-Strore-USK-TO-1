@extends('layouts.app')

@section('title', 'Pendaftaran Akun - Toko Buku USK')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Pendaftaran Akun Baru</h2>
    <p class="auth-subtitle">Silakan lengkapi formulir di bawah ini untuk mulai berbelanja buku</p>

    <form method="POST" action="{{ route('register') }}" id="formRegist">
        @csrf
        <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap *</label>
            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: Muhammad Fadhil" required autofocus autocomplete="name">
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Alamat Email *</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Contoh: fadhil@gmail.com" required autocomplete="email" pattern=".+@gmail\.com" title="Alamat email wajib menggunakan domain @gmail.com">
            <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 5px;">
                Ketentuan: Pendaftaran akun wajib menggunakan alamat email @gmail.com.
            </span>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password *</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" class="form-control" minlength="8" placeholder="Minimal 8 karakter (huruf dan angka)" required autocomplete="new-password">
                <button type="button" class="password-toggle-btn" onclick="togglePassword('password', this)" style="font-size: 13px; font-weight: 600; color: var(--primary);">
                    Lihat
                </button>
            </div>
            <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 5px;">
                Ketentuan: Minimal 8 karakter serta memuat perpaduan huruf dan angka.
            </span>
        </div>

        <div style="background: var(--background); padding: 12px 16px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color); font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5;">
            <strong>Informasi Akun:</strong> Anda dapat langsung masuk ke sistem menggunakan alamat email dan password setelah pendaftaran.
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg">Daftar Akun</button>
    </form>

    <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
        Sudah memiliki akun? <a href="{{ route('login') }}" style="font-weight: 600;">Masuk di sini</a>
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
