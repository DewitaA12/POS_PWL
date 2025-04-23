<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Kategori', 'url' => '/kategori']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'kategori'; // set menu yang sedang aktif
    
        $kategori = KategoriModel::all(); 

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori'=>$kategori]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        return DataTables::of($kategori)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = '<a href="'. url('/kategori/'. $kategori->kategori_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'. url('/kategori/'. $kategori->kategori_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'. url('/kategori/'. $kategori->kategori_id) .'" method="POST" class="d-inline-block">'.
                         csrf_field() . method_field('DELETE') .
                         '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus kategori ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
    $breadcrumb = (object) [
        'title' => 'Tambah Kategori',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Kategori', 'url' => '/kategori'],
            ['title' => 'Tambah', 'url' => '/kategori/tambah']
        ],
    ];
    $page = (object) ['title' => 'Tambah Kategori']; 
    $activeMenu = 'kategori';

    return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
    // Validasi data yang diterima dari permintaan
    $request->validate([
        'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode', // Kode kategori harus diisi, string, maksimal 10 karakter, dan unik
        'kategori_nama' => 'required|string|max:100', // Nama kategori harus diisi, string, dan maksimal 100 karakter         
    ]);

    // Menyimpan kategori baru
    KategoriModel::create([
        'kategori_kode' => $request->kategori_kode,
        'kategori_nama' => $request->kategori_nama, 
    ]);

    // Mengalihkan dengan pesan sukses
    return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function show(string $id)
    {
    $kategori = KategoriModel::find($id); // Mengambil kategori berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Detail Kategori',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Kategori', 'url' => '/kategori'],
            ['title' => 'Detail', 'url' => '/kategori/detail/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Detail Kategori'
    ];

    $activeMenu = 'kategori'; // Set menu yang sedang aktif

    return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
    $kategori = KategoriModel::find($id); // Mengambil kategori berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Edit Kategori',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Kategori', 'url' => '/kategori'],
            ['title' => 'Edit', 'url' => '/kategori/edit/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Edit Kategori'
    ];

    $activeMenu = 'kategori';

    return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
    // Validasi data yang diterima dari permintaan
    $request->validate([
        'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id', // kategori_kode harus diisi, berupa string, maksimal 10 karakter, dan unik
        'kategori_nama' => 'required|string|max:100', // kategori_nama harus diisi, berupa string, dan maksimal 100 karakter
    ]);

    // Mencari kategori berdasarkan kategori_id
    $kategori = KategoriModel::findOrFail($id); 

    // Menyiapkan data untuk diperbarui
    $dataToUpdate = [
        'kategori_kode' => $request->kategori_kode,
        'kategori_nama' => $request->kategori_nama,
    ];

    // Memperbarui kategori
    $kategori->update($dataToUpdate);

    // Mengalihkan dengan pesan sukses
    return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroy(string $id)
    {
    $check = KategoriModel::find($id);

    if (!$check) {
        // Mengecek apakah data kategori dengan id yang dimaksud ada atau tidak
        return redirect('kategori')->with('error', 'Data kategori tidak ditemukan');
    }

    try {
        KategoriModel::destroy($id); // Hapus data kategori

        return redirect('kategori')->with('success', 'Data kategori berhasil dihapus');

    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
    }

}
