@extends('layouts.app')

@section('title', 'Toko Buku USK - Selamat Datang di Toko Buku Pilihan Anda')
@section('hide_nav', 'true')

@section('content')
<div class="hero-section" style="padding: 56px 36px; text-align: center;">
    <div style="display: inline-block; background: rgba(255, 255, 255, 0.2); color: #ffffff; padding: 4px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; margin-bottom: 14px;">
        Portal Resmi Toko Buku Universitas Syiah Kuala
    </div>
    <h1 class="hero-title" style="font-size: 38px; line-height: 1.2; margin-bottom: 14px;">
        Temukan Buku Pilihan dan Koleksi Literatur Terbaik
    </h1>
    <p class="hero-subtitle" style="margin: 0 auto 28px; font-size: 16px; max-width: 680px;">
        Selamat datang di Toko Buku USK. Kami menyediakan beragam literatur akademik, novel, dan referensi bacaan terpercaya. Untuk melakukan pemesanan buku, menambah ke keranjang belanja, serta proses pengiriman, silakan masuk ke akun Anda terlebih dahulu.
    </p>

    <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 24px;">
        @guest
            <a href="{{ route('login') }}" class="btn btn-secondary btn-lg" style="background: #ffffff; color: var(--primary); font-weight: 700; box-shadow: var(--shadow-md);">
                Masuk untuk Memesan
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg" style="background: rgba(255,255,255,0.18); color: #ffffff; border-color: rgba(255,255,255,0.35);">
                Daftar Akun Baru
            </a>
        @else
            <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-lg" style="background: #ffffff; color: var(--primary); font-weight: 700; box-shadow: var(--shadow-md);">
                Buka Katalog Buku &rarr;
            </a>
            <a href="{{ route('pesanan.riwayat') }}" class="btn btn-secondary btn-lg" style="background: rgba(255,255,255,0.18); color: #ffffff; border-color: rgba(255,255,255,0.35);">
                Riwayat Pesanan Saya
            </a>
        @endguest
    </div>
</div>

<div class="card" style="padding: 24px 28px; margin-bottom: 32px; border-left: 5px solid var(--primary); background: linear-gradient(135deg, #eff6ff, #f8fafc);">
    <div style="display: flex; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
            <h3 style="font-size: 17px; font-weight: 800; color: #1e3a8a; margin-bottom: 6px;">
                Ketentuan Pemesanan: Masuk Akun Terlebih Dahulu
            </h3>
            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 12px;">
                Demi menjaga validitas data pemesan dan kelancaran proses pengiriman ke alamat tujuan, pemesanan buku serta penggunaan keranjang belanja hanya dapat dilakukan oleh pengguna yang telah terdaftar dan masuk ke sistem.
            </p>
            @guest
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Masuk ke Akun Anda</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-sm">Buat Akun Baru</a>
                </div>
            @else
                <span class="status-badge badge-success" style="padding: 4px 10px; font-size: 12px;">Anda telah masuk sebagai {{ Auth::user()->nama }} (dapat langsung memesan buku)</span>
            @endguest
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="text-align: center;">
        <div class="stat-value">{{ $totalBuku }}</div>
        <div class="stat-label">Judul Buku Tersedia</div>
    </div>
    <div class="stat-card" style="text-align: center;">
        <div class="stat-value">{{ $kategoris->count() }}</div>
        <div class="stat-label">Pilihan Kategori</div>
    </div>
    <div class="stat-card" style="text-align: center;">
        <div class="stat-value">{{ $totalUser }}</div>
        <div class="stat-label">Pembaca Terdaftar</div>
    </div>
    <div class="stat-card" style="text-align: center;">
        <div class="stat-value" style="font-size: 20px; padding-top: 6px;">Transfer & COD</div>
        <div class="stat-label">Metode Pembayaran</div>
    </div>
</div>

<div class="card" style="padding: 32px; margin-bottom: 36px;">
    <h2 style="font-size: 20px; font-weight: 800; text-align: center; margin-bottom: 8px;">Alur Pemesanan Buku</h2>
    <p style="text-align: center; color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Langkah mudah untuk melakukan pemesanan buku di Toko Buku USK</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
        <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); text-align: center;">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: #ffffff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 700; font-size: 14px;">1</div>
            <strong style="font-size: 15px; display: block; margin-bottom: 6px;">Masuk atau Daftar Akun</strong>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Gunakan alamat email dan password Anda untuk masuk ke sistem.</p>
        </div>

        <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); text-align: center;">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: #ffffff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 700; font-size: 14px;">2</div>
            <strong style="font-size: 15px; display: block; margin-bottom: 6px;">Pilih Buku yang Diinginkan</strong>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Buka katalog, periksa sinopsis serta stok buku, lalu masukkan ke keranjang belanja.</p>
        </div>

        <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); text-align: center;">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: #ffffff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 700; font-size: 14px;">3</div>
            <strong style="font-size: 15px; display: block; margin-bottom: 6px;">Pilih Cara Pembayaran</strong>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Pilih opsi transfer sebelum pengiriman (Payment at Delivery) atau bayar di tempat (COD).</p>
        </div>

        <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); text-align: center;">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: #ffffff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 700; font-size: 14px;">4</div>
            <strong style="font-size: 15px; display: block; margin-bottom: 6px;">Pengiriman Buku</strong>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Pantau perkembangan pesanan Anda hingga buku sampai di alamat rumah.</p>
        </div>
    </div>
