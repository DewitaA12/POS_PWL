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
            'list' => ['Home', 'User']
        ];
        
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'user'; // set menu yang sedang aktif
        
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }



    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
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
                (object) ['title' => 'Home', 'url' => '/'],
                (object) ['title' => 'User', 'url' => '/user'],
                (object) ['title' => 'Edit'],
            ],
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id, // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan tidak bisa diisi
            'level_id' => 'required|integer', // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? Hash::make($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }



    public function testRelasi()
    {
        $data = UserModel::with('level')->get();
        return $data;
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