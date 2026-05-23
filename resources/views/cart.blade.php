@php
    $cart = $cart ?? session('cart', []);
    $total = 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.cart-body{
    background:#fdecae;
    min-height:calc(100vh - 68px);
    padding:18px 20px 40px;
}

.cart-body h1{
    font-size:25px;
    font-weight:700;
    color:#215486;
    margin-bottom:16px;
}

.cart-item-box{
    background:white;
    border-radius:18px;
    padding:12px;
    display:flex;
    gap:14px;
    align-items:flex-start;
    margin-bottom:14px;
    box-shadow:0 4px 8px rgba(0,0,0,.12);
}

.cart-item-img{
    width:105px;
    height:105px;
    border-radius:14px;
    overflow:hidden;
    background:#eee;
    flex-shrink:0;
}

.cart-item-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.cart-item-info h2{
    color:#215486;
    font-size:21px;
    line-height:1.1;
    font-weight:700;
    margin-bottom:12px;
}

.cart-item-info p{
    color:#215486;
    font-size:15px;
    font-weight:700;
    line-height:1.4;
}

.cart-subtotal{
    color:#b33b4b !important;
    font-size:22px !important;
    font-weight:700 !important;
    margin-top:10px;
}

.cart-empty{
    color:#215486;
    font-size:18px;
    font-weight:700;
    text-align:center;
    margin-top:60px;
}
.qty-box-fix{
    display:flex;
    align-items:center;
    gap:10px;
    margin-top:10px;
    margin-bottom:10px;
}

.qty-btn-fix{
    width:28px;
    height:28px;
    border-radius:50%;
    background:#f1f1f1;
    color:#666;
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    font-weight:700;
}

.qty-number-fix{
    width:38px;
    height:32px;
    border-radius:8px;
    background:#f4f4f4;
    color:#215486;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    font-weight:700;
}
</style>
</head>

<body>
<div class="phone">
    <div class="topbar">
        <a href="{{ url('/katalog') }}" class="menu-btn" style="text-decoration:none;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="brand-title">SAMIN MERCH</div>
        <div class="shop"><i class="fa-solid fa-shop"></i></div>
    </div>

    <main class="cart-body">
        <h1>Keranjang</h1>

        @forelse($cart as $kode => $item)
            @php
                $produk = $item['produk'];
                $qty = $item['qty'];
                $subtotal = $produk->harga * $qty;
                $total += $subtotal;
            @endphp

            <div class="cart-item-box">
                <div class="cart-item-img">
                    @if(!empty($produk->foto))
                        <img src="{{ asset('upload/' . basename($produk->foto)) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="no-img">Tidak ada gambar</div>
                    @endif
                </div>

                <div class="cart-item-info">
    <h2>{{ $produk->nama_produk }}</h2>

    <p>Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

    <div class="qty-box-fix">
        <a href="{{ url('/cart/minus/' . $kode) }}" class="qty-btn-fix">−</a>

        <div class="qty-number-fix">
            {{ $qty }}
        </div>

        <a href="{{ url('/cart/add/' . $kode) }}" class="qty-btn-fix">+</a>
    </div>

    <p class="cart-subtotal">
        Rp {{ number_format($subtotal, 0, ',', '.') }}
    </p>
</div>
            </div>
        @empty
            <div class="cart-empty">Keranjang masih kosong</div>
        @endforelse
    </main>
</div>
</body>
</html>