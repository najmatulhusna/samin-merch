<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=edit_fix_100">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="phone">

    <header class="topbar">
        <button class="menu-btn" onclick="openSidebar()" type="button">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="brand-title">SAMIN MERCH</div>
        <div class="shop">🏪</div>
    </header>

    @include('sidebar-admin')

    <main class="tambah-page">

        <h1>Edit Produk</h1>

        <form action="{{ url('/edit/' . $produk->kode_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" placeholder="Masukkan nama produk...">

            <label>Harga</label>
            <input type="number" name="harga" value="{{ $produk->harga }}" placeholder="Masukkan harga produk...">

            <label>Stok</label>
            <input type="number" name="stok" value="{{ $produk->stok ?? '' }}">

            <label>Kategori</label>
            <select name="kategori">
                <option value="">Pilih kategori produk...</option>
                <option value="Gelang" {{ $produk->kategori == 'Gelang' ? 'selected' : '' }}>Gelang</option>
                <option value="Kalung" {{ $produk->kategori == 'Kalung' ? 'selected' : '' }}>Kalung</option>
                <option value="Ganci" {{ $produk->kategori == 'Ganci' ? 'selected' : '' }}>Ganci</option>
                <option value="Cincin" {{ $produk->kategori == 'Cincin' ? 'selected' : '' }}>Cincin</option>
                <option value="Stiker" {{ $produk->kategori == 'Stiker' ? 'selected' : '' }}>Stiker</option>
            </select>

            <label>Deskripsi</label>
            <input type="text" name="deskripsi" value="{{ $produk->deskripsi }}" placeholder="Tambahkan deskripsi produk...">

            <label>Brand</label>
            <input type="text" name="brand" value="{{ $produk->brand }}" placeholder="Tambahkan brand produk...">

            <label>Pemilik</label>
            <input type="text" name="pemilik" value="{{ $produk->pemilik }}" placeholder="Masukkan pemilik/pengkarya produk...">

            <label>Foto produk (.png atau .jpg)</label>
            <input type="file" name="foto" accept=".png,.jpg,.jpeg">

            <button type="submit" class="btn-simpan">Simpan</button>
        </form>

    </main>

</div>

<script>
function openSidebar(){
    document.getElementById('sidebar').classList.add('active');
}

function closeSidebar(){
    document.getElementById('sidebar').classList.remove('active');
}
</script>

</body>
</html>