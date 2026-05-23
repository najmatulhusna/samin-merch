 @php
    $keyword = request('search', '');
    $kategori = request('kategori', '');
    $isAdmin = $isAdmin ?? request()->is('admin*');
    $baseUrl = $isAdmin ? url('/admin/beranda') : url('/');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMIN MERCH</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=beranda_fix_final">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .beranda-card .cart-btn,
        .beranda-card .admin-edit-btn {
            display: none !important;
        }
    </style>
</head>

<body>
<div class="phone">

    @include('partials', ['isAdmin' => $isAdmin])

    <main class="beranda-page">

        <form class="search-box" method="GET" action="{{ $baseUrl }}">
            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text" name="search" placeholder="Search...." value="{{ $keyword }}">

            @if($kategori != '')
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif

            <button type="submit" style="display:none;"></button>
            <i class="fa-solid fa-microphone"></i>
        </form>

        <div class="beranda-kategori">
            <a href="{{ $baseUrl }}">All</a>
            <a href="{{ $baseUrl }}?kategori=Gelang">Gelang</a>
            <a href="{{ $baseUrl }}?kategori=Kalung">Kalung</a>
            <a href="{{ $baseUrl }}?kategori=Ganci">Ganci</a>
            <a href="{{ $baseUrl }}?kategori=Cincin">Cincin</a>
            <a href="{{ $baseUrl }}?kategori=Stiker">Stiker</a>
        </div>

        <div class="beranda-grid">
            @forelse($data as $produk)
                <div class="beranda-card">

                    <div class="beranda-brand">
                        {{ !empty($produk->brand) ? $produk->brand : 'BRAND' }}
                    </div>

                    <div class="beranda-img">
                        @if (!empty($produk->foto))
                            <img src="{{ asset('upload/' . basename($produk->foto)) }}" alt="{{ $produk->nama_produk }}">
                        @else
                            <div class="no-img">Tidak ada gambar</div>
                        @endif
                    </div>

                    <div class="beranda-text">
                        <h2>{{ $produk->nama_produk }}</h2>
                        <p class="deskripsi">{{ $produk->deskripsi ?? 'Deskripsi' }}</p>

                        <div class="harga-area">
                            <h3>Rp {{ number_format($produk->harga, 0, ',', '.') }}</h3>
                            <p>Stock {{ $produk->stok }}</p>
                            <p>Pengkarya: {{ $produk->pemilik ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            @empty
                <div class="produk-kosong">Produk belum tersedia</div>
            @endforelse
        </div>

    </main>

</div>

@include('scripts')

</body>
</html>