</div>

<div style="margin-bottom: 36px;">
    <div class="section-header">
        <div class="section-title">
            <span>Cuplikan Rekomendasi Buku Terbaru</span>
        </div>
        @auth
            <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-sm">Lihat Semua di Katalog &rarr;</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk untuk Melihat Katalog Lengkap &rarr;</a>
        @endauth
    </div>

    <div class="tokopedia-grid">
        @foreach ($bukuTerbaru as $item)
            @guest
                <a href="{{ route('login') }}" class="tokopedia-card" title="Masuk untuk melihat dan memesan {{ $item->judul }}">
            @else
                <a href="{{ route('buku.show', $item->id) }}" class="tokopedia-card" title="{{ $item->judul }}">
            @endguest
                <div class="tokopedia-thumb">
                    @if ($item->gambar && file_exists(public_path($item->gambar)))
                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->judul }}">
                    @else
                        <div style="text-align: center; color: var(--text-muted); padding: 12px; font-size: 13px; font-weight: 600;">
                            BukuKita USK
                        </div>
                    @endif
                </div>

                <div class="tokopedia-body">
                    <div class="tokopedia-title" title="{{ $item->judul }}">{{ $item->judul }}</div>
                    <div class="tokopedia-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="tokopedia-meta">Penulis: {{ $item->penulis }}</div>

                    <div class="tokopedia-footer">
                        <span>Banda Aceh</span>
                        @if ($item->stok > 0)
                            <span class="tokopedia-badge-stock in">Sisa {{ $item->stok }}</span>
                        @else
                            <span class="tokopedia-badge-stock out">Habis</span>
                        @endif
                    </div>

                    @guest
                        <div style="margin-top: 8px; font-size: 12px; color: var(--primary); font-weight: 600; text-align: center; padding-top: 6px; border-top: 1px dashed var(--border-color);">
                            Masuk Akun untuk Memesan
                        </div>
                    @else
                        <div style="margin-top: 8px; font-size: 12px; color: #10b981; font-weight: 600; text-align: center; padding-top: 6px; border-top: 1px dashed var(--border-color);">
                            Lihat Detail & Pesan &rarr;
                        </div>
                    @endguest
                </div>
            </a>
        @endforeach
    </div>
</div>

<div class="card" style="padding: 36px; margin-bottom: 36px;">
    <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 8px; text-align: center;">Kategori Pilihan Pembaca</h2>
    <p style="text-align: center; color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Temukan buku berdasarkan minat dan bidang studi Anda</p>

    <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
        @foreach ($kategoris as $kat)
            @auth
                <a href="{{ route('buku.index', ['kategori' => $kat->id]) }}" class="filter-pill" style="font-size: 14px; padding: 10px 20px;">
                    {{ $kat->nama_kategori }} ({{ $kat->buku_count }})
                </a>
            @else
                <a href="{{ route('login') }}" class="filter-pill" style="font-size: 14px; padding: 10px 20px;">
                    {{ $kat->nama_kategori }} ({{ $kat->buku_count }})
                </a>
            @endauth
        @endforeach
    </div>
</div>

<div class="about-features" style="margin-bottom: 36px;">
    <div class="feature-box">
        <div class="feature-title">Buku Asli dan Terverifikasi</div>
        <div class="feature-desc">Seluruh koleksi buku bersumber dari penerbit resmi dengan kualitas cetak dan konten terbaik.</div>
    </div>
    <div class="feature-box">
        <div class="feature-title">Metode Pembayaran Fleksibel</div>
        <div class="feature-desc">Mendukung transfer bank resmi sebelum buku dikirim maupun pembayaran tunai saat kurir tiba di lokasi (COD).</div>
    </div>
    <div class="feature-box">
        <div class="feature-title">Bantuan dan Layanan Admin</div>
        <div class="feature-desc">Pengguna yang telah masuk akun dapat mengirimkan pesan langsung kepada admin melalui menu Contact Admin.</div>
    </div>
</div>

@guest
    <div class="card" style="padding: 36px; text-align: center; background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: var(--primary-border);">
        <h3 style="font-size: 22px; font-weight: 800; color: #1e3a8a; margin-bottom: 8px;">Mulai Belanja Buku Pilihan Anda</h3>
        <p style="color: #3b82f6; font-size: 15px; margin-bottom: 20px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Daftarkan diri Anda hanya dengan nama lengkap, alamat email, dan password. Sistem akan menyiapkan akun Anda secara otomatis.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Akun Baru</a>
            <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Masuk Akun</a>
        </div>
    </div>
@endguest
@endsection

