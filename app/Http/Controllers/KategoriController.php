<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                // $btn = '<a href="'. url('/kategori/'. $kategori->kategori_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'. url('/kategori/'. $kategori->kategori_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form action="'. url('/kategori/'. $kategori->kategori_id) .'" method="POST" class="d-inline-block">'.
                //          csrf_field() . method_field('DELETE') .
                //          '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus kategori ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

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

    public function create_ajax(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('kategori.create_ajax');
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'kategori_kode' => 'required|string|max:50',
                'kategori_nama' => 'required|string|max:50'
            ];
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
    
            KategoriModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }

     public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:50',
                'kategori_nama' => 'required|string|max:50'
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // Respon JSON, false berarti gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // Menampilkan field yang error
                ]);
            }

            // Cek apakah user dengan ID tersebut ada
            $user = KategoriModel::find($id);
            if ($user) {
                // Jika password tidak diisi, hapus dari request agar tidak terupdate
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $user->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $user = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = KategoriModel::find($id);

            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function show_ajax(string $id){
        $user = KategoriModel::find($id);
        return view('kategori.show_ajax', ['kategori' => $user]);
    }

}
