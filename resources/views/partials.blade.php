@php $isAdmin = $isAdmin ?? request()->is('admin*'); @endphp
<div class="topbar">
    <button class="menu-btn" onclick="openSidebar()" type="button"><i class="fa-solid fa-bars"></i></button>
    <div class="brand-title">SAMIN MERCH</div>
    <i class="fa-solid fa-shop shop"></i>
</div>

<div class="sidebar {{ $isAdmin ? 'admin-sidebar' : '' }}" id="sidebar">
    <div class="sidebar-close" onclick="closeSidebar()"><span>×</span><span>TUTUP</span></div>
    <div class="sidebar-brand"><i class="fa-solid fa-shop"></i><span>SAMIN MERCH</span></div>

    @if($isAdmin)
        <div class="sidebar-menu admin-menu">
            <a href="{{ url('/admin/beranda') }}"><i class="fa-solid fa-house"></i><span>BERANDA</span></a>
            <a href="{{ url('/admin/katalog') }}"><i class="fa-solid fa-folder"></i><span>KATALOG PRODUK</span></a>
            <a href="{{ url('/admin/kategori') }}"><i class="fa-solid fa-briefcase"></i><span>KATEGORI PRODUK</span></a>
            <a href="{{ url('/admin') }}"><i class="fa-solid fa-pen"></i><span>DAFTAR PRODUK</span></a>
            <a href="{{ url('/logout') }}"><i class="fa-solid fa-door-open"></i><span>LOGOUT</span></a>
        </div>
        <div class="sidebar-line"></div>
        <div class="admin-sidebar-bottom"></div>
    @else
        <div class="sidebar-menu">
            <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i><span>BERANDA</span></a>
            <a href="{{ url('/katalog') }}"><i class="fa-solid fa-folder"></i><span>KATALOG PRODUK</span></a>
            <a href="{{ url('/kategori') }}"><i class="fa-solid fa-briefcase"></i><span>KATEGORI PRODUK</span></a>
        </div>
        <div class="sidebar-line"></div>
        <a class="wa-button" href="https://wa.me/6281234567890" target="_blank"><i class="fa-brands fa-whatsapp"></i><span>HUBUNGI ADMIN</span></a>
    @endif
</div>
