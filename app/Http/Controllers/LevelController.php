<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{   
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Level', 'url' => '/level']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'level'; // set menu yang sedang aktif
    
        $level = LevelModel::all(); 

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'levels' => $level]);
    }

    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($level)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="'. url('/level/'. $level->level_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'. url('/level/'. $level->level_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'. url('/level/'. $level->level_id) .'" method="POST" class="d-inline-block">'.
                         csrf_field() . method_field('DELETE') .
                         '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus level ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
    $level = LevelModel::all(); 
    $breadcrumb = (object) [
        'title' => 'Tambah Level User',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Level ', 'url' => '/level'],
            ['title' => 'Tambah', 'url' => '/level/tambah']
        ],
    ];
    $page = (object) ['title' => 'Tambah Level User']; 
    $activeMenu = 'level';

   return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|integer', // level_id harus diisi dan berupa angka
            'level_kode' => 'required|string|max:10', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'level_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter         
        ]);

        LevelModel::create([
            'level_id' => $request->level_id,
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama, 
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    //Menampilkan detail level
    public function show(string $id)
    {
    $level = LevelModel::find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Level ', 'url' => '/level'],
            ['title' => 'Detail', 'url' => '/level/detail']
        ],
    ];

    $page = (object) [
        'title' => 'Detail level'
    ];

    $activeMenu = 'level'; // set menu yang sedang aktif

    return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
    $level = LevelModel::find($id); // Retrieve the level by ID

    $breadcrumb = (object) [
        'title' => 'Edit Level',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Level', 'url' => '/level'],
            ['title' => 'Edit', 'url' => '/level/edit/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Edit Level'
    ];

    $activeMenu = 'level';

    return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
    // Validasi data yang diterima dari permintaan
    $request->validate([
        'level_kode' => 'required|string|max:10|unique:m_level,level_kode,' . $id . ',level_id', // level_kode harus diisi, berupa string, maksimal 10 karakter, dan unik
        'level_nama' => 'required|string|max:100', // level_nama harus diisi, berupa string, dan maksimal 100 karakter
    ]);

    // Mencari level berdasarkan level_id
    $level = LevelModel::findOrFail($id); 


    // Menyiapkan data untuk diperbarui
    $dataToUpdate = [
        'level_kode' => $request->level_kode,
        'level_nama' => $request->level_nama,
    ];

    // Memperbarui level
    $level->update($dataToUpdate);

    // Mengalihkan dengan pesan sukses
    return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = LevelModel::find($id);

        if (!$check) {
            // untuk mengecek apakah data level dengan id yang dimaksud ada atau tidak
            return redirect('level')->with('error', 'Data level tidak ditemukan');
        }

        try{
            LevelModel::destroy($id); // Hapus data level

            return redirect('level')->with('success', 'Data level berhasil dihapus');

        }catch (\Illuminate\Database\QueryException $e){
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

}
