<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                // $btn = '<a href="'. url('/level/'. $level->level_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'. url('/level/'. $level->level_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form action="'. url('/level/'. $level->level_id) .'" method="POST" class="d-inline-block">'.
                //          csrf_field() . method_field('DELETE') .
                //          '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus level ini?\');">Hapus</button></form>';
                
                $btn = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

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

    public function create_ajax(){
        $level = LevelModel::select('level_kode', 'level_nama')->get();

        return view('level.create_ajax');
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'level_kode' => 'required|string|max:50',
                'level_nama' => 'required|string|max:50'
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
    
            LevelModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);

        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:50',
                'level_nama' => 'required|string|max:50'
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
            $level = LevelModel::find($id);
            if ($level) {
                // Jika password tidak diisi, hapus dari request agar tidak terupdate
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $level->update($request->all());
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

    public function show_ajax(string $id){
        $level = LevelModel::find($id);
        return view('level.show_ajax', ['level' => $level]);
    }

    public function confirm_ajax(string $id){
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);

            if ($level) {
                $level->delete();
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
    }

}