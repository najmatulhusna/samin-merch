@php
    $edit = !empty($produk);
    $isAdmin = true;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $edit ? 'Edit Produk' : 'Tambah Produk' }}</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=tambah_edit_fix_001">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="phone">

    @include('partials', ['isAdmin' => true])

    <main class="form-produk-page">

        <h1>{{ $edit ? 'Edit Produk' : 'Tambah Produk' }}</h1>

        <form
            class="form-produk"
            action="{{ $edit ? url('/edit/' . $produk->kode_produk) : url('/tambah') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <label>Nama Produk</label>
            <input
                type="text"
                name="nama_produk"
                value="{{ old('nama_produk', $produk->nama_produk ?? '') }}"
                placeholder="Masukkan nama produk..."
                required
            >

            <label>Harga</label>
            <input
                type="text"
                name="harga"
                value="{{ old('harga', $produk->harga ?? '') }}"
                placeholder="Masukkan harga produk..."
                required
            >

            <label>Stok</label>
            <input
                type="number"
                name="stok"
                value="{{ old('stok', $produk->stok ?? '') }}"
                placeholder="Masukkan jumlah stok produk..."
                required
            >

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">Pilih kategori produk...</option>

                @foreach(['Gelang', 'Kalung', 'Ganci', 'Cincin', 'Stiker'] as $kat)
                    <option
                        value="{{ $kat }}"
                        {{ old('kategori', $produk->kategori ?? '') == $kat ? 'selected' : '' }}
                    >
                        {{ $kat }}
                    </option>
                @endforeach
            </select>

            <label>Deskripsi</label>
            <input
                type="text"
                name="deskripsi"
                value="{{ old('deskripsi', $produk->deskripsi ?? '') }}"
                placeholder="Tambahkan deskripsi produk..."
            >

            <label>Brand</label>
            <input
                type="text"
                name="brand"
                value="{{ old('brand', $produk->brand ?? '') }}"
                placeholder="Tambahkan brand produk..."
            >

            <label>Pemilik</label>
            <input
                type="text"
                name="pemilik"
                value="{{ old('pemilik', $produk->pemilik ?? '') }}"
                placeholder="Masukkan pemilik/pengkarya produk..."
            >

            <label>Foto produk (.png atau .jpg)</label>
            <div class="file-box">
                <input type="file" name="foto" accept=".png,.jpg,.jpeg">
            </div>

            <button type="submit" class="btn-simpan">
                Simpan
            </button>

        </form>

    </main>

</div>

@include('scripts')

</body>
</html>