<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Barang', 'url' => '/barang']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'barang'; // set menu yang sedang aktif
    
        $kategori = BarangModel::all(); 

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang'=>$kategori]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $barang = BarangModel::with('kategori')->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');
    
        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama ?? 'N/A';
            })
            ->addColumn('aksi', function ($barang) {
                $btn = '<a href="'. url('/barang/' . $barang->barang_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'. url('/barang/' . $barang->barang_id . '/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'. url('/barang/' . $barang->barang_id) .'" method="POST" class="d-inline-block">'
                    . csrf_field() . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus barang ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Barang', 'url' => '/barang'],
                ['title' => 'Tambah', 'url' => '/barang/tambah']
            ],
        ];
    
        $page = (object) ['title' => 'Tambah Barang']; 
        $activeMenu = 'barang';
    
        // Ambil data kategori dari tabel m_kategori
        $kategori = KategoriModel::all(); // Ambil semua kategori
    
        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }
    

    public function store(Request $request)
    {
        // Validasi data yang diterima dari permintaan
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode', // Kode barang harus diisi, string, maksimal 10 karakter, dan unik
            'barang_nama' => 'required|string|max:100', // Nama barang harus diisi, string, dan maksimal 100 karakter
            'kategori_id' => 'required|exists:m_kategori,kategori_id', // Pastikan kategori_id ada dan valid
            'harga_beli' => 'required|numeric', // Harga beli harus diisi dan berupa angka
            'harga_jual' => 'required|numeric', // Harga jual harus diisi dan berupa angka
        ]);
    
        // Menyimpan barang baru
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id, // Menyimpan kategori_id
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);
    
        // Mengalihkan dengan pesan sukses
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }
    
public function show(string $id)
    {
    $barang = BarangModel::find($id); // Mengambil barang berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Detail Barang',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Barang', 'url' => '/barang'],
            ['title' => 'Detail', 'url' => '/barang/detail/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Detail Barang'
    ];

    $activeMenu = 'barang'; // Set menu yang sedang aktif

    return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

public function edit($id)
    {
    $barang = BarangModel::find($id); // Mengambil barang berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Edit Barang',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Barang', 'url' => '/barang'],
            ['title' => 'Edit', 'url' => '/barang/edit/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Edit Barang'
    ];

    $activeMenu = 'barang';

    return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
    // Validasi data yang diterima dari permintaan
    $request->validate([
        'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id', // barang_kode harus diisi, berupa string, maksimal 10 karakter, dan unik
        'barang_nama' => 'required|string|max:100', // barang_nama harus diisi, berupa string, dan maksimal 100 karakter
        'harga_beli' => 'required|numeric', // Harga beli harus diisi dan berupa angka
        'harga_jual' => 'required|numeric', // Harga jual harus diisi dan berupa angka
    ]);

    // Mencari barang berdasarkan barang_id
    $barang = BarangModel::findOrFail($id); 

    // Menyiapkan data untuk diperbarui
    $dataToUpdate = [
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
    ];  

    // Memperbarui barang
    $barang->update($dataToUpdate);

    // Mengalihkan dengan pesan sukses
    return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
    $check = BarangModel::find($id);

    if (!$check) {
        // Mengecek apakah data barang dengan id yang dimaksud ada atau tidak
        return redirect('barang')->with('error', 'Data barang tidak ditemukan');
    }

    try {
        BarangModel::destroy($id); // Hapus data barang

        return redirect('barang')->with('success', 'Data barang berhasil dihapus');

    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
    }
}
