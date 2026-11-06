@extends('layouts.app')

@section('title', 'Checkout & Pembayaran - Toko Buku USK')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('keranjang.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Keranjang</a>
    <h1 style="font-size: 24px; font-weight: 800; margin-top: 12px;">Checkout Pemesanan Buku</h1>
    <p style="color: var(--text-muted); font-size: 14px;">Lengkapi data pengiriman dan pilih metode pembayaran</p>
</div>

<div class="cart-layout">
    <div>
        <div class="card" style="padding: 28px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">1. Informasi Pengiriman</h3>

            <form id="formCheckout" method="POST" action="{{ route('pesanan.store_checkout') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_penerima">Nama Penerima *</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" class="form-control" value="{{ old('nama_penerima', $user->nama) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="telepon">Nomor WhatsApp / Telepon *</label>
                    <input type="text" id="telepon" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat_pengiriman">Alamat Lengkap Pengiriman *</label>
                    <textarea id="alamat_pengiriman" name="alamat_pengiriman" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos" required>{{ old('alamat_pengiriman') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan Tambahan (Opsional)</label>
                    <input type="text" id="catatan" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Contoh: Titipkan di satpam jika tidak ada orang di rumah">
                </div>

                <h3 style="font-size: 18px; font-weight: 700; margin: 28px 0 16px; border-top: 1px solid var(--border-color); padding-top: 20px;">2. Metode Pembayaran</h3>

                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <label class="payment-card-option" id="card_payment_delivery">
                        <input type="radio" id="radio_payment_delivery" name="metode_pembayaran" value="Payment at Delivery" checked style="margin-top: 4px;">
                        <div>
                            <strong class="payment-option-title" style="display: block; font-size: 15px;">Payment at Delivery (Pembayaran sebelum buku dikirim)</strong>
                            <span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 2px;">Lakukan transfer pembayaran melalui rekening bank setelah pesanan dibuat, buku langsung dikirim setelah konfirmasi admin.</span>
                        </div>
                    </label>

                    <label class="payment-card-option" id="card_cod">
                        <input type="radio" id="radio_cod" name="metode_pembayaran" value="COD" style="margin-top: 4px;">
                        <div>
                            <strong class="payment-option-title" style="display: block; font-size: 15px;">Cash on Delivery (Bayar di Tempat)</strong>
                            <span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 2px;">Pembayaran tunai kepada kurir saat buku sampai di alamat Anda.</span>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Konfirmasi & Buat Pesanan</button>
            </form>
        </div>
    </div>

    <div>
        <div class="card" style="padding: 24px; position: sticky; top: 80px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Daftar Buku Dipesan</h3>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 18px;">
                @foreach ($items as $item)
                    <div style="display: flex; justify-content: space-between; font-size: 14px; gap: 10px;">
                        <div>
                            <span style="font-weight: 600; display: block;">{{ $item->buku->judul }}</span>
                            <span style="font-size: 12px; color: var(--text-muted);">{{ $item->jumlah }} x Rp {{ number_format($item->buku->harga, 0, ',', '.') }}</span>
                        </div>
                        <strong style="white-space: nowrap;">Rp {{ number_format($item->jumlah * $item->buku->harga, 0, ',', '.') }}</strong>
                    </div>
                @endforeach
            </div>

            <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; margin-bottom: 14px;">
                <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--text-muted); margin-bottom: 6px;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--text-muted); margin-bottom: 6px;">
                    <span>Biaya Pengiriman</span>
                    <span style="color: #10b981; font-weight: 600;">GRATIS</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; color: var(--primary); margin-top: 10px;">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const options = document.querySelectorAll('.payment-card-option');
    const radios = document.querySelectorAll('input[name="metode_pembayaran"]');

    function syncActive() {
        radios.forEach(radio => {
            const card = radio.closest('.payment-card-option');
            if (card) {
                if (radio.checked) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', syncActive);
    });

    syncActive();
});
</script>
@endsection

