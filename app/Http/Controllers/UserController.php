<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

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
        
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
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
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level')->get();

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('level', function ($user) {
                return $user->level ? $user->level->level_nama : '-'; // Ambil nama level dari relasi
            })
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<a href="'. url('/user/'. $user->user_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'. url('/user/'. $user->user_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'. url('/user/'. $user->user_id) .'" method="POST" class="d-inline-block">'.
                         csrf_field() . method_field('DELETE') .
                         '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm
                         (\'Apakah Anda yakin menghapus data ini ?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
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
}