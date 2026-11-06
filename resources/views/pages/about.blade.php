@extends('layouts.app')

@section('title', 'Dashboard - Toko Buku USK')

@section('content')
@if (Auth::user()->role === 'admin')
        <div class="hero-section" style="padding: 48px 32px; text-align: center;">
            <div style="display: inline-block; background: rgba(255, 255, 255, 0.2); padding: 4px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; margin-bottom: 12px;">
                Dashboard Administrator
            </div>
            <h1 class="hero-title" style="font-size: 32px;">Selamat Datang, {{ Auth::user()->nama }}</h1>
            <p class="hero-subtitle" style="margin: 0 auto 24px; font-size: 16px;">
                Panel pengelolaan katalog buku, verifikasi transaksi pesanan masuk, dan tanggapan pesan pelanggan Toko Buku USK.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-lg" style="background: #ffffff; color: var(--primary); font-weight: 700;">
                    Katalog Buku &rarr;
                </a>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-lg" style="background: rgba(255,255,255,0.15); color: #ffffff; border-color: rgba(255,255,255,0.3);">
                    Pesanan Masuk
                    @if ($pesananBaru > 0)
                        <span style="background: var(--danger); color: #fff; font-size: 11px; padding: 2px 8px; border-radius: 9999px; margin-left: 6px;">{{ $pesananBaru }} baru</span>
                    @endif
                </a>
            </div>
        </div>

        @if ($pesananBaru > 0 || $pesanBaru > 0)
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                @if ($pesananBaru > 0)
                    <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: var(--radius); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <span style="font-size: 14px; color: #991b1b; font-weight: 600;">
                            Pemberitahuan: Ada <strong>{{ $pesananBaru }} pesanan baru</strong> yang memerlukan verifikasi dan pemrosesan.
                        </span>
                        <a href="{{ route('admin.pesanan.index', ['status_pesanan' => 'Menunggu Konfirmasi']) }}" class="btn btn-primary btn-sm">Periksa Pesanan &rarr;</a>
                    </div>
                @endif
                @if ($pesanBaru > 0)
                    <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: var(--radius); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <span style="font-size: 14px; color: #1e40af; font-weight: 600;">
                            Pemberitahuan: Ada <strong>{{ $pesanBaru }} pesan masuk</strong> dari pengguna yang belum dibaca.
                        </span>
                        <a href="{{ route('admin.pesan.index') }}" class="btn btn-primary btn-sm">Buka Pesan Masuk &rarr;</a>
                    </div>
                @endif
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card" style="text-align: center;">
                <div class="stat-value" style="color: var(--primary);">{{ $totalBuku }}</div>
                <div class="stat-label">Total Judul Buku</div>
            </div>

            <div class="stat-card" style="text-align: center;">
                <div class="stat-value" style="color: #0ea5e9;">{{ $totalKategori }}</div>
                <div class="stat-label">Kategori Literatur</div>
            </div>

            <div class="stat-card" style="text-align: center;">
                <div class="stat-value" style="color: #f59e0b;">{{ $totalPesanan }}</div>
                <div class="stat-label">Total Pesanan ({{ $pesananBaru }} baru)</div>
            </div>

            <div class="stat-card" style="text-align: center;">
                <div class="stat-value" style="color: #10b981;">{{ $totalUser }}</div>
                <div class="stat-label">Pengguna Terdaftar</div>
            </div>

            <div class="stat-card" style="text-align: center;">
                <div class="stat-value" style="color: #8b5cf6;">{{ $pesanBaru }}</div>
                <div class="stat-label">Pesan Belum Dibalas</div>
            </div>
        </div>

        <div class="card" style="padding: 32px; margin-bottom: 28px;">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Panduan Tugas Administrator</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Berikut alur pengelolaan data dan operasional yang perlu diperhatikan:</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">1. Pengelolaan Buku dan Kategori</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Pastikan kategori buku telah terdaftar sebelum menambahkan judul buku baru beserta harga, stok, dan gambar sampul.
                    </p>
                </div>

                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">2. Verifikasi Transaksi Masuk</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Periksa keabsahan bukti transfer pembayaran pembeli, kemudian perbarui status transaksi menjadi Diproses atau Dikirim.
                    </p>
                </div>

                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">3. Layanan Pertanyaan Pengguna</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Tanggapi pesan masuk dari pembeli melalui fitur balasan agar kebutuhan pembaca dapat terlayani secara tepat.
                    </p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 28px; background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: var(--primary-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #1e3a8a; margin-bottom: 4px;">Pintasan Menu Pengelolaan</h3>
                <p style="color: #3b82f6; font-size: 14px;">Akses seluruh menu administrasi melalui tombol pintas berikut.</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Kategori</a>
                <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">+ Tambah Buku</a>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Pesanan</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Data User</a>
                <a href="{{ route('admin.pesan.index') }}" class="btn btn-secondary">Pesan Masuk</a>
            </div>
        </div>
    @else
        <div class="hero-section" style="padding: 48px 32px; text-align: center;">
            <div style="display: inline-block; background: rgba(255, 255, 255, 0.2); padding: 4px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; margin-bottom: 12px;">
                Toko Buku Universitas Syiah Kuala
            </div>
            <h1 class="hero-title" style="font-size: 32px;">Pusat Pembelian Buku dan Bahan Ajar</h1>
            <p class="hero-subtitle" style="margin: 0 auto 24px; font-size: 16px;">
                Halo, <strong>{{ Auth::user()->nama }}</strong>. Temukan literatur berkualitas untuk menunjang kegiatan perkuliahan dan minat membaca Anda.
            </p>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-lg" style="background: #ffffff; color: var(--primary); font-weight: 700;">
                Mulai Belanja & Buka Katalog Buku &rarr;
            </a>
        </div>

        <div class="card" style="padding: 32px; margin-bottom: 28px;">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Informasi Belanja</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Panduan mudah membeli buku di platform Toko Buku USK:</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">1. Cari dan Pilih Buku</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Telusuri buku melalui katalog berdasarkan kategori atau kata kunci judul dan nama penulis.
                    </p>
                </div>

                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">2. Masukkan ke Keranjang</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Tentukan jumlah eksemplar buku yang ingin dipesan melalui halaman detail buku.
                    </p>
                </div>

                <div style="background: var(--background); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <strong style="font-size: 16px; display: block; margin-bottom: 6px;">3. Pembayaran dan Pengiriman</strong>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                        Gunakan metode transfer bank sebelum buku dikirimkan atau pilih pembayaran tunai di tempat (COD).
                    </p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 28px; background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: var(--primary-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #1e3a8a; margin-bottom: 4px;">Butuh Buku Bacaan Baru?</h3>
                <p style="color: #3b82f6; font-size: 14px;">Koleksi buku perkuliahan dan literatur umum siap untuk dipesan.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('pages.contact') }}" class="btn btn-secondary">Hubungi Admin</a>
                <a href="{{ route('buku.index') }}" class="btn btn-primary btn-lg">Katalog Buku &rarr;</a>
            </div>
        </div>
    @endif
@endsection
