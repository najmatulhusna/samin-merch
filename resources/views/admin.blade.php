@php
    $keyword = request('search', '');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<div class="phone">

    @include('partials', ['isAdmin' => true])

    <main class="admin-page">

        <div class="admin-title-row">
            <h1>Daftar Produk</h1>

            <a href="{{ url('/tambah') }}" class="admin-add-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah</span>
            </a>
        </div>

        <form class="search-box admin-search" method="GET" action="{{ url('/admin') }}">
            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                name="search"
                placeholder="Search...."
                value="{{ $keyword }}"
            >

            <button type="submit" style="display:none;"></button>

            <i class="fa-solid fa-microphone"></i>
        </form>

        <div class="table-card">
            <table class="produk-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA PRODUK</th>
                        <th>HARGA</th>
                        <th>STOK</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $produk)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="nama-produk">
                                {{ $produk->nama_produk }}
                            </td>

                            <td>
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $produk->stok }}
                            </td>

                            <td>
                                <div class="aksi-btn">
                                    <a href="{{ url('/edit/' . $produk->kode_produk) }}" class="edit-btn">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="{{ url('/hapus/' . $produk->kode_produk) }}"
                                       class="delete-btn"
                                       onclick="return confirm('Hapus produk ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="produk-kosong">
                                Produk belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

</div>

@include('scripts')

</body>
</html>