@php
    $keyword = request('search', '');
    $isAdmin = request()->is('admin*');
    $baseUrl = $isAdmin ? url('/admin/kategori') : url('/kategori');

    function cocokKategori($produk, $kategori) {
        $pk = strtolower(trim($produk->kategori ?? ''));
        $kt = strtolower(trim($kategori));

        if ($pk === $kt) return true;

        $nama = strtolower($produk->nama_produk ?? '');

        if ($kt === 'gelang' && str_contains($nama, 'gelang')) return true;
        if ($kt === 'kalung' && str_contains($nama, 'kalung')) return true;
        if ($kt === 'ganci' && (str_contains($nama, 'ganci') || str_contains($nama, 'gantungan'))) return true;
        if ($kt === 'cincin' && (str_contains($nama, 'cincin') || str_contains($nama, 'ring'))) return true;
        if ($kt === 'stiker' && (str_contains($nama, 'stiker') || str_contains($nama, 'sticker'))) return true;

        return false;
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Produk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=kategori_fix_999">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="phone">

    @include('partials', ['isAdmin' => $isAdmin])

    <main class="kategori-page">

        <h1>Kategori Produk</h1>
        <p>Lihat koleksi produk kami</p>

        <form class="search-box kategori-search" method="GET" action="{{ $baseUrl }}">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Search...." value="{{ $keyword }}">
            <button type="submit" style="display:none;"></button>
            <i class="fa-solid fa-microphone"></i>
        </form>

        @php
            $kategoriList = $kategoriList ?? ['Gelang', 'Kalung', 'Ganci', 'Cincin', 'Stiker'];
        @endphp

        @if ($data && $data->count() > 0)

            @foreach ($kategoriList as $index => $kategori)

                @php
                    $produkKategori = $data->filter(fn($p) => cocokKategori($p, $kategori));
                    $warnaBox = ($index % 2 == 0) ? 'yellow-box' : 'blue-box';
                    $rowClass = ($index % 2 == 0) ? 'row-yellow' : 'row-blue';
                @endphp

                @if ($produkKategori->count() > 0)

                    <div class="kategori-row {{ $rowClass }}">

                        @if ($index % 2 == 0)
                            <h2>{{ strtoupper($kategori) }}</h2>
                        @endif

                        <div class="kategori-box {{ $warnaBox }}">
                            <div class="kategori-scroll">

                                @foreach ($produkKategori as $produk)

                                    <div class="mini-img">
                                        @if (!empty($produk->foto))
    <img src="{{ asset('upload/' . basename($produk->foto)) }}" alt="{{ $produk->nama_produk }}">
@else
    <div class="no-img">Tidak ada gambar</div>
@endif
                                    </div>

                                @endforeach

                            </div>
                        </div>

                        @if ($index % 2 != 0)
                            <h2>{{ strtoupper($kategori) }}</h2>
                        @endif

                    </div>

                @endif

            @endforeach

        @else

            <div class="produk-kosong">Produk belum tersedia</div>

        @endif

    </main>

</div>

@include('scripts')

</body>
</html>