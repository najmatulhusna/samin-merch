<div class="sidebar admin-sidebar" id="sidebar">
    <div class="sidebar-close" onclick="closeSidebar()">
        <span>×</span>
        <span>TUTUP</span>
    </div>

    <div class="sidebar-brand">
        <i class="fa-solid fa-shop"></i>
        <span>SAMIN MERCH</span>
    </div>

    <div class="sidebar-menu admin-menu">
        <a href="{{ url('/admin/beranda') }}">
            <i class="fa-solid fa-house"></i>
            <span>BERANDA</span>
        </a>

        <a href="{{ url('/admin/katalog') }}">
            <i class="fa-solid fa-folder"></i>
            <span>KATALOG PRODUK</span>
        </a>

        <a href="{{ url('/admin/kategori') }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>KATEGORI PRODUK</span>
        </a>

        <a href="{{ url('/admin') }}">
            <i class="fa-solid fa-pen"></i>
            <span>DAFTAR PRODUK</span>
        </a>
    </div>

    <div class="sidebar-line"></div>
    <div class="admin-sidebar-bottom"></div>
</div>