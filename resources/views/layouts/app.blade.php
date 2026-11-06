<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Buku USK') - Sistem Pembelian Buku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @yield('styles')
</head>
<body>
    <header class="navbar">
        <div class="nav-container">
            <a href="{{ route('landing') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ffffff;"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                </div>
                <span>BukuKita USK</span>
            </a>

            <nav class="nav-menu">
                @auth
                    @if (!View::hasSection('hide_nav') && !request()->routeIs('landing') && request()->path() !== '/' && request()->path() !== '')
                        <a href="{{ route('pages.about') }}" class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('buku.index') }}" class="nav-link {{ request()->routeIs('buku.*') && !request()->routeIs('admin.*') ? 'active' : '' }}">Katalog Buku</a>

                        @if (Auth::user()->role === 'user')
                            @php
                                $userActiveOrders = \App\Models\Pesanan::where('user_id', Auth::id())
                                    ->whereIn('status_pesanan', ['Menunggu Konfirmasi', 'Diproses', 'Dikirim'])
                                    ->count();
                            @endphp
                            <a href="{{ route('pesanan.riwayat') }}" class="nav-link {{ request()->routeIs('pesanan.riwayat') ? 'active' : '' }}">
                                <span>Pesanan Saya</span>
                                @if ($userActiveOrders > 0)
                                    <span class="nav-sub-badge">{{ $userActiveOrders }}</span>
                                @endif
                            </a>
                            <a href="{{ route('pages.contact') }}" class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}">Contact Admin</a>
                        @endif

                        @if (Auth::user()->role === 'admin')
                            @php
                                $unreadMsg = \App\Models\PesanKontak::where('status', 'Belum Dibaca')->count();
                                $pendingOrd = \App\Models\Pesanan::where('status_pesanan', 'Menunggu Konfirmasi')->count();
                                $totalAdminNotif = $unreadMsg + $pendingOrd;
                            @endphp
                            <div class="nav-dropdown {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <button type="button" class="nav-dropdown-toggle">
                                    <span>Kelola Toko</span>
                                    <span style="font-size: 11px;">▼</span>
                                    @if ($totalAdminNotif > 0)
                                        <span class="nav-notif-pill">{{ $totalAdminNotif }}</span>
                                    @endif
                                </button>
                                <div class="nav-dropdown-menu">
                                    <a href="{{ route('admin.kategori.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                                        <span>Kategori Buku</span>
                                    </a>
                                    <a href="{{ route('admin.buku.create') }}" class="nav-dropdown-item {{ request()->routeIs('admin.buku.create') ? 'active' : '' }}">
                                        <span>+ Tambah Buku</span>
                                    </a>
                                    <a href="{{ route('admin.pesanan.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                                        <span>Pesanan Masuk</span>
                                        @if ($pendingOrd > 0)
                                            <span class="status-badge badge-danger" style="font-size: 10px; padding: 2px 6px;">{{ $pendingOrd }} baru</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <span>Data User</span>
                                    </a>
                                    <a href="{{ route('admin.pesan.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.pesan.*') ? 'active' : '' }}">
                                        <span>Pesan Masuk</span>
                                        @if ($unreadMsg > 0)
                                            <span class="status-badge badge-danger" style="font-size: 10px; padding: 2px 6px;">{{ $unreadMsg }} baru</span>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                @endauth
            </nav>

            <div class="nav-right">
                @auth
                    @if (Auth::user()->role === 'user')
                        @php
                            $cartCount = \App\Models\Keranjang::where('user_id', Auth::id())->sum('jumlah');
                        @endphp
                        <a href="{{ route('keranjang.index') }}" class="cart-btn" title="Keranjang Belanja">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            @if ($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    @endif

                    <div class="user-badge">
                        <span>{{ Auth::user()->nama }}</span>
                        <span class="role-pill {{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                    </div>

                    <a href="{{ route('logout') }}" class="btn btn-secondary btn-sm" style="color: var(--danger);">Keluar</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <div>
                    <strong>Perhatian:</strong>
                    <ul style="margin-left: 20px; margin-top: 4px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Toko Buku USK. Sistem Pembelian & Pengelolaan Katalog Buku.</p>
    </footer>

    @yield('scripts')
</body>
</html>
