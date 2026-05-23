<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

function sm_cols($table = 'produk') {
    try {
        return Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
    } catch (Throwable $e) {
        return [];
    }
}

function sm_col($cols, $pilihan) {
    foreach ($pilihan as $col) {
        if (in_array($col, $cols)) return $col;
    }
    return null;
}

function sm_angka($nilai) {
    return (int) preg_replace('/[^0-9]/', '', $nilai ?? 0);
}

function sm_need_login() {
    if (!Session::get('login')) return redirect('/login');
    return null;
}

function sm_kategori_dari_nama($nama) {
    $n = Str::lower($nama ?? '');

    if (str_contains($n, 'gelang')) return 'Gelang';
    if (str_contains($n, 'kalung')) return 'Kalung';
    if (str_contains($n, 'ganci')) return 'Ganci';
    if (str_contains($n, 'cincin') || str_contains($n, 'ring')) return 'Cincin';
    if (str_contains($n, 'stiker') || str_contains($n, 'sticker')) return 'Stiker';

    return 'Lainnya';
}

function sm_ambil_stok($kode) {
    if (!$kode || !Schema::hasTable('stok')) return 0;

    $stok = DB::table('stok')
        ->where('kode_produk', $kode)
        ->orderBy('periode', 'desc')
        ->value('total_stok');

    if ($stok === null) {
        $stok = DB::table('stok')
            ->where('kode_produk', $kode)
            ->orderBy('periode', 'desc')
            ->value('stok_awal');
    }

    return (int) ($stok ?? 0);
}

function sm_simpan_stok($kode, $stok) {
    if (!$kode || !Schema::hasTable('stok')) return;

    DB::table('stok')->updateOrInsert(
        [
            'kode_produk' => $kode,
            'periode' => date('Y-m-d'),
        ],
        [
            'stok_awal' => (int) $stok,
            'restock' => 0,
            'terjual' => 0,
            'retur' => 0,
            'stok_hilang' => 0,
        ]
    );
}

function sm_produk($limit = null, $kategori = '', $keyword = '') {
    if (!Schema::hasTable('produk')) return collect();

    $cols = sm_cols('produk');

    $kodeCol = sm_col($cols, ['kode_produk', 'id']);
    $namaCol = sm_col($cols, ['nama_produk', 'nama']);
    $hargaCol = sm_col($cols, ['harga', 'harga_produk', 'harga_jual']);
    $kategoriCol = sm_col($cols, ['kategori']);
    $deskripsiCol = sm_col($cols, ['deskripsi', 'catatan']);
    $brandCol = sm_col($cols, ['brand', 'nama_brand']);
    $pemilikCol = sm_col($cols, ['pemilik', 'pengkarya', 'nama_pemilik']);
    $fotoCol = sm_col($cols, ['foto', 'foto_produk', 'gambar']);

    $orderCol = in_array('id', $cols) ? 'id' : ($kodeCol ?? $cols[0]);

    $rows = DB::table('produk')->orderBy('nama_produk', 'asc')->get();

    $data = $rows->map(function ($r) use ($kodeCol, $namaCol, $hargaCol, $kategoriCol, $deskripsiCol, $brandCol, $pemilikCol, $fotoCol) {
        $kode = $kodeCol ? $r->$kodeCol : null;
        $nama = $namaCol ? $r->$namaCol : 'Produk';

        return (object) [
            'id' => $kode,
            'kode_produk' => $kode,
            'nama_produk' => $nama,
            'harga' => $hargaCol ? (int) $r->$hargaCol : 0,
            'stok' => isset($r->stok) ? (int) $r->stok : 0,
            'kategori' => $kategoriCol ? ($r->$kategoriCol ?? sm_kategori_dari_nama($nama)) : sm_kategori_dari_nama($nama),
            'deskripsi' => $deskripsiCol ? ($r->$deskripsiCol ?? 'Deskripsi') : 'Deskripsi',
            'brand' => $brandCol ? ($r->$brandCol ?? 'BRAND') : 'BRAND',
            'pemilik' => $pemilikCol ? ($r->$pemilikCol ?? '-') : '-',
            'foto' => $fotoCol ? ($r->$fotoCol ?? null) : null,
        ];
    });

    $kw = Str::lower(trim($keyword ?? ''));

    if ($kw !== '' && $kw !== 'all') {
        $data = $data->filter(fn($p) =>
            str_contains(Str::lower($p->nama_produk), $kw) ||
            str_contains(Str::lower($p->kategori), $kw) ||
            str_contains(Str::lower($p->brand), $kw) ||
            str_contains(Str::lower($p->pemilik), $kw)
        );
    }

    $kat = Str::lower(trim($kategori ?? ''));

    if ($kat !== '' && $kat !== 'all') {
        $data = $data->filter(fn($p) => Str::lower($p->kategori) === $kat);
    }

    $data = $data->values();

    return $limit ? $data->take($limit) : $data;
}

