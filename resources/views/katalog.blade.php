@php
    $keyword = request('search', '');
    $isAdmin = request()->is('admin/*');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=katalog_fix_stok">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .cart-btn-disabled {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 9px;
            height: 25px;
            background: #e5e5e5;
            color: #777;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            border: none;
            cursor: not-allowed;
            z-index: 10;
        }
    </style>
</head>

<body>
<div class="phone">

    @include('partials', ['isAdmin' => $isAdmin])

    <main class="katalog-content">

        <h1>
            Katalog Produk
            <span>({{ $data->count() }})</span>
        </h1>

        <p>Lihat koleksi produk kami</p>

        <form class="search-box" method="GET" action="{{ $isAdmin ? url('/admin/katalog') : url('/katalog') }}">
            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text" name="search" placeholder="Search...." value="{{ $keyword }}">

            <button type="submit" style="display:none;"></button>
            <i class="fa-solid fa-microphone"></i>
        </form>

        @if($data && $data->count() > 0)

            <div class="katalog-grid">

                @foreach($data as $produk)

                    <div class="katalog-card">

                        <div class="brand-label">
                            {{ !empty($produk->brand) ? $produk->brand : 'BRAND' }}
                        </div>

                        <div class="foto-box">
                            @if (!empty($produk->foto))
                                <img src="{{ asset('upload/' . basename($produk->foto)) }}" alt="{{ $produk->nama_produk }}">
                            @else
                                <div class="no-img">Tidak ada gambar</div>
                            @endif
                        </div>

                        <div class="produk-info">
                            <h2>{{ $produk->nama_produk }}</h2>

                            <p>Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                            <p>Stock {{ $produk->stok }}</p>
                        </div>

                        @if($isAdmin)

                            <a href="{{ url('/edit/' . $produk->id) }}" class="admin-edit-btn">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                        @else

                            @if($produk->stok > 0)
                                <a href="{{ url('/cart/add/' . $produk->kode_produk) }}" class="cart-btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </a>
                            @else
                                <button class="cart-btn-disabled" disabled>
                                    Habis
                                </button>
                            @endif

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div class="produk-kosong">
                Produk belum tersedia
            </div>

        @endif

    </main>

</div>

@include('scripts')

</body>
</html>