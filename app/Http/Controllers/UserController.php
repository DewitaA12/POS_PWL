<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // public function index()
    // {
    //    $user = UserModel::all();
    //    return view('user', ['data' => $user]);
    // }

    // public function index()
    // {
    //    $user = UserModel::with('level')->get();
    //    return view('user', ['data'=> $user]);
    // }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar user',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'User ', 'url' => '/user']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'user'; // set menu yang sedang aktif
        
        $level = LevelModel::all(); //ambil data level untuk filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level'=> $level, 'activeMenu' => $activeMenu]);
    }

    //Menampilkan detail user
    public function show(string $id)
    {
    $user = UserModel::with('level')->find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'User ', 'url' => '/user'],
            ['title' => 'Detail', 'url' => '/user/detail']
        ],
    ];

    $page = (object) [
        'title' => 'Detail user'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }


    public function create_ajax(){
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax')->with('level', $level);
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
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
    
            UserModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }

    
    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $user_id)
    {
    $user = UserModel::find($user_id);
    $level = LevelModel::select('level_id', 'level_nama')->get();

    return view('user.edit_ajax', compact('user', 'level'));
    }   


    public function update_ajax(Request $request, $user_id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $user_id . ',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
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
            $user = UserModel::find($user_id);
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
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }

   public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

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

    

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'User ', 'url' => '/user'],
                ['title' => 'Tambah', 'url' => '/user/tambah']
            ],
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    
    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5', // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer', // level_id harus diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => [
                 ['title' => 'Home', 'url' => '/'],
                 ['title' => 'User', 'url' => '/user'],
                 ['title' => 'Edit', 'url' => '/user/edit'],
            ],
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 
        'level' => $level, 'activeMenu' => $activeMenu]);
    }

    
    // Menyimpan data perubahan user
    public function update(Request $request, string $id)
    {
    // Validate the incoming request data
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
        'nama' => 'required|string|max:100', // Name must be filled, string, and max 100 characters
        'password' => 'nullable|string|min:5', // Password can be filled (min 5 characters)
        'level_id' => 'required|integer', // level_id must be filled and an integer
    ]);

    // Find the user by user_id
    $user = UserModel::findOrFail($id); 

    // Prepare the data for update
    $dataToUpdate = [
        'username' => $request->username,
        'nama' => $request->nama,
        'level_id' => $request->level_id,
    ];

    // Update password only if it is provided
    if ($request->filled('password')) {
        $dataToUpdate['password'] = Hash::make($request->password);
    }

    // Update the user
    $user->update($dataToUpdate);

    // Redirect with success message
    return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function testRelasi()
    {
        $data = UserModel::with('level')->get();
        return $data;
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);

        if (!$check) {
            // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('user')->with('error', 'Data user tidak ditemukan');
        }

        try{
            UserModel::destroy($id); // Hapus data level

            return redirect('user')->with('success', 'Data user berhasil dihapus');

        }catch (\Illuminate\Database\QueryException $e){
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }


    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request){
        $user = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');
    
        // Filter
        if ($request->level_id) {
            $user->where('level_id', $request->level_id);
        }
    
        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                // Menambahkan kolom aksi dengan URL yang benar untuk setiap tombol
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }
    

    public function show_ajax(string $id){
        $user = UserModel::find($id);
        return view('user.show_ajax', ['user' => $user]);
    }
    
    
    public function tambah()
    {
        return view('user_tambah');
    }

    public function jumlahPengguna()
    {
        $jumlahPengguna = UserModel::where('level_id', 2)->count();
        return view('jumlahUser', ['jumlahPengguna' => $jumlahPengguna]);
    }

    public function tambah_simpan(Request $request)
    {
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => Hash::make($request->password),
        'level_id' => $request->level_id
    ]);

    return redirect('/user');
    }

    public function ubah($id)
    {
    $user = UserModel::find($id);
    return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request)
{
    $user = UserModel::find($id);

    $user->username = $request->username;
    $user->nama = $request->nama;
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }    
    $user->level_id = $request->level_id;

    $user->save();

    return redirect('/user');
}

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

     public function export_pdf(){
        //ambil data yang akan di export
        $user = UserModel::select('level_id', 'user_id', 'username', 'nama','password')
        ->orderBy('level_id')
        ->with('level')
        ->get();

        //use Barruvdh\DomPDF\Facade\\Pdf
       $pdf = Pdf::loadView('user.export_pdf', ['user' =>$user]);
       $pdf->setPaper('a4', 'potrait');
       $pdf->setOption("isRemoteEnabled", true);
       $pdf->render();

       return $pdf->download('Data User '.date('Y-m-d H:i:s').'.pdf');
   }

  public function editProfil()
    {
    $breadcrumb = (object)[
        'title' => 'Edit Profil',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Edit Profil', 'url' => '/edit_profil']
        ]
    ];

    $page = (object)[
        'title' => 'Edit profil pengguna'
    ];

    return view('edit_profil', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => 'profil'
    ]);
    }


   public function updateFoto(Request $request)
    {
    $request->validate([
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $userId = Auth::id(); // dapatkan ID user yang login

    // Ambil data user dari model
    $user = UserModel::find($userId);

    // Hapus foto lama jika ada dan bukan default
    if ($user->foto && file_exists(public_path($user->foto)) && $user->foto !== 'uploads/foto_user/default.jpg') {
        unlink(public_path($user->foto));
    }

    // Simpan foto baru
    $file = $request->file('foto');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads/foto_user'), $filename);

    // Update langsung pakai Eloquent
    UserModel::where('user_id', $userId)->update([
        'foto' => 'uploads/foto_user/' . $filename,
    ]);

    return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

}