function sm_data_produk(Request $r, $kode = null) {
    $cols = sm_cols('produk');
    $data = [];

    $map = [
        'nama_produk' => ['nama_produk', 'nama'],
        'kategori' => ['kategori'],
        'deskripsi' => ['deskripsi', 'catatan'],
        'brand' => ['brand', 'nama_brand'],
        'pemilik' => ['pemilik', 'pengkarya', 'nama_pemilik'],
    ];

    foreach ($map as $input => $kolom) {
        $col = sm_col($cols, $kolom);
        if ($col) $data[$col] = $r->input($input);
    }

    $hargaCol = sm_col($cols, ['harga', 'harga_produk', 'harga_jual']);
    if ($hargaCol) {
        $data[$hargaCol] = sm_angka($r->input('harga'));
    }

    $kodeCol = sm_col($cols, ['kode_produk']);
    if ($kodeCol && $kode) {
        $data[$kodeCol] = $kode;
    }

    if ($r->hasFile('foto')) {
        $fotoCol = sm_col($cols, ['foto', 'foto_produk', 'gambar']);

        if ($fotoCol) {
            $file = $r->file('foto');
            $foto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload'), $foto);
            $data[$fotoCol] = $foto;
        }
    }

    return $data;
}

/* PENGUNJUNG */

Route::get('/', fn() => view('beranda', [
    'data' => sm_produk(4, request('kategori', ''), request('search', '')),
    'isAdmin' => false
]));

Route::get('/beranda', fn() => view('beranda', [
    'data' => sm_produk(4, request('kategori', ''), request('search', '')),
    'isAdmin' => false
]));

Route::get('/katalog', fn() => view('katalog', [
    'data' => sm_produk(null, '', request('search', '')),
    'isAdmin' => false
]));

Route::get('/kategori', fn() => view('kategori', [
    'data' => sm_produk(null, '', request('search', '')),
    'kategoriList' => ['Gelang', 'Kalung', 'Ganci', 'Cincin', 'Stiker'],
    'isAdmin' => false
]));

/* LOGIN */

Route::get('/login', fn() => view('login'));

Route::post('/login', function (Request $r) {
    if ($r->username === 'admin' && ($r->password === 'admin' || $r->password === '123')) {
        Session::put('login', true);
        return redirect('/admin/beranda');
    }

    return back()->with('error', 'Username atau password salah');
});

Route::get('/logout', function () {
    Session::forget('login');
    return redirect('/login');
});

/* ADMIN */

Route::get('/admin/beranda', function () {
    if ($r = sm_need_login()) return $r;

    return view('beranda', [
        'data' => sm_produk(4, request('kategori', ''), request('search', '')),
        'isAdmin' => true
    ]);
});

Route::get('/admin/katalog', function () {
    if ($r = sm_need_login()) return $r;

    return view('katalog', [
        'data' => sm_produk(null, '', request('search', '')),
        'isAdmin' => true
    ]);
});

Route::get('/admin/kategori', function () {
    if ($r = sm_need_login()) return $r;

    return view('kategori', [
        'data' => sm_produk(null, '', request('search', '')),
        'kategoriList' => ['Gelang', 'Kalung', 'Ganci', 'Cincin', 'Stiker'],
        'isAdmin' => true
    ]);
});

Route::get('/admin', function () {
    if ($r = sm_need_login()) return $r;

    return view('admin', [
        'data' => sm_produk(null, '', request('search', '')),
        'isAdmin' => true
    ]);
});

/* TAMBAH */

Route::get('/tambah', function () {
    if ($r = sm_need_login()) return $r;

    return view('tambah', [
        'produk' => null,
        'isAdmin' => true
    ]);
});

Route::post('/tambah', function (Request $r) {
    if ($ret = sm_need_login()) return $ret;

    $data = sm_data_produk($r);
    $data['stok'] = sm_angka($r->input('stok'));

    DB::table('produk')->insert($data);

    return redirect('/admin');
});

/* EDIT */

Route::get('/edit/{kode}', function ($kode) {
    if ($r = sm_need_login()) return $r;

    $produk = sm_produk(null)->where('kode_produk', $kode)->first();

    if (!$produk) return redirect('/admin');

    return view('tambah', [
        'produk' => $produk,
        'isAdmin' => true
    ]);
});

Route::post('/edit/{kode}', function (Request $r, $kode) {
    if ($ret = sm_need_login()) return $ret;

    $data = sm_data_produk($r);
    $data['stok'] = sm_angka($r->input('stok'));

    DB::table('produk')->where('id', $kode)->update($data);

    return redirect('/admin');
});

/* HAPUS */

Route::get('/hapus/{kode}', function ($kode) {
    if ($r = sm_need_login()) return $r;

    DB::table('stok')->where('kode_produk', $kode)->delete();
    DB::table('produk')->where('id', $kode)->delete();

    return redirect('/admin');
});

/* CART */

Route::get('/cart', fn() => view('cart', [
    'cart' => Session::get('cart', [])
]));

Route::get('/cart/add/{kode}', function ($kode) {
    $produk = sm_produk(null)->where('kode_produk', $kode)->first();

    if ($produk) {
        $cart = Session::get('cart', []);

        if (!isset($cart[$kode])) {
            $cart[$kode] = [
                'produk' => $produk,
                'qty' => 1
            ];
        } else {
            $cart[$kode]['qty']++;
        }

        Session::put('cart', $cart);
    }

    return redirect('/cart');
});

Route::get('/cart/minus/{kode}', function ($kode) {
    $cart = Session::get('cart', []);

    if (isset($cart[$kode])) {
        $cart[$kode]['qty']--;

        if ($cart[$kode]['qty'] <= 0) {
            unset($cart[$kode]);
        }

        Session::put('cart', $cart);
    }

    return redirect('/cart');
});